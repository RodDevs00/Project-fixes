<script setup lang="ts">
import { onMounted, onUnmounted, computed } from "vue";

const props = defineProps<{
  requestInfo: any;
}>();

const emits = defineEmits<{
  closeModal: [value: boolean];
}>();

function openInNewTab(url: string) {
  window.open(url, "_blank", "noreferrer");
}

// Computed property to calculate the cedula payment
const calculatePayment = computed(() => {
  if (props.requestInfo.salary !== undefined && props.requestInfo.salary !== null) {
    const baseTax = 5;
    const additionalTaxPerThousand = 1;
    const salary = parseFloat(props.requestInfo.salary);
    const additionalTax = Math.floor(salary / 1000) * additionalTaxPerThousand;
    return baseTax + additionalTax;
  }
  return 0;
});

onMounted(() => (document.body.style.overflowY = "hidden"));
onUnmounted(() => (document.body.style.overflowY = "auto"));
</script>

<template>
  <div
    class="fixed inset-0 z-[100] flex justify-center items-center bg-black bg-opacity-40 p-5"
    @click.self="emits('closeModal', false)"
  >
    <div class="p-7 w-full max-w-lg bg-white shadow-lg rounded-lg border border-gray-300">
      <h5 class="text-center font-bold text-lg mb-3">Cedula Request</h5>

      <!-- Request ID and Date -->
      <div class="flex justify-between mb-4">
        <span class="text-sm font-semibold">Request #{{ props.requestInfo.request_uuid }}</span>
        <span class="text-sm text-gray-500">{{ new Date().toLocaleDateString() }}</span>
      </div>

      <!-- Requirements List -->
      <p class="text-sm mt-3 font-semibold">Submitted Requirements:</p>
      <ul class="mt-2 mb-4">
        <li
          class="hover:text-blue-500 w-fit hover:cursor-pointer hover:underline transition-all text-sm"
          v-for="(item, index) in props.requestInfo.requirements"
          :key="index"
          @click="openInNewTab(item.file_path)"
        >
          {{ item.file_name }}
        </li>
      </ul>

      <!-- Payment Details -->
      <div class="border-t border-b py-4 mb-4">
        <p class="text-sm font-semibold">Cedula Payment</p>
        <p class="text-xl font-bold text-right">PHP {{ calculatePayment.toFixed(2) }}</p>
      </div>

      <!-- Footer / Buttons -->
      <div class="w-full flex justify-center items-center">
        <button
          @click="emits('closeModal', false)"
          class="inline-flex items-center justify-center px-6 py-3 bg-indigo-700 hover:bg-indigo-600 focus:outline-none rounded shadow active:scale-95 transition-all"
        >
          <p class="text-sm font-medium leading-none text-white">Close</p>
        </button>
      </div>
    </div>
  </div>
</template>
