<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const showNotif = ref(false)
const sidebarOpen = ref(false)
const mainContent = ref(null)
const page = usePage()
const admin = computed(() => page.props.admin)

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}
onMounted(() => {
  window.addEventListener('click', () => {
    showNotif.value = false
  })
})

const months = ref([
  { name: 'Jan', height: '40%', revenue: '1,200' },
  { name: 'Feb', height: '60%', revenue: '2,100' },
  { name: 'Mar', height: '30%', revenue: '900' },
  { name: 'Apr', height: '50%', revenue: '1,800' },
  { name: 'May', height: '70%', revenue: '2,500' },
  { name: 'Jun', height: '55%', revenue: '2,000' }
])

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}
function goToMainMenu() {
  router.visit('/adminchoice')
}
function goToDashboard() {
  router.visit('/dashboard')
}
function goToManageParking() {
  router.visit('/managep2p')
}
function goToReports() {
  router.visit('/reports')
}

function goToLogs() {
  router.visit('/logs')
}

// Helper to get the correct profile picture URL
function getProfilePictureUrl(path) {
  if (!path) return 'https://i.pravatar.cc/300';
  if (path.startsWith('http')) return path;
  if (path.startsWith('storage/')) path = path.replace('storage/', '');
  return `/storage/${path}`;
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
        <h1 class="text-lg font-bold">Pay to Park <span class="text-yellow-500">Admin</span></h1>
      </div>
      <nav class="space-y-4">
        <a href="#" @click="goToDashboard"
          class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="#" @click="goToManageParking"
          class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-parking"></i> Manage Parking
        </a>
        <a href="#" @click="goToReports"
          class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-file-invoice-dollar"></i> Reports
        </a>
        <a href="#" @click="goToLogs"
          class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-file-alt"></i> Logs
        </a>
      </nav>
    </div>

    <!-- Bottom Notification + Profile Section -->
    <div class="mt-6 relative">
      <!-- Profile Row -->
      <div class="flex items-center justify-between px-2 mt-4">
        <!-- Profile Left -->
        <div class="flex items-center gap-3 cursor-pointer">
          <img :src="getProfilePictureUrl(admin?.profile_picture)"
            class="w-12 h-12 rounded-full border-2 border-white object-cover" />
          <p class="text-sm">{{ admin ? (admin.first_name + ' ' + admin.last_name) : 'Admin' }}</p>
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
      <button @click.prevent="goToMainMenu" type="button"
        class="flex items-center gap-3 text-sm text-white-400 hover:text-white transition px-2 w-full">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
          </svg>
         Main Menu
      </button>
    </div>
  </aside>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

body {
  font-family: 'Inter', sans-serif;
}
</style>
