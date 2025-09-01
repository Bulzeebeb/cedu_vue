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
const toggleSidebar = () => (sidebarOpen.value = !sidebarOpen)

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

// ✅ Watch chart as a whole for updates
watch(() => props.chart, () => renderChart(), { deep: true })
onMounted(renderChart)

function resetDependentSelections(newMode) {
  if (newMode === 'multi-year') {
    year.value = null; month.value = null; startDate.value = null; endDate.value = null
  } else if (newMode === 'monthly') {
    month.value = null; startDate.value = null; endDate.value = null
    if (!year.value && availableYears.value.length) year.value = availableYears.value[0]
  } else if (newMode === 'daily') {
    startDate.value = null; endDate.value = null
    if (!year.value && availableYears.value.length) year.value = availableYears.value[0]
    if (!month.value) month.value = new Date().getMonth() + 1
  } else if (newMode === 'custom') {
    year.value = null; month.value = null
    if (!startDate.value || !endDate.value) {
      const now = new Date()
      const iso = (d) => d.toISOString().slice(0, 10)
      startDate.value = iso(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 6))
      endDate.value = iso(now)
    }
  }
}

function triggerFetch() {
  const params = { mode: mode.value } // ✅ send mode explicitly
  if (mode.value === 'monthly' && year.value != null) params.year = year.value
  else if (mode.value === 'daily' && year.value != null && month.value != null) {
    params.year = year.value; params.month = month.value
  } else if (mode.value === 'custom' && startDate.value && endDate.value) {
    params.startDate = startDate.value; params.endDate = endDate.value
  }
  router.get(route('admin.dashboard'), params, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  })
}

watch(mode, (m) => { resetDependentSelections(m); triggerFetch() })
watch([year, month, startDate, endDate], () => {
  if (mode.value === 'monthly' && !year.value) return
  if (mode.value === 'daily' && (!year.value || !month.value)) return
  if (mode.value === 'custom' && (!startDate.value || !endDate.value)) return
  triggerFetch()
})
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P />

    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Dashboard</h1>
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Filters Row -->
      

      <!-- KPI Cards -->
      <main class="p-4 flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center mb-6">
          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-car text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ props.totalVehicles }}</h3>
            <p class="text-yellow-400">Total Vehicles</p>
          </div>

          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-coins text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">₱{{ props.totalIncome }}</h3>
            <p class="text-yellow-400">Total Income</p>
          </div>

          <div class="bg-[#5F1213] p-6 rounded shadow w-full">
            <div class="flex justify-center mb-2">
              <i class="fas fa-clock text-2xl text-yellow-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ props.peakHours }}</h3>
            <p class="text-yellow-400">Peak Hours</p>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <div class="col-span-2 bg-white border border-gray-200 p-6 rounded-lg shadow">
            <div class="mb-6">
              <h3 class="text-lg font-bold text-[#5F1213]">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                  
        <div class="bg-white p-4 rounded-lg shadow border">
          
          <label class="text-sm text-gray-600">View</label>
          <select v-model="mode" class="mt-1 w-full border rounded p-2">
            <option value="multi-year">All Years (Totals)</option>
            <option value="monthly">By Year → Monthly</option>
            <option value="daily">By Month → Daily</option>
            <option value="custom">Date Range</option>
          </select>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border" v-if="mode === 'monthly' || mode === 'daily'">
          <label class="text-sm text-gray-600">Year</label>
          <select v-model.number="year" class="mt-1 w-full border rounded p-2">
            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border" v-if="mode === 'daily'">
          <label class="text-sm text-gray-600">Month</label>
          <select v-model.number="month" class="mt-1 w-full border rounded p-2">
            <option v-for="m in monthsNames" :key="m.v" :value="m.v">{{ m.n }}</option>
          </select>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border" v-if="mode === 'custom'">
          <label class="text-sm text-gray-600">Start Date</label>
          <input type="date" v-model="startDate" class="mt-1 w-full border rounded p-2" />
        </div>

        <div class="bg-white p-4 rounded-lg shadow border" v-if="mode === 'custom'">
          <label class="text-sm text-gray-600">End Date</label>
          <input type="date" v-model="endDate" class="mt-1 w-full border rounded p-2" />
        </div>
      </div>
                REVENUE GRAPH — {{ props.chart.title }}
              </h3>
            </div>
            <div class="relative h-72">
              <canvas ref="chartCanvas"></canvas>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow space-y-6">
            <h3 class="text-lg font-bold mb-4 text-[#5F1213]">Insights</h3>
            <div class="flex items-center justify-between">
              <span>Avg. Parking Time</span><span class="font-semibold">{{ props.avgParkingTime }} mins</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Returning Clients</span><span class="font-semibold">{{ props.returningClients }}%</span>
            </div>
            <div class="flex items-center justify-between">
              <span>New Users</span><span class="font-semibold">{{ props.newUsersThisMonth }}</span>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
