<template>
  <div class="min-h-screen flex font-sans bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-6 pt-4 text-[#5F1213] flex flex-col">
      <!-- Page Header -->
      <div class="bg-white w-full rounded-xl p-6 mb-6 shadow-lg border-l-4 border-[#5F1213]">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-[#5F1213] mb-2">Facility Reports</h1>
            <p class="text-gray-600">Comprehensive booking and facility usage analytics</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Generated on</p>
            <p class="text-lg font-semibold text-[#5F1213]">{{ currentDate }}</p>
          </div>
        </div>
      </div>

      <!-- Summary Statistics Cards -->
      <div v-if="selectedReport && filteredData.length > 0" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-blue-500">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Bookings</p>
              <p class="text-2xl font-bold text-blue-600">{{ totalBookings }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Confirmed</p>
              <p class="text-2xl font-bold text-green-600">{{ confirmedBookings }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-orange-500">
          <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-full">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Revenue</p>
              <p class="text-2xl font-bold text-orange-600">₱{{ totalRevenue.toLocaleString() }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-purple-500">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Occupancy Rate</p>
              <p class="text-2xl font-bold text-purple-600">{{ occupancyRate }}%</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Report Controls -->
      <div class="bg-white w-full rounded-xl p-6 mb-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
          <!-- Category -->
          <div class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Category</label>
            <select
              v-model="selectedReport"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
              @change="fetchReportsData"
            >
              <option value="">All Categories</option>
              <option v-for="cat in allCategories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <!-- Period -->
          <div v-if="selectedReport" class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Period</label>
            <select
              v-model="selectedPeriod"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
            >
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>

          <!-- Client Type Filter -->
          <div v-if="selectedReport" class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Client Type</label>
            <select
              v-model="clientTypeFilter"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
            >
              <option value="">All Clients</option>
              <option value="student">Students</option>
              <option value="external">External</option>
            </select>
          </div>

          <!-- Search -->
          <div class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Search</label>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search clients or facilities..."
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
            />
          </div>

          <!-- Date From -->
          <div v-if="selectedReport" class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">From Date</label>
            <input
              type="date"
              v-model="startDate"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
              @change="fetchReportsData"
            />
          </div>

          <!-- Date To -->
          <div v-if="selectedReport" class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">To Date</label>
            <input
              type="date"
              v-model="endDate"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
              @change="fetchReportsData"
            />
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mt-6">
          <button
            @click="fetchReportsData"
            class="bg-[#5F1213] text-white px-6 py-2 rounded-lg hover:bg-[#4a0e11] transition-colors flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Generate Report
          </button>

          <button
            @click="resetFilters"
            class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reset Filters
          </button>
        </div>
      </div>

      <!-- Report Table -->
      <div
        v-if="selectedReport && filteredData.length > 0"
        class="bg-white w-full rounded-xl shadow-lg overflow-hidden"
      >
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-xl font-semibold text-[#5F1213]">{{ reportTitle }}</h3>
              <p class="text-sm text-gray-600 mt-1">{{ filteredData.length }} bookings found</p>
            </div>

            <!-- Export Dropdown -->
            <div class="relative">
              <button
                @click="toggleDropdown"
                class="bg-[#FFA600] text-white px-4 py-2 rounded-lg hover:bg-[#e29400] transition-colors flex items-center gap-2 shadow-md"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export <span class="ml-1">▼</span>
              </button>
              <div
                v-show="showDropdown"
                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
              >
                <button
                  v-for="format in formats"
                  :key="format"
                  @click="exportReport(format)"
                  class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors flex items-center gap-2 text-sm"
                >
                  <svg v-if="format === 'pdf'" class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else-if="format === 'excel'" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                  </svg>
                  {{ format.toUpperCase() }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
          <table id="report-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#5F1213]">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Facility</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Occupied</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Available</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Sales</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="(item, index) in paginatedData"
                :key="index"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate(item.date) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.time }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.client }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ item.cat }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="getStatusColor(item.status)">
                    {{ item.status || 'N/A' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.occupied }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.available }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#5F1213]">₱{{ item.total_sales.toLocaleString() }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, filteredData.length) }} of {{ filteredData.length }} results
            </div>
            <div class="flex items-center space-x-2">
              <button
                @click="currentPage = Math.max(1, currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Previous
              </button>

              <span class="text-sm text-gray-700">
                Page {{ currentPage }} of {{ totalPages }}
              </span>

              <button
                @click="currentPage = Math.min(totalPages, currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div
        v-else-if="isLoading"
        class="bg-white w-full rounded-xl p-12 shadow-lg text-center"
      >
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5F1213] mx-auto mb-4"></div>
        <p class="text-gray-600">Generating report...</p>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="bg-white w-full rounded-xl p-12 shadow-lg text-center"
      >
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Reports Available</h3>
        <p class="text-gray-500 mb-6">Select a category and date range to generate facility usage reports.</p>
        <button
          @click="showHelp = true"
          class="bg-[#5F1213] text-white px-6 py-2 rounded-lg hover:bg-[#4a0e11] transition-colors"
        >
          How to Generate Reports
        </button>
      </div>

      <!-- Help Modal -->
      <div v-if="showHelp" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-[#5F1213]">How to Generate Reports</h3>
            <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div class="space-y-3 text-sm text-gray-600">
            <p><strong>1.</strong> Select a category from the dropdown</p>
            <p><strong>2.</strong> Choose a date range (optional)</p>
            <p><strong>3.</strong> Click "Generate Report" to view data</p>
            <p><strong>4.</strong> Use filters to refine results</p>
            <p><strong>5.</strong> Export data in PDF, Excel, or Word format</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import AdminSidebar from "./sidebar.vue"
import axios from "axios"
import jsPDF from "jspdf"
import autoTable from "jspdf-autotable"

// Reactive variables
const selectedReport = ref("")
const selectedPeriod = ref("daily")
const searchQuery = ref("")
const clientTypeFilter = ref("")
const showDropdown = ref(false)
const showHelp = ref(false)
const isLoading = ref(false)
const formats = ["pdf", "excel", "csv"]

// Date range
const startDate = ref("")
const endDate = ref("")

// Pagination
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Real data from backend
const reportData = ref([])
const allCategories = ref([])

// Computed properties
const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const totalBookings = computed(() => {
  return filteredData.value.length
})

const confirmedBookings = computed(() => {
  return filteredData.value.filter(item => item.status === 'confirmed').length
})

const totalRevenue = computed(() => {
  return filteredData.value.reduce((sum, item) => sum + item.total_sales, 0)
})

const occupancyRate = computed(() => {
  if (filteredData.value.length === 0) return 0
  const totalOccupied = filteredData.value.reduce((sum, item) => sum + item.occupied, 0)
  const totalAvailable = filteredData.value.reduce((sum, item) => sum + item.available, 0)
  const totalCapacity = totalOccupied + totalAvailable
  return totalCapacity > 0 ? Math.round((totalOccupied / totalCapacity) * 100) : 0
})

const reportTitle = computed(() => {
  if (!selectedReport.value) return "Facility Reports"
  const selectedCat = allCategories.value.find(cat => cat.id == selectedReport.value)
  const catName = selectedCat ? selectedCat.name : selectedReport.value
  const period = selectedPeriod.value.charAt(0).toUpperCase() + selectedPeriod.value.slice(1)
  return `${catName} Report - ${period}`
})

const filteredData = computed(() => {
  let data = reportData.value

  // Search filter
  if (searchQuery.value) {
    data = data.filter(
      (item) =>
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.client.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.cat.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  // Client type filter
  if (clientTypeFilter.value) {
    if (clientTypeFilter.value === 'student') {
      data = data.filter((item) => item.client.toLowerCase().includes('student'))
    } else if (clientTypeFilter.value === 'external') {
      data = data.filter((item) => !item.client.toLowerCase().includes('student'))
    }
  }

  // Date range filter (additional client-side filtering)
  if (startDate.value && endDate.value) {
    data = data.filter(
      (item) => item.date >= startDate.value && item.date <= endDate.value
    )
  }

  return data
})

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredData.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredData.value.length / itemsPerPage.value)
})

// Functions
const fetchReportsData = async () => {
  isLoading.value = true
  try {
    const params = {}
    if (selectedReport.value) params.cat = selectedReport.value
    if (selectedPeriod.value) params.period = selectedPeriod.value
    if (startDate.value) params.start_date = startDate.value
    if (endDate.value) params.end_date = endDate.value

    const response = await axios.get('/use/reportsdata', { params })
    if (response.data.success) {
      reportData.value = response.data.data
      allCategories.value = response.data.categories
    }
  } catch (error) {
    console.error('Error fetching reports data:', error)
    reportData.value = []
    allCategories.value = []
  } finally {
    isLoading.value = false
  }
}

const resetFilters = () => {
  searchQuery.value = ""
  clientTypeFilter.value = ""
  startDate.value = ""
  endDate.value = ""
  currentPage.value = 1
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'confirmed':
      return 'bg-green-100 text-green-800'
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

const exportReport = (format) => {
  if (format === "pdf") {
    const doc = new jsPDF('l', 'mm', 'a4') // landscape orientation
    doc.setFontSize(16)
    doc.text(reportTitle.value, 14, 20)
    doc.setFontSize(10)
    doc.text(`Generated on: ${currentDate.value}`, 14, 30)
    doc.text(`Total Records: ${filteredData.value.length}`, 14, 35)

    autoTable(doc, {
      html: "#report-table",
      startY: 45,
      styles: {
        fontSize: 8,
        cellPadding: 2,
      },
      headStyles: {
        fillColor: [95, 18, 19], // #5F1213
        textColor: 255,
      },
      alternateRowStyles: {
        fillColor: [245, 245, 245],
      },
    })
    doc.save(`${reportTitle.value.replace(/[^a-z0-9]/gi, '_').toLowerCase()}.pdf`)
  } else if (format === "excel") {
    // Simple CSV export as Excel alternative
    let csv = 'Date,Time,Client,Facility,Category,Status,Occupied,Available,Total Sales\n'
    filteredData.value.forEach(item => {
      csv += `"${item.date}","${item.time}","${item.client}","${item.name}","${item.cat}","${item.status || 'N/A'}","${item.occupied}","${item.available}","${item.total_sales}"\n`
    })

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', `${reportTitle.value.replace(/[^a-z0-9]/gi, '_').toLowerCase()}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } else if (format === "csv") {
    let csv = 'Date,Time,Client,Facility,Category,Status,Occupied,Available,Total Sales\n'
    filteredData.value.forEach(item => {
      csv += `"${item.date}","${item.time}","${item.client}","${item.name}","${item.cat}","${item.status || 'N/A'}","${item.occupied}","${item.available}","${item.total_sales}"\n`
    })

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', `${reportTitle.value.replace(/[^a-z0-9]/gi, '_').toLowerCase()}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }
}

// Fetch initial data on component mount
onMounted(() => {
  fetchReportsData()
})
</script>
