<template>
  <div class="bg-gray-50 text-gray-900 min-h-screen font-sans">
    <!-- Header -->
    <header
      class="bg-maroon text-white py-2 px-4 flex flex-col sm:flex-row justify-between items-center gap-2"
    >
      <div class="flex items-center gap-2">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-lg font-bold">
          CEDU <span class="text-blue-300">iCentral</span>
        </h1>
      </div>
    </header>

    <!-- Parking History Section -->
    <section class="px-4 py-6 sm:px-8">
      <h2
        class="text-2xl font-bold text-yellow-600 mb-6 border-l-4 border-maroon pl-3"
      >
        Parking History
      </h2>

      <div class="bg-white rounded-xl shadow-xl p-6">
        <!-- Search + Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name, plate no, or date..."
            class="px-3 py-2 rounded border border-gray-300 text-sm w-full sm:w-64"
          />

          <!-- Time Filter -->
          <select
            v-model="selectedFilter"
            class="px-3 py-2 rounded border border-gray-300 text-sm w-full sm:w-48"
          >
            <option value="all">All</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
          <table class="min-w-full text-sm border-collapse">
            <thead>
              <tr class="bg-maroon text-white">
                <th class="py-3 px-4 text-left font-semibold">#</th>
                <th class="py-3 px-4 text-left font-semibold">Name</th>
                <th class="py-3 px-4 text-left font-semibold">Plate</th>
                <th class="py-3 px-4 text-left font-semibold">Time In</th>
                <th class="py-3 px-4 text-left font-semibold">Time Out</th>
                <th class="py-3 px-4 text-left font-semibold">Total Hours</th>
                <th class="py-3 px-4 text-left font-semibold">Amount</th>
                <th class="py-3 px-4 text-left font-semibold">Status</th>
                <th class="py-3 px-4 text-left font-semibold">QR Code</th>
                <th class="py-3 px-4 text-center font-semibold">POS</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="(entry, index) in paginatedEntries"
                :key="entry.id"
                class="hover:bg-yellow-50 transition-colors"
              >
                <td class="py-3 px-4">
                  {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                </td>
                <td class="py-3 px-4 font-medium text-gray-900">
                  {{ entry.name }}
                </td>
                <td class="py-3 px-4">{{ entry.plate }}</td>
                <td class="py-3 px-4">{{ formatTime(entry.time_in) }}</td>
                <td class="py-3 px-4">{{ formatTime(entry.time_out) }}</td>
                <td class="py-3 px-4">
                  {{ computeHours(entry.time_in, entry.time_out) }}
                </td>
                <td class="py-3 px-4 font-semibold text-gray-700">
                  {{ entry.total_amount || '—' }}
                </td>
                <td class="py-3 px-4">
                  <span
                    class="px-2 py-1 rounded-full text-xs font-semibold"
                    :class="entry.status === 'Paid'
                      ? 'bg-green-100 text-green-700'
                      : 'bg-yellow-100 text-yellow-700'"
                  >
                    {{ entry.status }}
                  </span>
                </td>
                <td class="py-3 px-4">
                  <img
                    :src="`https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${entry.plate}`"
                    alt="QR Code"
                    class="w-12 h-12 rounded border"
                  />
                </td>
                <td class="py-3 px-4 text-center">
                  <button
                    @click="downloadPOS(entry)"
                    title="Download POS"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded-lg shadow-sm transition"
                  >
                    <i class="fa-solid fa-file-arrow-down"></i>
                  </button>
                </td>
              </tr>

              <!-- Empty rows -->
              <tr
                v-for="n in emptyRowCount"
                :key="'blank-' + n"
                class="bg-gray-50"
              >
                <td
                  colspan="10"
                  class="py-3 px-4 text-center text-gray-300"
                >—</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center items-center mt-6 gap-3">
        <!-- Prev Button -->
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === 1
            ? 'opacity-40 cursor-not-allowed'
            : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>

        <!-- Dots -->
        <div class="flex items-center gap-2">
          <span
            v-for="n in 5"
            :key="n"
            class="w-3 h-3 rounded-full transition"
            :class="{
              'bg-yellow-400': n === dotPosition,
              'bg-gray-300': n !== dotPosition
            }"
          ></span>
        </div>

        <!-- Next Button -->
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === totalPages
            ? 'opacity-40 cursor-not-allowed'
            : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, toRef, watch } from 'vue'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const props = defineProps({
  entries: {
    type: Array,
    default: () => []
  }
})

const entries = toRef(props, 'entries')

// states
const currentPage = ref(1)
const itemsPerPage = ref(10)
const searchQuery = ref('')
const selectedFilter = ref('all')

// Reset page when search/filter changes
watch([searchQuery, selectedFilter], () => {
  currentPage.value = 1
})

// filter entries by search + time filter
const filteredEntries = computed(() => {
  const term = searchQuery.value.toLowerCase().trim()
  const now = new Date()

  return entries.value.filter(entry => {
    const formattedTimeIn = entry.time_in ? new Date(entry.time_in).toLocaleDateString() : ''
    const formattedTimeOut = entry.time_out ? new Date(entry.time_out).toLocaleDateString() : ''

    const matchesSearch =
      !term ||
      entry.name?.toLowerCase().includes(term) ||
      entry.plate?.toLowerCase().includes(term) ||
      formattedTimeIn.includes(term) ||
      formattedTimeOut.includes(term)

    if (!matchesSearch) return false

    if (selectedFilter.value === 'all') return true
    const timeIn = new Date(entry.time_in)
    const diffDays = (now - timeIn) / (1000 * 60 * 60 * 24)

    switch (selectedFilter.value) {
      case 'daily': return diffDays < 1
      case 'weekly': return diffDays < 7
      case 'monthly': return diffDays < 30
      case 'quarterly': return diffDays < 90
      case 'yearly': return diffDays < 365
      default: return true
    }
  })
})

// pagination core
const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredEntries.value.length / itemsPerPage.value))
)

const paginatedEntries = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredEntries.value.slice(start, start + itemsPerPage.value)
})

const emptyRowCount = computed(() =>
  itemsPerPage.value - paginatedEntries.value.length
)

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const dotPosition = computed(() => {
  if (totalPages.value <= 5) return currentPage.value
  if (currentPage.value <= 2) return currentPage.value
  if (currentPage.value >= totalPages.value - 1)
    return 5 - (totalPages.value - currentPage.value)
  return 3
})

// helpers
function formatTime(time) {
  return time
    ? new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    : '—'
}

function computeHours(start, end) {
  if (!start || !end) return '—'
  const s = new Date(start)
  const e = new Date(end)
  return ((e - s) / (1000 * 60 * 60)).toFixed(1)
}

// ✅ FIXED downloadPOS to use entry data
function downloadPOS(entry) {
  try {
    const pdf = new jsPDF('p', 'mm', 'a4')
    const marginLeft = 14
    let y = 15

    const name = entry.name || 'Unknown'
    const plate = entry.plate || 'N/A'
    const dateToday = new Date().toLocaleDateString()
    const controlNo = `POS-${Date.now()}`
    const facilityCost = Number(entry.total_amount) || 0

    //LOGO
    pdf.addImage('/images/PayToPark/logo.png', 'PNG', marginLeft, y - 5, 20, 20)

    // === HEADER ===
    pdf.setTextColor(0, 0, 0)
    pdf.setFontSize(10)
    pdf.setFont('helvetica', 'normal')
    pdf.text('Republic of the Philippines', 105, y, { align: 'center' })
    y += 5
    pdf.setFont('helvetica', 'bold')
    pdf.text('University of Southeastern Philippines', 105, y, { align: 'center' })
    y += 5
    pdf.setFont('helvetica', 'italic')
    pdf.text('Resource Management Division (RMD)', 105, y, { align: 'center' })
    y += 6

    pdf.setFont('helvetica', 'bold')
    pdf.setFontSize(12)
    pdf.text('ORDER OF PAYMENT', 105, y, { align: 'center' })
    y += 10

    // === INFO BOX ===
    pdf.setFontSize(9)
    pdf.setFont('helvetica', 'normal')
    pdf.rect(marginLeft, y, 182, 32)

   // Right side (Control No.)
    pdf.setTextColor(200, 0, 0)
    pdf.text(`Control no.: ${controlNo}`, 180, y + 8, { align: 'right' })
    pdf.setTextColor(0, 0, 0)

    // Left side info
    pdf.text(`Name: ${name}`, marginLeft + 2, y + 12)
    pdf.text(`Plate: ${plate}`, marginLeft + 2, y + 18)
    pdf.text(`Date Applied: ${dateToday}`, marginLeft + 2, y + 24)
    pdf.text(`Validity Period: ____________________________`, marginLeft + 2, y + 30)

    y += 38


    // === FACILITY TABLE ===
    pdf.setFont('helvetica', 'bold')
    pdf.setFontSize(10)
    pdf.text('FACILITY', marginLeft, y)
    y += 4

    const facilities = [
      'Billboard Posting',
      'Classrooms',
      'Commercial Stall',
      'Consultancy',
      'Covered Court',
      'Farmer’s Training Center',
      'Fitness Center',
      'Gymnasium',
      'Hostel Dining Hall',
      'Hostel Rooms',
      'Hostel Training Hall',
      'Open Space/Ground',
      'Printing Press',
      'Social Hall',
      'Others (specify): PAY TO PARK'
    ]

    const facilityRows = facilities.map(item => {
  const isChecked = item.includes('Others')
  return [
    isChecked ? '/' : '', // now handled as checkbox
    item,
    isChecked ? facilityCost.toFixed(2) : ''
  ]
})

    autoTable(pdf, {
  startY: y,
  head: [['', 'Facility', 'Cost']],
  body: facilityRows,
  theme: 'grid',
  headStyles: { fillColor: [200, 200, 200], fontSize: 9, halign: 'center' },
  bodyStyles: { fontSize: 9 },
  columnStyles: {
    0: { cellWidth: 12, halign: 'center' },
    1: { cellWidth: 118 },
    2: { cellWidth: 52, halign: 'right' }
  },
  didDrawCell: function (data) {
    if (data.column.index === 0 && data.section === 'body') {
      const { x, y, height } = data.cell
      const size = 4 // checkbox size
      const checkboxX = x + (data.cell.width - size) / 2
      const checkboxY = y + (height - size) / 2

      // draw square
      pdf.rect(checkboxX, checkboxY, size, size)

      // if checked
      if (data.cell.raw && data.cell.raw.toString().includes('✔')) {
        pdf.setFontSize(8)
        pdf.text('/', checkboxX + 0.5, checkboxY + size - 0.5)
      }
    }
  }
})


    y = pdf.lastAutoTable.finalY + 6

    // === PAYMENT TABLE ===
pdf.setFont('helvetica', 'bold')
pdf.setFontSize(10)
pdf.text('PAYMENT', marginLeft, y)
y += 4

let paymentRows = []
let paymentsTotal = 0

if (Array.isArray(entry.payments) && entry.payments.length > 0) {
  paymentRows = entry.payments.map(p => {
    const cost = Number(p.cost) || 0
    paymentsTotal += cost
    // mark ✔ if has cost
    return [cost > 0 ? '/' : '', p.description || p.desc || 'Other', cost ? cost.toFixed(2) : '']
  })
} else {
  const defaultPayments = [
    'Excess Hour/s',
    'Overtime Pay of Staff',
    'Use of Generator',
    'Others (specify): ______________________'
  ]
  paymentRows = defaultPayments.map(desc => ['', desc, ''])
  paymentsTotal = 0
}

autoTable(pdf, {
  startY: y,
  head: [['', 'Description', 'Cost']],
  body: paymentRows,
  theme: 'grid',
  headStyles: { fillColor: [200, 200, 200], fontSize: 9, halign: 'center' },
  bodyStyles: { fontSize: 9 },
  columnStyles: {
    0: { cellWidth: 12, halign: 'center' },
    1: { cellWidth: 118 },
    2: { cellWidth: 52, halign: 'right' }
  },
  didDrawCell: function (data) {
    if (data.column.index === 0 && data.section === 'body') {
      const { x, y, height } = data.cell
      const size = 4 // checkbox size
      const checkboxX = x + (data.cell.width - size) / 2
      const checkboxY = y + (height - size) / 2

      // draw square
      pdf.rect(checkboxX, checkboxY, size, size)

      // draw ✔ if marked
      if (data.cell.raw && data.cell.raw.toString().includes('✔')) {
        pdf.setFontSize(8)
        pdf.text('✔', checkboxX + 0.5, checkboxY + size - 0.5)
      }
    }
  }
})

    y = pdf.lastAutoTable.finalY + 6

    // === TOTAL COST ===
    const totalCost = facilityCost + paymentsTotal
    pdf.setFont('helvetica', 'bold')
    pdf.setFontSize(10)
    pdf.text(`Total Cost: Php`, 135, y)
    pdf.text(totalCost.toFixed(2), 195, y, { align: 'right' })
    y += 18

    // === SIGNATURE SECTION ===
    pdf.setFont('helvetica', 'normal')
    pdf.setFontSize(10)
    pdf.text('By: Staff', marginLeft, y)
    pdf.text('Noted by: CEDU Head / RMD Director', 130, y)
    y += 6
    pdf.text('_____________________', marginLeft, y)
    pdf.text('_____________________', 130, y)

    // Save
    pdf.save(`POS_${name.replace(/\s+/g, '_')}.pdf`)
  } catch (err) {
    console.error('Error generating PDF:', err)
  }
}

</script>


<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
</style>
