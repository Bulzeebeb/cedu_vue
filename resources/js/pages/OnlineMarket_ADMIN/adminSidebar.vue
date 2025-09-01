<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const admin = computed(() => page.props.admin)
const showNotif = ref(false)

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}
function goToDashboard() {
  router.visit('/admin/dashboard')
}
function goToInventory() {
  router.visit('/admins/inventory')
}
function goToProducts() {
  router.visit('/admins/products')
}
function goToOrders() {
  router.visit('/admins/orders')
}
function goToLogs() {
  router.visit('/logsOnlineMarketAdmin')
}
function goToManageAccount() {
  router.visit('/admins/manageacc')
}
function goToProfile() {
  router.visit('/admins/profile')
}
function goToReports() {
  router.visit('/admin/reports')
}
function goToHome() {
  router.visit('/landingpage')
}

// Helper to get the correct profile picture URL
function getProfilePictureUrl(path) {
  if (!path) return 'https://i.pravatar.cc/300';
  if (path.startsWith('http')) return path;
  if (path.startsWith('storage/admin_profiles')) path = path.substring(1);
  return `/${path}`;
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
        <h1 class="text-sm text-yellow-500">Online Market Admin</h1>
      </div>
      <nav class="space-y-4">
        <a href="#" @click="goToDashboard"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="#" @click="goToProducts"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-box"></i> Products
        </a>
        <a href="#" @click="goToOrders"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-cart-shopping"></i> Orders
        </a>
        <a href="#" @click="goToInventory"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-warehouse"></i> Inventory
        </a>
        <a href="#" @click="goToReports"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-chart-bar"></i> Reports
        </a>
        <a href="#" @click="goToManageAccount"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-user-circle"></i> Accounts
        </a>
        <a href="#" @click="goToLogs"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-file-alt"></i> Logs
        </a>
        <a href="#" @click="goToEditStore"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-store"></i> Online Market
        </a>
      </nav>
    </div>

    <!-- Bottom Notification + Profile Section -->
    <div class="mt-6 relative">
      <!-- Profile Row -->
      <div class="flex items-center justify-between px-2 mt-4">
        <!-- Profile Left -->
        <div class="flex items-center gap-3 cursor-pointer" @click="goToProfile">
          <img :src="getProfilePictureUrl(admin?.profile_picture)"
            class="w-12 h-12 rounded-full border-2 border-white object-cover" />
          <p class="text-sm">{{ $page.props.admin ? ($page.props.admin.first_name + ' ' + $page.props.admin.last_name) :
            'Admin'}}</p>
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
      <button @click="goToHome" type="button"
        class="flex items-center gap-3 text-sm text-red-400 hover:text-white transition px-2 w-full">
        <i class="fas fa-power-off text-lg"></i> Log Out
      </button>
    </div>
  </aside>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>