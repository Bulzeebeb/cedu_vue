<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <AdminSidebar />

    <main class="ml-64 flex-1 p-6 pt-4 text-[#5F1213]">
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
          <p class="text-3xl font-bold mt-2">₱{{ totalSales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.totalSalesGrowth !== null"
             :class="stats.totalSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'"
             class="text-sm mt-1">
            {{ stats.totalSalesGrowth >= 0 ? '+' : '' }}{{ stats.totalSalesGrowth }}% since last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Crop Sales</h2>
          <p class="text-3xl font-bold mt-2">₱{{ stats.cropSales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.cropSalesGrowth !== null"
             :class="stats.cropSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'"
             class="text-sm mt-1">
            {{ stats.cropSalesGrowth >= 0 ? '+' : '' }}{{ stats.cropSalesGrowth }}% from last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Poultry Sales</h2>
          <p class="text-3xl font-bold mt-2">₱{{ stats.poultrySales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.poultrySalesGrowth !== null"
             :class="stats.poultrySalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'"
             class="text-sm mt-1">
            {{ stats.poultrySalesGrowth >= 0 ? '+' : '' }}{{ stats.poultrySalesGrowth }}% from last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Total Orders</h2>
          <p class="text-3xl font-bold mt-2">{{ stats.totalOrders?.toLocaleString() ?? 0 }}</p>
          <p class="text-sm text-blue-600 mt-1">{{ stats.pendingOrders }} pending • {{ stats.completedOrders }} completed</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Line Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Sales Report</h3>
            <div class="flex items-center space-x-2">
              <select v-model="lineChartFilter"
                class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
              </select>
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
            </div>
          </div>
          <canvas id="donutChart" class="w-full h-full"></canvas>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Recent Orders</h3>
          <button @click="refreshOrders" class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]">
            Refresh
          </button>
        </div>

        <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-500">
          No orders found.
        </div>

        <table v-else class="w-full text-sm text-left">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2">ID</th>
              <th class="py-2 px-2">Name</th>
              <th class="py-2 px-2">Amount</th>
              <th class="py-2 px-2">Order Date</th>
              <th class="py-2 px-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50">
              <td class="py-2 px-2">#{{ order.id.toString().padStart(3, '0') }}</td>
              <td class="py-2 px-2">{{ order.first_name }} {{ order.last_name }}</td>
              <td class="py-2 px-2">₱{{ order.total_amount }}</td>
              <td class="py-2 px-2">{{ formatDate(order.display_date || order.created_at) }}</td>
              <td class="py-2 px-2">
                <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ formatStatus(order.status) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount, computed } from 'vue'
import Chart from 'chart.js/auto'
import AdminSidebar from './adminSidebar.vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const stats = computed(() => ({
  totalSales: page.props.stats?.total_sales ?? 0,
  cropSales: page.props.stats?.crop_sales ?? 0,
  poultrySales: page.props.stats?.poultry_sales ?? 0,
  completedOrders: page.props.stats?.completed_orders ?? 0,
  pendingOrders: page.props.stats?.pending_orders ?? 0,
  totalOrders: page.props.stats?.total_orders ?? 0,
  totalSalesGrowth: page.props.stats?.total_sales_growth ?? null,
  cropSalesGrowth: page.props.stats?.crop_sales_growth ?? null,
  poultrySalesGrowth: page.props.stats?.poultry_sales_growth ?? null,
  ordersGrowth: page.props.stats?.orders_growth ?? null
}))

const totalSales = computed(() => stats.value.totalSales)
const recentOrders = ref(page.props.recentOrders ?? [])
const chartData = page.props.chartData ?? {
  lineChart: {
    monthly: { labels: [], totalSales: [], cropSales: [], poultrySales: [] },
    quarterly: { labels: [], totalSales: [], cropSales: [], poultrySales: [] },
    yearly: { labels: [], totalSales: [], cropSales: [], poultrySales: [] }
  },
  pieChart: {
    monthly: [0, 0, 0],
    quarterly: [0, 0, 0],
    yearly: [0, 0, 0]
  }
}

const lineChartFilter = ref('monthly')
const pieChartFilter = ref('monthly')

let lineChartInstance = null
let donutChartInstance = null

// Helper functions
function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = dateString instanceof Date ? dateString : new Date(dateString)
  if (isNaN(date.getTime())) return 'Invalid Date'
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function formatStatus(status) {
  if (!status) return 'Unknown'
  // Handle both capitalized and lowercase statuses
  const statusLower = status.toLowerCase()
  switch (statusLower) {
    case 'completed':
    case 'paid':
      return 'Completed'
    case 'pending':
      return 'Pending'
    case 'cancelled':
      return 'Cancelled'
    default:
      return status.charAt(0).toUpperCase() + status.slice(1)
  }
}

function getStatusClass(status) {
  if (!status) return 'bg-gray-100 text-gray-800'

  const statusLower = status.toLowerCase()
  switch (statusLower) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'completed':
    case 'paid':
      return 'bg-green-100 text-green-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

function refreshOrders() {
  router.reload({ only: ['recentOrders', 'stats'] })
}

function renderLineChart() {
  const ctx = document.getElementById('lineChart')
  if (lineChartInstance) lineChartInstance.destroy()

  const currentData = chartData.lineChart[lineChartFilter.value]
  const labels = currentData.labels
  const totalSalesData = currentData.totalSales
  const cropSalesData = currentData.cropSales
  const poultrySalesData = currentData.poultrySales

  lineChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Total Sales',
          data: totalSalesData,
          borderColor: '#FFA600',
          backgroundColor: 'rgba(255,166,0,0.2)',
          fill: true,
          tension: 0.4
        },
        {
          label: 'Crop Sales',
          data: cropSalesData,
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76,175,80,0.2)',
          fill: true,
          tension: 0.4
        },
        {
          label: 'Poultry Sales',
          data: poultrySalesData,
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
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '₱' + value.toLocaleString()
            }
          }
        }
      }
    }
  })
}

function renderDonutChart() {
  const ctx = document.getElementById('donutChart')
  if (donutChartInstance) donutChartInstance.destroy()

  const currentData = chartData.pieChart[pieChartFilter.value]
  const total = currentData.reduce((sum, val) => sum + val, 0)
  const hasData = total > 0

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Fruits', 'Vegetables', 'Poultry'],
      datasets: [{
        data: hasData ? currentData : [1, 1, 1],
        backgroundColor: hasData ? ['#FFA600', '#5F1213', '#FF6384'] : ['#e5e5e5', '#e5e5e5', '#e5e5e5'],
        borderWidth: hasData ? 0 : 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '60%',
      layout: {
        padding: { top: 20, bottom: 30, left: 10, right: 10 }
      },
      plugins: {
        legend: {
          display: hasData,
          position: 'bottom'
        },
        tooltip: {
          enabled: hasData,
          callbacks: hasData ? {
            label: function(context) {
              const value = context.parsed
              const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0
              return `${context.label}: ₱${value.toLocaleString()} (${percentage}%)`
            }
          } : { label: () => 'No data available' }
        }
      }
    }
  })

  if (!hasData) {
    const centerText = {
      id: 'centerText',
      beforeDraw: function(chart) {
        const ctx = chart.ctx
        ctx.save()
        ctx.font = '16px Arial'
        ctx.fillStyle = '#666'
        ctx.textAlign = 'center'
        ctx.textBaseline = 'middle'
        const centerX = (chart.chartArea.left + chart.chartArea.right) / 2
        const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2
        ctx.fillText('No Data Available', centerX, centerY)
        ctx.restore()
      }
    }
    Chart.register(centerText)
  }
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
