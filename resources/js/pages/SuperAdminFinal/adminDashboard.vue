<template>
  <div class="min-h-screen flex font-sans bg-gray-50">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-8 text-[#5F1213]">
      <!-- Page Title -->
      <div class="bg-gradient-to-r from-[#5F1213] to-[#8B1A1E] rounded-lg p-6 mb-8 shadow-lg text-white">
        <div class="flex items-center space-x-4">
          <i class="i-icon-park-outline-dashboard text-4xl"></i>
          <div>
            <h1 class="text-3xl font-bold">Super Admin Dashboard</h1>
            <p class="text-white/80 text-sm mt-1">System Overview & Analytics</p>
          </div>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Online Market Sales -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <p class="text-gray-700 text-xs font-bold uppercase tracking-wider">Online Market</p>
              <p class="text-3xl font-bold text-green-700 mt-3">₱{{ formatCurrency(stats.crop_sales) }}</p>
              <div v-if="stats.crop_sales_growth !== null" class="mt-3">
                <span :class="stats.crop_sales_growth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold flex items-center">
                  <i :class="stats.crop_sales_growth >= 0 ? 'i-tabler-trending-up' : 'i-tabler-trending-down'" class="mr-1"></i>
                  {{ stats.crop_sales_growth >= 0 ? '+' : '' }}{{ stats.crop_sales_growth }}% vs last week
                </span>
              </div>
              <p v-else class="text-gray-500 text-xs mt-3">No comparative data</p>
            </div>
            <div class="text-4xl opacity-20">🛒</div>
          </div>
        </div>

        <!-- Pay to Park Sales -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-blue-500">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <p class="text-gray-700 text-xs font-bold uppercase tracking-wider">Pay to Park</p>
              <p class="text-3xl font-bold text-blue-700 mt-3">₱{{ formatCurrency(stats.paypark_sales) }}</p>
              <div v-if="stats.paypark_sales_growth !== null" class="mt-3">
                <span :class="stats.paypark_sales_growth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold flex items-center">
                  <i :class="stats.paypark_sales_growth >= 0 ? 'i-tabler-trending-up' : 'i-tabler-trending-down'" class="mr-1"></i>
                  {{ stats.paypark_sales_growth >= 0 ? '+' : '' }}{{ stats.paypark_sales_growth }}% vs last week
                </span>
              </div>
              <p v-else class="text-gray-500 text-xs mt-3">No comparative data</p>
            </div>
            <div class="text-4xl opacity-20">🅿️</div>
          </div>
        </div>

        <!-- Rental Facilities Sales -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-amber-500">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <p class="text-gray-700 text-xs font-bold uppercase tracking-wider">Rental Facilities</p>
              <p class="text-3xl font-bold text-amber-700 mt-3">₱{{ formatCurrency(stats.rental_sales) }}</p>
              <div v-if="stats.rental_sales_growth !== null" class="mt-3">
                <span :class="stats.rental_sales_growth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold flex items-center">
                  <i :class="stats.rental_sales_growth >= 0 ? 'i-tabler-trending-up' : 'i-tabler-trending-down'" class="mr-1"></i>
                  {{ stats.rental_sales_growth >= 0 ? '+' : '' }}{{ stats.rental_sales_growth }}% vs last week
                </span>
              </div>
              <p v-else class="text-gray-500 text-xs mt-3">No comparative data</p>
            </div>
            <div class="text-4xl opacity-20">🏠</div>
          </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-amber-500">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <p class="text-gray-700 text-xs font-bold uppercase tracking-wider">Total Revenue</p>
              <p class="text-3xl font-bold text-amber-700 mt-3">₱{{ formatCurrency(stats.crop_sales + stats.paypark_sales + stats.rental_sales) }}</p>
              <div v-if="stats.total_sales_growth !== null" class="mt-3">
                <span :class="stats.total_sales_growth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold flex items-center">
                  <i :class="stats.total_sales_growth >= 0 ? 'i-tabler-trending-up' : 'i-tabler-trending-down'" class="mr-1"></i>
                  {{ stats.total_sales_growth >= 0 ? '+' : '' }}{{ stats.total_sales_growth }}% vs last week
                </span>
              </div>
              <p v-else class="text-gray-500 text-xs mt-3">No comparative data</p>
            </div>
            <div class="text-4xl opacity-20">💰</div>
          </div>
        </div>
      </div>

      <!-- Facilities Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-md border-t-4 border-indigo-500">
          <h3 class="text-lg font-bold text-[#5F1213] mb-4">Facilities Overview</h3>
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-indigo-50 rounded-lg p-4 text-center">
              <p class="text-3xl font-bold text-indigo-600">{{ stats.total_facilities }}</p>
              <p class="text-sm text-gray-600 mt-1">Total Facilities</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
              <p class="text-3xl font-bold text-green-600">{{ stats.active_facilities }}</p>
              <p class="text-sm text-gray-600 mt-1">Active Now</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
              <p class="text-3xl font-bold text-purple-600">{{ stats.facility_bookings }}</p>
              <p class="text-sm text-gray-600 mt-1">Bookings</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-md border-t-4 border-green-500">
          <h3 class="text-lg font-bold text-[#5F1213] mb-4">Sales Distribution</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Online Market</span>
              <span class="font-semibold text-green-600">₱{{ formatCurrency(stats.crop_sales) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-green-500 h-2 rounded-full" :style="{ width: getTotalRevenue() > 0 ? ((stats.crop_sales / getTotalRevenue()) * 100) + '%' : '0%' }"></div>
            </div>
            
            <div class="flex justify-between items-center mt-4">
              <span class="text-sm text-gray-600">Pay to Park</span>
              <span class="font-semibold text-blue-600">₱{{ formatCurrency(stats.paypark_sales) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-blue-500 h-2 rounded-full" :style="{ width: getTotalRevenue() > 0 ? ((stats.paypark_sales / getTotalRevenue()) * 100) + '%' : '0%' }"></div>
            </div>

            <div class="flex justify-between items-center mt-4">
              <span class="text-sm text-gray-600">Rental Facilities</span>
              <span class="font-semibold text-amber-600">₱{{ formatCurrency(stats.rental_sales) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-amber-500 h-2 rounded-full" :style="{ width: getTotalRevenue() > 0 ? ((stats.rental_sales / getTotalRevenue()) * 100) + '%' : '0%' }"></div>
            </div>
          </div>
        </div>
      </div>


      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Line Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-blue-500">
          <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-bold text-[#5F1213]">Sales Trend</h3>
                <p class="text-gray-500 text-sm mt-1">Revenue comparison over time</p>
              </div>
              <div>
                <select v-model="lineChartFilter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                  <option value="monthly">Monthly</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="yearly">Yearly</option>
                </select>
              </div>
            </div>
          </div>
          <div class="relative h-80 p-6">
            <canvas id="lineChart" class="w-full h-full"></canvas>
          </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-purple-500">
          <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-bold text-[#5F1213]">Sales Distribution</h3>
                <p class="text-gray-500 text-sm mt-1">Market share breakdown</p>
              </div>
              <div>
                <select v-model="pieChartFilter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium">
                  <option value="monthly">Monthly</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="yearly">Yearly</option>
                </select>
              </div>
            </div>
          </div>
          <div class="relative h-80 p-6">
            <canvas id="donutChart" class="w-full h-full"></canvas>
          </div>
        </div>
      </div>


      <!-- Recent Orders and Bookings -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-orange-500">
          <div class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 flex justify-between items-center border-b border-gray-200">
            <div>
              <h3 class="text-lg font-bold text-[#5F1213]">Recent Orders</h3>
              <p class="text-gray-600 text-sm mt-1">Latest transactions from Online Market</p>
            </div>
            <button @click="refreshOrders" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg transition-colors text-xs font-semibold">
              🔄 Refresh
            </button>
          </div>

          <div v-if="recentOrders.length === 0" class="text-center py-12 text-gray-500">
            <p class="text-lg">📦 No orders found</p>
            <p class="text-sm mt-1">Orders will appear here when they are created</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Order ID</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Customer</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Amount</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                  <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 font-semibold text-[#5F1213]">#{{ String(order.id).padStart(5, '0') }}</td>
                  <td class="px-6 py-4">{{ order.first_name }} {{ order.last_name }}</td>
                  <td class="px-6 py-4 font-semibold text-orange-600">₱{{ formatCurrency(order.total_amount) }}</td>
                  <td class="px-6 py-4 text-gray-600 text-xs">{{ formatDateTime(order.created_at) }}</td>
                  <td class="px-6 py-4">
                    <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-xs font-semibold inline-block">
                      {{ formatStatus(order.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                      <button @click="viewOrder(order)" class="text-[#5F1213] hover:text-[#8B1A1E] font-semibold text-xs hover:underline">View</button>
                      <button v-if="order.status === 'pending'" @click="markAsPaid(order)" class="text-green-600 hover:text-green-700 font-semibold text-xs hover:underline">Pay</button>
                      <button v-if="order.status === 'pending'" @click="deleteOrder(order)" class="text-red-600 hover:text-red-700 font-semibold text-xs hover:underline">Delete</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-purple-500">
          <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-6 flex justify-between items-center border-b border-gray-200">
            <div>
              <h3 class="text-lg font-bold text-[#5F1213]">Recent Bookings</h3>
              <p class="text-gray-600 text-sm mt-1">Latest facility reservations</p>
            </div>
            <button @click="refreshBookings" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded-lg transition-colors text-xs font-semibold">
              🔄 Refresh
            </button>
          </div>

          <div v-if="recentBookings.length === 0" class="text-center py-12 text-gray-500">
            <p class="text-lg">🏢 No bookings found</p>
            <p class="text-sm mt-1">Bookings will appear here when they are made</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Booking ID</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Facility</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Customer</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                  <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="booking in recentBookings" :key="booking.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 font-semibold text-[#5F1213]">#{{ String(booking.id).padStart(5, '0') }}</td>
                  <td class="px-6 py-4">{{ booking.facility?.name || 'N/A' }}</td>
                  <td class="px-6 py-4">{{ booking.user?.first_name }} {{ booking.user?.last_name }}</td>
                  <td class="px-6 py-4 text-gray-600 text-xs">{{ formatDateTime(booking.created_at) }}</td>
                  <td class="px-6 py-4">
                    <span :class="getBookingStatusClass(booking.status)" class="px-3 py-1 rounded-full text-xs font-semibold inline-block">
                      {{ formatBookingStatus(booking.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                      <button @click="viewBooking(booking)" class="text-[#5F1213] hover:text-[#8B1A1E] font-semibold text-xs hover:underline">View</button>
                      <button v-if="booking.status === 'pending'" @click="confirmBooking(booking)" class="text-green-600 hover:text-green-700 font-semibold text-xs hover:underline">Confirm</button>
                      <button v-if="booking.status === 'pending'" @click="cancelBooking(booking)" class="text-red-600 hover:text-red-700 font-semibold text-xs hover:underline">Cancel</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'
import Sidebar from './sidebar.vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

// Debug: Log all props
console.log('Dashboard Props:', page.props)

// Get data from props with proper defaults
const stats = ref({
  total_sales: page.props.stats?.total_sales || 0,
  crop_sales: page.props.stats?.crop_sales || 0,
  paypark_sales: page.props.stats?.paypark_sales || 0,
  rental_sales: page.props.stats?.rental_sales || 0,
  total_facilities: page.props.stats?.total_facilities || 0,
  active_facilities: page.props.stats?.active_facilities || 0,
  facility_bookings: page.props.stats?.facility_bookings || 0,
  confirmed_bookings: page.props.stats?.confirmed_bookings || 0,
  total_sales_growth: page.props.stats?.total_sales_growth ?? null,
  crop_sales_growth: page.props.stats?.crop_sales_growth ?? null,
  paypark_sales_growth: page.props.stats?.paypark_sales_growth ?? null,
  rental_sales_growth: page.props.stats?.rental_sales_growth ?? null,
})

// Debug: Log stats data
console.log('Stats Data:', stats.value)

const recentOrders = ref(page.props.recentOrders || [])
const recentBookings = ref(page.props.recentBookings || [])

// Ensure chartData has proper structure with defaults
const chartData = ref({
  lineChart: {
    monthly: { 
      labels: page.props.chartData?.lineChart?.monthly?.labels || ['Week 1', 'Week 2', 'Week 3', 'Week 4'], 
      cropSales: page.props.chartData?.lineChart?.monthly?.cropSales || [0, 0, 0, 0], 
      payparkSales: page.props.chartData?.lineChart?.monthly?.payparkSales || [0, 0, 0, 0], 
      rentalSales: page.props.chartData?.lineChart?.monthly?.rentalSales || [0, 0, 0, 0] 
    },
    quarterly: { 
      labels: page.props.chartData?.lineChart?.quarterly?.labels || ['Q1', 'Q2', 'Q3', 'Q4'], 
      cropSales: page.props.chartData?.lineChart?.quarterly?.cropSales || [0, 0, 0, 0], 
      payparkSales: page.props.chartData?.lineChart?.quarterly?.payparkSales || [0, 0, 0, 0], 
      rentalSales: page.props.chartData?.lineChart?.quarterly?.rentalSales || [0, 0, 0, 0] 
    },
    yearly: { 
      labels: page.props.chartData?.lineChart?.yearly?.labels || ['2021', '2022', '2023', '2024'], 
      cropSales: page.props.chartData?.lineChart?.yearly?.cropSales || [0, 0, 0, 0], 
      payparkSales: page.props.chartData?.lineChart?.yearly?.payparkSales || [0, 0, 0, 0], 
      rentalSales: page.props.chartData?.lineChart?.yearly?.rentalSales || [0, 0, 0, 0] 
    }
  },
  pieChart: {
    monthly: page.props.chartData?.pieChart?.monthly || [0, 0, 0],
    quarterly: page.props.chartData?.pieChart?.quarterly || [0, 0, 0],
    yearly: page.props.chartData?.pieChart?.yearly || [0, 0, 0]
  }
})

// Debug: Log chart data
console.log('Chart Data:', chartData.value)
console.log('Recent Orders:', recentOrders.value)
console.log('Recent Bookings:', recentBookings.value)

const lineChartFilter = ref('monthly')
const pieChartFilter = ref('monthly')

let lineChartInstance = null
let donutChartInstance = null
let chartsInitialized = false

// Helper functions
function getTotalRevenue() {
  const crop = Number(stats.value.crop_sales) || 0
  const paypark = Number(stats.value.paypark_sales) || 0
  const rental = Number(stats.value.rental_sales) || 0
  return crop + paypark + rental
}

function formatCurrency(value) {
  const num = Number(value) || 0
  return num.toLocaleString('en-PH', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })
}

function formatDateTime(dateString) {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function formatStatus(status) {
  const statusMap = {
    'pending': '⏳ Pending',
    'paid': '✓ Paid',
    'cancelled': '✗ Cancelled',
    'completed': '✓ Completed'
  }
  return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1)
}

function getStatusClass(status) {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'paid':
    case 'completed':
      return 'bg-green-100 text-green-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

function formatBookingStatus(status) {
  const statusMap = {
    'pending': '⏳ Pending',
    'confirmed': '✓ Confirmed',
    'approved': '✓ Approved',
    'cancelled': '✗ Cancelled',
    'completed': '✓ Completed'
  }
  return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1)
}

function getBookingStatusClass(status) {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'confirmed':
    case 'approved':
    case 'completed':
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

function refreshBookings() {
  router.reload({ only: ['recentBookings', 'stats'] })
}

function viewBooking(booking) {
  // Implement view booking logic
  console.log('View booking:', booking)
}

function confirmBooking(booking) {
  // Implement confirm booking logic
  console.log('Confirm booking:', booking)
}

function cancelBooking(booking) {
  // Implement cancel booking logic
  console.log('Cancel booking:', booking)
}

function viewOrder(order) {
  alert(`Viewing order #${order.id}`)
  // Implement navigation to order details
  console.log('View order:', order)
}

function markAsPaid(order) {
  if (confirm(`Mark order #${order.id} as paid?`)) {
    router.put(`/admin/orders/${order.id}/mark-paid`, {}, {
      onSuccess: () => {
        refreshOrders()
        alert('Order marked as paid successfully!')
      },
      onError: (errors) => {
        console.error('Update failed:', errors)
        alert('Failed to update order status. Please try again.')
      }
    })
  }
}

function deleteOrder(order) {
  if (confirm(`Are you sure you want to delete order #${order.id}? This action cannot be undone.`)) {
    router.delete(`/admin/orders/${order.id}`, {
      onSuccess: () => {
        refreshOrders()
        alert('Order deleted successfully!')
      },
      onError: (errors) => {
        console.error('Delete failed:', errors)
        alert('Failed to delete order. Please try again.')
      }
    })
  }
}

function updateLineChartData() {
  if (!lineChartInstance) return
  
  const currentData = chartData.value.lineChart[lineChartFilter.value]
  if (!currentData) return

  lineChartInstance.data.labels = currentData.labels || []
  lineChartInstance.data.datasets[0].data = (currentData.cropSales || []).map(v => Number(v) || 0)
  lineChartInstance.data.datasets[1].data = (currentData.payparkSales || []).map(v => Number(v) || 0)
  lineChartInstance.data.datasets[2].data = (currentData.rentalSales || []).map(v => Number(v) || 0)
  lineChartInstance.update('none')
}

function updateDonutChartData() {
  if (!donutChartInstance) return
  
  const currentData = (chartData.value.pieChart[pieChartFilter.value] || [0, 0, 0]).map(v => Number(v) || 0)
  const total = currentData.reduce((sum, val) => sum + val, 0)
  const hasData = total > 0

  donutChartInstance.data.datasets[0].data = hasData ? currentData : [1, 1, 1]
  donutChartInstance.data.datasets[0].backgroundColor = hasData 
    ? ['#4CAF50', '#2196F3', '#FF9800']
    : ['#e5e5e5', '#e5e5e5', '#e5e5e5']
  donutChartInstance.options.plugins.legend.display = hasData
  donutChartInstance.update('none')
}

function renderLineChart() {
  const ctx = document.getElementById('lineChart')
  if (!ctx) return
  
  if (lineChartInstance) {
    lineChartInstance.destroy()
    lineChartInstance = null
  }

  const currentData = chartData.value.lineChart[lineChartFilter.value]
  if (!currentData || !currentData.labels) return

  const labels = currentData.labels || []
  const cropSalesData = (currentData.cropSales || []).map(v => Number(v) || 0)
  const payparkSalesData = (currentData.payparkSales || []).map(v => Number(v) || 0)
  const rentalSalesData = (currentData.rentalSales || []).map(v => Number(v) || 0)

  lineChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Online Market',
          data: cropSalesData,
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76,175,80,0.1)',
          borderWidth: 3,
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: '#4CAF50',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointHoverRadius: 7
        },
        {
          label: 'Pay to Park',
          data: payparkSalesData,
          borderColor: '#2196F3',
          backgroundColor: 'rgba(33,150,243,0.1)',
          borderWidth: 3,
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: '#2196F3',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointHoverRadius: 7
        },
        {
          label: 'Rental Facilities',
          data: rentalSalesData,
          borderColor: '#FF9800',
          backgroundColor: 'rgba(255,152,0,0.1)',
          borderWidth: 3,
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: '#FF9800',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointHoverRadius: 7
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      interaction: {
        intersect: false,
        mode: 'index'
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: { size: 12, weight: 'bold' },
            color: '#5F1213'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 12, weight: 'bold' },
          bodyFont: { size: 11 },
          borderColor: 'rgba(255, 255, 255, 0.2)',
          borderWidth: 1,
          displayColors: true,
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString('en-PH')
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0, 0, 0, 0.05)',
            drawBorder: false
          },
          ticks: {
            callback: function(value) {
              return '₱' + value.toLocaleString('en-PH')
            },
            font: { size: 11 },
            color: '#666'
          }
        },
        x: {
          grid: {
            display: false,
            drawBorder: false
          },
          ticks: {
            font: { size: 11 },
            color: '#666'
          }
        }
      }
    }
  })
}

function renderDonutChart() {
  const ctx = document.getElementById('donutChart')
  if (!ctx) return
  
  if (donutChartInstance) {
    donutChartInstance.destroy()
    donutChartInstance = null
  }

  const currentData = (chartData.value.pieChart[pieChartFilter.value] || [0, 0, 0]).map(v => Number(v) || 0)
  const total = currentData.reduce((sum, val) => sum + val, 0)
  const hasData = total > 0

  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Online Market', 'Pay to Park', 'Rental Facilities'],
      datasets: [{
        data: hasData ? currentData : [1, 1, 1],
        backgroundColor: hasData 
          ? ['#4CAF50', '#2196F3', '#FF9800']
          : ['#e5e5e5', '#e5e5e5', '#e5e5e5'],
        borderWidth: 3,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      cutout: '60%',
      plugins: {
        legend: {
          display: hasData,
          position: 'bottom',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: { size: 12, weight: 'bold' },
            color: '#5F1213'
          }
        },
        tooltip: {
          enabled: hasData,
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 12, weight: 'bold' },
          bodyFont: { size: 11 },
          borderColor: 'rgba(255, 255, 255, 0.2)',
          borderWidth: 1,
          callbacks: hasData ? {
            label: function(context) {
              const value = context.parsed
              const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0
              return `₱${value.toLocaleString('en-PH')} (${percentage}%)`
            }
          } : {}
        }
      }
    }
  })
}

onMounted(() => {
  // Initialize charts only once
  if (!chartsInitialized) {
    chartsInitialized = true
    setTimeout(() => {
      renderLineChart()
      renderDonutChart()
    }, 200)
  }
})

watch(lineChartFilter, () => {
  if (chartsInitialized && lineChartInstance) {
    updateLineChartData()
  }
}, { flush: 'post' })

watch(pieChartFilter, () => {
  if (chartsInitialized && donutChartInstance) {
    updateDonutChartData()
  }
}, { flush: 'post' })

onBeforeUnmount(() => {
  chartsInitialized = false
  if (lineChartInstance) {
    lineChartInstance.destroy()
    lineChartInstance = null
  }
  if (donutChartInstance) {
    donutChartInstance.destroy()
    donutChartInstance = null
  }
})
</script>

<style scoped>
canvas {
  box-sizing: border-box;
}
</style>
