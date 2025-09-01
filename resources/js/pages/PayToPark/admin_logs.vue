<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebarP2P />

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      <!-- Page Header -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border">
        <div class="flex items-center space-x-4">
          <i class="fas fa-clipboard-list text-3xl text-black"></i>
          <h1 class="text-2xl font-semibold">Logs</h1>
        </div>
      </div>

      <!-- Search Filter -->
      <div class="bg-white rounded-xl p-6 shadow mb-6">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by user or action..."
          class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full focus:ring-[#FFA600] focus:border-[#FFA600]"
        />
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-xl p-6 shadow border overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-[#FFA600] text-white">
            <tr>
              <th class="px-6 py-3">#</th>
              <th class="px-6 py-3">User</th>
              <th class="px-6 py-3">Action</th>
              <th class="px-6 py-3">Details</th>
              <th class="px-6 py-3">Timestamp</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(log, index) in paginatedLogs"
              :key="log.id"
              class="border-t hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4">{{ index + 1 + (currentPage - 1) * perPage }}</td>
              <td class="px-6 py-4 font-semibold">{{ log.user_name || 'Unknown' }}</td>
              <td class="px-6 py-4">{{ log.action }}</td>
              <td class="px-6 py-4">{{ log.details }}</td>
              <td class="px-6 py-4 text-xs text-gray-500">{{ formatDate(log.created_at) }}</td>
            </tr>
            <tr v-if="filteredLogs.length === 0">
              <td colspan="5" class="text-center py-6 text-gray-400">No logs found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-end mt-4 space-x-2 text-sm">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
        >
          Prev
        </button>
        <span class="px-3 py-1">{{ currentPage }} / {{ totalPages }}</span>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </main>
  </div>
</template>

<script setup>
import AdminSidebarP2P from './adminSidebarP2P.vue'
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

// Props from controller
const props = defineProps({
  logs: { type: Array, required: true }
})
const logs = ref(props.logs.data || props.logs) // support pagination

// Search filter
const searchQuery = ref('')

// Computed filtered logs
const filteredLogs = computed(() => {
  return logs.value.filter(log =>
    (log.user_name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (log.action || '').toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Pagination
const currentPage = ref(1)
const perPage = 5
const totalPages = computed(() => Math.ceil(filteredLogs.value.length / perPage))
const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredLogs.value.slice(start, start + perPage)
})

// Reset page on search
watch(searchQuery, () => {
  currentPage.value = 1
})

// Format timestamp
const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleString()
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
