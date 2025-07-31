<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />
    <!-- Main Body -->
        <main class="p-8 flex-1 ml-64 bg-[#f5f5f5] min-h-screen">
          <div class="max-w-7xl mx-auto space-y-6">
            <!-- Title Section -->
            <div class="flex items-center justify-between">
              <h2 class="text-3xl font-bold text-[#5F1213] tracking-wide">Use-of-Facilities Reports</h2>
            </div>

            <!-- Search & Filter Card -->
            <div class="flex flex-col gap-6">

              <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <!-- Left: Search + Filter -->
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                  <!-- Search Input -->
                  <div class="w-full sm:w-2/3 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search by Name or Plate</label>
                    <i class="fas fa-search absolute left-3 top-10 sm:top-9 text-gray-400"></i>
                    <input
                      v-model="searchQuery"
                      type="text"
                      placeholder="e.g. Juan Dela Cruz or ABC123"
                      class="pl-10 pr-4 py-2 border border-gray-300 bg-gray-50 text-gray-800 rounded-md w-full focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                    />
                  </div>

                  <!-- Filter Dropdown -->
                  <div class="w-full sm:w-1/3">
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
              <select
                v-model="filterStatus"
                class="h-11 border border-gray-300 p-2 w-full bg-gray-50 text-gray-800 rounded-md text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none"
              >
                <option value="All">All</option>
                <option value="Active">Active</option>
                <option value="Completed">Completed</option>
              </select>
            </div>

            </div>

            <!-- Right: Print Button -->
            <div class="w-full lg:w-auto">
              <label class="block text-sm font-medium text-transparent mb-1">Print</label>
              <button
          @click="printLogs"
          class="flex items-center justify-center gap-2 bg-[#5F1213] text-white px-5 py-2 rounded-md shadow hover:bg-yellow-500 hover:text-[#5F1213] transition w-48"
        >
          <i class="fas fa-print"></i> Print Logs
        </button>

            </div>
          </div>
        </div>


            <!-- Table Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
              <table class="w-full text-sm text-left text-gray-700">
              <thead class="bg-[#5F1213] text-white text-xs uppercase">
                <tr>
                  <th class="px-6 py-3">No.</th>
                  <th class="px-6 py-3">Name</th>
                  <th class="px-6 py-3">Plate No.</th>
                  <th class="px-6 py-3">Date & Time IN</th>
                  <th class="px-6 py-3">Date & Time OUT</th>
                  <th class="px-6 py-3">Total Hours</th>
                  <th class="px-6 py-3">Total Amount</th>
                  <th class="px-6 py-3">Status</th>
                  <th class="px-6 py-3">QR</th>
                  <th class="px-6 py-3">Download POS</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(log, index) in filteredLogs"
                  :key="index"
                  class="border-t hover:bg-yellow-50 transition"
                >
                  <td class="px-6 py-4 font-medium">{{ index + 1 }}</td>
                  <td class="px-6 py-4">{{ log.name }}</td>
                  <td class="px-6 py-4">{{ log.plate }}</td>
                  <td class="px-6 py-4">{{ log.in }}</td>
                  <td class="px-6 py-4">{{ log.out }}</td>
                  <td class="px-6 py-4">{{ log.hours }}</td>
                  <td class="px-6 py-4">{{ log.amount }}</td>
                  <td class="px-6 py-4">
                    <span
                      :class="log.status === 'Active'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-200 text-gray-600'"
                      class="px-3 py-1 rounded-full text-xs font-semibold"
                    >
                      {{ log.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <img :src="log.qrCode" alt="QR Code" class="w-8 h-8" />
                  </td>
                  <td class="px-6 py-4">
                    <a
                      :href="log.posLink"
                      target="_blank"
                      class="text-blue-600 hover:underline text-xs"
                      download
                    >
                      Download
                    </a>
                  </td>
                </tr>
                <tr v-if="filteredLogs.length === 0">
                  <td colspan="10" class="text-center px-6 py-4 text-gray-500">
                    No matching logs found.
                  </td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
          <!-- Page Navigation -->
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
import Sidebar from './sidebar.vue'

const logs = ref([
  {
    name: 'Juan Dela Cruz',
    plate: 'ABC123',
    in: '2025-07-24 08:00',
    out: '2025-07-24 11:00',
    hours: 3,
    amount: '₱60',
    status: 'Active',
  },
  {
    name: 'Maria Clara',
    plate: 'XYZ456',
    in: '2025-07-24 09:30',
    out: '2025-07-24 12:00',
    hours: 2.5,
    amount: '₱50',
    status: 'Completed',
  },
])

const searchQuery = ref('')
const filterStatus = ref('All')

// Auto Filtered Logs
const filteredLogs = computed(() => {
  return logs.value.filter((log) => {
    const matchesSearch =
      log.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      log.plate.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus =
      filterStatus.value === 'All' || log.status === filterStatus.value
    return matchesSearch && matchesStatus
  })
})

// Print Logs Handler
const printLogs = () => {
  window.print()
}

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

@media print {
  aside,
  nav,
  button,
  input,
  select,
  h2 {
    display: none !important;
  }
  table {
    font-size: 12px;
  }
}


/* Print button hover */
button:hover {
  transform: scale(1.02);
  box-shadow: 0 3px 6px rgba(95, 18, 19, 0.3);
}

/* Input & Select styling */
input,
select {
  outline: none;
  border: 1px solid #ccc;
  transition: 0.2s ease;
}

input:focus,
select:focus {
  border-color: #ffa600;
  box-shadow: 0 0 0 2px rgba(255, 166, 0, 0.3);
}

/* Table hover */
tbody tr:hover {
  background-color: #fef3c7;
  cursor: pointer;
}

/* Print styles optimization */
@media print {
  body {
    background: white !important;
    color: black !important;
  }

  aside,
  nav,
  .text-yellow-500,
  button,
  input,
  select {
    display: none !important;
  }

  table {
    border: 1px solid #000 !important;
    font-size: 12px;
  }

  th,
  td {
    border: 1px solid #000 !important;
  }
}
</style>