<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

// --------------------
// Notification Logic
// --------------------
const showNotif = ref(false)
const notifDropdown = ref(null)
const notifItems = ref([])
const notifCount = ref(0)
const isFetching = ref(false)  // Add flag to prevent multiple fetches

// --------------------
// Dropdown State
// --------------------
const openDropdowns = ref({
  reports: false,
  account: false,
  logs: false
})

function toggleDropdown(name) {
  openDropdowns.value[name] = !openDropdowns.value[name]
}

// Toggle dropdown
function toggleNotif() {
  showNotif.value = !showNotif.value
}

// Close dropdown if clicked outside
function handleClickOutside(event) {
  if (showNotif.value && notifDropdown.value && !notifDropdown.value.contains(event.target)) {
    showNotif.value = false
  }
}

// Fetch notifications from backend
const fetchNotifications = async () => {
  if (isFetching.value) return  // Prevent multiple calls
  isFetching.value = true
  try {
    // Assuming axios.get; change to axios.post if backend requires POST
    const response = await axios.get('/notifications/orders')
    notifItems.value = response.data
    notifCount.value = notifItems.value.filter(item => !item.read).length
  } catch (error) {
    console.error('Failed to fetch notifications:', error.message)  // Log once instead of spamming
  } finally {
    isFetching.value = false
  }
}

// Icon class based on status
function getIconClass(status) {
  if (status === 'completed') return 'fas fa-check'
  if (status === 'cancelled') return 'fas fa-times'
  return 'fas fa-shopping-cart'
}

// Icon background based on status
function getIconBg(status) {
  if (status === 'completed') return 'bg-green-500'
  if (status === 'cancelled') return 'bg-red-500'
  return 'bg-[#FFA600]'
}

// Mark notification as read
function markAsRead(item) {
  item.read = true
  notifCount.value = notifItems.value.filter(i => !i.read).length
}

// Auto-refresh notifications every 10 seconds
let intervalId;
onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside)
  fetchNotifications()
  intervalId = setInterval(fetchNotifications, 10000)
})

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutside)
  clearInterval(intervalId)
})

// --------------------
// Navigation / Routing
// --------------------
function goToLandingPage() { router.visit('/landingpage') }
function goToDashboard() { router.visit('/superadmindashboard') }
function goToPayToParkReport() { router.visit('/superadminpay2parkreport') }
function goToOnlineMarketReport() { router.visit('/superadminonlinemarketreport') }
function goToRentalFacilityReport() { router.visit('/superadminfacilityreport') }
function goToProfile() { router.visit('/superadminclientaccount') }
function goToManageAccount() { router.visit('/superadminmanageaccount') }
function goToLogs() { router.visit('/superadminlogs') }
function goToAdminStaffLogs() { router.visit('/adminstafflogs') }
function logout() { router.visit('/logout') }
</script>

<template>
<div class="min-h-screen flex font-sans">
  <!-- Sidebar -->
  <aside class="w-66 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 flex flex-col justify-between">
    <div>
      <div class="mb-10">
        <h1 class="text-lg font-bold">CEDU iCentral <span class="text-yellow-500"></span></h1>
        <h1 class="text-lg font-bold"><span class="text-yellow-500">Super Admin</span></h1>
      </div>
      <nav class="space-y-4">
        <button @click="goToDashboard"
          class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
          <i class="fas fa-home"></i> Dashboard
        </button>

        <!-- Reports Group -->
        <div class="group">
          <button type="button" @click="toggleDropdown('reports')"
            class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-chart-bar"></i> Reports</span>
            <i class="fas fa-caret-down" :class="{ 'rotate-180': openDropdowns.reports }" style="transition: transform 0.2s;"></i>
          </button>
          <div v-if="openDropdowns.reports" class="ml-6 mt-2 space-y-2 animate-in fade-in duration-200">
            <button @click="goToPayToParkReport"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-parking"></i> Pay-to-Park
            </button>
            <button @click="goToOnlineMarketReport"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-store"></i> Online Market
            </button>
            <button @click="goToRentalFacilityReport"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-building"></i> Use-of-Facilities
            </button>
          </div>
        </div>

        <!-- Account Group -->
        <div class="group">
          <button type="button" @click="toggleDropdown('account')"
            class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-user-circle"></i> Manage Account</span>
            <i class="fas fa-caret-down" :class="{ 'rotate-180': openDropdowns.account }" style="transition: transform 0.2s;"></i>
          </button>
          <div v-if="openDropdowns.account" class="ml-6 mt-2 space-y-2 animate-in fade-in duration-200">
            <button @click="goToProfile"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-users-cog"></i> Client Accounts
            </button>
            <button @click="goToManageAccount"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-users-cog"></i> Admin Accounts
            </button>
          </div>
        </div>

        <!-- Logs Group -->
        <div class="group">
          <button type="button" @click="toggleDropdown('logs')"
            class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-user-circle"></i> Logs</span>
            <i class="fas fa-caret-down" :class="{ 'rotate-180': openDropdowns.logs }" style="transition: transform 0.2s;"></i>
          </button>
          <div v-if="openDropdowns.logs" class="ml-6 mt-2 space-y-2 animate-in fade-in duration-200">
            <button @click="goToLogs"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-user"></i> Super Admin Logs
            </button>
            <button @click="goToAdminStaffLogs"
              class="flex items-center gap-3 px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition w-full">
              <i class="fas fa-users-cog"></i> Admin/Staff Logs
            </button>
          </div>
        </div>

      </nav>
    </div>

    <!-- Bottom Section: Profile + Notifications + Logout -->
    <div class="mt-6 relative">
      <div class="flex items-center justify-between px-2 mt-4">
        <!-- Profile -->
        <div class="flex items-center gap-3 cursor-pointer">
          <img src="/images/ProfileImages/female.png" alt="Profile"
               class="rounded-full w-10 h-10 border-2 border-white cursor-pointer transition-transform duration-200 ease-in-out 
                      group-hover:scale-110 group-hover:border-yellow-400 group-hover:shadow-lg active:scale-95"/>
          <p class="text-sm">Super Admin</p>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-[#FFA600]/40 my-3"></div>

      <!-- Logout -->
      <a href="#" @click="goToLandingPage"
         class="flex items-center gap-3 text-sm font-semibold text-white px-3 py-2 rounded-lg transition-all duration-200 hover:text-white hover:bg-red-500 hover:shadow-md active:scale-95">
        <i class="fas fa-power-off text-lg"></i>
        <span class="tracking-wide">Log Out</span>
      </a>
    </div>
  </aside>
</div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
