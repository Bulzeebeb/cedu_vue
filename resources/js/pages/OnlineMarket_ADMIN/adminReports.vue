<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border">
        <div class="flex items-center space-x-4">
          <i class="i-icon-park-outline-dashboard text-3xl text-black"></i>
          <h1 class="text-2xl font-semibold">Reports</h1>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Total Sales</h2>
          <p class="text-3xl font-bold mt-2">₱145,320</p>
          <p class="text-sm text-green-600 mt-1">+10.2% since last week</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Fruit Sales</h2>
          <p class="text-3xl font-bold mt-2">₱52,000</p>
          <p class="text-sm text-green-600 mt-1">+6.8% from last week</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Vegetable Sales</h2>
          <p class="text-3xl font-bold mt-2">₱35,192</p>
          <p class="text-sm text-green-600 mt-1">+4.2% from last week</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Poultry Sales</h2>
          <p class="text-3xl font-bold mt-2">₱28,192</p>
          <p class="text-sm text-green-600 mt-1">+5.2% from last week</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Line Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Sales Report</h3>
            <div class="flex items-center space-x-2">
              <select v-model="lineChartFilter" class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <button class="bg-[#FFA600] text-white px-3 py-1 text-sm rounded hover:bg-[#e29400]">Print</button>
              <button class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]">Export</button>
            </div>
          </div>
          <canvas id="lineChart" class="w-full h-full"></canvas>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Category Breakdown</h3>
            <div class="flex items-center space-x-2">
              <select v-model="pieChartFilter" class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <button class="bg-[#FFA600] text-white px-3 py-1 text-sm rounded hover:bg-[#e29400]">Print</button>
              <button class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]">Export</button>
            </div>
          </div>
          <canvas id="donutChart" class="w-full h-full"></canvas>
        </div>
      </div>

      <!-- Inventory Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg mt-6">
        <h3 class="text-lg font-semibold mb-4">All Inventory Items</h3>

        <!-- Filters -->
        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
          <div class="flex gap-4">
            <select v-model="selectedBranch" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#FFA600] focus:border-[#FFA600]">
              <option value="">All Branches</option>
              <option value="Tagum">Tagum</option>
              <option value="Mabini">Mabini</option>
            </select>

            <select v-model="selectedCategory" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#FFA600] focus:border-[#FFA600]">
              <option value="">All Categories</option>
              <option value="Fruit">Fruit</option>
              <option value="Vegetable">Vegetable</option>
              <option value="Poultry">Poultry</option>
            </select>
          </div>

          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search product..."
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full max-w-xs focus:ring-[#FFA600] focus:border-[#FFA600]"
          />
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow border overflow-x-auto">
          <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-[#FFA600] text-white">
              <tr>
                <th class="px-6 py-3">Product</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Branch</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Stock</th>
                <th class="px-6 py-3">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in paginatedReports"
                :key="index"
                class="border-t hover:bg-gray-50 transition"
              >
                <td class="px-6 py-4">{{ item.name }}</td>
                <td class="px-6 py-4">{{ item.category }}</td>
                <td class="px-6 py-4">{{ item.branch }}</td>
                <td class="px-6 py-4">₱{{ item.price }}</td>
                <td class="px-6 py-4">{{ item.stock }}</td>
                <td class="px-6 py-4">
                  <span
                    :class="{
                      'text-green-600': item.status === 'Active',
                      'text-red-500': item.status === 'Inactive'
                    }"
                  >
                    {{ item.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end mt-4 space-x-2 text-sm">
          <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>
          <span class="px-3 py-1">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'
import Sidebar from './adminSidebar.vue'

// Filters
const selectedBranch = ref('')
const selectedCategory = ref('')
const searchQuery = ref('')

// Dummy data
const reports = ref([
  { name: 'Apple', category: 'Fruit', branch: 'Tagum', price: 50, stock: 100, status: 'Active' },
  { name: 'Carrot', category: 'Vegetable', branch: 'Mabini', price: 25, stock: 80, status: 'Active' },
  { name: 'Chicken', category: 'Poultry', branch: 'Tagum', price: 150, stock: 30, status: 'Inactive' },
  { name: 'Banana', category: 'Fruit', branch: 'Mabini', price: 40, stock: 60, status: 'Active' },
  { name: 'Spinach', category: 'Vegetable', branch: 'Tagum', price: 20, stock: 90, status: 'Active' },
])

const filteredReports = computed(() => {
  return reports.value.filter((item) => {
    const matchesBranch = !selectedBranch.value || item.branch === selectedBranch.value
    const matchesCategory = !selectedCategory.value || item.category === selectedCategory.value
    const matchesSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesBranch && matchesCategory && matchesSearch
  })
})

// Pagination
const currentPage = ref(1)
const itemsPerPage = 5
const totalPages = computed(() => Math.ceil(filteredReports.value.length / itemsPerPage))
const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredReports.value.slice(start, start + itemsPerPage)
})
watch(filteredReports, () => {
  currentPage.value = 1
})

// Chart Filters
const lineChartFilter = ref('monthly')
const pieChartFilter = ref('monthly')

// Chart Data
let lineChartInstance = null
let donutChartInstance = null

const lineChartDataByFilter = {
  monthly: [15000, 21000, 18000, 22000, 19500, 25000, 24000],
  quarterly: [60000, 72000, 68000, 74000],
  yearly: [210000, 180000, 230000, 250000]
}

const lineChartLabelsByFilter = {
  monthly: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  quarterly: ['Q1', 'Q2', 'Q3', 'Q4'],
  yearly: ['2021', '2022', '2023', '2024']
}

const pieChartDataByFilter = {
  monthly: [35, 25, 40],
  quarterly: [40, 30, 30],
  yearly: [38, 27, 35]
}

function renderLineChart() {
  const ctx = document.getElementById('lineChart')
  if (lineChartInstance) lineChartInstance.destroy()

  const labels = lineChartLabelsByFilter[lineChartFilter.value]
  const total = lineChartDataByFilter[lineChartFilter.value]

  lineChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Total Sales',
          data: total,
          borderColor: '#FFA600',
          backgroundColor: 'rgba(255,166,0,0.2)',
          fill: true,
          tension: 0.4
        },
        {
          label: 'Crop Sales',
          data: total.map(v => v * 0.6),
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76,175,80,0.2)',
          fill: true,
          tension: 0.4
        },
        {
          label: 'Poultry Sales',
          data: total.map(v => v * 0.4),
          borderColor: '#2196F3',
          backgroundColor: 'rgba(33,150,243,0.2)',
          fill: true,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: 20 },
      plugins: {
        legend: { position: 'top' }
      }
    }
  })
}

function renderDonutChart() {
  const ctx = document.getElementById('donutChart')
  if (donutChartInstance) donutChartInstance.destroy()

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Fruits', 'Vegetables', 'Poultry'],
      datasets: [{
        data: pieChartDataByFilter[pieChartFilter.value],
        backgroundColor: ['#FFA600', '#5F1213', '#FF6384']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '60%',
      layout: {
        padding: { top: 20, bottom: 30, left: 10, right: 10 }
      }
    }
  })
}

onMounted(() => {
  renderLineChart()
  renderDonutChart()
})

watch(lineChartFilter, renderLineChart)
watch(pieChartFilter, renderDonutChart)

onBeforeUnmount(() => {
  if (lineChartInstance) lineChartInstance.destroy()
  if (donutChartInstance) donutChartInstance.destroy()
})
</script>

<style scoped>
canvas {
  box-sizing: border-box;
}
</style>
