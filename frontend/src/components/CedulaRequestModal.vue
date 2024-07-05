<script setup lang="ts">
import { onMounted, onUnmounted, ref } from "vue";
import { useApprovedUsersStore } from "@/modules/approved-users/store";
import { CedulaRequestService } from "@/modules/cedula-requests/services";
import { storeToRefs } from "pinia";
import { UserAccountInfoState } from "@/lib/utils/types";

import UserInfo from "@/components/UserInfo.vue";
import PrimaryInput from "@/components/PrimaryInput.vue";
import PrimaryTextArea from "@/components/PrimaryTextArea.vue";
import { toast } from "vue3-toastify";

const { selected_user } = storeToRefs(useApprovedUsersStore());

const props = defineProps<{
  requestInfo: any;
}>();

const emits = defineEmits<{
  closeModal: [value: boolean];
}>();

const is_loading = ref<boolean>(false);

const cedula_info = ref<any>({
  pick_up_date: props.requestInfo.pick_up_date,
  pick_up_time: props.requestInfo.pick_up_time,
  staff_comment: props.requestInfo.staff_comment,
  id: props.requestInfo.id,
});

const BASE_URL = import.meta.env.VITE_BASE_URL || 'http://localhost';

function successCallback(success: string, toastId: string) {
  toast.remove(toastId);
  toast.success(success);
  emits("closeModal", false);
}

async function updateRequest() {
  if (!cedula_info.value.pick_up_date) return toast.error("Please select a date of pick up");
  if (!cedula_info.value.pick_up_time) return toast.error("Please select a time of pick up");
  toast.loading("Updating request", { toastId: "updating_toast" });
  is_loading.value = true;
  const res = await CedulaRequestService.new().updateCedulaRequest(cedula_info.value);
  if (res.status === 200) return successCallback(res.data.success, "updating_toast");
  toast.remove("updating_toast");
  is_loading.value = false;
}

async function approveRequest() {
  if (!cedula_info.value.pick_up_date) return toast.error("Please select a date of pick up");
  if (!cedula_info.value.pick_up_time) return toast.error("Please select a time of pick up");
  toast.loading("Approving request", { toastId: "approving_toast" });
  is_loading.value = true;
  const res = await CedulaRequestService.new().approveCedulaRequest(cedula_info.value);
  if (res.status === 200) return successCallback(res.data.success, "approving_toast");
  toast.remove("approving_toast");
  is_loading.value = false;
}

async function rejectRequest() {
  if (!cedula_info.value.staff_comment) return toast.error("Please specify a reason or add a comment");
  toast.loading("Rejecting request", { toastId: "rejecting_toast" });
  is_loading.value = true;
  const res = await CedulaRequestService.new().rejectCedulaRequest(cedula_info.value);
  if (res.status === 200) return successCallback(res.data.success, "rejecting_toast");
  toast.remove("rejecting_toast");
  is_loading.value = false;
}

async function markAsReleased() {
  toast.loading("Marking as released", { toastId: "releasing_toast" });
  is_loading.value = true;
  const res = await CedulaRequestService.new().markAsReleased(cedula_info.value.id);
  if (res.status === 200) return successCallback(res.data.success, "releasing_toast");
  toast.remove("releasing_toast");
  is_loading.value = false;
}

function setSelectedUser(user: UserAccountInfoState) {
  selected_user.value = user;
}

function openInNewTab(url: string) {
  window.open(url, "_blank", "noreferrer");
}

onMounted(() => (document.body.style.overflowY = "hidden"));
onUnmounted(() => (document.body.style.overflowY = "auto"));
</script>

<template>
  <UserInfo v-if="selected_user" :user-status="selected_user.account.account_activated" />
  <div v-else class="fixed inset-0 z-[100] flex justify-center items-center bg-black bg-opacity-40 p-5" @click.self="emits('closeModal', false)">
    <form class="p-7 w-full max-w-lg bg-white shadow rounded-lg max-h-[90vh] overflow-y-auto">
      <h5 class="text-center font-semibold pb-5">Cedula Request #{{ props.requestInfo.request_uuid }}</h5>
     
      <ul class="grid grid-cols-2 gap-4">
      <!-- User Information List Items -->
      <li v-if="props.requestInfo.first_name" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>First name:</strong> {{ props.requestInfo.first_name }}
      </li>
      <li v-if="props.requestInfo.middle_name" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Middle name:</strong> {{ props.requestInfo.middle_name }}
      </li>
      <li v-if="props.requestInfo.last_name" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Last name:</strong> {{ props.requestInfo.last_name }}
      </li>
      <li v-if="props.requestInfo.sex" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
       <strong>Sex:</strong> {{ props.requestInfo.sex }}
      </li>
      <li v-if="props.requestInfo.date_of_birth" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Date of birth:</strong> {{ props.requestInfo.date_of_birth }}
      </li>
      <li v-if="props.requestInfo.place_of_birth" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Place of birth:</strong> {{ props.requestInfo.place_of_birth }}
      </li>
      <li v-if="props.requestInfo.civil_status" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Civil status:</strong> {{ props.requestInfo.civil_status }}
      </li>
      <li v-if="props.requestInfo.citizenship" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Citizenship:</strong> {{ props.requestInfo.citizenship }}
      </li>
      <li v-if="props.requestInfo.barangay_selection" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Barangay:</strong> {{ props.requestInfo.barangay_selection }}
      </li>
      <li v-if="props.requestInfo.profession_occupation_business" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Profession/Business:</strong> {{ props.requestInfo.profession_occupation_business }}
      </li>
      <li v-if="props.requestInfo.tax_identification_number" class="hover:text-blue-500 w-fit hover:cursor-pointer transition-all">
        <strong>Tax Identification Number:</strong> {{ props.requestInfo.tax_identification_number }}
      </li>
    </ul>

    <p class="mt-4 text-gray-500 text-opacity-50">Click a file to view or download</p>
    <p class="text-md mt-3"><strong>Your submitted requirements:</strong></p>
    <ul>
      <li v-for="(item, index) in props.requestInfo.requirements" :key="index" class="hover:text-blue-500 w-fit hover:cursor-pointer hover:underline transition-all" @click="openInNewTab(`${BASE_URL}${item.file_path}`)">
        {{ item.file_name }}
      </li>
    </ul>
      <div class="my-5 flex flex-col gap-2">
        <PrimaryInput v-model="cedula_info.pick_up_date" type="date" id="pick_up_date" label="Pick up date and time" placeholder="" :disabled="is_loading" />
        <PrimaryInput v-model="cedula_info.pick_up_time" type="time" id="pick_up_time" placeholder="" :disabled="is_loading" />
        <PrimaryTextArea v-model="cedula_info.staff_comment" id="staff_comment" placeholder="Add a comment" :disabled="is_loading" />
      </div>
      <div class="w-full flex flex-col justify-center items-center gap-3 mt-7">
        <p class="hover:text-blue-500 w-fit hover:cursor-pointer hover:underline transition-all" @click="setSelectedUser(props.requestInfo.owner.user_info)">
          View User Information
        </p>
        <div class="inline-flex items gap-3">
          <button @click="emits('closeModal', false)" :disabled="is_loading" class="inline-flex items-start justify-start px-6 py-3 bg-indigo-700 hover:bg-indigo-600 focus:outline-none rounded shadow active:scale-95 transition-all disabled:bg-gray-500 disabled:cursor-not-allowed disabled:pointer-events-none">
            <p class="text-sm font-medium leading-none text-white">Close</p>
          </button>
          <button type="button" :disabled="is_loading" @click="updateRequest" class="inline-flex items-start justify-start px-6 py-3 bg-purple-700 hover:bg-purple-600 focus:outline-none rounded shadow active:scale-95 transition-all disabled:bg-gray-500 disabled:cursor-not-allowed disabled:pointer-events-none">
            <p class="text-sm font-medium leading-none text-white">Update</p>
          </button>
          <button v-if="props.requestInfo.status === 'ON PROCESS'" @click="approveRequest" type="button" :disabled="is_loading" class="inline-flex items-start justify-start px-6 py-3 bg-green-700 hover:bg-green-600 focus:outline-none rounded shadow active:scale-95 transition-all disabled:bg-gray-500 disabled:cursor-not-allowed disabled:pointer-events-none">
            <p class="text-sm font-medium leading-none text-white">Approve</p>
          </button>
          <button v-if="props.requestInfo.status === 'ON PROCESS'" type="button" @click="rejectRequest" :disabled="is_loading" class="inline-flex items-start justify-start px-6 py-3 bg-red-700 hover:bg-red-600 focus:outline-none rounded shadow active:scale-95 transition-all disabled:bg-gray-500 disabled:cursor-not-allowed disabled:pointer-events-none">
            <p class="text-sm font-medium leading-none text-white">Reject</p>
          </button>
          <button v-if="props.requestInfo.status === 'FOR RELEASE'" type="button" @click="markAsReleased" :disabled="is_loading" class="inline-flex items-start justify-start px-6 py-3 bg-green-700 hover:bg-green-600 focus:outline-none rounded shadow active:scale-95 transition-all disabled:bg-gray-500 disabled:cursor-not-allowed disabled:pointer-events-none">
            <p class="text-sm font-medium leading-none text-white">Mark as released</p>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
