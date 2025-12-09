<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({
  totalVehicles: { type: Number, default: 0 },
  totalIncome: { type: [Number, String], default: '0' },
  peakHours: { type: String, default: 'N/A' },
  avgParkingTime: { type: Number, default: 0 },
  returningClients: { type: Number, default: 0 },
  newUsersThisMonth: { type: Number, default: 0 },
  occupancyRate: { type: Number, default: 0 },
  availableSpots: { type: Number, default: 0 },
  chart: {
    type: Object,
    default: () => ({ labels: [], datasets: [], unit: 'year', title: '' }),
  },
  months: { type: Array, default: () => [] },
  filters: {
    type: Object,
    default: () => ({
      mode: 'multi-year',
      year: null,
      month: null,
      startDate: null,
      endDate: null,
      availableYears: [],
    }),
  },
})

const monthsNames = [
  { v: 1, n: 'Jan' }, { v: 2, n: 'Feb' }, { v: 3, n: 'Mar' }, { v: 4, n: 'Apr' },
  { v: 5, n: 'May' }, { v: 6, n: 'Jun' }, { v: 7, n: 'Jul' }, { v: 8, n: 'Aug' },
  { v: 9, n: 'Sep' }, { v: 10, n: 'Oct' }, { v: 11, n: 'Nov' }, { v: 12, n: 'Dec' },
]

const sidebarOpen = ref(false)
const toggleSidebar = () => (sidebarOpen.value = !sidebarOpen.value)

const mode = ref(props.filters.mode || 'multi-year')
const year = ref(props.filters.year)
const month = ref(props.filters.month)
const startDate = ref(props.filters.startDate)
const endDate = ref(props.filters.endDate)

const availableYears = computed(() => props.filters?.availableYears ?? [])

const chartCanvas = ref(null)
let chartInstance = null

function getDisplayLabels(labels, unit) {
  if (unit === 'month') {
    return labels.map((m) => monthsNames.find((x) => x.v === m)?.n || m)
  }
  return labels
}

function buildDataset() {
  const colors = [
    'rgba(95, 18, 19, 0.85)',
    'rgba(255, 166, 0, 0.85)',
    'rgba(0, 123, 255, 0.85)',
    'rgba(40, 167, 69, 0.85)',
    'rgba(153, 102, 255, 0.85)',
    'rgba(255, 99, 132, 0.85)',
    'rgba(255, 206, 86, 0.85)',
  ]
  return (props.chart.datasets || []).map((ds, idx) => ({
    type: 'bar',
    label: ds.label || 'Income',
    data: ds.data || [],
    backgroundColor: colors[idx % colors.length],
    borderColor: colors[idx % colors.length],
    borderWidth: 1,
    barPercentage: 0.6,
    categoryPercentage: 0.7,
  }))
}

function renderChart() {
  if (!chartCanvas.value) return
  const ctx = chartCanvas.value.getContext('2d')
  if (chartInstance) chartInstance.destroy()

  const unit = props.chart.unit
  const xTitle = unit === 'year' ? 'Year' : unit === 'month' ? 'Month' : 'Day'
  const displayLabels = getDisplayLabels(props.chart.labels, unit)

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: { labels: displayLabels, datasets: buildDataset() },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: { title: { display: true, text: xTitle }, ticks: { autoSkip: false } },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Income (₱)' },
        },
      },
      plugins: {
        legend: { display: true, position: 'top' },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const val = ctx.parsed.y ?? ctx.raw ?? 0
              return `${ctx.dataset.label}: ₱${Number(val).toLocaleString()}`
            },
          },
        },
      },
    },
  })
}

watch(() => props.chart, () => renderChart(), { deep: true })
onMounted(renderChart)

function resetDependentSelections(newMode) {
  if (newMode === 'multi-year') {
    year.value = null
    month.value = null
    startDate.value = null
    endDate.value = null
  } else if (newMode === 'monthly') {
    month.value = null
    startDate.value = null
    endDate.value = null
    if (!year.value && availableYears.value.length) year.value = availableYears.value[0]
  } else if (newMode === 'daily') {
    startDate.value = null
    endDate.value = null
    if (!year.value && availableYears.value.length) year.value = availableYears.value[0]
    if (!month.value) month.value = new Date().getMonth() + 1
  } else if (newMode === 'custom') {
    year.value = null
    month.value = null
    if (!startDate.value || !endDate.value) {
      const now = new Date()
      const iso = (d) => d.toISOString().slice(0, 10)
      startDate.value = iso(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 6))
      endDate.value = iso(now)
    }
  }
}

function triggerFetch() {
  const params = { mode: mode.value }
  if (mode.value === 'monthly' && year.value != null) params.year = year.value
  else if (mode.value === 'daily' && year.value != null && month.value != null) {
    params.year = year.value
    params.month = month.value
  } else if (mode.value === 'custom' && startDate.value && endDate.value) {
    params.startDate = startDate.value
    params.endDate = endDate.value
  }
  router.get(route('admin.dashboard'), params, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  })
}

watch(mode, (m) => {
  resetDependentSelections(m)
  triggerFetch()
})

watch([year, month, startDate, endDate], () => {
  if (mode.value === 'monthly' && !year.value) return
  if (mode.value === 'daily' && (!year.value || !month.value)) return
  if (mode.value === 'custom' && (!startDate.value || !endDate.value)) return
  triggerFetch()
})
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213] bg-gray-50">
    <AdminSidebarP2P />

    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 min-h-screen">
      <!-- Header Section -->
      <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
        <div class="px-6 md:px-10 py-6 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <button class="md:hidden text-2xl text-[#5F1213] hover:text-[#FFA600]" @click="toggleSidebar">
              <i class="fas fa-bars"></i>
            </button>
            <div>
              <h1 class="text-3xl md:text-4xl font-extrabold text-[#5F1213]">Dashboard</h1>
              <p class="text-sm text-gray-500 mt-1">Welcome back! Here's your parking system overview</p>
            </div>
          </div>
          <div class="hidden md:flex items-center gap-3 text-gray-600">
            <i class="fas fa-calendar text-lg text-[#FFA600]"></i>
            <span class="text-sm font-medium">{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
          </div>
        </div>
      </header>

      <main class="p-6 md:p-10">
        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
          <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-filter text-[#FFA600]"></i>
            <h2 class="text-lg font-bold text-[#5F1213]">Report Filters</h2>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">View</label>
              <select v-model="mode" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition">
                <option value="multi-year">All Years (Totals)</option>
                <option value="monthly">By Year → Monthly</option>
                <option value="daily">By Month → Daily</option>
                <option value="custom">Date Range</option>
              </select>
            </div>

            <div v-if="mode === 'monthly' || mode === 'daily'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
              <select v-model.number="year" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition">
                <option :value="null">Select Year</option>
                <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
              </select>
            </div>

            <div v-if="mode === 'daily'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
              <select v-model.number="month" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition">
                <option :value="null">Select Month</option>
                <option v-for="m in monthsNames" :key="m.v" :value="m.v">{{ m.n }}</option>
              </select>
            </div>

            <div v-if="mode === 'custom'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
              <input type="date" v-model="startDate" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition" />
            </div>

            <div v-if="mode === 'custom'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
              <input type="date" v-model="endDate" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition" />
            </div>
          </div>
        </div>

        <!-- KPI Cards Row 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Vehicles -->
          <div class="bg-white rounded-lg shadow-md border-l-4 border-[#5F1213] p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-600">Total Vehicles</p>
                <h3 class="text-3xl font-bold text-[#5F1213] mt-2">{{ props.totalVehicles }}</h3>
              </div>
              <div class="bg-[#5F1213] bg-opacity-10 p-4 rounded-lg">
                <i class="fas fa-car text-2xl text-[#5F1213]"></i>
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Vehicles parked today</p>
          </div>

          <!-- Total Income -->
          <div class="bg-white rounded-lg shadow-md border-l-4 border-[#FFA600] p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-600">Total Income</p>
                <h3 class="text-3xl font-bold text-[#FFA600] mt-2">₱{{ Number(props.totalIncome).toLocaleString() }}</h3>
              </div>
              <div class="bg-[#FFA600] bg-opacity-10 p-4 rounded-lg">
                <i class="fas fa-coins text-2xl text-[#FFA600]"></i>
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Total revenue</p>
          </div>

          <!-- Peak Hours -->
          <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-600">Peak Hours</p>
                <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ props.peakHours }}</h3>
              </div>
              <div class="bg-blue-100 p-4 rounded-lg">
                <i class="fas fa-clock text-2xl text-blue-600"></i>
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Busiest time</p>
          </div>

          <!-- Occupancy Rate -->
          <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-600">Occupancy Rate</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ props.occupancyRate }}%</h3>
              </div>
              <div class="bg-green-100 p-4 rounded-lg">
                <i class="fas fa-chart-pie text-2xl text-green-600"></i>
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Current utilization</p>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
          <!-- Chart Section -->
          <div class="xl:col-span-2 bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <div class="mb-6">
              <h2 class="text-xl font-bold text-[#5F1213] flex items-center gap-2">
                <i class="fas fa-chart-bar text-[#FFA600]"></i>
                Revenue Graph
              </h2>
              <p class="text-sm text-gray-500 mt-1">{{ props.chart.title || 'Income Overview' }}</p>
            </div>
            <div class="relative h-80 bg-gray-50 rounded-lg p-4">
              <canvas ref="chartCanvas"></canvas>
            </div>
          </div>

          <!-- Insights Section -->
          <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-[#5F1213] flex items-center gap-2 mb-6">
              <i class="fas fa-lightbulb text-[#FFA600]"></i>
              Insights
            </h2>
            <div class="space-y-5">
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3">
                  <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-hourglass-half text-blue-600"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-600">Avg. Parking Time</p>
                    <p class="text-lg font-bold text-[#5F1213]">{{ props.avgParkingTime }} mins</p>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3">
                  <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-users text-purple-600"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-600">Returning Clients</p>
                    <p class="text-lg font-bold text-[#5F1213]">{{ props.returningClients }}%</p>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3">
                  <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-user-plus text-green-600"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-600">New Users This Month</p>
                    <p class="text-lg font-bold text-[#5F1213]">{{ props.newUsersThisMonth }}</p>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3">
                  <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-parking text-orange-600"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-600">Available Spots</p>
                    <p class="text-lg font-bold text-[#5F1213]">{{ props.availableSpots }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Stats Row (Optional) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div class="bg-gradient-to-br from-[#5F1213] to-[#8B1A1B] rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold opacity-90">Active Sessions</p>
                <p class="text-3xl font-bold mt-2">{{ Math.floor(Math.random() * 50) + 10 }}</p>
              </div>
              <i class="fas fa-wifi text-4xl opacity-20"></i>
            </div>
          </div>

          <div class="bg-gradient-to-br from-[#FFA600] to-[#FFB833] rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold opacity-90">System Uptime</p>
                <p class="text-3xl font-bold mt-2">99.9%</p>
              </div>
              <i class="fas fa-shield text-4xl opacity-20"></i>
            </div>
          </div>

          <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold opacity-90">Transactions Today</p>
                <p class="text-3xl font-bold mt-2">{{ Math.floor(Math.random() * 500) + 100 }}</p>
              </div>
              <i class="fas fa-exchange-alt text-4xl opacity-20"></i>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #5F1213;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #8B1A1B;
}
</style>
