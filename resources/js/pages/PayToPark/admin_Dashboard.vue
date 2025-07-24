<script setup>
import { router } from '@inertiajs/vue3'

function admin_Dashboard() {
  router.visit('/admin_Dashboard')
}

function admin_ManageParking() {
  router.visit('/admin_ManageParking')
}
function admin_Account() {
  router.visit('/admin_Account')
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
   <!-- Sidebar -->
<aside
  :class="[
    'bg-[#5F1213] text-white p-6 h-screen fixed top-0 z-40 transition-all duration-300',
    sidebarOpen ? 'left-0 w-64' : '-left-64',
    'md:left-0 md:w-64 md:block'
  ]"
>
  <!-- Close Icon (Mobile Only) -->
  <div class="flex justify-between items-center mb-10">
    <h1 class="text-lg font-bold">
      CEDU <span class="text-yellow-300">iCentral</span>
    </h1>
    <button class="md:hidden text-xl" @click="toggleSidebar">
      <i class="fas fa-times"></i>
    </button>
  </div>

 <!-- Sidebar Menu -->
 <nav class="space-y-4">
        <a href="#" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="#" @click="admin_ManageParking" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-parking"></i> Manage Parking
        </a>
        <a href="#" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-file-invoice-dollar"></i> Reports
        </a>
        <a href="#" @click="admin_Account" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-user"></i> Account
        </a>
        <a href="#" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-chart-bar"></i> Log out
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen bg-gray-100">
      <!-- Header -->
      <header class="bg-white shadow px-4 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-xl font-bold">Dashboard</h1>
        </div>
        <div class="flex items-center gap-4">
          <button class="relative">
            <i class="fas fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          <img src="https://i.pravatar.cc/40" class="rounded-full w-10 h-10 border-2 border-white" alt="Profile" />
        </div>
      </header>

      <!-- Main Section -->
      <main class="p-4 flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center mb-6">
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <h3 class="text-2xl font-bold text-white">20</h3>
            <p class="text-yellow-400">Total Vehicles Parked</p>
          </div>
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <h3 class="text-2xl font-bold text-white">7</h3>
            <p class="text-yellow-400">Total Income</p>
          </div>
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <h3 class="text-2xl font-bold text-white">13</h3>
            <p class="text-yellow-400">Peak Hours</p>
          </div>
        </div>

        <!-- Graph -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <h3 class="text-lg font-bold">REVENUE BAR GRAPH</h3>
            <select class="border p-1 rounded text-sm w-full sm:w-auto">
              <option>Month</option>
              <option>Week</option>
              <option>Year</option>
            </select>
          </div>
          <div class="h-64 bg-gradient-to-b from-blue-200 to-white rounded flex items-end justify-between px-2">
            <div v-for="month in months" :key="month.name" class="flex flex-col items-center">
              <div :style="{ height: month.height }" class="w-6 bg-blue-500 rounded-t"></div>
              <span class="text-xs mt-1 text-gray-500">{{ month.name }}</span>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminDashboard',
  data() {
    return {
      sidebarOpen: false,
      months: [
        { name: 'Jan', height: '40%' },
        { name: 'Feb', height: '60%' },
        { name: 'Mar', height: '30%' },
        { name: 'Apr', height: '50%' },
        { name: 'May', height: '70%' },
        { name: 'Jun', height: '55%' }
      ]
    };
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

body {
  font-family: 'Inter', sans-serif;
}
</style>
