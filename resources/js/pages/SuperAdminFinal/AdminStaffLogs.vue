<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <SiteHeader />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-50 to-gray-100 p-10 text-[#5F1213]">


      <!-- Page Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-[#5F1213] flex items-center gap-2">
          <i class="fas fa-file-alt"></i> Admin and Staff Activity Logs
        </h1>
        <p class="text-gray-700 mt-1 text-sm">Monitor and track all administrative activities</p>
      </div>

      <!-- Sorting & Filters -->
      <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700">Filter by Action:</label>
          <select v-model="actionFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm shadow-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#FFA600]">
            <option value="">All Actions</option>
            <option v-for="action in actionTypes" :key="action" :value="action">{{ action }}</option>
          </select>
        </div>

        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700">Sort by Date:</label>
          <select v-model="sortOption" class="px-4 py-2 border border-gray-300 rounded-lg text-sm shadow-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#FFA600]">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
          </select>
        </div>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-300 overflow-hidden">
        <table class="min-w-full text-sm text-[#333]">
          <thead class="bg-[#5F1213] text-white text-sm uppercase">
            <tr>
              <th class="text-left px-6 py-4">ID</th>
              <th class="text-left px-6 py-4">Name</th>
              <th class="text-left px-6 py-4">Role</th>
              <th class="text-left px-6 py-4">Action</th>
              <th class="text-left px-6 py-4">Date & Time</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <!-- Loading State -->
            <tr v-if="loading">
              <td colspan="5" class="text-center py-12">
                <div class="flex flex-col items-center justify-center">
                  <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5F1213]"></div>
                  <p class="mt-3 text-gray-600 font-medium">Loading activity logs...</p>
                </div>
              </td>
            </tr>

            <!-- Error State -->
            <tr v-else-if="error">
              <td colspan="5" class="text-center py-12">
                <div class="flex flex-col items-center">
                  <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-3"></i>
                  <p class="text-lg text-red-600 font-semibold mb-2">Unable to Load Logs</p>
                  <p class="text-gray-600 mb-4">{{ error }}</p>
                  <button @click="fetchLogs" class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#4a0e11] transition-colors font-semibold">
                    <i class="fas fa-redo mr-2"></i>Retry
                  </button>
                </div>
              </td>
            </tr>

            <!-- Logs Table Rows -->
            <tr v-for="log in filteredSortedLogs" :key="log.id" class="hover:bg-yellow-50">
              <td class="px-6 py-3 font-medium">{{ log.id }}</td>
              <td class="px-6 py-3 font-semibold">{{ log.name }}</td>
              <td class="px-6 py-3">
                <span :class="log.role === 'Admin' ? 'bg-blue-600' : 'bg-green-600'" class="text-white text-xs px-3 py-1 rounded-full font-semibold">
                  {{ log.role }}
                </span>
              </td>
              <td class="px-6 py-3">{{ log.action }}</td>
              <td class="px-6 py-3 text-gray-600">{{ formatTimestamp(log.created_at) }}</td>
            </tr>

            <!-- No Logs State -->
            <tr v-if="!loading && !error && filteredSortedLogs.length === 0">
              <td colspan="5" class="text-center py-12">
                <div class="flex flex-col items-center">
                  <i class="fas fa-info-circle text-4xl text-gray-300 mb-3"></i>
                  <p class="text-lg text-gray-500 font-semibold mb-1">No Activity Logs Found</p>
                  <p class="text-gray-400">There are no logs matching your current filters.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import SiteHeader from './sidebar.vue'

const sortOption = ref('desc')
const actionFilter = ref('')
const logs = ref([])
const loading = ref(true)
const error = ref(null)
let pollInterval = null
let lastTimestamp = null

// Fetch logs from API
const fetchLogs = async (isRealTimeUpdate = false) => {
  try {
    if (!isRealTimeUpdate) {
      loading.value = true
    }
    error.value = null

    // Build query parameters
    const params = { limit: 500 }
    if (isRealTimeUpdate && lastTimestamp) {
      params.since = lastTimestamp
    }

    const response = await axios.get('/api/activity-logs', { params })
    
    if (response.data && response.data.data) {
      const newLogs = response.data.data
      
      if (isRealTimeUpdate && lastTimestamp) {
        // Add new logs to the top
        logs.value = [...newLogs.reverse(), ...logs.value]
      } else {
        // Initial load
        logs.value = newLogs
      }

      // Update lastTimestamp for next poll
      if (response.data.timestamp) {
        lastTimestamp = response.data.timestamp
      }
    }
  } catch (err) {
    if (!isRealTimeUpdate || !lastTimestamp) {
      error.value = 'Failed to load logs. Please try again.'
    }
    console.error('Error fetching logs:', err)
  } finally {
    if (!isRealTimeUpdate) {
      loading.value = false
    }
  }
}

const actionTypes = computed(() => [...new Set(logs.value.map(l => l.action).filter(Boolean))])

// Filter by action, then sort by date
const filteredSortedLogs = computed(() => {
  const filtered = actionFilter.value
    ? logs.value.filter(l => l.action === actionFilter.value)
    : [...logs.value]

  return filtered.sort((a, b) => {
    const timeA = new Date(a.created_at)
    const timeB = new Date(b.created_at)
    return sortOption.value === 'asc' ? timeA - timeB : timeB - timeA
  })
})

// Format timestamp for display
const formatTimestamp = (timestamp) => {
  return new Date(timestamp).toLocaleString()
}

// Start real-time polling
const startPolling = () => {
  // Poll every 3 seconds for new logs
  pollInterval = setInterval(() => {
    fetchLogs(true)
  }, 3000)
}

// Stop polling
const stopPolling = () => {
  if (pollInterval) {
    clearInterval(pollInterval)
    pollInterval = null
  }
}

onMounted(() => {
  // Initial fetch
  fetchLogs(false).then(() => {
    // Start polling after initial load
    startPolling()
  })
})

onUnmounted(() => {
  stopPolling()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
