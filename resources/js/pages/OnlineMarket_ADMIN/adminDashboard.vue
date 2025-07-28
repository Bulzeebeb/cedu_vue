<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      <!-- Header -->
      <SiteHeader />

      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border">
        <div class="flex items-center space-x-4">
          <i class="i-icon-park-outline-dashboard text-3xl text-black"></i>
          <h1 class="text-2xl font-semibold">Dashboard</h1>
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
          <h2 class="text-sm font-medium uppercase">Crop Sales</h2>
          <p class="text-3xl font-bold mt-2">₱87,192</p>
          <p class="text-sm text-green-600 mt-1">+7.5% from last week</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Poultry Sales</h2>
          <p class="text-3xl font-bold mt-2">₱58,128</p>
          <p class="text-sm text-green-600 mt-1">+12.0% from last week</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Completed Orders</h2>
          <p class="text-3xl font-bold mt-2">312</p>
          <p class="text-sm text-green-600 mt-1">↑ +24 from yesterday</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Line Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Sales Report</h3>
            <select
              v-model="lineChartFilter"
              class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0"
            >
              <option value="monthly">Monthly</option>
              <option value="quarterly">Quarterly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          <canvas id="lineChart" class="w-full h-full"></canvas>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Category Breakdown</h3>
            <select
              v-model="pieChartFilter"
              class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0"
            >
              <option value="monthly">Monthly</option>
              <option value="quarterly">Quarterly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          <canvas id="donutChart" class="w-full h-full"></canvas>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
        <table class="w-full text-sm text-left">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2">ID</th>
              <th class="py-2 px-2">Name</th>
              <th class="py-2 px-2">Amount</th>
              <th class="py-2 px-2">Order Time</th>
              <th class="py-2 px-2">Status</th>
              <th class="py-2 px-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
              <td class="py-2 px-2">#001</td>
              <td class="py-2 px-2">Banana Chips</td>
              <td class="py-2 px-2">₱120</td>
              <td class="py-2 px-2">2025-07-16 10:12 AM</td>
              <td class="py-2 px-2 text-green-600 font-medium">Paid</td>
              <td class="py-2 px-2 space-x-2">
                <button class="text-blue-600 hover:underline text-xs">Print</button>
                <button class="text-indigo-600 hover:underline text-xs">View</button>
              </td>
            </tr>
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
              <td class="py-2 px-2">#002</td>
              <td class="py-2 px-2">T-Shirt</td>
              <td class="py-2 px-2">₱250</td>
              <td class="py-2 px-2">2025-07-16 11:45 AM</td>
              <td class="py-2 px-2 text-yellow-600 font-medium">Pending</td>
              <td class="py-2 px-2 space-x-2">
                <button class="text-blue-600 hover:underline text-xs">Print</button>
                <button class="text-indigo-600 hover:underline text-xs">View</button>
              </td>
            </tr>
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
              <td class="py-2 px-2">#003</td>
              <td class="py-2 px-2">Eco Bag</td>
              <td class="py-2 px-2">₱80</td>
              <td class="py-2 px-2">2025-07-16 1:03 PM</td>
              <td class="py-2 px-2 text-green-600 font-medium">Paid</td>
              <td class="py-2 px-2 space-x-2">
                <button class="text-blue-600 hover:underline text-xs">Print</button>
                <button class="text-indigo-600 hover:underline text-xs">View</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'
import SiteHeader from './adminHeaderbar.vue'
import Sidebar from './adminSidebar.vue'

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
