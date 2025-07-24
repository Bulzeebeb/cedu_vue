<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Navigation
function admin_ManageParking() {
  router.visit('/admin_ManageParking')
}
function admin_Account() {
  router.visit('/admin_Account')
}

// State
const sidebarOpen = ref(false)
const showEditModal = ref(false)

// Button Handlers
function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}
function handleSave() {
  alert('Settings have been saved!')
}
function handleEdit() {
  showEditModal.value = true
}
function handleBack() {
  window.history.back()
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
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen bg-white">
      <!-- Header -->
      <header class="bg-white shadow px-4 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-xl font-bold">Manage Parking</h1>
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

      <!-- Main Body -->
      <main class="p-4 flex-1">
        <div class="max-w-5xl mx-auto">
          <!-- SET PARKING RATE -->
          <div class="bg-gray-100 p-6 rounded mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">SET PARKING RATE</h3>
            <div class="flex flex-wrap items-center gap-4">
              <label class="flex items-center gap-2">
                Time:
                <input type="number" class="border p-1 w-16 rounded" placeholder="1" />
                <select class="border p-1 rounded text-sm">
                  <option>00</option>
                  <option>30</option>
                </select>
                <select class="border p-1 rounded text-sm">
                  <option>AM</option>
                  <option>PM</option>
                </select>
              </label>
              <label class="flex items-center gap-2">
                Hourly Rate:
                <input type="text" class="border p-1 rounded" placeholder="₱" />
              </label>
            </div>
          </div>

          <!-- SET PARKING PERIOD -->
          <div class="bg-gray-100 p-6 rounded mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">SET PARKING PERIOD</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <label>
                Max Parking Period:
                <input type="text" class="border p-1 w-full rounded" placeholder="e.g., 8 hrs" />
              </label>
              <label>
                Overtime Fee:
                <input type="text" class="border p-1 w-full rounded" placeholder="₱" />
                <span class="text-sm text-gray-600">per hour</span>
              </label>
              <label>
                Overnight Fee:
                <input type="text" class="border p-1 w-full rounded" placeholder="₱" />
              </label>
            </div>
          </div>

          <!-- OPERATING HOURS -->
          <div class="bg-gray-100 p-6 rounded mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">OPERATING HOURS</h3>
            <div class="flex flex-wrap gap-6">
              <label class="flex items-center gap-2">
                Opening Time:
                <input type="number" class="border p-1 w-16 rounded" placeholder="1" />
                <select class="border p-1 rounded text-sm">
                  <option>00</option>
                  <option>30</option>
                </select>
                <select class="border p-1 rounded text-sm">
                  <option>AM</option>
                  <option>PM</option>
                </select>
              </label>
              <label class="flex items-center gap-2">
                Closing Time:
                <input type="number" class="border p-1 w-16 rounded" placeholder="10" />
                <select class="border p-1 rounded text-sm">
                  <option>00</option>
                  <option>30</option>
                </select>
                <select class="border p-1 rounded text-sm">
                  <option>AM</option>
                  <option>PM</option>
                </select>
              </label>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-4 mt-6">
            <button @click="handleSave" class="bg-[#5F1213] text-white px-6 py-2 rounded">SAVE</button>
            <button @click="handleEdit" class="bg-yellow-500 text-white px-6 py-2 rounded">EDIT</button>
            <button @click="handleBack" class="border border-gray-400 px-6 py-2 rounded">BACK</button>
          </div>
        </div>
      </main>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg relative">
        <h2 class="text-lg font-semibold mb-4">Edit Parking Settings</h2>
        <div class="space-y-4">
          <div>
            <label class="block font-medium">Hourly Rate:</label>
            <input type="text" class="w-full border p-2 rounded" placeholder="₱100">
          </div>
          <div>
            <label class="block font-medium">Max Parking Period:</label>
            <input type="text" class="w-full border p-2 rounded" placeholder="8 hrs">
          </div>
          <div>
            <label class="block font-medium">Overtime Fee:</label>
            <input type="text" class="w-full border p-2 rounded" placeholder="₱20/hr">
          </div>
        </div>
        <div class="flex justify-end gap-4 mt-6">
          <button @click="showEditModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
          <button @click="showEditModal = false" class="bg-[#5F1213] text-white px-4 py-2 rounded">Update</button>
        </div>
        <button @click="showEditModal = false" class="absolute top-2 right-2 text-xl">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
body {
  font-family: 'Inter', sans-serif;
}
</style>
