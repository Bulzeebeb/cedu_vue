<template>
  <div class="min-h-screen flex font-sans bg-gray-50">
    <AdminSidebar />

    <main class="lg:ml-64 flex-1 p-4 lg:p-6 pt-4 text-[#5F1213] transition-all duration-300">
      <!-- Page Header -->
      <div class="bg-white rounded-xl p-4 lg:p-6 mb-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-[#5F1213] to-[#7d1a1e] rounded-lg">
              <i class="fas fa-tachometer-alt text-2xl text-white"></i>
            </div>
            <div>
              <h1 class="text-xl lg:text-2xl font-bold text-gray-800">Dashboard Overview</h1>
              <p class="text-sm text-gray-600">Welcome back! Here's what's happening with your online market.</p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div v-if="stats.pendingOrders > 0" class="relative">
              <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold animate-pulse">
                {{ stats.pendingOrders }}
              </div>
              <button @click="showOrdersModal = true" class="flex items-center space-x-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200">
                <i class="fas fa-bell text-sm"></i>
                <span class="hidden sm:inline">Orders Alert</span>
              </button>
            </div>
            <button @click="refreshDashboard" class="flex items-center space-x-2 px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#441011] transition-colors duration-200">
              <i class="fas fa-sync-alt text-sm"></i>
              <span class="hidden sm:inline">Refresh</span>
            </button>
            <div class="text-sm text-gray-500">
              Last updated: {{ lastUpdated }}
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Sales Card -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-lg group-hover:scale-110 transition-transform duration-200">
              <i class="fas fa-dollar-sign text-xl text-white"></i>
            </div>
            <div v-if="stats.totalSalesGrowth !== null" class="flex items-center space-x-1">
              <i :class="stats.totalSalesGrowth >= 0 ? 'fas fa-arrow-up text-green-500' : 'fas fa-arrow-down text-red-500'" class="text-sm"></i>
              <span :class="stats.totalSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                {{ stats.totalSalesGrowth >= 0 ? '+' : '' }}{{ stats.totalSalesGrowth }}%
              </span>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Sales</h3>
          <p class="text-2xl lg:text-3xl font-bold text-gray-800 mt-2">₱{{ totalSales?.toLocaleString() ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>

        <!-- Crop Sales Card -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg group-hover:scale-110 transition-transform duration-200">
              <i class="fas fa-leaf text-xl text-white"></i>
            </div>
            <div v-if="stats.cropSalesGrowth !== null" class="flex items-center space-x-1">
              <i :class="stats.cropSalesGrowth >= 0 ? 'fas fa-arrow-up text-green-500' : 'fas fa-arrow-down text-red-500'" class="text-sm"></i>
              <span :class="stats.cropSalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                {{ stats.cropSalesGrowth >= 0 ? '+' : '' }}{{ stats.cropSalesGrowth }}%
              </span>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Crop Sales</h3>
          <p class="text-2xl lg:text-3xl font-bold text-gray-800 mt-2">₱{{ stats.cropSales?.toLocaleString() ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">Fruits & Vegetables</p>
        </div>

        <!-- Poultry Sales Card -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg group-hover:scale-110 transition-transform duration-200">
              <i class="fas fa-drumstick-bite text-xl text-white"></i>
            </div>
            <div v-if="stats.poultrySalesGrowth !== null" class="flex items-center space-x-1">
              <i :class="stats.poultrySalesGrowth >= 0 ? 'fas fa-arrow-up text-green-500' : 'fas fa-arrow-down text-red-500'" class="text-sm"></i>
              <span :class="stats.poultrySalesGrowth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                {{ stats.poultrySalesGrowth >= 0 ? '+' : '' }}{{ stats.poultrySalesGrowth }}%
              </span>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Poultry Sales</h3>
          <p class="text-2xl lg:text-3xl font-bold text-gray-800 mt-2">₱{{ stats.poultrySales?.toLocaleString() ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">Meat & Poultry</p>
        </div>

        <!-- Orders Overview Card -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg group-hover:scale-110 transition-transform duration-200">
              <i class="fas fa-shopping-cart text-xl text-white"></i>
            </div>
            <div class="text-right">
              <div class="text-xs text-gray-500">This Month</div>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Orders</h3>
          <p class="text-2xl lg:text-3xl font-bold text-gray-800 mt-2">{{ stats.totalOrders?.toLocaleString() ?? 0 }}</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-xs text-yellow-600 font-medium">{{ stats.pendingOrders }} pending</span>
            <span class="text-xs text-green-600 font-medium">{{ stats.completedOrders }} completed</span>
          </div>
        </div>
      </div>

      <!-- Quick Actions & Alerts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-[#5F1213] mr-2"></i>
            Quick Actions
          </h3>
          <div class="space-y-3">
            <button @click="goToProducts" class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
              <div class="flex items-center">
                <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                <span class="text-sm font-medium">Add New Product</span>
              </div>
              <i class="fas fa-chevron-right text-gray-400"></i>
            </button>
            <button @click="goToOrders" class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
              <div class="flex items-center">
                <i class="fas fa-list text-blue-600 mr-3"></i>
                <span class="text-sm font-medium">Manage Orders</span>
              </div>
              <i class="fas fa-chevron-right text-gray-400"></i>
            </button>
            <button @click="goToReports" class="w-full flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
              <div class="flex items-center">
                <i class="fas fa-chart-bar text-purple-600 mr-3"></i>
                <span class="text-sm font-medium">View Reports</span>
              </div>
              <i class="fas fa-chevron-right text-gray-400"></i>
            </button>
          </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
            Low Stock Alert
          </h3>
          <div v-if="lowStockItems.length === 0" class="text-center py-4 text-gray-500">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
            <p class="text-sm">All products are well-stocked!</p>
          </div>
          <div v-else class="space-y-2 max-h-32 overflow-y-auto">
            <div v-for="item in lowStockItems" :key="item.id" class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
              <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <span class="text-sm font-medium text-gray-800">{{ item.name }}</span>
              </div>
              <span class="text-xs text-red-600 font-semibold">{{ item.stock }} left</span>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-indigo-600 mr-2"></i>
            Recent Activity
          </h3>
          <div class="space-y-3 max-h-32 overflow-y-auto">
            <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-3">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                  <i :class="activity.icon" :style="{ color: activity.color }" class="text-sm"></i>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800">{{ activity.message }}</p>
                <p class="text-xs text-gray-500">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Alert Modal -->
      <div v-if="showOrdersModal" class="fixed inset-0 bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <i class="fas fa-bell text-white text-xl"></i>
              <h2 class="text-xl font-bold text-white">Orders Alert</h2>
              <span v-if="pendingOrdersAlert.length > 0" class="ml-2 bg-white text-red-600 rounded-full px-3 py-1 text-sm font-bold">
                {{ pendingOrdersAlert.length }}
              </span>
            </div>
            <button @click="showOrdersModal = false" class="text-white hover:text-gray-200 transition-colors">
              <i class="fas fa-times text-2xl"></i>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="flex-1 overflow-y-auto p-6">
            <div v-if="pendingOrdersAlert.length === 0" class="text-center py-12">
              <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
              <p class="text-lg font-semibold text-gray-800">No Pending Orders</p>
              <p class="text-gray-600 mt-1">All caught up! No orders pending.</p>
            </div>

            <div v-else class="space-y-4">
              <div v-for="order in pendingOrdersAlert" :key="order.id" 
                   class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg hover:bg-red-100 transition-colors duration-200">
                <div class="flex items-start justify-between mb-3">
                  <div class="flex items-center space-x-3">
                    <div class="p-3 bg-red-100 rounded-lg">
                      <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                    </div>
                    <div>
                      <p class="font-bold text-gray-800 text-lg">Order #{{ order.id.toString().padStart(3, '0') }}</p>
                      <p class="text-sm text-gray-600">{{ order.first_name }} {{ order.last_name }}</p>
                    </div>
                  </div>
                  <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                    {{ formatStatus(order.status) }}
                  </span>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-3 text-center">
                  <div class="bg-white rounded-lg p-2">
                    <p class="text-xs text-gray-600">Amount</p>
                    <p class="font-bold text-red-600">₱{{ order.total_amount?.toLocaleString() ?? 0 }}</p>
                  </div>
                  <div class="bg-white rounded-lg p-2">
                    <p class="text-xs text-gray-600">Order Date</p>
                    <p class="font-bold text-gray-800 text-sm">{{ formatDate(order.created_at) }}</p>
                  </div>
                  <div class="bg-white rounded-lg p-2">
                    <p class="text-xs text-gray-600">Items</p>
                    <p class="font-bold text-gray-800">{{ order.items_count ?? 0 }}</p>
                  </div>
                </div>

                <button @click="viewOrderDetails(order.id)" class="w-full py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                  View Details →
                </button>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <button @click="showOrdersModal = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors font-medium">
              Close
            </button>
            <button v-if="pendingOrdersAlert.length > 0" @click="goToOrdersAndClose" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
              Manage All Orders
            </button>
          </div>
        </div>
      </div>

      <!-- Analytics Charts -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <!-- Sales Trend Chart -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-200">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <div>
              <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-chart-line text-[#5F1213] mr-2"></i>
                Sales Trend
              </h3>
              <p class="text-sm text-gray-600">Revenue performance over time</p>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="lineChartFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent bg-white">
                <option value="monthly">Last 7 Days</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <button @click="exportChart('line')" class="p-2 text-gray-500 hover:text-[#5F1213] hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-download text-sm"></i>
              </button>
            </div>
          </div>
          <div class="relative h-[300px] lg:h-[350px]">
            <canvas id="lineChart" class="w-full h-full"></canvas>
            <div v-if="isLoadingCharts" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg">
              <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#5F1213]"></div>
                <span class="text-sm text-gray-600">Loading chart...</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-200">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <div>
              <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-chart-pie text-[#5F1213] mr-2"></i>
                Category Breakdown
              </h3>
              <p class="text-sm text-gray-600">Sales distribution by category</p>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="pieChartFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent bg-white">
                <option value="monthly">This Month</option>
                <option value="quarterly">This Quarter</option>
                <option value="yearly">This Year</option>
              </select>
              <button @click="exportChart('pie')" class="p-2 text-gray-500 hover:text-[#5F1213] hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-download text-sm"></i>
              </button>
            </div>
          </div>
          <div class="relative h-[300px] lg:h-[350px]">
            <canvas id="donutChart" class="w-full h-full"></canvas>
            <div v-if="isLoadingCharts" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg">
              <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#5F1213]"></div>
                <span class="text-sm text-gray-600">Loading chart...</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products & Recent Orders -->
      <div class="bg-white rounded-xl p-4 lg:p-6 mb-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
        <!-- Recent Orders Table -->
        <div class="bg-white rounded-xl p-4 lg:p-6 shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-receipt text-blue-500 mr-2"></i>
                Recent Orders
              </h3>
              <p class="text-sm text-gray-600">Latest orders from customers</p>
            </div>
            <button @click="refreshOrders" class="text-[#5F1213] hover:text-[#441011] text-sm font-medium transition-colors duration-200">
              Refresh
            </button>
          </div>

          <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p>No orders found</p>
          </div>

          <div v-else class="space-y-3 max-h-64 overflow-y-auto">
            <div v-for="order in recentOrders.slice(0, 5)" :key="order.id" 
                 class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer border-l-4 border-[#5F1213]"
                 @click="viewOrderDetails(order.id)">
              <div class="flex items-center justify-between mb-2">
                <p class="font-semibold text-gray-800">#{{ order.id.toString().padStart(3, '0') }}</p>
                <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded text-xs font-medium">
                  {{ formatStatus(order.status) }}
                </span>
              </div>
              <p class="text-sm text-gray-600">{{ order.first_name }} {{ order.last_name }}</p>
              <div class="flex justify-between items-center mt-2">
                <p class="text-xs text-gray-500">{{ formatDate(order.created_at) }}</p>
                <p class="text-sm font-bold text-[#5F1213]">₱{{ order.total_amount?.toLocaleString() ?? 0 }}</p>
              </div>
            </div>
          </div>

          <button @click="goToOrders" class="w-full mt-4 py-2 text-sm font-medium text-[#5F1213] hover:text-[#441011] border border-[#5F1213] hover:border-[#441011] rounded-lg transition-colors duration-200">
            View All Orders →
          </button>
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
const topProducts = ref(page.props.topProducts ?? [])
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
const isLoadingCharts = ref(false)
const lastUpdated = ref(new Date().toLocaleTimeString())
const lowStockItems = ref([])
const recentActivities = ref([
  { id: 1, message: 'New order placed by Maria Santos', time: '2 minutes ago', icon: 'fas fa-shopping-cart', color: '#10B981' },
  { id: 2, message: 'Product "Fresh Tomatoes" restocked', time: '15 minutes ago', icon: 'fas fa-plus-circle', color: '#3B82F6' },
  { id: 3, message: 'Payment received for Order #1023', time: '1 hour ago', icon: 'fas fa-credit-card', color: '#8B5CF6' },
  { id: 4, message: 'Low stock alert: Chicken Breast', time: '2 hours ago', icon: 'fas fa-exclamation-triangle', color: '#F59E0B' }
])
const pendingOrdersAlert = ref([])
const showOrdersModal = ref(false)

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

function goToOrders() {
  router.visit('/admins/orders')
}
function goToProducts() {
  router.visit('/admins/products')
}
function goToReports() {
  router.visit('/admin/reports')
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
  loadPendingOrders()
})

function loadPendingOrders() {
  // Filter orders with pending status
  const pending = recentOrders.value.filter(order => {
    const statusLower = order.status?.toLowerCase() || ''
    return statusLower === 'pending'
  })
  pendingOrdersAlert.value = pending.slice(0, 5) // Show top 5 pending orders
}

function refreshDashboard() {
  router.reload({ only: ['recentOrders', 'stats', 'topProducts'] })
  loadPendingOrders()
}

function viewOrderDetails(orderId) {
  if (!orderId) {
    console.error('Order ID is required')
    return
  }
  showOrdersModal.value = false
  // Navigate to orders list and let the user select the order
  // Or use the edit route if available
  router.visit(`/admins/orders`)
}

function goToOrdersAndClose() {
  showOrdersModal.value = false
  router.visit('/admins/orders')
}

watch(recentOrders, loadPendingOrders)
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
