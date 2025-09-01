<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 shadow-2xl z-10">
      <div class="mb-10">
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-500">iCentral</span></h1>
      </div>
      <nav class="space-y-4">
        <Link href="/admin/dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-home"></i> Dashboard
        </Link>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-arrows-rotate"></i> Update</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/update/paytopark" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Pay-to-Park</Link>
            <Link href="/admin/update/onlinemarket" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</Link>
            <Link href="/admin/update/useoffacilities" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</Link>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-chart-bar"></i> Reports</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/report/paytopark" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Pay-to-Park</Link>
            <Link href="/admin/report/onlinemarket" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</Link>
            <Link href="/admin/report/useoffacilities" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</Link>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-user-circle"></i> Account</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/profile" class="block px-4 py-2 text-sm rounded-md bg-[#5F1213] hover:bg-[#FFA600] hover:text-[#5F1213] transition">Profile</Link>
            <Link href="/admin/account" class="block px-4 py-2 text-sm rounded-md bg-[#5F1213] hover:bg-[#FFA600] hover:text-[#5F1213] transition">Manage Account</Link>
          </div>
        </div>
        <Link href="/admin/logs" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#FFA600] text-[#5F1213] font-bold shadow transition">
          <i class="fas fa-file-alt"></i> Logs
        </Link>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-red-400 font-medium shadow hover:bg-red-600 hover:text-white transition">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-50 to-gray-200 p-10">
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-[#5F1213] flex items-center gap-2">
          <i class=""></i> Log History
        </h1>
        <p class="text-gray-700 mt-1 text-sm">Super Admin overview of all Admin and Staff activities</p>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700">Search Name:</label>
          <input type="text" v-model="searchName" placeholder="e.g. Juan"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm shadow-sm text-gray-800 placeholder-gray-500 bg-white focus:ring-2 focus:ring-[#FFA600]" />
        </div>
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700">Filter by Date:</label>
          <input type="date" v-model="selectedDate"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm shadow-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#FFA600]" />
          <button v-if="selectedDate || searchName" @click="resetFilters"
            class="text-xs px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-red-100 hover:text-red-600 transition">
            Clear
          </button>
        </div>
      </div>

      <!-- Log Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-300 overflow-hidden">
        <table class="min-w-full text-sm text-[#333]">
          <thead class="bg-[#5F1213] text-white text-sm uppercase">
            <tr>
              <th class="text-left px-6 py-4">Date</th>
              <th class="text-left px-6 py-4">Time</th>
              <th class="text-left px-6 py-4">Name</th>
              <th class="text-left px-6 py-4">Role</th>
              <th class="text-left px-6 py-4">Activity</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="(log, index) in paginatedLogs" :key="index" class="hover:bg-yellow-50">
              <td class="px-6 py-3 font-medium">{{ log.date }}</td>
              <td class="px-6 py-3">{{ log.time }}</td>
              <td class="px-6 py-3 font-semibold">{{ log.name }}</td>
              <td class="px-6 py-3">
                <span :class="log.role === 'Admin' ? 'bg-green-600' : 'bg-blue-500'" class="text-white text-xs px-3 py-1 rounded-full font-semibold">
                  {{ log.role }}
                </span>
              </td>
              <td class="px-6 py-3">{{ log.action }}</td>
            </tr>
            <tr v-if="paginatedLogs.length === 0">
              <td colspan="5" class="text-center py-6 text-gray-500 italic">No logs found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex justify-end items-center gap-2">
        <button @click="currentPage--" :disabled="currentPage === 1"
          class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50">Prev</button>

        <button v-for="page in totalPages" :key="page" @click="currentPage = page"
          :class="currentPage === page ? 'bg-[#FFA600] text-white' : 'bg-white text-gray-800'"
          class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-[#5F1213]">{{ page }}</button>

        <button @click="currentPage++" :disabled="currentPage === totalPages"
          class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50">Next</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const logs = ref([
  { date: '2025-07-15', time: '10:45 AM', name: 'Juan Dela Cruz', role: 'Admin', action: 'Updated profile picture' },
  { date: '2025-07-15', time: '11:10 AM', name: 'Maria Santos', role: 'Staff', action: 'Added new Pay-to-Park entry' },
  { date: '2025-07-16', time: '08:30 AM', name: 'Carlo Reyes', role: 'Admin', action: 'Removed old user account' },
  { date: '2025-07-16', time: '09:00 AM', name: 'Jen Cruz', role: 'Staff', action: 'Generated monthly report' },
  // Add more logs here...
])

const searchName = ref('')
const selectedDate = ref('')
const currentPage = ref(1)
const logsPerPage = 10

const filteredLogs = computed(() => {
  return logs.value.filter(log => {
    const matchDate = selectedDate.value ? log.date === selectedDate.value : true
    const matchName = searchName.value ? log.name.toLowerCase().includes(searchName.value.toLowerCase()) : true
    return matchDate && matchName
  })
})

const totalPages = computed(() => Math.ceil(filteredLogs.value.length / logsPerPage))

const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * logsPerPage
  return filteredLogs.value.slice(start, start + logsPerPage)
})

const resetFilters = () => {
  selectedDate.value = ''
  searchName.value = ''
  currentPage.value = 1
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
