<script setup>
import { ref, watch, onMounted, computed, nextTick } from 'vue'
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
  logs: { type: Array, default: () => [] }, // if you want logs separately
})

const monthsNames = [
  { v: 1, n: 'Jan' }, { v: 2, n: 'Feb' }, { v: 3, n: 'Mar' }, { v: 4, n: 'Apr' },
  { v: 5, n: 'May' }, { v: 6, n: 'Jun' }, { v: 7, n: 'Jul' }, { v: 8, n: 'Aug' },
  { v: 9, n: 'Sep' }, { v: 10, n: 'Oct' }, { v: 11, n: 'Nov' }, { v: 12, n: 'Dec' },
]

// Sidebar toggle state
const sidebarOpen = ref(false)

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}

function closeSidebar() {
  console.log('Sidebar closed') // ✅ this should appear
  sidebarOpen.value = false
}

const mode = ref(props.filters.mode || 'multi-year')
const year = ref(props.filters.year)
const month = ref(props.filters.month)
const startDate = ref(props.filters.startDate)
const endDate = ref(props.filters.endDate)

const availableYears = computed(() => props.filters?.availableYears ?? [])

// Chart.js
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
  if (!chartCanvas.value) return;
  const ctx = chartCanvas.value.getContext('2d');
  if (chartInstance) chartInstance.destroy();

  const unit = props.chart.unit;
  const xTitle = unit === 'year' ? 'Year' : unit === 'month' ? 'Month' : 'Day';
  const displayLabels = getDisplayLabels(props.chart.labels, unit);

  // Create gradient fills dynamically
  const gradient = ctx.createLinearGradient(0, 0, 0, 400);
  gradient.addColorStop(0, 'rgba(255, 166, 0, 0.9)');
  gradient.addColorStop(1, 'rgba(95, 18, 19, 0.9)');

  // Build datasets with a line overlay for trends
  const datasets = buildDataset().map((ds, idx) => ({
    ...ds,
    backgroundColor: gradient,
    borderColor: 'rgba(95, 18, 19, 1)',
    borderWidth: 2,
  }));

  // Optional: Add a line dataset for trends
  datasets.push({
    type: 'line',
    label: 'Trend',
    data: props.chart.datasets[0]?.data || [],
    borderColor: '#FFA600',
    borderWidth: 3,
    fill: false,
    tension: 0.3,
    pointBackgroundColor: '#5F1213',
    pointRadius: 5,
  });

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: { labels: displayLabels, datasets },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 1200,
        easing: 'easeOutQuart'
      },
      hover: {
        mode: 'nearest',
        intersect: true
      },
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: {
          title: { display: true, text: xTitle },
          ticks: { autoSkip: false }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Income (₱)' }
        }
      },
      plugins: {
        legend: { display: true, position: 'top' },
        tooltip: {
          backgroundColor: '#5F1213',
          titleColor: '#fff',
          bodyColor: '#fff',
          borderColor: '#FFA600',
          borderWidth: 1,
          callbacks: {
            label: (ctx) => {
              const val = ctx.parsed.y ?? ctx.raw ?? 0;
              const totalIncomeNum = typeof props.totalIncome === 'string' ? Number(props.totalIncome.replace(/,/g, '')) : props.totalIncome;
              const percentage =
                totalIncomeNum > 0
                  ? ((val / totalIncomeNum) * 100).toFixed(1) + '%'
                  : '';
              return `${ctx.dataset.label}: ₱${Number(val).toLocaleString()} (${percentage})`;
            }
          }
        }
      }
    }
  });
}

// ✅ Live-update when backend updates chart data
watch(() => props.chart, () => renderChart(), { deep: true })
onMounted(renderChart)

// ---------- Filters & Fetch ----------
function resetDependentSelections(newMode) {
  if (newMode === 'multi-year') {
    year.value = null; month.value = null; startDate.value = null; endDate.value = null
  } else if (newMode === 'monthly') {
    month.value = null; startDate.value = null; endDate.value = null
    if (availableYears.value.length && (year.value == null)) {
      year.value = availableYears.value[0]
    }
  } else if (newMode === 'daily') {
    startDate.value = null; endDate.value = null
    if (availableYears.value.length && (year.value == null)) {
      year.value = availableYears.value[0]
    }
    if (month.value == null) month.value = new Date().getMonth() + 1
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

let fetchTimer = null

function triggerFetch() {
  if (fetchTimer) clearTimeout(fetchTimer)

  fetchTimer = setTimeout(() => {
    const params = { mode: mode.value }

    // Always include what we have; backend can ignore when not needed
    if (year.value != null)  params.year  = year.value
    if (month.value != null) params.month = month.value
    if (startDate.value)     params.startDate = startDate.value
    if (endDate.value)       params.endDate   = endDate.value

    router.get(route('admin.dashboard'), params, {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    })
  }, 120)
}

watch(mode, async (m) => {
  resetDependentSelections(m)
  // ensure year/month defaults are applied before fetching
  await nextTick()
  triggerFetch()
})

// When any filter changes, just fetch. No early returns.
watch([year, month, startDate, endDate], () => {
  triggerFetch()
})

function exportChart() {
  if (!chartCanvas.value) return

  const canvas = chartCanvas.value
  const tempCanvas = document.createElement('canvas')
  const ctx = tempCanvas.getContext('2d')

  // Match size of original canvas
  tempCanvas.width = canvas.width
  tempCanvas.height = canvas.height

  // Fill white background
  ctx.fillStyle = '#ffffff'
  ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height)

  // Draw original chart on top
  ctx.drawImage(canvas, 0, 0)

  // Create download link
  const link = document.createElement('a')
  link.href = tempCanvas.toDataURL('image/png')
  link.download = `RevenueGraph-${Date.now()}.png`
  link.click()
}


</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P :sidebarOpen="sidebarOpen" @close="closeSidebar"/>

    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10 transition-all duration-300">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Dashboard</h1>
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

     

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
  <!-- Revenue Graph -->
  <div class="col-span-2 bg-white border border-gray-200 p-6 rounded-lg shadow">
    <div class="mb-6 flex items-center justify-between">
      <h3 class="text-lg font-bold text-[#5F1213]">
        REVENUE GRAPH — {{ props.chart.title }}
      </h3>
      <button class="bg-[#5F1213] hover:bg-[#7a1a1b] text-white px-3 py-1 rounded text-sm" @click="exportChart">Export</button>
    </div>
    <div class="relative h-72">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>

  <!-- Filters + Insights Container -->
  <div class="flex flex-col gap-6">
    <!-- Graph Filters -->
    <div
      class="flex flex-wrap gap-4 bg-white p-4 rounded-lg shadow items-end justify-start"
    >
      <!-- View -->
      <div class="flex flex-col min-w-[180px]">
        <label class="text-sm text-gray-600 font-semibold mb-1">View</label>
        <select
          v-model="mode"
          class="border rounded p-2 focus:ring-2 focus:ring-[#FFA600] focus:border-[#5F1213] text-[#5F1213] font-medium bg-white"
        >
          <option value="multi-year">All Years (Totals)</option>
          <option value="monthly">By Year → Monthly</option>
          <option value="daily">By Month → Daily</option>
          <option value="custom">Date Range</option>
        </select>
      </div>

      <!-- Year -->
      <div
        v-if="mode === 'monthly' || mode === 'daily'"
        class="flex flex-col min-w-[120px]"
      >
        <label class="text-sm text-gray-600 font-semibold mb-1">Year</label>
        <select
          v-model.number="year"
          class="border rounded p-2 focus:ring-2 focus:ring-[#FFA600] focus:border-[#5F1213] text-[#5F1213] font-medium bg-white"
        >
          <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
        </select>
      </div>

      <!-- Month -->
      <div v-if="mode === 'daily'" class="flex flex-col min-w-[120px]">
        <label class="text-sm text-gray-600 font-semibold mb-1">Month</label>
        <select
          v-model.number="month"
          class="border rounded p-2 focus:ring-2 focus:ring-[#FFA600] focus:border-[#5F1213] text-[#5F1213] font-medium bg-white"
        >
          <option v-for="m in monthsNames" :key="m.v" :value="m.v">{{ m.n }}</option>
        </select>
      </div>

      <!-- Start Date -->
      <div v-if="mode === 'custom'" class="flex flex-col min-w-[150px]">
        <label class="text-sm text-gray-600 font-semibold mb-1">Start Date</label>
        <input
          type="date"
          v-model="startDate"
          class="border rounded p-2 focus:ring-2 focus:ring-[#FFA600] focus:border-[#5F1213] text-[#5F1213] font-medium bg-white"
        />
      </div>

      <!-- End Date -->
      <div v-if="mode === 'custom'" class="flex flex-col min-w-[150px]">
        <label class="text-sm text-gray-600 font-semibold mb-1">End Date</label>
        <input
          type="date"
          v-model="endDate"
          class="border rounded p-2 focus:ring-2 focus:ring-[#FFA600] focus:border-[#5F1213] text-[#5F1213] font-medium bg-white"
        />
      </div>
    </div>

    <!-- Insights Below Filters -->
    <div class="bg-white p-6 rounded-lg shadow space-y-6">
      <h3 class="text-lg font-bold mb-4 text-[#5F1213]">Insights</h3>
      <div class="flex items-center justify-between">
        <span>Avg. Parking Time</span>
        <span class="font-semibold">{{ props.avgParkingTime }} mins</span>
      </div>
      <div class="flex items-center justify-between">
        <span>Returning Clients</span>
        <span class="font-semibold">{{ props.returningClients }}%</span>
      </div>
      <div class="flex items-center justify-between">
        <span>New Users</span>
        <span class="font-semibold">{{ props.newUsersThisMonth }}</span>
      </div>
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