<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

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

  <!-- Card 2: Fruit Sales -->
  <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
    <h2 class="text-sm font-medium uppercase">Fruit Sales</h2>
    <p class="text-3xl font-bold mt-2">₱52,000</p>
    <p class="text-sm text-green-600 mt-1">+6.8% from last week</p>
  </div>

  <!-- Card 3: Vegetable Sales -->
  <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
    <h2 class="text-sm font-medium uppercase">Vegetable Sales</h2>
    <p class="text-3xl font-bold mt-2">₱35,192</p>
    <p class="text-sm text-green-600 mt-1">+4.2% from last week</p>
  </div>

   <!-- Card 4: Poultry Sales -->
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
        <select
          v-model="lineChartFilter"
          class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0"
        >
          <option value="monthly">Monthly</option>
          <option value="quarterly">Quarterly</option>
          <option value="yearly">Yearly</option>
        </select>
        <button class="bg-[#FFA600] text-white px-3 py-1 text-sm rounded hover:bg-[#e29400]">
          Print
        </button>
        <button class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]">
          Export
        </button>
      </div>
    </div>
    <canvas id="lineChart" class="w-full h-full"></canvas>
  </div>

  <!-- Donut Chart -->
  <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Category Breakdown</h3>
      <div class="flex items-center space-x-2">
        <select
          v-model="pieChartFilter"
          class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0"
        >
          <option value="monthly">Monthly</option>
          <option value="quarterly">Quarterly</option>
          <option value="yearly">Yearly</option>
        </select>
        <button class="bg-[#FFA600] text-white px-3 py-1 text-sm rounded hover:bg-[#e29400]">
          Print
        </button>
        <button class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]">
          Export
        </button>
      </div>
    </div>
    <canvas id="donutChart" class="w-full h-full"></canvas>
  </div>
</div>
<div class="bg-white rounded-xl p-6 shadow-lg mt-6">
  <h3 class="text-lg font-semibold mb-4">All Inventory Items</h3>

  <table class="w-full text-sm text-left table-auto">
    <thead>
      <tr class="border-b border-gray-200 font-semibold text-gray-700">
        <th class="py-2 px-2">ID</th>
        <th class="py-2 px-2">Image</th>
        <th class="py-2 px-2">Product Name</th>
        <th class="py-2 px-2">Branch</th>
        <th class="py-2 px-2">Category</th>
        <th class="py-2 px-2">Price</th>
        <th class="py-2 px-2">Stock</th>
        <th class="py-2 px-2">Status</th>
        <th class="py-2 px-2">Date Added</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in paginatedProducts" :key="product.id" class="border-b border-gray-100 hover:bg-gray-50">
        <td class="py-2 px-2">{{ product.id }}</td>
        <td class="py-2 px-2">
          <img :src="product.image" class="w-10 h-10 object-cover rounded" />
        </td>
        <td class="py-2 px-2">{{ product.name }}</td>
        <td class="py-2 px-2">{{ product.branch }}</td>
        <td class="py-2 px-2">{{ product.category }}</td>
        <td class="py-2 px-2">₱{{ product.price }} <span class="text-xs text-gray-500">{{ product.unit }}</span></td>
        <td class="py-2 px-2">{{ product.stock }} <span class="text-xs text-gray-500">{{ product.unit }}</span></td>
        <td class="py-2 px-2">
          <span :class="product.status === 'Available' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
            {{ product.status }}
          </span>
        </td>
        <td class="py-2 px-2">{{ product.dateAdded }}</td>
      </tr>
    </tbody>
  </table>

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
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'
import AdminSidebar from './adminSidebar.vue'

const lineChartFilter = ref('monthly')
const pieChartFilter = ref('monthly')

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
