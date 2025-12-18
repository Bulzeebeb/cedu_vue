<template>
  <div class="min-h-screen py-24 flex flex-col font-sans bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Sidebar -->
    <SiteHeader />

    <!-- Main Dashboard -->
    <main class="flex-1 p-6 pt-4 text-[#5F1213] flex flex-col items-center justify-center">


      <!-- Page Title -->
      <div class="bg-white rounded-2xl p-8 mb-8 shadow-lg border border-gray-200 flex items-center space-x-6 w-full max-w-7xl">
        <div class="bg-gradient-to-r from-[#FFA600] to-[#FF8C00] p-4 rounded-xl">
          <i class="fas fa-file-alt text-4xl text-white"></i>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Admin Activity Logs</h1>
          <p class="text-gray-600 mt-1">Monitor and track all administrative activities</p>
        </div>
      </div>

      <!-- Sorting & Filters -->
      <div class="bg-white rounded-2xl p-6 mb-6 shadow-lg border border-gray-200 w-full max-w-7xl">
        <div class="flex flex-wrap items-center gap-6">
          <div class="flex items-center space-x-3">
            <i class="fas fa-filter text-[#5F1213]"></i>
            <label class="font-semibold text-gray-700">Filter by Action:</label>
            <select v-model="actionFilter" class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all">
              <option value="">All Actions</option>
              <option v-for="action in actionTypes" :key="action" :value="action">{{ action }}</option>
            </select>
          </div>

          <div class="flex items-center space-x-3">
            <i class="fas fa-sort text-[#5F1213]"></i>
            <label class="font-semibold text-gray-700">Sort by Date:</label>
            <select v-model="sortOption" class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all">
              <option value="desc">Newest First</option>
              <option value="asc">Oldest First</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 overflow-x-auto flex-grow w-full max-w-7xl">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Activity Logs</h2>
          <div class="text-sm text-gray-500">
            Total: {{ filteredSortedLogs.length }} entries
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-16">
          <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-[#5F1213]"></div>
          <p class="mt-4 text-lg text-gray-600 font-medium">Loading activity logs...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="flex flex-col items-center justify-center py-16">
          <i class="fas fa-exclamation-triangle text-6xl text-red-400 mb-4"></i>
          <p class="text-xl text-red-600 font-semibold mb-2">Unable to Load Logs</p>
          <p class="text-gray-600 mb-6 text-center">{{ error }}</p>
          <button @click="fetchLogs" class="px-6 py-3 bg-[#5F1213] text-white rounded-lg hover:bg-[#4a0e11] transition-colors font-semibold shadow-md">
            <i class="fas fa-redo mr-2"></i>Retry Loading
          </button>
        </div>

        <!-- Logs Table -->
        <div v-else-if="filteredSortedLogs.length > 0" class="overflow-x-auto">
          <table class="w-full text-sm text-left border-collapse">
            <thead>
              <tr class="border-b-2 border-gray-300 bg-gray-50">
                <th class="py-4 px-6 font-bold text-gray-800 text-left">ID</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-left">Name</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-left">Role</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-left">Action</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-left">Date & Time</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="log in filteredSortedLogs"
                :key="log.id"
                class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
              >
                <td class="py-4 px-6 text-gray-700 font-medium">{{ log.id }}</td>
                <td class="py-4 px-6 text-gray-800">{{ log.name }}</td>
                <td class="py-4 px-6">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="log.role === 'Admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'">
                    {{ log.role }}
                  </span>
                </td>
                <td class="py-4 px-6 text-gray-800">{{ log.action }}</td>
                <td class="py-4 px-6 text-gray-600">{{ formatTimestamp(log.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- No Logs State -->
        <div v-else class="flex flex-col items-center justify-center py-16">
          <i class="fas fa-info-circle text-6xl text-gray-300 mb-4"></i>
          <p class="text-xl text-gray-500 font-semibold mb-2">No Activity Logs Found</p>
          <p class="text-gray-400 text-center">There are no logs matching your current filters.</p>
        </div>
      </div>

    </main>
    <SiteFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import SiteHeader from './AdminChoiceHeader.vue'
import SiteFooter from '../footer.vue'

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
