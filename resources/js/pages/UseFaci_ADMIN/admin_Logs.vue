<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      
      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border flex items-center space-x-4">
        <i class="fas fa-file-alt text-3xl text-[#FFA600]"></i>
        <h1 class="text-2xl font-semibold">Admin Logs</h1>
      </div>

      <!-- Sorting & Filters -->
      <div class="bg-white rounded-xl p-4 mb-4 shadow border flex items-center gap-4">
        <label class="font-medium text-sm">Filter by Action:</label>
        <select v-model="actionFilter" class="border rounded px-3 py-2 focus:ring-2 focus:ring-[#5F1213]">
          <option value="">All</option>
          <option v-for="action in actionTypes" :key="action" :value="action">{{ action }}</option>
        </select>

        <label class="font-medium text-sm">Sort by Date:</label>
        <select v-model="sortOption" class="border rounded px-3 py-2 focus:ring-2 focus:ring-[#5F1213]">
          <option value="asc">Oldest First</option>
          <option value="desc">Newest First</option>
        </select>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Activity Logs</h2>
        <table class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-3">ID</th>
              <th class="py-2 px-3">Admin</th>
              <th class="py-2 px-3">Action</th>
              <th class="py-2 px-3">Description</th>
              <th class="py-2 px-3">Date & Time</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="log in filteredSortedLogs"
              :key="log.id"
              class="border-b border-gray-100 hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ log.id }}</td>
              <td class="py-2 px-3">{{ log.admin }}</td>
              <td class="py-2 px-3">{{ log.action }}</td>
              <td class="py-2 px-3">{{ log.description }}</td>
              <td class="py-2 px-3">{{ log.timestamp }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AdminSidebar from './adminSidebar.vue'

const sortOption = ref('desc')
const actionFilter = ref('')

// Dummy logs data
const logs = ref([
  { id: 1, admin: 'John Doe', action: 'Create', description: 'Created booking for Deluxe Room 101', timestamp: '2025-09-10 08:15:00' },
  { id: 2, admin: 'Jane Smith', action: 'Update', description: 'Approved booking for Conference Hall A', timestamp: '2025-09-11 10:30:00' },
  { id: 3, admin: 'Michael Lee', action: 'Delete', description: 'Deleted booking for Tractor', timestamp: '2025-09-12 14:45:00' },
  { id: 4, admin: 'John Doe', action: 'Update', description: 'Changed status to Pending for Room B', timestamp: '2025-09-13 09:20:00' }
])

const actionTypes = computed(() => [...new Set(logs.value.map(l => l.action))])

// Filter by action, then sort by date
const filteredSortedLogs = computed(() => {
  const filtered = actionFilter.value
    ? logs.value.filter(l => l.action === actionFilter.value)
    : [...logs.value]

  return filtered.sort((a, b) => {
    const timeA = new Date(a.timestamp)
    const timeB = new Date(b.timestamp)
    return sortOption.value === 'asc' ? timeA - timeB : timeB - timeA
  })
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
