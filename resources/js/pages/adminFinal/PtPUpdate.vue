<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 z-10">
      <div class="mb-10">
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-500">iCentral</span></h1>
      </div>
      <nav class="space-y-4">
        <Link href="/admin/dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
      <i class="fas fa-home"></i> Dashboard
    </Link>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3">
              <i class="fas fa-arrows-rotate"></i> Update
            </span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
          <Link
            href="/admin/update/paytopark"
            :class="[
              'block px-4 py-2 text-sm rounded-md transition',
              isActive('/admin/update/paytopark')
                ? 'bg-[#FFA600] text-[#5F1213] font-semibold'
                : 'text-white hover:bg-[#FFA600] hover:text-[#5F1213]'
            ]">
            Pay-to-Park
          </Link>

            <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</a>
            <Link href="/admin/update/useoffacilities" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</Link>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3">
              <i class="fas fa-chart-bar"></i> Reports
            </span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <a href="/admin/parkReport"  class="block px-4 py-2 text-sm rounded-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">Pay-to-Park</a>
            <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</a>
            <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</a>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3">
              <i class="fas fa-user-circle"></i> Account
            </span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/profile" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Profile</Link>
            <Link href="/admin/account" class="block px-4 py-2 text-sm rounded-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">Manage Account</Link>
          </div>
        </div>
        <Link href="/admin/logs" class="flex items-center gap-3 px-4 py-2 rounded-lg shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition">
          <i class="fas fa-file-alt"></i> Logs
        </Link>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-red-400 shadow hover:bg-red-600 hover:text-white transition">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-50 to-gray-200 p-10">
      <h1 class="text-4xl font-bold text-[#5F1213] mb-8">Pay-to-Park</h1>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-center mb-6">
        <div class="bg-[#5F1213] p-4 rounded-xl shadow-md transform transition hover:scale-105 animate-fade-in">
          <div class="text-yellow-400 text-2xl mb-1 animate-pulse"><i class="fas fa-car"></i></div>
          <h3 class="text-xl font-bold text-white">20</h3>
          <p class="text-yellow-400 text-xs mt-1">Total Vehicles Parked</p>
        </div>
        <div class="bg-[#5F1213] p-4 rounded-xl shadow-md transform transition hover:scale-105 animate-fade-in delay-100">
          <div class="text-yellow-400 text-2xl mb-1 animate-pulse"><i class="fas fa-wallet"></i></div>
          <h3 class="text-xl font-bold text-white">₱7,000</h3>
          <p class="text-yellow-400 text-xs mt-1">Total Income</p>
        </div>
        <div class="bg-[#5F1213] p-4 rounded-xl shadow-md transform transition hover:scale-105 animate-fade-in delay-200">
          <div class="text-yellow-400 text-2xl mb-1 animate-pulse"><i class="fas fa-clock"></i></div>
          <h3 class="text-xl font-bold text-white">13</h3>
          <p class="text-yellow-400 text-xs mt-1">Peak Hours</p>
        </div>
      </div>

      <!-- Manage Parking Section -->
      <div class="bg-white shadow-2xl rounded-2xl p-10 space-y-10 border-t-8 border-[#5F1213]">
  <h2 class="text-4xl font-extrabold text-[#5F1213] flex items-center gap-3">MANAGE PARKING</h2>

  <!-- Divider -->
  <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full"></div>

  <!-- Parking Rate -->
  <section class="p-6 bg-[#FFF9F0] rounded-xl shadow-inner border border-[#FFD580]">
    <h3 class="text-2xl font-semibold text-[#5F1213] mb-4">🚗 SET PARKING RATE</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="col-span-1">
        <label class="text-sm text-gray-700">Time:</label>
        <input type="time" v-model="form.rateTime" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
      <div class="md:col-span-3">
        <label class="text-sm text-gray-700">Hourly Rate (₱):</label>
        <input type="number" v-model="form.hourlyRate" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
    </div>
  </section>

  <!-- Parking Period -->
  <section class="p-6 bg-[#F3F4F6] rounded-xl shadow-inner border border-gray-300">
    <h3 class="text-2xl font-semibold text-[#5F1213] mb-4">⏱️ SET PARKING PERIOD</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm text-gray-700">Max Parking Period (hrs):</label>
        <input type="number" v-model="form.maxPeriod" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
      <div>
        <label class="text-sm text-gray-700">Overtime Fee (₱/hr):</label>
        <input type="number" v-model="form.overtimeFee" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
      <div>
        <label class="text-sm text-gray-700">Overnight Fee (₱):</label>
        <input type="number" v-model="form.overnightFee" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
    </div>
  </section>

  <!-- Operating Hours -->
  <section class="p-6 bg-[#EFF6FF] rounded-xl shadow-inner border border-blue-200">
    <h3 class="text-2xl font-semibold text-[#5F1213] mb-4">🕔 OPERATING HOURS</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm text-gray-700">Opening Time:</label>
        <input type="time" v-model="form.openTime" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
      <div>
        <label class="text-sm text-gray-700">Closing Time:</label>
        <input type="time" v-model="form.closeTime" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-[#FFA600] text-black" :disabled="!isEditing" />
      </div>
    </div>
  </section>

  <!-- Action Buttons -->
  <div class="pt-6 flex justify-end space-x-4">
    <button v-if="!isEditing" @click="enableEdit"
      class="px-6 py-2 bg-[#5F1213] text-white font-semibold rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-300">
      Edit
    </button>
    <template v-else>
      <button @click="saveChanges"
        class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-300">
        Save
      </button>
      <button @click="cancelEdit"
        class="px-6 py-2 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition duration-300">
        Cancel
      </button>
    </template>
  </div>
</div>

    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

// Detect the current page URL
const page = usePage()
const currentRoute = computed(() => page.url)

// Highlight check helper
const isActive = (path) => currentRoute.value === path

const isEditing = ref(false)

const form = ref({
  rateTime: '01:00',
  hourlyRate: 20,
  maxPeriod: 8,
  overtimeFee: 50,
  overnightFee: 100,
  openTime: '07:00',
  closeTime: '22:00'
})

const enableEdit = () => {
  isEditing.value = true
}

const cancelEdit = () => {
  isEditing.value = false
}

const saveChanges = () => {
  alert('Changes saved!')
  isEditing.value = false
}
</script>


<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/chart.js');

</style>
