<template>
  <div class="min-h-screen flex font-sans bg-gray-50">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center min-h-screen">
        <div class="text-center">
          <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200 border-t-[#5F1213] mx-auto mb-4"></div>
          <p class="text-gray-600 font-medium">Loading dashboard...</p>
        </div>
      </div>

      <div v-else class="space-y-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
              <div class="p-4 bg-gradient-to-br from-[#5F1213] to-[#441011] rounded-xl shadow-lg">
                <IconPark.Dashboard theme="filled" size="32" fill="white" />
              </div>
              <div>
                <h1 class="text-3xl font-bold text-gray-900">Facilities Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, <span class="font-semibold">{{ admin?.first_name || 'Admin' }}</span>!</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <button 
                @click="refreshDashboard" 
                :disabled="loading"
                class="p-3 text-gray-600 hover:text-[#5F1213] hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50"
                title="Refresh dashboard"
              >
                <IconPark.Refresh theme="outline" size="24" />
              </button>
              <div v-if="pendingOrdersAlert.length > 0" class="relative">
                <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold animate-pulse">
                  {{ pendingOrdersAlert.length }}
                </div>
                <button 
                  @click="showOrdersModal = true" 
                  class="p-3 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  title="View pending bookings"
                >
                  <IconPark.Notification theme="outline" size="24" />
                </button>
              </div>
              <div class="relative">
                <button 
                  @click="showAlertsModal = !showAlertsModal" 
                  class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 hover:shadow-lg transition-all flex items-center gap-2 font-medium relative"
                >
                  <IconPark.Bell theme="outline" size="18" />
                  <span>Booking Alerts</span>
                  <span v-if="alerts.length > 0" class="absolute -top-2 -right-2 bg-red-900 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold animate-pulse">
                    {{ alerts.length }}
                  </span>
                </button>

                <!-- Alerts Dropdown Modal -->
                <Transition name="slideDown">
                  <div 
                    v-if="showAlertsModal" 
                    class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden z-50 animate-slideUp"
                    @click.stop
                  >
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                      <div class="flex items-center space-x-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                          <IconPark.Bell theme="filled" size="20" fill="#EA580C" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">System Alerts</h3>
                      </div>
                      <button 
                        @click.stop="showAlertsModal = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                      >
                        <IconPark.Close theme="outline" size="20" />
                      </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="max-h-96 overflow-y-auto p-4">
                      <div v-if="alerts.length === 0" class="text-center py-8 text-gray-500">
                        <IconPark.CheckOne theme="filled" size="48" fill="#22C55E" class="mb-2 mx-auto" />
                        <p class="font-semibold">No alerts</p>
                        <p class="text-xs mt-1">Everything is running smoothly!</p>
                      </div>

                      <div v-else class="space-y-2">
                        <div 
                          v-for="alert in alerts" 
                          :key="alert.message"
                          :class="getAlertClasses(alert.type)" 
                          class="rounded-lg p-3 flex items-start justify-between border-l-4 text-sm"
                        >
                          <div class="flex items-start space-x-2 flex-1">
                            <component :is="getAlertIconComponent(alert.icon)" class="text-base mt-0.5 flex-shrink-0" />
                            <div class="flex-1 min-w-0">
                              <p class="font-semibold text-sm">{{ alert.message }}</p>
                              <button 
                                v-if="alert.action" 
                                @click.stop="handleAlertAction(alert)"
                                class="text-xs font-semibold underline hover:no-underline mt-1 inline-block"
                              >
                                {{ alert.action }} &rarr;
                              </button>
                            </div>
                          </div>
                          <button 
                            @click.stop="dismissAlert(alert)"
                            class="text-gray-400 hover:text-gray-600 flex-shrink-0 ml-2 transition-colors"
                          >
                            <IconPark.Close theme="outline" size="16" />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </Transition>
              </div>
            </div>
          </div>
        </div>

        <!-- Alerts Section (Inline) -->
        <div v-if="alerts.length > 0" class="space-y-3">
          <div v-for="alert in alerts" :key="alert.message" :class="getAlertClasses(alert.type)" class="rounded-xl p-4 flex items-start justify-between animate-fadeIn border-l-4">
            <div class="flex items-start space-x-4 flex-1">
              <component :is="getAlertIconComponent(alert.icon)" class="text-lg mt-1 flex-shrink-0" />
              <div class="flex-1">
                <p class="font-semibold">{{ alert.message }}</p>
                <button v-if="alert.action" @click="handleAlertAction(alert)" class="text-sm font-semibold underline hover:no-underline mt-2 inline-block">
                  {{ alert.action }} &rarr;
                </button>
              </div>
            </div>
            <button @click="dismissAlert(alert)" class="text-gray-400 hover:text-gray-600 flex-shrink-0 ml-4">
              <IconPark.Close theme="outline" size="20" />
            </button>
          </div>
        </div>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatsCard
            title="Total Revenue"
            :value="totalRevenueDisplay"
            :subtitle="`₱${formatCurrency(stats.revenue?.monthly || 0)} this month`"
            icon="i-icon-park-outline-money-cny-circle"
            :color="'green'"
            />
          <StatsCard
            title="Total Bookings"
            :value="stats.total_bookings || 0"
            :subtitle="`${stats.pending_bookings || 0} pending`"
            icon="i-icon-park-outline-calendar"
            :color="'blue'"
          />
          <StatsCard
            title="Occupancy Rate"
            :value="`${stats.occupancy_rates?.overall || 0}%`"
            subtitle="Overall utilization"
            icon="i-icon-park-outline-percent"
            :color="'purple'"
          />
          <StatsCard
            title="Active Facilities"
            :value="stats.facilities || 0"
            :subtitle="`${stats.facilities_active || 0} in use`"
            icon="i-icon-park-outline-building"
            :color="'orange'"
          />
        </div>

        <!-- Detailed Facility Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <FacilityCard
            title="All Facilities"
            :count="stats.facilities || 0"
            :active="stats.facilities_active || 0"
            subtitle="Total available"
            icon="i-icon-park-outline-setting"
            color="purple"
          />
          <FacilityCard
            title="Hostel Rooms"
            :count="stats.hostel_rooms || 0"
            :active="stats.hostel_active || 0"
            :occupancy="stats.occupancy_rates?.hostel || 0"
            icon="i-icon-park-outline-home"
            color="blue"
          />
          <FacilityCard
            title="Rental Spaces"
            :count="stats.rental_spaces || 0"
            :active="stats.rental_active || 0"
            :occupancy="stats.occupancy_rates?.rental || 0"
            icon="i-icon-park-outline-shop"
            color="orange"
          />
          <FacilityCard
            title="Commercial Spaces"
            :count="stats.commercial_spaces || 0"
            :active="stats.commercial_active || 0"
            :occupancy="stats.occupancy_rates?.commercial || 0"
            icon="i-icon-park-outline-office-building"
            color="red"
          />
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Line Chart -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Booking Trends</h3>
                <p class="text-sm text-gray-600 mt-1">Historical booking data</p>
              </div>
              <div class="flex items-center gap-2">
                <select
                  v-model="lineChartFilter"
                  class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent"
                >
                  <option value="monthly">Monthly</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="yearly">Yearly</option>
                </select>
                <button
                  @click="exportChartToPDF('lineChart', 'booking-trends')"
                  class="bg-[#5F1213] text-white px-3 py-2 text-sm rounded-lg hover:bg-[#441011] transition-colors"
                >
                  Export
                </button>
              </div>
            </div>
            <div class="h-80">
              <canvas id="lineChart"></canvas>
            </div>
          </div>

          <!-- Donut Chart -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Facility Breakdown</h3>
                <p class="text-sm text-gray-600 mt-1">Distribution by category</p>
              </div>
              <div class="flex items-center gap-2">
                <select
                  v-model="pieChartFilter"
                  class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent"
                >
                  <option value="monthly">Monthly</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="yearly">Yearly</option>
                </select>
                <button
                  @click="exportChartToPDF('donutChart', 'facility-breakdown')"
                  class="bg-[#5F1213] text-white px-3 py-2 text-sm rounded-lg hover:bg-[#441011] transition-colors"
                >
                  Export
                </button>
              </div>
            </div>
            <div class="h-80">
              <canvas id="donutChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Booking Calendar -->
        <div id="calendar-section" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Booking Calendar</h3>
          <BookingCalendar
            :bookings="recentBookings"
            :on-date-select="handleDateSelect"
            :on-event-click="handleEventClick"
          />
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
              <p class="text-sm text-gray-600 mt-1">Latest facility bookings</p>
            </div>
            <button
              @click="refreshBookings"
              class="bg-[#5F1213] text-white px-4 py-2 rounded-lg hover:bg-[#441011] transition-colors text-sm font-medium"
            >
              Refresh
            </button>
          </div>

          <!-- Search and Filter Controls -->
          <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by booking ID, facility name, or user name..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent text-sm"
                @input="debouncedSearch"
              />
            </div>
            <div class="flex gap-2">
              <select
                v-model="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent text-sm"
                @change="fetchBookings"
              >
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="cancelled">Cancelled</option>
              </select>
              <button
                @click="clearFilters"
                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"
              >
                Clear
              </button>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <div v-if="recentBookings.length === 0" class="text-center py-12 text-gray-500">
              <i class="i-icon-park-outline-inbox text-4xl mb-3"></i>
              <p class="font-medium">No bookings found</p>
            </div>

            <table v-else class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200 font-semibold text-gray-700 bg-gray-50">
                  <th class="py-3 px-4 text-left">ID</th>
                  <th class="py-3 px-4 text-left">Category</th>
                  <th class="py-3 px-4 text-left">Facility / Name</th>
                  <th class="py-3 px-4 text-left">Date</th>
                  <th class="py-3 px-4 text-left">Amount</th>
                  <th class="py-3 px-4 text-left">Status</th>
                  <th class="py-3 px-4 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="booking in recentBookings"
                  :key="booking.id"
                  class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                >
                  <td class="py-3 px-4 font-semibold text-gray-900">#{{ booking.id.toString().padStart(3, '0') }}</td>
                  <td class="py-3 px-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                      {{ booking.category }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-gray-800">{{ booking.name }}</td>
                  <td class="py-3 px-4 text-gray-600">{{ formatDate(booking.date) }}</td>
                  <td class="py-3 px-4 font-semibold text-gray-900">{{ formatCurrency(booking.total_amount || 0) }}</td>
                  <td class="py-3 px-4">
                    <span
                      :class="getStatusClass(booking.status)"
                      class="px-3 py-1 rounded-full text-xs font-bold"
                    >
                      {{ formatStatus(booking.status) }}
                    </span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="flex gap-2">
                      <button
                        @click="viewBooking(booking)"
                        class="text-indigo-600 hover:text-indigo-800 hover:underline text-xs font-medium"
                      >
                        View
                      </button>
                      <button
                        v-if="booking.status === 'pending'"
                        @click="approveBooking(booking)"
                        class="text-green-600 hover:text-green-800 hover:underline text-xs font-medium"
                      >
                        Approve
                      </button>
                      <button
                        v-if="booking.status === 'pending'"
                        @click="cancelBooking(booking)"
                        class="text-red-600 hover:text-red-800 hover:underline text-xs font-medium"
                      >
                        Cancel
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- Pending Orders Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div 
          v-if="showOrdersModal" 
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
          @click.self="showOrdersModal = false"
        >
          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-slideUp">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5 flex items-center justify-between flex-shrink-0">
              <div class="flex items-center space-x-4">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                  <IconPark.Bell theme="filled" size="28" fill="white" />
                </div>
                <div>
                  <h2 class="text-xl font-bold text-white">Pending Bookings Alert</h2>
                  <p class="text-sm text-red-100 mt-1">{{ pendingOrdersAlert.length }} booking(s) require attention</p>
                </div>
              </div>
              <button 
                @click="showOrdersModal = false"
                class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors"
              >
                <IconPark.Close theme="filled" size="28" />
              </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6">
              <div v-if="pendingOrdersAlert.length === 0" class="text-center py-12">
                <IconPark.CheckCircle theme="filled" size="64" fill="#22C55E" class="mb-4 mx-auto" />
                <p class="text-lg font-semibold text-gray-800">No Pending Bookings</p>
                <p class="text-gray-600 mt-2">All bookings are up to date!</p>
              </div>

              <div v-else class="space-y-4">
                <div 
                  v-for="booking in pendingOrdersAlert" 
                  :key="booking.id"
                  class="p-5 bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-xl hover:shadow-md transition-shadow"
                >
                  <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                      <div class="p-3 bg-red-100 rounded-lg flex-shrink-0">
                        <IconPark.AlertCircle theme="filled" size="24" fill="#DC2626" />
                      </div>
                      <div>
                        <p class="font-bold text-gray-900 text-base">Booking #{{ booking.id.toString().padStart(3, '0') }}</p>
                        <p class="text-sm text-gray-600">{{ booking.name }}</p>
                      </div>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold flex-shrink-0">
                      {{ formatStatus(booking.status) }}
                    </span>
                  </div>

                  <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-white rounded-lg p-3">
                      <p class="text-xs text-gray-600 font-medium">Category</p>
                      <p class="font-bold text-gray-900 text-sm mt-1">{{ booking.category }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                      <p class="text-xs text-gray-600 font-medium">Date</p>
                      <p class="font-bold text-gray-900 text-sm mt-1">{{ formatDate(booking.date) }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                      <p class="text-xs text-gray-600 font-medium">Amount</p>
                      <p class="font-bold text-gray-900 text-sm mt-1">{{ formatCurrency(booking.total_amount || 0) }}</p>
                    </div>
                  </div>

                  <div class="flex gap-3">
                    <button 
                      @click="approveBooking(booking)"
                      class="flex-1 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium"
                    >
                      Approve
                    </button>
                    <button 
                      @click="viewBooking(booking)"
                      class="flex-1 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium"
                    >
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3 flex-shrink-0">
              <button
                @click="showOrdersModal = false"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors font-medium text-sm"
              >
                Close
              </button>
              <button 
                v-if="pendingOrdersAlert.length > 0"
                @click="goToBookingsPage"
                class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#441011] transition-colors font-medium text-sm"
              >
                Manage All Bookings
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount, computed } from 'vue'
import Chart from 'chart.js/auto'
import AdminSidebar from './adminSidebar.vue'
import BookingCalendar from '../../components/BookingCalendar.vue'
import StatsCard from '../../components/StatsCard.vue'
import FacilityCard from '../../components/FacilityCard.vue'
import { usePage, router } from '@inertiajs/vue3'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import html2canvas from 'html2canvas'
// Add Icon Park icons
import * as IconPark from '@icon-park/vue-next'

const page = usePage()
const admin = computed(() => page.props.admin)

// Stats
const stats = computed(() => {
  const defaultStats = page.props.stats || {}
  const revenueData = defaultStats.revenue || {}
  
  return {
    revenue: {
      total: parseFloat(revenueData.total) || 0,
      monthly: parseFloat(revenueData.monthly) || 0
    },
    total_bookings: defaultStats.total_bookings || 0,
    pending_bookings: defaultStats.pending_bookings || 0,
    facilities: defaultStats.facilities || 0,
    facilities_active: defaultStats.facilities_active || 0,
    hostel_rooms: defaultStats.hostel_rooms || 0,
    hostel_active: defaultStats.hostel_active || 0,
    rental_spaces: defaultStats.rental_spaces || 0,
    rental_active: defaultStats.rental_active || 0,
    commercial_spaces: defaultStats.commercial_spaces || 0,
    commercial_active: defaultStats.commercial_active || 0,
    occupancy_rates: defaultStats.occupancy_rates || {
      overall: 0,
      hostel: 0,
      rental: 0,
      commercial: 0
    }
  }
})
const alerts = computed(() => page.props.alerts || [])
const loading = ref(false)
const showAlertsModal = ref(false)
const showOrdersModal = ref(false)

// Bookings
const recentBookings = ref(page.props.recentBookings ?? [])
const pendingOrdersAlert = ref([])

// Chart Data with proper initialization
const chartData = ref({
  lineChart: {
    monthly: { labels: [], hostel: [], facilities: [], rental: [], commercial: [] },
    quarterly: { labels: [], hostel: [], facilities: [], rental: [], commercial: [] },
    yearly: { labels: [], hostel: [], facilities: [], rental: [], commercial: [] }
  },
  pieChart: {
    monthly: [0, 0, 0, 0],
    quarterly: [0, 0, 0, 0],
    yearly: [0, 0, 0, 0]
  }
})

// Initialize chart data from props
onMounted(() => {
  if (page.props.chartData) {
    chartData.value = page.props.chartData
  }
})

const lineChartFilter = ref('monthly')
const pieChartFilter = ref('monthly')

// Search and Filter
const searchQuery = ref('')
const statusFilter = ref('')
let searchTimeout = null

let lineChartInstance = null
let donutChartInstance = null

// Formatters
function formatDate(dateString) {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

function formatStatus(status) {
  return status.charAt(0).toUpperCase() + status.slice(1)
}

function getStatusClass(status) {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'approved': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800',
    'completed': 'bg-blue-100 text-blue-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 0,
  }).format(amount || 0)
}

// Add a new computed property for total revenue display
const totalRevenueDisplay = computed(() => {
  const total = stats.value.revenue?.total || 0
  return formatCurrency(total)
})

// Alert Functions
function getAlertClasses(type) {
  const classes = {
    'warning': 'bg-yellow-50 border-yellow-300 text-yellow-800',
    'success': 'bg-green-50 border-green-300 text-green-800',
    'info': 'bg-blue-50 border-blue-300 text-blue-800',
    'error': 'bg-red-50 border-red-300 text-red-800'
  }
  return classes[type] || 'bg-gray-50 border-gray-300 text-gray-800'
}

function getAlertIcon(icon) {
  const icons = {
    'clock': 'i-icon-park-outline-time',
    'chart-line': 'i-icon-park-outline-trending-up',
    'calendar': 'i-icon-park-outline-calendar',
    'alert': 'i-icon-park-outline-alert',
    'info': 'i-icon-park-outline-info-circle'
  }
  return icons[icon] || 'i-icon-park-outline-info-circle'
}

function getAlertIconComponent(icon) {
  const iconMap = {
    'clock': IconPark.Time,
    'chart-line': IconPark.TrendingUp,
    'calendar': IconPark.Calendar,
    'alert': IconPark.AlertCircle,
    'info': IconPark.InfoCircle
  }
  return iconMap[icon] || IconPark.InfoCircle
}

function dismissAlert(alert) {
  const index = alerts.value.indexOf(alert)
  if (index > -1) {
    alerts.value.splice(index, 1)
  }
}

function handleAlertAction(alert) {
  if (alert.action === 'View Calendar') {
    const calendarSection = document.getElementById('calendar-section')
    if (calendarSection) {
      calendarSection.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  } else if (alert.action === 'Review Bookings') {
    router.visit('/use/booking')
  } else if (alert.action === 'View Reports') {
    window.location.href = '#charts'
  }
}

// Booking Functions
function refreshBookings() {
  router.reload({ only: ['recentBookings', 'stats'] })
}

async function viewBooking(booking) {
  try {
    const response = await fetch(`/use/bookings/${booking.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })
    const data = await response.json()
    if (data.booking) {
      // Show booking details - improved version
      showBookingModal(data.booking)
    }
  } catch (error) {
    console.error('Error viewing booking:', error)
    showNotification('Error loading booking details', 'error')
  }
}

async function approveBooking(booking) {
  if (!confirm(`Approve booking #${booking.id}?`)) return

  try {
    const response = await fetch(`/use/bookings/${booking.id}/approve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })
    const data = await response.json()
    if (data.success) {
      showNotification('Booking approved successfully', 'success')
      refreshBookings()
    } else {
      showNotification('Error approving booking', 'error')
    }
  } catch (error) {
    console.error('Error approving booking:', error)
    showNotification('Error approving booking', 'error')
  }
}

async function cancelBooking(booking) {
  if (!confirm(`Cancel booking #${booking.id}?`)) return

  try {
    const response = await fetch(`/use/bookings/${booking.id}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })
    const data = await response.json()
    if (data.success) {
      showNotification('Booking cancelled successfully', 'success')
      refreshBookings()
    } else {
      showNotification('Error cancelling booking', 'error')
    }
  } catch (error) {
    console.error('Error cancelling booking:', error)
    showNotification('Error cancelling booking', 'error')
  }
}

async function fetchBookings() {
  try {
    const params = new URLSearchParams()
    if (searchQuery.value) params.append('search', searchQuery.value)
    if (statusFilter.value) params.append('status', statusFilter.value)

    const response = await fetch(`/use/bookings?${params.toString()}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })

    if (!response.ok) {
      if (response.status === 401) {
        showNotification('Session expired. Please log in again.', 'error')
        return
      }
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    recentBookings.value = data.bookings || []
  } catch (error) {
    console.error('Error fetching bookings:', error)
  }
}

function debouncedSearch() {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchBookings()
  }, 500)
}

function clearFilters() {
  searchQuery.value = ''
  statusFilter.value = ''
  fetchBookings()
}

// Dashboard Functions
function refreshDashboard() {
  loading.value = true
  router.reload({ only: ['stats', 'recentBookings', 'chartData', 'alerts'] })
    .then(() => {
      loading.value = false
    })
    .catch(() => {
      loading.value = false
    })
}

function exportDashboardData() {
  const data = {
    stats: stats.value,
    recentBookings: recentBookings.value,
    alerts: alerts.value,
    exportedAt: new Date().toISOString()
  }
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `dashboard_${new Date().toISOString().split('T')[0]}.json`
  a.click()
  URL.revokeObjectURL(url)
}

// Chart Export
async function exportChartToPDF(chartId, filename) {
  try {
    const canvas = document.getElementById(chartId)
    if (!canvas) return

    const imageData = canvas.toDataURL('image/png')
    const doc = new jsPDF('landscape')
    const imgWidth = 270
    const imgHeight = 150
    doc.addImage(imageData, 'PNG', 10, 10, imgWidth, imgHeight)
    doc.save(`${filename}_${new Date().toISOString().split('T')[0]}.pdf`)
  } catch (error) {
    console.error('Error exporting chart:', error)
    showNotification('Error exporting chart', 'error')
  }
}

// Chart Rendering
function renderLineChart() {
  const ctx = document.getElementById('lineChart')
  if (!ctx) return
  
  if (lineChartInstance) lineChartInstance.destroy()

  const currentData = chartData.value.lineChart[lineChartFilter.value]
  
  lineChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: currentData.labels || [],
      datasets: [
        {
          label: 'Hostel Rooms',
          data: currentData.hostel || [],
          borderColor: '#4F46E5',
          backgroundColor: 'rgba(79,70,229,0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 2,
          pointRadius: 4,
          pointBackgroundColor: '#4F46E5',
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        },
        {
          label: 'Rental Spaces',
          data: currentData.rental || [],
          borderColor: '#F97316',
          backgroundColor: 'rgba(249,115,22,0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 2,
          pointRadius: 4,
          pointBackgroundColor: '#F97316',
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        },
        {
          label: 'Commercial Spaces',
          data: currentData.commercial || [],
          borderColor: '#8B5CF6',
          backgroundColor: 'rgba(139,92,246,0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 2,
          pointRadius: 4,
          pointBackgroundColor: '#8B5CF6',
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false
      },
      plugins: {
        legend: {
          position: 'top',
          labels: { 
            usePointStyle: true, 
            padding: 15,
            font: { size: 12, weight: 'bold' }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          padding: 12,
          titleFont: { size: 13, weight: 'bold' },
          bodyFont: { size: 12 },
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': ' + context.parsed.y + ' bookings'
            }
          }
        }
      },
      scales: {
        y: { 
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Bookings'
          }
        },
        x: {
          title: {
            display: true,
            text: lineChartFilter.value.charAt(0).toUpperCase() + lineChartFilter.value.slice(1)
          }
        }
      }
    }
  })
}

function renderDonutChart() {
  const ctx = document.getElementById('donutChart')
  if (!ctx) return
  
  if (donutChartInstance) donutChartInstance.destroy()
  
  const currentData = chartData.value.pieChart[pieChartFilter.value]
  const facilityLabels = ['Hostel Rooms', 'Rental Spaces', 'Commercial Spaces']
  
  // Calculate total for percentage display
  const total = (currentData || [0, 0, 0, 0]).reduce((a, b) => a + b, 0) || 1
  
  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: facilityLabels,
      datasets: [
        {
          data: currentData ? currentData.slice(1) : [0, 0, 0],
          backgroundColor: ['#F97316', '#8B5CF6', '#EC4899'],
          borderColor: '#fff',
          borderWidth: 2
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '60%',
      plugins: {
        legend: {
          position: 'right',
          labels: { 
            usePointStyle: true, 
            padding: 15,
            font: { size: 12, weight: 'bold' }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          padding: 12,
          callbacks: {
            label: function(context) {
              const value = context.parsed
              const percentage = ((value / total) * 100).toFixed(1)
              return context.label + ': ' + value + ' (' + percentage + '%)'
            }
          }
        }
      }
    }
  })
}

function loadPendingBookings() {
  const pending = recentBookings.value.filter(booking => booking.status === 'pending')
  pendingOrdersAlert.value = pending.slice(0, 5)
}

function goToBookingsPage() {
  showOrdersModal.value = false
  router.visit('/use/booking')
}

// Calendar handlers
function handleDateSelect(date) {
  const selectedDate = date.toISOString().split('T')[0]
  fetchBookingsByDate(selectedDate)
}

function handleEventClick(event) {
  const bookingId = event.extendedProps?.bookingId
  if (bookingId) {
    const booking = recentBookings.value.find(b => b.id === bookingId)
    if (booking) {
      viewBooking(booking)
    }
  }
}

async function fetchBookingsByDate(date) {
  try {
    const response = await fetch(`/use/bookings?date=${date}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })
    const data = await response.json()
    recentBookings.value = data.bookings || []
  } catch (error) {
    console.error('Error fetching bookings by date:', error)
  }
}

// Utility Functions
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
}

function showNotification(message, type = 'info') {
  // Could be replaced with a toast notification library
  console.log(`[${type.toUpperCase()}] ${message}`)
}

function showBookingModal(booking) {
  // Can be expanded later to show a detailed modal
  console.log('Booking Details:', booking)
}

// Lifecycle Hooks
onMounted(() => {
  setTimeout(() => {
    renderLineChart()
    renderDonutChart()
  }, 100)
  loadPendingBookings()
})

watch(lineChartFilter, renderLineChart)
watch(pieChartFilter, renderDonutChart)
watch(recentBookings, loadPendingBookings)

onBeforeUnmount(() => {
  if (lineChartInstance) lineChartInstance.destroy()
  if (donutChartInstance) donutChartInstance.destroy()
})
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
