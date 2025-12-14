<template>
  <div class="min-h-screen flex flex-col md:flex-row font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebarP2P :sidebarOpen="sidebarOpen" @close="closeSidebar" />

    <!-- Main Content -->
    <main
      class="flex-1 p-4 sm:p-6 text-[#5F1213] transition-all duration-300 md:ml-64"
      :class="{ 'ml-0': !sidebarOpen }"
    >
      <!-- Page Header -->
      <header class="flex items-center justify-between mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#5F1213]">Logs</h1>
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Search Filter -->
      <div class="flex flex-col md:flex-row md:items-center gap-3 sm:gap-4 mb-4">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by user or action..."
          class="bg-white text-black border border-gray-300 px-3 sm:px-4 py-2 rounded w-full md:w-64 text-sm sm:text-base"
        />
        <select
          v-model="selectedFilter"
          class="bg-white text-black border border-gray-300 px-3 sm:px-4 py-2 rounded w-full md:w-40 text-sm sm:text-base"
        >
          <option value="all">All</option>
          <option value="daily">Today</option>
          <option value="weekly">Last 7 days</option>
          <option value="monthly">Last Month</option>
        </select>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-xl p-4 sm:p-6 shadow w-full overflow-x-auto">
        <table class="min-w-full text-xs sm:text-sm text-left text-gray-700">
          <thead class="bg-[#FFA600] text-white">
            <tr>
              <th class="px-3 sm:px-6 py-3">#</th>
              <th class="px-3 sm:px-6 py-3">User</th>
              <th class="px-3 sm:px-6 py-3">Action</th>
              <th class="px-3 sm:px-6 py-3">Details</th>
              <th class="px-3 sm:px-6 py-3">Timestamp</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(log, index) in paginatedLogs"
              :key="log.id"
              class="border-t hover:bg-gray-50 transition"
            >
              <td class="px-3 sm:px-6 py-4">{{ index + 1 + (currentPage - 1) * perPage }}</td>
              <td class="px-3 sm:px-6 py-4 font-semibold">{{ log.user_name || 'Unknown' }}</td>
              <td class="px-3 sm:px-6 py-4">{{ log.action }}</td>
              <td class="px-3 sm:px-6 py-4">{{ log.details }}</td>
              <td class="px-3 sm:px-6 py-4 text-[10px] sm:text-xs text-gray-500">{{ formatDate(log.created_at) }}</td>
            </tr>
            <tr v-if="filteredLogs.length === 0">
              <td colspan="5" class="text-center py-6 text-gray-400 text-sm">No logs found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center items-center mt-6 gap-2 sm:gap-3 text-sm sm:text-base">
        <!-- Prev Button -->
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === 1
            ? 'opacity-40 cursor-not-allowed'
            : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>

        <!-- Dots -->
        <div class="flex items-center gap-1 sm:gap-2">
          <span
            v-for="n in 5"
            :key="n"
            class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition"
            :class="{
              'bg-yellow-400': n === dotPosition,
              'bg-gray-300': n !== dotPosition
            }"
          ></span>
        </div>

        <!-- Next Button -->
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === totalPages
            ? 'opacity-40 cursor-not-allowed'
            : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
      </div>
    </main>
  </div>
</template>

<script setup>
import AdminSidebarP2P from './adminSidebarP2P.vue'
import { ref, computed, watch } from 'vue'

const props = defineProps({
  logs: { type: Array, required: true }
})
const logs = ref(props.logs.data || props.logs)

const searchQuery = ref('')
const selectedFilter = ref('all')

const isWithinRange = (dateStr, filterType) => {
  const logDate = new Date(dateStr)
  const now = new Date()
  switch (filterType) {
    case 'daily': return logDate.toDateString() === now.toDateString()
    case 'weekly': return (now - logDate) / (1000 * 60 * 60 * 24) <= 7
    case 'monthly':
      return logDate.getFullYear() === now.getFullYear() && logDate.getMonth() === now.getMonth()
    default: return true
  }
}

const filteredLogs = computed(() =>
  logs.value.filter(log => {
    const matchesSearch =
      (log.user_name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (log.action || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesDate = isWithinRange(log.created_at, selectedFilter.value)
    return matchesSearch && matchesDate
  })
)

const currentPage = ref(1)
const perPage = 10
const totalPages = computed(() => Math.max(1, Math.ceil(filteredLogs.value.length / perPage)))

const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredLogs.value.slice(start, start + perPage)
})

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}

const dotPosition = computed(() => {
  if (totalPages.value <= 5) return currentPage.value
  const groupSize = Math.ceil(totalPages.value / 5)
  return Math.ceil(currentPage.value / groupSize)
})

watch([searchQuery, selectedFilter], () => {
  currentPage.value = 1
})

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleString()
}

const sidebarOpen = ref(false)
function toggleSidebar() { sidebarOpen.value = !sidebarOpen.value }
function closeSidebar() { sidebarOpen.value = false }
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
