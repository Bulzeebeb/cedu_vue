<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const showNotif = ref(false)

function getProfilePictureUrl(picture) {
  return picture ? `/storage/${picture}` : 'https://i.pravatar.cc/300'
}

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}

// Fixed routes according to your PHP Laravel/Inertia routes
function goToDashboard() {
  router.visit('/use/facidashboard')
}
function goToCategory() {
  router.visit('/use/category')
}
function goToFacilities() {
  router.visit('/use/facilities')
}
function goToBooking() {
  router.visit('/use/booking')
}
function goToReports() {
  router.visit('/use/reports')
}
function goToProfile() {
  router.visit('/use/accounts')
}
function goToLogs() {
  router.visit('/use/logs')
}
function goToHome() {
  router.visit('/') // landing/login page
}
function goToAdminProf() {
  router.visit('/use/profile') // admin profile page
}

onMounted(() => {
  window.addEventListener('click', () => {
    showNotif.value = false
  })
})
</script>


<template>
  <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 flex flex-col justify-between">
    <!-- Top Logo and Navigation -->
    <div>
      <div class="mb-10">
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-500">iCentral</span></h1>
      </div>
      <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="#" @click.prevent="goToDashboard"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-home"></i> Dashboard
        </a>

        <!-- Category -->
        <a href="#" @click.prevent="goToCategory"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-tags"></i> Category
        </a>

        <!-- Facilities -->
        <a href="#" @click.prevent="goToFacilities"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-building"></i> Facilities
        </a>

        <!-- Booking -->
        <a href="#" @click.prevent="goToBooking"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-calendar-check"></i> Booking
        </a>

        <!-- Reports -->
        <a href="#" @click.prevent="goToReports"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-chart-bar"></i> Reports
        </a>

        <!-- Accounts -->
        <a href="#" @click.prevent="goToProfile"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-user-circle"></i> Accounts
        </a>

        <!-- Logs -->
        <a href="#" @click.prevent="goToLogs"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-file-alt"></i> Logs
        </a>
      </nav>
    </div>

    <!-- Bottom Notification + Profile Section -->
    <div class="mt-6 relative">
      <!-- Profile Row -->
      <div class="flex items-center justify-between px-2 mt-4">
        <!-- Profile Left -->
        <div class="flex items-center gap-3 cursor-pointer" @click="goToAdminProf">
          <img :src="getProfilePictureUrl(admin?.profile_picture)"
            class="w-12 h-12 rounded-full border-2 border-white object-cover" />
          <p class="text-sm">{{ $page.props.admin ? ($page.props.admin.first_name + ' ' + $page.props.admin.last_name) :
            'Admin'}}</p>
        </div>

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
        class="absolute bottom-16 left-0 w-72 bg-white rounded-xl shadow-2xl z-50 overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-[#FFA600] to-[#ffcc66] text-[#5F1213] font-semibold px-4 py-3 text-sm">
          Notifications Center
        </div>
        <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100">
          <li class="flex items-start gap-3 px-4 py-4 hover:bg-gray-50 cursor-pointer transition">
            <div class="bg-[#FFA600] rounded-full p-2">
              <i class="fas fa-info-circle text-white text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-[#5F1213]">System update available</p>
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
      <button @click.prevent="goToHome" type="button"
        class="flex items-center gap-3 text-sm text-red-400 hover:text-white transition px-2 w-full">
        <i class="fas fa-power-off text-lg"></i> Log Out
      </button>
    </div>
  </aside>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
