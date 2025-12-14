<script setup>
import { ref, computed, toRef } from 'vue'
import { router } from '@inertiajs/vue3'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import AdminSidebarP2P from './adminSidebarP2P.vue'

// Props from Laravel/Inertia
const props = defineProps({
  entries: {
    type: Array,
    default: () => []
  }
})
const entries = toRef(props, 'entries')

// Sidebar toggle state
const sidebarOpen = ref(false)

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}

function closeSidebar() {
  console.log('Sidebar closed') // ✅ this should appear
  sidebarOpen.value = false
}

// Navigation
const admin_Dashboard = () => router.visit('/admin_Dashboard')
const admin_ManageParking = () => router.visit('/admin_ManageParking')
const admin_Reports = () => router.visit('/admin_Reports')
const admin_Account = () => router.visit('/admin_Account')

// Filters
const selectedFilter = ref('all')
const searchQuery = ref('')

// Filtered reports
const filteredReports = computed(() => {
  const now = new Date()
  return entries.value.filter((report) => {
    const inDate = report.time_in ? new Date(report.time_in) : null
    const matchesFilter = (() => {
      switch (selectedFilter.value) {
        case 'daily':
          return inDate && inDate.toDateString() === now.toDateString()
        case 'weekly':
          if (!inDate) return false
          const startOfWeek = new Date(now)
          startOfWeek.setDate(now.getDate() - now.getDay())
          const endOfWeek = new Date(startOfWeek)
          endOfWeek.setDate(startOfWeek.getDate() + 6)
          return inDate >= startOfWeek && inDate <= endOfWeek
        case 'monthly':
          return inDate && inDate.getMonth() === now.getMonth() && inDate.getFullYear() === now.getFullYear()
        case 'annually':
          return inDate && inDate.getFullYear() === now.getFullYear()
        default:
          return true
      }
    })()

    const matchesSearch =
      report.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      report.plate?.toLowerCase().includes(searchQuery.value.toLowerCase())

    return matchesFilter && matchesSearch
  })
})

// Pagination states
const currentPage = ref(1)
const itemsPerPage = ref(10)

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredReports.value.length / itemsPerPage.value))
)

const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredReports.value.slice(start, start + itemsPerPage.value)
})

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

// Total sales (all filtered results)
const totalSales = computed(() =>
  filteredReports.value.reduce((t, report) => t + (report.total_amount || 0), 0)
)

// Compute total hours
function getHours(inTime, outTime) {
  if (!inTime || !outTime) return '—'
  const diffMs = new Date(outTime) - new Date(inTime)
  return Math.round((diffMs / 1000 / 60 / 60) * 10) / 10
}


// Download POS PDF
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


function downloadFilteredPDF() {
  // Landscape A4
  const pdf = new jsPDF('l', 'mm', 'a4');

  // Header
  pdf.setFillColor(95, 18, 19);
  pdf.rect(0, 0, 297, 20, 'F');
  pdf.setFontSize(14);
  pdf.setFont('helvetica', 'bold');
  pdf.setTextColor(255, 255, 255);
  pdf.text('Filtered Parking Logs (All Results)', 148.5, 13, { align: 'center' });

  // Columns
  const tableColumns = ['#', 'Client Name', 'Plate No.', 'Time In', 'Time Out', 'Hours', 'Amount'];

  // Helper to safely get numeric amount and format with 2 decimals
  function safeAmount(val) {
    // If it's already a number, use it
    if (typeof val === 'number' && !isNaN(val)) return val.toFixed(2);

    // If it's a string, remove anything except digits, minus sign, and dot
    if (typeof val === 'string') {
      const cleaned = val.replace(/[^0-9.\-]/g, '');
      const n = parseFloat(cleaned);
      if (!isNaN(n)) return n.toFixed(2);
    }

    // fallback to 0.00
    return '0.00';
  }

  // Build rows from filteredReports (all filtered results)
  const tableRows = filteredReports.value.map((report, idx) => {
    const hoursVal = getHours(report.time_in, report.time_out);
    const amountStr = safeAmount(report.total_amount);

    return [
      idx + 1,
      report.name || '',
      report.plate || '',
      report.time_in
        ? new Date(report.time_in).toLocaleString('en-PH', { hour12: true })
        : '—',
      report.time_out
        ? new Date(report.time_out).toLocaleString('en-PH', { hour12: true })
        : '—',
      typeof hoursVal === 'number' ? hoursVal.toFixed(2) : hoursVal,
      // Use ASCII 'P' to avoid font glyph problems: "P40.00"
      `P${amountStr}`
    ];
  });

  // AutoTable
  autoTable(pdf, {
    startY: 25,
    head: [tableColumns],
    body: tableRows,
    theme: 'grid',
    headStyles: {
      fillColor: [95, 18, 19],
      textColor: 255,
      fontStyle: 'bold',
      halign: 'center',
      valign: 'middle'
    },
    bodyStyles: {
      valign: 'middle',
      fontSize: 9,
      cellWidth: 'wrap',
      overflow: 'linebreak'
    },
    columnStyles: {
      0: { cellWidth: 10, halign: 'center' },
      1: { cellWidth: 50, halign: 'left' },
      2: { cellWidth: 35, halign: 'left' },
      3: { cellWidth: 55, halign: 'center' },
      4: { cellWidth: 55, halign: 'center' },
      5: { cellWidth: 25, halign: 'right' },
      6: { cellWidth: 35, halign: 'right' }
    },
    alternateRowStyles: { fillColor: [245, 245, 245] },
    styles: { lineColor: [200, 200, 200], lineWidth: 0.1 }
  });

  // Total for all filtered results (sanitized)
  const total = filteredReports.value.reduce((sum, r) => {
    const a = parseFloat(safeAmount(r.total_amount));
    return sum + (isNaN(a) ? 0 : a);
  }, 0);

  const finalY = pdf.lastAutoTable?.finalY || 25;
  pdf.setFontSize(12);
  pdf.setFont('helvetica', 'bold');
  pdf.setTextColor(0, 0, 0);
  // Use ASCII 'P' here as well
  pdf.text(`Total Sales: P${total.toFixed(2)}`, 287, finalY + 10, { align: 'right' });

  pdf.save('filtered_logs_all_results.pdf');
}



</script>

<template>
  <div class="min-h-screen flex flex-col md:flex-row font-sans text-[#5F1213]">
    <!-- Sidebar -->
    <AdminSidebarP2P :sidebarOpen="sidebarOpen" @close="closeSidebar" />

    <!-- Main Content -->
    <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-4 sm:p-6 md:p-10 md:ml-64">
      <!-- Header -->
      <header class="flex flex-wrap items-center justify-between mb-6 gap-3">
        <div class="flex items-center gap-3">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#5F1213]">Reports</h1>
        </div>
      </header>

      <!-- Line divider -->
      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-4 sm:mb-6"></div>

      <!-- Filters -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search name or plate..."
          class="bg-white text-black border border-gray-300 px-3 py-2 rounded w-full sm:w-64 text-sm sm:text-base"
        />
        <select
          v-model="selectedFilter"
          class="bg-white text-black border border-gray-300 px-3 py-2 rounded w-full sm:w-40 text-sm sm:text-base"
        >
          <option value="all">All</option>
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="annually">Annually</option>
        </select>

        <button
          @click="downloadFilteredPDF"
          class="bg-[#5F1213] text-white px-4 py-2 rounded text-sm sm:text-base w-full sm:w-auto hover:bg-[#FFA600] transition"
        >
          Download PDF
        </button>
      </div>
      

      <!-- ✅ Responsive table wrapper -->
      <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full border-separate border-spacing-0 text-xs sm:text-sm md:text-base">
          <thead>
            <tr class="bg-[#5F1213] text-white">
              <th class="p-3 px-4 text-left rounded-tl-lg">#</th>
              <th class="p-3 px-4 text-left">Name</th>
              <th class="p-3 px-4 text-left">Plate</th>
              <th class="p-3 px-4 text-left">Time In</th>
              <th class="p-3 px-4 text-left">Time Out</th>
              <th class="p-3 px-4 text-left">Total Hours</th>
              <th class="p-3 px-4 text-left">Amount</th>
              <th class="p-3 px-4 text-left">QR Code</th>
              <th class="p-3 px-4 text-left rounded-tr-lg">POS</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(report, index) in paginatedReports"
              :key="report.id"
              class="bg-white border-b hover:bg-yellow-50 transition"
            >
              <td class="py-2 px-4 whitespace-nowrap">
                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
              </td>
              <td class="p-3 font-medium text-gray-900 whitespace-nowrap">{{ report.name }}</td>
              <td class="p-3 whitespace-nowrap">{{ report.plate }}</td>
              <td class="p-3 whitespace-nowrap">
                {{
                  report.time_in
                    ? new Date(report.time_in).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                      })
                    : '—'
                }}
              </td>
              <td class="p-3 whitespace-nowrap">
                {{
                  report.time_out
                    ? new Date(report.time_out).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                      })
                    : '—'
                }}
              </td>
              <td class="p-3 whitespace-nowrap">{{ getHours(report.time_in, report.time_out) }}</td>
              <td class="p-3 whitespace-nowrap">₱{{ report.total_amount?.toFixed(2) || '—' }}</td>
              <td class="p-3 whitespace-nowrap">
                <img
                  :src="`https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${report.plate}`"
                  alt="QR Code"
                  class="w-10 h-10 sm:w-12 sm:h-12 mx-auto"
                />
              </td>
              <td class="p-3 whitespace-nowrap">
                <button
                  @click="downloadPOS(report)"
                  class="bg-yellow-400 p-2 rounded-lg hover:bg-yellow-500 transition flex items-center justify-center"
                >
                  <i class="fa-solid fa-file-arrow-down text-white text-sm sm:text-base"></i>
                </button>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9" class="bg-white text-right text-lg sm:text-xl font-bold text-[#5F1213] p-3 sm:p-4">
                <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-3 sm:mb-4"></div>
                Total Sales: ₱{{ totalSales.toFixed(2) }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center items-center mt-6 gap-3 flex-wrap">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>

        <div class="flex items-center gap-2">
          <span
            v-for="n in 5"
            :key="n"
            class="w-3 h-3 rounded-full transition"
            :class="{ 'bg-yellow-400': n === dotPosition, 'bg-gray-300': n !== dotPosition }"
          ></span>
        </div>

        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="w-8 h-8 flex items-center justify-center rounded-full border border-maroon text-maroon transition"
          :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'"
        >
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
      </div>
    </div>
  </div>
</template>


<script>
export default { name: 'admin_parking_reports' }
</script>