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
function goToReports() {
  router.visit('/admin/reports')
}
function goToMainMenu() {
  router.visit('/adminchoice')
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
        <h1 class="text-lg font-bold">Online Market <span class="text-yellow-500">Admin</span></h1>
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
        <div class="flex items-center gap-3 cursor-pointer">
          <img :src="getProfilePictureUrl(admin?.profile_picture)"
            class="w-12 h-12 rounded-full border-2 border-white object-cover" />
          <p class="text-sm">{{ $page.props.admin ? ($page.props.admin.first_name + ' ' + $page.props.admin.last_name) :
            'Admin'}}</p>
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
</style>