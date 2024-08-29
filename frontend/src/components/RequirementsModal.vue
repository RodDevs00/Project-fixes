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
     <div class="p-7 w-full max-w-lg bg-white shadow rounded-lg">
      <h5 class="text-center font-semibold">Cedula Request #{{ props.requestInfo.request_uuid }}</h5>
      <p class="mt-4 text-gray-500 text-opacity-50">Click a file to view or download</p>
      <p class="text-sm mt-3">Your submitted requirements:</p>
      <ul>
       <li
        class="hover:text-blue-500 w-fit hover:cursor-pointer hover:underline transition-all"
        v-for="(item, index) in props.requestInfo.requirements"
        :key="index"
        @click="openInNewTab(item.file_path)"
       >
        {{ item.file_name }}
       </li>
      </ul>
      <div class="mt-7">
       <p class="text-sm font-medium">Cedula Payment: PHP {{ calculatePayment }}</p>
      </div>
      <div class="w-full flex justify-center items-center mt-7">
       <button
        @click="emits('closeModal', false)"
        class="inline-flex items-start justify-start px-6 py-3 bg-indigo-700 hover:bg-indigo-600 focus:outline-none rounded shadow active:scale-95 transition-all"
       >
        <p class="text-sm font-medium leading-none text-white">Close</p>
       </button>
      </div>
     </div>
    </div>
   </template>
   