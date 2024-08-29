<script setup lang="ts">
import { computed, onMounted } from "vue";
import { usePendingUsersStore } from "@modules/pending-users/store";
import { useApprovedUsersStore } from "@modules/approved-users/store";
import { useSuspendedUsersStore } from "@modules/suspended-users/store";
import { storeToRefs } from "pinia";
import moment from "moment";

import Avatar from "vue3-avatar";
import { useRoute } from "vue-router";
import { RouteNames } from "@/lib/utils/enums";

const props = defineProps<{
 userStatus: number;
}>();

let useStore;

if (props.userStatus === 0) {
 useStore = usePendingUsersStore();
} else if (props.userStatus === 2) {
 useStore = useSuspendedUsersStore();
} else {
 useStore = useApprovedUsersStore();
}

const { selected_user, is_loading } = storeToRefs(useStore);
const { approveUser, declineUser } = usePendingUsersStore();
const { unsuspendUser, deleteUser } = useSuspendedUsersStore();
const { suspendUser } = useApprovedUsersStore();
const route = useRoute();

const getRoute = computed(() => route.name);

const userAge = computed(() => {
 const birthDateObj = new Date(selected_user.value!.date_of_birth);
 const currentDate = new Date();
 const age = currentDate.getFullYear() - birthDateObj.getFullYear();
 if (currentDate.getMonth() < birthDateObj.getMonth() || (currentDate.getMonth() === birthDateObj.getMonth() && currentDate.getDate() < birthDateObj.getDate())) {
  return age - 1;
 }

 return age;
});

function confirmDecline() {
 const userConfirmed = window.confirm("Are you sure you want to decline this user?");
 if (userConfirmed) {
  declineUser();
 }
}

function confirmDelete() {
 const userConfirmed = window.confirm("Are you sure you want to delete this user?");
 if (userConfirmed) {
  deleteUser();
 }
}

function confirmSuspend() {
 const userConfirmed = window.confirm("Are you sure you want to suspend this user?");
 if (userConfirmed) {
  suspendUser();
 }
}

onMounted(() => (document.body.style.overflowY = "auto"));
</script>
<template>
    <div class="flex items-center justify-center min-h-screen py-4">
      <div class="w-full max-w-5xl px-4">
        <div class="grid grid-cols-8 gap-6">
          <div class="col-span-8">
            <div class="relative">
              <div class="absolute top-4 right-4">
                <button
                  v-if="!is_loading"
                  class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded active:scale-95 transition-all"
                  @click="selected_user = undefined"
                >
                  Close
                </button>
              </div>
              <div class="bg-white shadow rounded-lg p-6 flex flex-col items-center">
                <div>
                  <Avatar
                    :name="`${selected_user!.first_name} ${selected_user!.surname}`"
                    :size="200"
                  />
                </div>
                <h1 class="text-xl font-bold text-center mt-4">
                  {{ selected_user!.first_name + " " + selected_user!.middle_name + " " + selected_user!.surname }}, {{ userAge }}
                </h1>
                <p class="text-gray-700 text-center">{{ selected_user!.address }}</p>
                
                <!-- Buttons for User Status -->
                <div class="mt-6 flex flex-wrap gap-4 justify-center">
                  <button
                    v-if="props.userStatus === 0 && !is_loading"
                    class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded active:scale-95 transition-all"
                    @click="approveUser"
                  >
                    Approve
                  </button>
                  <button
                    v-if="props.userStatus === 0 && !is_loading"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded active:scale-95 transition-all"
                    @click="confirmDecline"
                  >
                    Decline
                  </button>
                </div>
                
                <!-- Warning message -->
                <div v-if="props.userStatus === 0" class="mt-4">
                  <p class="text-sm text-justify text-red-600">
                    <strong>IMPORTANT!</strong> If you decline a user, their account will be permanently deleted and it cannot be undone.
                  </p>
                </div>
                
                <!-- Other Status-specific Buttons -->
                <!-- More details about the user -->
                <hr class="my-6 border-t border-gray-300" />
                <div class="flex flex-col items-center">
                <span class="text-gray-700 uppercase font-bold tracking-wider mb-2">More Details</span>
                <ul class="text-center">
                    <li class="mb-2">
                    <strong>Email:</strong> {{ selected_user!.account.email }}
                    </li>
                    <li class="mb-2">
                    <strong>Phone Number:</strong> {{ selected_user!.phone_number }}
                    </li>
                    <li class="mb-2">
                    <strong>Baranggay:</strong> {{ selected_user!.baranggay }}
                    </li>
                    <li class="mb-2">
                    <strong>Sex:</strong> {{ selected_user!.sex }}
                    </li>
                </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  