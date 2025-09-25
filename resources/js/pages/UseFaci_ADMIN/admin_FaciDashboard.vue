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
          <h1 class="text-2xl font-semibold">Facilities Dashboard</h1>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Hostel Rooms -->
        <div
          class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center text-center"
        >
          <h2 class="text-sm font-medium uppercase">Hostel Rooms</h2>
          <p class="text-3xl font-bold mt-2">{{ stats.hostelRooms ?? 0 }}</p>
          <p class="text-sm mt-1">Active Bookings: {{ stats.hostelActive ?? 0 }}</p>
        </div>

        <!-- Facilities -->
        <div
          class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center text-center"
        >
          <h2 class="text-sm font-medium uppercase">Facilities</h2>
          <p class="text-3xl font-bold mt-2">{{ stats.facilities ?? 0 }}</p>
          <p class="text-sm mt-1">Currently In Use: {{ stats.facilitiesActive ?? 0 }}</p>
        </div>

        <!-- Rental Spaces -->
        <div
          class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center text-center"
        >
          <h2 class="text-sm font-medium uppercase">Rental Spaces</h2>
          <p class="text-3xl font-bold mt-2">{{ stats.rentalSpaces ?? 0 }}</p>
          <p class="text-sm mt-1">Ongoing Rentals: {{ stats.rentalActive ?? 0 }}</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Line Chart -->
        <div class="bg-white rounded-xl p-6 shadow-lg h-[400px]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Booking Trends</h3>
            <div class="flex items-center space-x-2">
              <select
                v-model="lineChartFilter"
                class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-0"
              >
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <!-- Only Export button -->
              <button
                @click="exportTableToPDF"
                class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]"
              >
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
              <!-- Only Export button -->
              <button
                @click="exportTableToPDF"
                class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]"
              >
                Export
              </button>
            </div>
          </div>
          <canvas id="donutChart" class="w-full h-full"></canvas>
        </div>
      </div>

      <!-- Recent Bookings Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Recent Bookings</h3>
          <button
            @click="refreshBookings"
            class="bg-[#5F1213] text-white px-3 py-1 text-sm rounded hover:bg-[#441011]"
          >
            Refresh
          </button>
        </div>

        <div v-if="recentBookings.length === 0" class="text-center py-8 text-gray-500">
          No bookings found.
        </div>

        <table v-else class="w-full text-sm text-left">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2">ID</th>
              <th class="py-2 px-2">Category</th>
              <th class="py-2 px-2">Name</th>
              <th class="py-2 px-2">Date</th>
              <th class="py-2 px-2">Status</th>
              <th class="py-2 px-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="booking in recentBookings"
              :key="booking.id"
              class="hover:bg-gray-50"
            >
              <td class="py-2 px-2">#{{ booking.id.toString().padStart(3, '0') }}</td>
              <td class="py-2 px-2">{{ booking.category }}</td>
              <td class="py-2 px-2">{{ booking.name }}</td>
              <td class="py-2 px-2">{{ formatDate(booking.date) }}</td>
              <td class="py-2 px-2">
                <span
                  :class="getStatusClass(booking.status)"
                  class="px-2 py-1 rounded-full text-xs font-medium"
                >
                  {{ formatStatus(booking.status) }}
                </span>
              </td>
              <td class="py-2 px-2 space-x-2">
                <button
                  @click="viewBooking(booking)"
                  class="text-indigo-600 hover:underline text-xs"
                >
                  View
                </button>
                <button
                  v-if="booking.status === 'pending'"
                  @click="approveBooking(booking)"
                  class="text-green-600 hover:underline text-xs"
                >
                  Approve
                </button>
                <button
                  v-if="booking.status === 'pending'"
                  @click="cancelBooking(booking)"
                  class="text-red-600 hover:underline text-xs"
                >
                  Cancel
                </button>
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
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const page = usePage()

// Stats
const stats = computed(() => ({
  hostelRooms: page.props.stats?.hostel_rooms ?? 0,
  hostelActive: page.props.stats?.hostel_active ?? 0,
  facilities: page.props.stats?.facilities ?? 0,
  facilitiesActive: page.props.stats?.facilities_active ?? 0,
  rentalSpaces: page.props.stats?.rental_spaces ?? 0,
  rentalActive: page.props.stats?.rental_active ?? 0,
}))

// Bookings
const recentBookings = ref(page.props.recentBookings ?? [])

// Chart Data
const chartData = page.props.chartData ?? {
  lineChart: {
    monthly: { labels: [], hostel: [], facilities: [], rental: [] },
    quarterly: { labels: [], hostel: [], facilities: [], rental: [] },
    yearly: { labels: [], hostel: [], facilities: [], rental: [] }
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

// Formatters
function formatDate(dateString) {
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
  switch (status) {
    case 'pending': return 'bg-yellow-100 text-yellow-800'
    case 'approved': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}
function refreshBookings() {
  router.reload({ only: ['recentBookings', 'stats'] })
}
function viewBooking(booking) { console.log('View booking:', booking) }
function approveBooking(booking) { console.log('Approve booking:', booking) }
function cancelBooking(booking) { console.log('Cancel booking:', booking) }

// ✅ Export Table to PDF
function exportTableToPDF() {
  const doc = new jsPDF()
  doc.text('Recent Bookings Report', 14, 15)

  const tableData = recentBookings.value.map((booking) => [
    `#${booking.id.toString().padStart(3, '0')}`,
    booking.category,
    booking.name,
    formatDate(booking.date),
    formatStatus(booking.status),
  ])

  autoTable(doc, {
    head: [['ID', 'Category', 'Name', 'Date', 'Status']],
    body: tableData,
    startY: 25,
  })

  doc.save('bookings_report.pdf')
}

// Line Chart
function renderLineChart() {
  const ctx = document.getElementById('lineChart')
  if (lineChartInstance) lineChartInstance.destroy()

  const currentData = chartData.lineChart[lineChartFilter.value]
  lineChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: currentData.labels,
      datasets: [
        {
          label: 'Hostel Rooms',
          data: currentData.hostel,
          borderColor: '#4F46E5',
          backgroundColor: 'rgba(79,70,229,0.2)',
          fill: true,
          tension: 0.4,
        },
        {
          label: 'Facilities',
          data: currentData.facilities,
          borderColor: '#22C55E',
          backgroundColor: 'rgba(34,197,94,0.2)',
          fill: true,
          tension: 0.4,
        },
        {
          label: 'Rental Spaces',
          data: currentData.rental,
          borderColor: '#F97316',
          backgroundColor: 'rgba(249,115,22,0.2)',
          fill: true,
          tension: 0.4,
        },
      ],
    },
    options: { responsive: true, maintainAspectRatio: false },
  })
}

// Donut Chart
function renderDonutChart() {
  const ctx = document.getElementById('donutChart')
  if (donutChartInstance) donutChartInstance.destroy()
  const currentData = chartData.pieChart[pieChartFilter.value]
  donutChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Hostel Rooms', 'Facilities', 'Rental Spaces'],
      datasets: [
        { data: currentData, backgroundColor: ['#4F46E5', '#22C55E', '#F97316'] },
      ],
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '60%' },
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
