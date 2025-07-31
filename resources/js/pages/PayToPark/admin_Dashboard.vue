<script setup>
import { onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'

const sidebarOpen = ref(false)
const mainContent = ref(null)

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

function admin_Dashboard() {
  router.visit('/admin_Dashboard')
}
function admin_ManageParking() {
  router.visit('/admin_ManageParking')
}
function admin_Account() {
  router.visit('/admin_Account')
}
function admin_Reports() {
  router.visit('/admin_parking_reports')
}

onMounted(() => {
  setTimeout(() => {
    if (mainContent.value) {
      mainContent.value.classList.remove('opacity-0', 'translate-y-6')
    }
  }, 100)
})
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P />

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <!-- Header -->
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Dashboard</h1>
        </div>
        
      </header>

      <!-- Divider -->
      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Main Section with animation -->
      <main
        ref="mainContent"
        class="p-4 flex-1 transition-all duration-700 ease-out transform opacity-0 translate-y-6"
      >
        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center mb-6">
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-car text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">20</h3>
            <p class="text-yellow-400">Total Vehicles Parked</p>
          </div>
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-coins text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">₱7,000</h3>
            <p class="text-yellow-400">Total Income</p>
          </div>
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-clock text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">13:00 - 15:00</h3>
            <p class="text-yellow-400">Peak Hours</p>
          </div>
        </div>

        <!-- Graph and Insights -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <!-- Bar Graph -->
          <div class="col-span-2 bg-white border border-gray-200 p-6 rounded-lg shadow">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
              <h3 class="text-lg font-bold text-[#5F1213]">REVENUE BAR GRAPH</h3>
              <select class="border border-[#5F1213] p-2 rounded text-sm text-[#5F1213] w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition">
                <option selected>Monthly</option>
                <option>Weekly</option>
                <option>Yearly</option>
              </select>
            </div>

            <div class="relative h-64 bg-gradient-to-b from-yellow-50 to-white rounded-lg border border-yellow-100 px-4 py-4">
              <div class="absolute top-1/4 left-0 right-0 border-t border-yellow-200"></div>
              <div class="absolute top-1/2 left-0 right-0 border-t border-yellow-200"></div>
              <div class="absolute top-3/4 left-0 right-0 border-t border-yellow-200"></div>

              <div class="flex justify-between items-end h-full">
                <div v-for="month in months" :key="month.name" class="group flex flex-col items-center">
                  <div
                    :style="{ height: month.height }"
                    class="w-8 bg-gradient-to-t from-[#FFA600] to-yellow-300 rounded-t-xl shadow-md transition-transform duration-300 transform hover:scale-110 relative"
                  >
                    <div class="absolute bottom-full mb-2 px-2 py-1 rounded bg-[#5F1213] text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                      ₱{{ month.revenue }}
                    </div>
                  </div>
                  <span class="text-xs mt-2 text-[#5F1213] font-medium">{{ month.name }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Insights -->
          <div class="bg-white p-6 rounded-lg shadow space-y-6">
            <h3 class="text-lg font-bold mb-4 text-[#5F1213]">Insights</h3>
            <div class="flex items-center justify-between">
              <span class="text-gray-700">Avg. Parking Time</span>
              <span class="font-semibold text-[#5F1213]">45 mins</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-700">Returning Clients</span>
              <span class="font-semibold text-[#5F1213]">35%</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-700">New Users This Month</span>
              <span class="font-semibold text-[#5F1213]">12</span>
            </div>
            <div class="mt-4 text-center">
              <div class="relative w-24 h-24 mx-auto">
                <svg class="transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="45" stroke="#E5E7EB" stroke-width="10" fill="none" />
                  <circle cx="50" cy="50" r="45" stroke="#FFA600" stroke-width="10" stroke-dasharray="282.6" stroke-dashoffset="85" fill="none" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center text-sm font-semibold text-[#5F1213]">70%</div>
              </div>
              <p class="mt-2 text-sm text-gray-600">Revenue Target</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

body {
  font-family: 'Inter', sans-serif;
}

</style>
