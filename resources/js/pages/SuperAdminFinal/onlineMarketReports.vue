<template>
  <div class="min-h-screen flex font-sans bg-gray-50">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-8 text-[#5F1213]">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#5F1213]">Sales Reports</h1>
        <p class="text-gray-600 mt-1">Track and analyze your online market sales performance</p>
      </div>

      <!-- Filters Section -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-[#5F1213] mb-4">Filters</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Category Filter -->
          <div class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Category</label>
            <select
              v-model="selectedReport"
              @change="fetchReportsData"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            >
              <option value="all">All Categories</option>
              <option v-for="cat in allCategories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <!-- Period Filter -->
          <div class="flex flex-col" v-if="selectedReport">
            <label class="font-semibold text-sm text-gray-700 mb-2">Period</label>
            <select
              v-model="selectedPeriod"
              @change="fetchReportsData"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            >
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>

          <!-- Date Range: From -->
          <div class="flex flex-col" v-if="selectedReport">
            <label class="font-semibold text-sm text-gray-700 mb-2">From Date</label>
            <input
              type="date"
              v-model="startDate"
              @change="fetchReportsData"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            />
          </div>

          <!-- Date Range: To -->
          <div class="flex flex-col" v-if="selectedReport">
            <label class="font-semibold text-sm text-gray-700 mb-2">To Date</label>
            <input
              type="date"
              v-model="endDate"
              @change="fetchReportsData"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            />
          </div>

          <!-- Sort By -->
          <div class="flex flex-col" v-if="selectedReport">
            <label class="font-semibold text-sm text-gray-700 mb-2">Sort By</label>
            <select
              v-model="sortKey"
              @change="applySort"
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            >
              <option value="">Default (Date)</option>
              <option value="price_high">Price: High to Low</option>
              <option value="price_low">Price: Low to High</option>
              <option value="quantity_high">Quantity: High to Low</option>
              <option value="total_high">Total: High to Low</option>
            </select>
          </div>

          <!-- Search -->
          <div class="flex flex-col">
            <label class="font-semibold text-sm text-gray-700 mb-2">Search</label>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search product or client..."
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition"
            />
          </div>

          <!-- Action Buttons -->
          <div class="flex items-end gap-2 col-span-1 md:col-span-2 lg:col-span-1">
            <button
              @click="clearFilters"
              class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-500 transition"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Summary Statistics -->
      <div v-if="selectedReport && reportData.length > 0" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Sales -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#FFA600]">
          <p class="text-gray-600 text-sm font-medium">Total Sales</p>
          <p class="text-2xl font-bold text-[#5F1213] mt-2">₱{{ formatCurrency(totalSales) }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ reportData.length }} transactions</p>
        </div>

        <!-- Total Quantity -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
          <p class="text-gray-600 text-sm font-medium">Total Quantity</p>
          <p class="text-2xl font-bold text-[#5F1213] mt-2">{{ totalQuantity }}</p>
          <p class="text-xs text-gray-500 mt-1">Units sold</p>
        </div>

        <!-- Average Sale -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
          <p class="text-gray-600 text-sm font-medium">Average Sale</p>
          <p class="text-2xl font-bold text-[#5F1213] mt-2">₱{{ formatCurrency(averageSale) }}</p>
          <p class="text-xs text-gray-500 mt-1">Per transaction</p>
        </div>

        <!-- Highest Sale -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
          <p class="text-gray-600 text-sm font-medium">Highest Sale</p>
          <p class="text-2xl font-bold text-[#5F1213] mt-2">₱{{ formatCurrency(highestSale) }}</p>
          <p class="text-xs text-gray-500 mt-1">Single transaction</p>
        </div>
      </div>

      <!-- Report Table Section -->
      <div
        v-if="selectedReport && filteredData.length > 0"
        class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden"
      >
        <!-- Header with Title and Download -->
        <div class="bg-gradient-to-r from-[#5F1213] to-[#8B1A1C] text-white p-6 flex justify-between items-center">
          <div>
            <h3 class="text-xl font-bold">{{ reportTitle }}</h3>
            <p class="text-sm text-gray-200 mt-1">{{ filteredData.length }} records</p>
          </div>

          <!-- Download Dropdown -->
          <div class="relative">
            <button
              @click="toggleDropdown"
              class="bg-[#FFA600] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#e29400] transition flex items-center gap-2 shadow-md"
            >
              <span>📥 Download</span>
              <span :class="['transition', { 'rotate-180': showDropdown }]">▼</span>
            </button>
            <div
              v-show="showDropdown"
              class="absolute right-0 mt-3 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden"
            >
              <button
                v-for="format in formats"
                :key="format"
                @click="exportReport(format)"
                class="w-full text-left px-4 py-3 hover:bg-gray-50 text-sm font-medium text-gray-700 transition border-b last:border-b-0"
              >
                📄 {{ formatLabel(format) }}
              </button>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table id="report-table" class="w-full border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Time</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Client</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Product</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Quantity</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Unit Price</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in filteredData"
                :key="index"
                class="border-b border-gray-200 hover:bg-gray-50 transition"
              >
                <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ index + 1 }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ formatDate(item.date) }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ item.time }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ item.client }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ item.product }}</td>
                <td class="px-6 py-4 text-sm">
                  <span class="inline-block bg-[#FFA600] bg-opacity-20 text-[#5F1213] px-3 py-1 rounded-full text-xs font-medium">
                    {{ item.category }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ item.quantity }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 text-right">₱{{ formatCurrency(item.price) }}</td>
                <td class="px-6 py-4 text-sm text-right font-semibold text-[#5F1213]">₱{{ formatCurrency(item.total) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Table Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-8">
          <div>
            <p class="text-sm text-gray-600">Total Quantity:</p>
            <p class="text-lg font-bold text-[#5F1213]">{{ totalQuantity }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Amount:</p>
            <p class="text-lg font-bold text-[#5F1213]">₱{{ formatCurrency(totalSales) }}</p>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="bg-white rounded-lg shadow-md p-12 text-center border border-gray-200"
      >
        <div class="text-5xl mb-4">📊</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Data Available</h3>
        <p class="text-gray-600">
          Select a category and set a date range to view detailed sales reports.
        </p>
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

const selectedReport = ref("all")
const selectedPeriod = ref("monthly")
const searchQuery = ref("")
const sortKey = ref("")
const showDropdown = ref(false)
const formats = ["pdf", "excel", "csv"]

const startDate = ref("")
const endDate = ref("")
const reportData = ref([])
const allCategories = ref([
  { id: 'fruit', name: 'Fruit' },
  { id: 'vegetables', name: 'Vegetables' },
  { id: 'poultry', name: 'Poultry' }
])

// Fetch reports data from backend
const fetchReportsData = async () => {
  try {
    const params = {}
    if (selectedReport.value) params.cat = selectedReport.value
    if (selectedPeriod.value) params.period = selectedPeriod.value
    if (startDate.value) params.start_date = startDate.value
    if (endDate.value) params.end_date = endDate.value

    const response = await axios.get('/onlinemarket/reportsdata', { params })
    if (response.data.success) {
      reportData.value = response.data.data || []
      if (response.data.categories) {
        allCategories.value = response.data.categories
      }
    } else {
      reportData.value = []
    }
  } catch (error) {
    console.error('Error fetching reports data:', error)
    reportData.value = []
  }
}

const reportTitle = computed(() => {
  if (!selectedReport.value) return "All Categories Report"
  if (selectedReport.value === "all") return `All Categories Report - ${selectedPeriod.value.charAt(0).toUpperCase() + selectedPeriod.value.slice(1)}`
  const selectedCat = allCategories.value.find(cat => cat.id === selectedReport.value)
  const catName = selectedCat ? selectedCat.name : selectedReport.value
  const period = selectedPeriod.value.charAt(0).toUpperCase() + selectedPeriod.value.slice(1)
  return `${catName} Report - ${period}`
})

const filteredData = computed(() => {
  let data = [...reportData.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    data = data.filter(
      (item) =>
        (item.product?.toLowerCase().includes(query)) ||
        (item.client?.toLowerCase().includes(query))
    )
  }

  if (startDate.value && endDate.value) {
    data = data.filter(
      (item) => item.date >= startDate.value && item.date <= endDate.value
    )
  }

  applySort(data)
  return data
})

const totalSales = computed(() => {
  return filteredData.value.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0)
})

const totalQuantity = computed(() => {
  return filteredData.value.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0)
})

const averageSale = computed(() => {
  if (filteredData.value.length === 0) return 0
  return totalSales.value / filteredData.value.length
})

const highestSale = computed(() => {
  if (filteredData.value.length === 0) return 0
  return Math.max(...filteredData.value.map(item => parseFloat(item.total) || 0))
})

function applySort(data = null) {
  const arrayToSort = data || filteredData.value
  
  if (sortKey.value === "price_high") {
    arrayToSort.sort((a, b) => parseFloat(b.price) - parseFloat(a.price))
  } else if (sortKey.value === "price_low") {
    arrayToSort.sort((a, b) => parseFloat(a.price) - parseFloat(b.price))
  } else if (sortKey.value === "quantity_high") {
    arrayToSort.sort((a, b) => parseInt(b.quantity) - parseInt(a.quantity))
  } else if (sortKey.value === "total_high") {
    arrayToSort.sort((a, b) => parseFloat(b.total) - parseFloat(a.total))
  }
  
  return arrayToSort
}

function formatCurrency(value) {
  const num = parseFloat(value) || 0
  return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatDate(dateString) {
  if (!dateString) return ""
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function formatLabel(format) {
  return format.charAt(0).toUpperCase() + format.slice(1).toUpperCase()
}

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function exportReport(format) {
  if (format === "pdf") {
    const doc = new jsPDF()
    doc.setFontSize(16)
    doc.text(reportTitle.value, 14, 15)
    doc.setFontSize(10)
    doc.text(`Generated: ${new Date().toLocaleDateString()}`, 14, 22)
    
    autoTable(doc, {
      html: "#report-table",
      startY: 30,
      didDrawPage: function(data) {
        const pageCount = doc.internal.getPages().length
        doc.setFontSize(9)
        doc.text(`Page ${data.pageNumber} of ${pageCount}`, 14, doc.internal.pageSize.getHeight() - 10)
      }
    })
    doc.save(`${reportTitle.value.replace(/\s+/g, '_')}.pdf`)
  } else if (format === "excel") {
    exportToExcel()
  } else if (format === "csv") {
    exportToCSV()
  }
  showDropdown.value = false
}

function exportToExcel() {
  const table = document.getElementById("report-table")
  const workbook = XLSX.utils.table_to_book(table)
  XLSX.writeFile(workbook, `${reportTitle.value.replace(/\s+/g, '_')}.xlsx`)
}

function exportToCSV() {
  const table = document.getElementById("report-table")
  let csv = []
  const rows = table.querySelectorAll("tr")
  
  rows.forEach(row => {
    const cols = row.querySelectorAll("td, th")
    const csvRow = []
    cols.forEach(col => {
      csvRow.push('"' + col.innerText.replace(/"/g, '""') + '"')
    })
    csv.push(csvRow.join(","))
  })
  
  const csvContent = csv.join("\n")
  const blob = new Blob([csvContent], { type: "text/csv" })
  const url = URL.createObjectURL(blob)
  const link = document.createElement("a")
  link.href = url
  link.download = `${reportTitle.value.replace(/\s+/g, '_')}.csv`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)
}

function clearFilters() {
  selectedReport.value = "all"
  selectedPeriod.value = "monthly"
  searchQuery.value = ""
  sortKey.value = ""
  startDate.value = ""
  endDate.value = ""
  fetchReportsData()
}

onMounted(() => {
  fetchReportsData()
})
</script>

