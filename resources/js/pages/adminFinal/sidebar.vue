<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const showNotif = ref(false)
const notifDropdown = ref(null)

function toggleNotif() {
  showNotif.value = !showNotif.value
}

function handleClickOutside(event) {
  if (showNotif.value && notifDropdown.value && !notifDropdown.value.contains(event.target)) {
    showNotif.value = false;
  }
}

import { onMounted, onBeforeUnmount } from 'vue'
onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside)
})
onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutside)
})

function goToLandingPage() {
  router.visit('/landingpage')
}
function goToDashboard() {
  router.visit('/superadmindashboard')
}
function goToPayToParkUpdate() {
  router.visit('/superadminpay2parkupdate')
}
function goToOnlineMarketUpdate() {
  router.visit('/superadminonlinemarketupdate')
}
function goToRentalFacilityUpdate() {
  router.visit('/superadminupdatefacility')
}
function goToPayToParkReport() {
  router.visit('/superadminpay2parkreport')
}
function goToOnlineMarketReport() {
  router.visit('/superadminonlinemarketreport')
}
function goToRentalFacilityReport() {
  router.visit('/superadminfacilityreport')
}
function goToProfile() {
  router.visit('/superadminprofile')
}
function goToManageAccount() {
  router.visit('/superadminmanageaccount')
}
function goToLogs() {
  router.visit('/superadminlogs')
}
function logout() {
  router.visit('/logout')
}
</script>

<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 flex flex-col justify-between">
      <div>
        <div class="mb-10">
          <h1 class="text-lg font-bold">CEDU <span class="text-yellow-500">iCentral</span></h1>
        </div>
        <nav class="space-y-4">
          <button @click="goToDashboard"
            class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#FFA600] text-[#5F1213] font-bold shadow transition w-full">
            <i class="fas fa-home"></i> Dashboard
          </button>
          <div class="group">
            <button type="button"
              class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
              <span class="flex items-center gap-3"><i class="fas fa-arrows-rotate"></i> Update</span><i
                class="fas fa-caret-down"></i>
            </button>
            <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
              <button @click="goToPayToParkUpdate"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Pay-to-Park</button>
              <button @click="goToOnlineMarketUpdate"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Online
                Market</button>
              <button @click="goToRentalFacilityUpdate"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Use-of-Facilities</button>
            </div>
          </div>
          <div class="group">
            <button type="button"
              class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
              <span class="flex items-center gap-3"><i class="fas fa-chart-bar"></i> Reports</span><i
                class="fas fa-caret-down"></i>
            </button>
            <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
              <button @click="goToPayToParkReport"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Pay-to-Park</button>
              <button @click="goToOnlineMarketReport"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Online
                Market</button>
              <button @click="goToRentalFacilityReport"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Use-of-Facilities</button>
            </div>
          </div>
          <div class="group">
            <button type="button"
              class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
              <span class="flex items-center gap-3"><i class="fas fa-user-circle"></i> Account</span>
              <i class="fas fa-caret-down"></i>
            </button>
            <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
              <button @click="goToProfile"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Profile</button>
              <button @click="goToManageAccount"
                class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">Manage
                Account</button>
            </div>
          </div>
          <button @click="goToLogs"
            class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
            <i class="fas fa-file-alt"></i> Logs
          </button>

        </nav>
      </div>

      <!-- Bottom Notification + Profile Section -->
      <div class="mt-6 relative">
        <!-- Profile Row -->
        <div class="flex items-center justify-between px-2 mt-4">
          <!-- Profile Left -->
          <div class="flex items-center gap-3 cursor-pointer" @click="goToProfile">
            <img src="https://i.pravatar.cc/300" alt="Profile"
              class="w-12 h-12 rounded-full border-2 border-white object-cover" />
            <p class="text-sm">Super Admin</p>
          </div>

          <!-- Bell Right -->
          <div class="relative">
            <button @click.stop="toggleNotif" class="focus:outline-none">
              <i class="fas fa-bell text-base"></i>
              <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
              <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
          </div>
        </div>

        <!-- Notification Dropdown -->
      <div v-if="showNotif"
        ref="notifDropdown"
        class="absolute bottom-16 left-0 w-72 bg-white rounded-xl shadow-2xl z-50 overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-[#FFA600] to-[#ffcc66] text-[#5F1213] font-semibold px-4 py-3 text-sm">
          Notifications Center
        </div>
        <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100">
          <li class="flex items-start gap-3 px-4 py-4 hover:bg-gray-50 cursor-pointer transition">
            <div class="bg-[#FFA600] rounded-full p-2">
              <i class="fas fa-shopping-cart text-white text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-[#5F1213]">New order placed by Maria Santos</p>
              <p class="text-xs text-gray-500">Just now</p>
            </div>
          </li>
        </ul>
        <div class="text-center text-xs text-gray-600 bg-gray-50 py-2 hover:bg-gray-100 transition cursor-pointer">
          View all notifications
        </div>
      </div>

        <!-- Divider Line -->
        <div class="border-t border-[#FFA600]/40 my-3"></div>

        <!-- Logout -->
        <a href="#" @click="goToLandingPage" class="flex items-center gap-3 text-sm text-red-400 hover:text-white transition px-2">
          <i class="fas fa-power-off text-lg"></i> Log Out
        </a>
      </div>


    </aside>
  </div>


</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>