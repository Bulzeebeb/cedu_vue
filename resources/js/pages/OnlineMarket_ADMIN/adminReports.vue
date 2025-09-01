<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 pt-4 text-[#5F1213]">
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
          <p class="text-3xl font-bold mt-2">₱{{ totalSales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.totalSalesGrowth !== null"
            :class="stats.totalSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm mt-1">
            {{ stats.totalSalesGrowth >= 0 ? '+' : '' }}{{ stats.totalSalesGrowth }}% since last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Crop Sales</h2>
          <p class="text-3xl font-bold mt-2">₱{{ stats.cropSales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.cropSalesGrowth !== null"
            :class="stats.cropSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm mt-1">
            {{ stats.cropSalesGrowth >= 0 ? '+' : '' }}{{ stats.cropSalesGrowth }}% from last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Poultry Sales</h2>
          <p class="text-3xl font-bold mt-2">₱{{ stats.poultrySales?.toLocaleString() ?? 0 }}</p>
          <p v-if="stats.poultrySalesGrowth !== null"
            :class="stats.poultrySalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm mt-1">
            {{ stats.poultrySalesGrowth >= 0 ? '+' : '' }}{{ stats.poultrySalesGrowth }}% from last week
          </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
          <h2 class="text-sm font-medium uppercase">Total Orders</h2>
          <p class="text-3xl font-bold mt-2">{{ stats.totalOrders?.toLocaleString() ?? 0 }}</p>
          <p class="text-sm text-blue-600 mt-1">{{ stats.pendingOrders }} pending • {{ stats.completedOrders }}
            completed</p>
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
            <select v-model="selectedBranch"
              class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#FFA600] focus:border-[#FFA600]">
              <option value="">All Branches</option>
              <option value="Tagum">Tagum</option>
              <option value="Mabini">Mabini</option>
            </select>

            <select v-model="selectedCategory"
              class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#FFA600] focus:border-[#FFA600]">
              <option value="">All Categories</option>
              <option value="Fruit">Fruit</option>
              <option value="Vegetable">Vegetable</option>
              <option value="Poultry">Poultry</option>
            </select>
          </div>

          <input type="text" v-model="searchQuery" placeholder="Search product..."
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full max-w-xs focus:ring-[#FFA600] focus:border-[#FFA600]" />
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow border overflow-x-auto">
          <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-[#FFA600] text-white text-center">
              <tr>
                <th class="px-6 py-3">Image</th>
                <th class="px-6 py-3">Product</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Branch</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Stock</th>
                <th class="px-6 py-3">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in paginatedProducts" :key="product.id"
                class="border-b border-gray-100 hover:bg-gray-50 text-center">
                <td class="py-2 px-2 text-center">
                  <div class="flex justify-center items-center">
                    <img :src="getProductImage(product.image)" class="w-10 h-10 object-cover rounded" />
                  </div>
                </td>

                <td class="py-2 px-2">{{ product.name }}</td>
                <td class="py-2 px-2">{{ product.category }}</td>
                <td class="py-2 px-2">{{ product.branch }}</td>
                <td class="py-2 px-2">₱{{ product.price }} <span class="text-xs text-gray-500">{{ product.unit }}</span>
                </td>
                <td class="py-2 px-2">{{ product.stock }} <span class="text-xs text-gray-500">{{ product.unit }}</span>
                </td>
                <td class="py-2 px-2">
                  <span :class="getStatusClass(product.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ formatStatus(product.status) }}
                  </span>
                </td>

              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end mt-4 space-x-2 text-sm">
          <button @click="currentPage--" :disabled="currentPage === 1"
            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>
          <span class="px-3 py-1">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="currentPage++" :disabled="currentPage === totalPages"
            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
        </div>
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
const props = defineProps({
  products: Array
})

const selectedIds = ref([])

const selectedProduct = ref(null)
const currentPage = ref(1)
const perPage = 5

const allSelected = computed(() => selectedIds.value.length === props.products.length)
const toggleSelectAll = () => {
  selectedIds.value = allSelected.value ? [] : props.products.map(p => p.id)
}

const totalPages = computed(() => Math.ceil(props.products.length / perPage))

const sortKey = ref('')
const sortAsc = ref(true)

const sortBy = (key) => {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value
  } else {
    sortKey.value = key
    sortAsc.value = true
  }
}

const getSortIcon = (key) => {
  if (sortKey.value !== key) return '⇅'
  return sortAsc.value ? '↑' : '↓'
}

const sortedProducts = computed(() => {
  const list = [...props.products]
  if (!sortKey.value) return list
  return list.sort((a, b) => {
    const aVal = a[sortKey.value]
    const bVal = b[sortKey.value]
    return typeof aVal === 'number'
      ? (sortAsc.value ? aVal - bVal : bVal - aVal)
      : (sortAsc.value ? String(aVal).localeCompare(bVal) : String(bVal).localeCompare(aVal))
  })
})

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedProducts.value.slice(start, start + perPage)
})


const stats = computed(() => ({
  totalSales: page.props.stats?.total_sales ?? 0,
  cropSales: page.props.stats?.crop_sales ?? 0,
  poultrySales: page.props.stats?.poultry_sales ?? 0,
  completedOrders: page.props.stats?.completed_orders ?? 0,
  pendingOrders: page.props.stats?.pending_orders ?? 0,
  totalOrders: page.props.stats?.total_orders ?? 0,
  // Comparison data from backend
  totalSalesGrowth: page.props.stats?.total_sales_growth ?? null,
  cropSalesGrowth: page.props.stats?.crop_sales_growth ?? null,
  poultrySalesGrowth: page.props.stats?.poultry_sales_growth ?? null,
  ordersGrowth: page.props.stats?.orders_growth ?? null
}))

const totalSales = computed(() => stats.value.totalSales)
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


function getProductImage(image) {
  if (!image) return '/images/no-image.png'
  // If image is a full URL or Laravel Storage::url output
  if (image.startsWith('http') || image.startsWith('/storage/')) return image
  // If image is a relative path like './storage/products/...'
  if (image.startsWith('./storage/')) return image.replace('./', '/')
  // Otherwise, fallback to default
  return '/images/no-image.png'
}

function formatStatus(status) {
  return status.charAt(0).toUpperCase() + status.slice(1)
}

function getStatusClass(status) {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'paid':
      return 'bg-green-100 text-green-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
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
            callback: function (value) {
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

  // Show actual values if there's data, otherwise show empty chart
  const hasData = total > 0

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Fruits', 'Vegetables', 'Poultry'],
      datasets: [{
        data: hasData ? currentData : [1, 1, 1], // Show equal segments if no data
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
            label: function (context) {
              const value = context.parsed
              const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0
              return `${context.label}: ₱${value.toLocaleString()} (${percentage}%)`
            }
          } : { label: () => 'No data available' }
        }
      }
    }
  })

  // Add "No Data" text in center if no data
  if (!hasData) {
    const centerText = {
      id: 'centerText',
      beforeDraw: function (chart) {
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
