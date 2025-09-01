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

// Sidebar toggle
const sidebarOpen = ref(false)
const toggleSidebar = () => { sidebarOpen.value = !sidebarOpen.value }

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

const totalPages = computed(() => Math.ceil(filteredReports.value.length / itemsPerPage.value))

// Slice reports for current page
const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredReports.value.slice(start, end)
})

// Visible page numbers
const visiblePages = computed(() => {
  const pages = []
  let start = Math.max(1, currentPage.value - 2)
  let end = Math.min(totalPages.value, currentPage.value + 2)
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Change page
function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

// Total sales (all filtered results)
const totalSales = computed(() =>
  filteredReports.value.reduce((t, report) => t + (report.total_amount || 0), 0)
)

// Compute total hours
function getHours(inTime, outTime) {
  if (!inTime || !outTime) return '—'
  const diffMs = new Date(outTime) - new Date(inTime)
  return Math.round(diffMs / 1000 / 60 / 60 * 10) / 10
}

// Download POS PDF
const downloadPOS = (report) => {
  const pdf = new jsPDF('p', 'mm', 'a4');
  const marginLeft = 14;
  let y = 15;

  const name = report.name || 'Unknown';
  const dateToday = new Date().toLocaleDateString();
  const controlNo = `POS-${String(report.id).padStart(6, '0')}`;
  const totalCost = report.total_amount || 0;

  pdf.setTextColor(0, 0, 0);
  pdf.setFontSize(10);
  pdf.setFont('helvetica', 'normal');
  pdf.text('Republic of the Philippines', 105, y, { align: 'center' });
  y += 5;
  pdf.setFont('helvetica', 'bold');
  pdf.text('University of Southeastern Philippines', 105, y, { align: 'center' });
  y += 5;
  pdf.setFont('helvetica', 'italic');
  pdf.text('Resource Management Division (RMD)', 105, y, { align: 'center' });
  y += 6;

  pdf.setFont('helvetica', 'bold');
  pdf.setFontSize(12);
  pdf.text('ORDER OF PAYMENT', 105, y, { align: 'center' });
  y += 10;

  pdf.setFontSize(9);
  pdf.setFont('helvetica', 'normal');
  pdf.rect(marginLeft, y, 182, 32);
  pdf.text(`Control No.: ${controlNo}`, marginLeft + 2, y + 6);
  pdf.text(`Name: ${name}`, marginLeft + 2, y + 12);
  pdf.text(`Organization: ____________________________`, marginLeft + 2, y + 18);
  pdf.text(`Date Applied: ${dateToday}`, marginLeft + 2, y + 24);
  pdf.text(`Validity Period: ____________________________`, marginLeft + 2, y + 30);
  y += 38;

  const facilities = [
    'Billboard Posting', 'Classrooms', 'Commercial Stall', 'Consultancy', 'Covered Court',
    'Farmer’s Training Center', 'Fitness Center', 'Gymnasium', 'Hostel Dining Hall', 'Hostel Rooms',
    'Hostel Training Hall', 'Open Space/Ground', 'Printing Press', 'Social Hall',
    'Others (specify): PAY TO PARK'
  ];

  const facilityRows = facilities.map(item => {
    const isChecked = item.includes('Others');
    return {
      select: isChecked ? '☑' : '☐',
      facility: item,
      cost: isChecked ? totalCost.toFixed(2) : ''
    };
  });

  autoTable(pdf, {
    startY: y,
    head: [['', 'Facility', 'Cost']],
    body: facilityRows.map(row => [row.select, row.facility, row.cost]),
    theme: 'grid',
    headStyles: { fillColor: [200, 200, 200], fontSize: 9, fontStyle: 'bold', halign: 'center' },
    bodyStyles: { fontSize: 9, valign: 'middle' },
    columnStyles: { 0: { cellWidth: 10, halign: 'center' }, 1: { cellWidth: 120 }, 2: { cellWidth: 52, halign: 'right' } }
  });

  y = pdf.lastAutoTable.finalY + 6;

  const payments = [
    'Excess Hour/s', 'Overtime Pay of Staff', 'Use of Generator', 'Others (specify): ______________________'
  ];
  const paymentRows = payments.map(item => ({ select: '☐', description: item, cost: '' }));

  autoTable(pdf, {
    startY: y,
    head: [['', 'Description', 'Cost']],
    body: paymentRows.map(row => [row.select, row.description, row.cost]),
    theme: 'grid',
    headStyles: { fillColor: [200, 200, 200], fontSize: 9, fontStyle: 'bold', halign: 'center' },
    bodyStyles: { fontSize: 9, valign: 'middle' },
    columnStyles: { 0: { cellWidth: 10, halign: 'center' }, 1: { cellWidth: 120 }, 2: { cellWidth: 52, halign: 'right' } }
  });

  y = pdf.lastAutoTable.finalY + 6;
  pdf.setFont('helvetica', 'bold');
  pdf.setFontSize(10);
  pdf.text(`Total Cost: Php`, 135, y);
  pdf.text(totalCost.toFixed(2), 195, y, { align: 'right' });
  y += 18;

  pdf.setFont('helvetica', 'normal');
  pdf.setFontSize(10);
  pdf.text('By: Staff', marginLeft, y);
  pdf.text('Noted by: CEDU Head / RMD Director', 130, y);
  y += 6;
  pdf.text('_____________________', marginLeft, y);
  pdf.text('_____________________', 130, y);

  pdf.save(`POS_${name.replace(/\s+/g, '_')}.pdf`);
};

// Download filtered logs as PDF
function downloadFilteredPDF() {
  const pdf = new jsPDF('p', 'mm', 'a4');
  pdf.setFillColor(95, 18, 19);
  pdf.rect(0, 0, 210, 20, 'F');
  pdf.setFontSize(14);
  pdf.setFont('helvetica', 'bold');
  pdf.setTextColor(255, 255, 255);
  pdf.text('Filtered Parking Logs', 105, 13, { align: 'center' });

  const tableColumns = ['Client Name', 'Plate No.', 'Time In', 'Time Out', 'Hours', 'Amount'];
  const tableRows = filteredReports.value.map((report) => {
    const hoursVal = getHours(report.time_in, report.time_out);
    return [
      report.name || '',
      report.plate || '',
      report.time_in ? new Date(report.time_in).toLocaleString() : '—',
      report.time_out ? new Date(report.time_out).toLocaleString() : '—',
      typeof hoursVal === 'number' ? hoursVal.toFixed(2) : hoursVal,
      `₱${(report.total_amount || 0).toFixed(2)}`
    ];
  });

  autoTable(pdf, {
    startY: 30,
    head: [tableColumns],
    body: tableRows,
    theme: 'grid',
    headStyles: { fillColor: [95, 18, 19], textColor: 255, fontStyle: 'bold', halign: 'center', valign: 'middle' },
    bodyStyles: { valign: 'middle', fontSize: 10 },
    columnStyles: { 0: { halign: 'left' }, 1: { halign: 'left' }, 2: { halign: 'center' }, 3: { halign: 'center' }, 4: { halign: 'right' }, 5: { halign: 'right' } },
    alternateRowStyles: { fillColor: [245, 245, 245] },
    styles: { lineColor: [200, 200, 200], lineWidth: 0.1 }
  });

  const finalY = pdf.lastAutoTable.finalY || 25;
  pdf.setFontSize(9);
  pdf.setFont('helvetica', 'bold');
  pdf.setTextColor(0, 0, 0);
  pdf.text(`Total Sales: ₱${totalSales.value.toFixed(2)}`, 175, finalY + 10, { align: 'right' });

  pdf.save('filtered_logs.pdf');
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P />
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Reports</h1>
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
        <input type="text" v-model="searchQuery" placeholder="Search name or plate..."
          class="bg-white text-black border border-gray-300 px-4 py-2 rounded w-full md:w-64" />
        <select v-model="selectedFilter"
          class="bg-white text-black border border-gray-300 px-4 py-2 rounded w-full md:w-40">
          <option value="all">All</option>
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="annually">Annually</option>
        </select>
        <button @click="downloadFilteredPDF" class="ml-auto bg-[#5F1213] text-white px-4 py-2 rounded">
          Download PDF
        </button>
      </div>

      <div class="overflow-auto rounded-lg shadow">
        <table class="w-full">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="p-3 text-left">#</th>
              <th class="p-3 text-left">Client Name</th>
              <th class="p-3 text-left">Plate No.</th>
              <th class="p-3 text-left">Time In</th>
              <th class="p-3 text-left">Time Out</th>
              <th class="p-3 text-left">Hours</th>
              <th class="p-3 text-left">Amount</th>
              <th class="p-3 text-left">QR</th>
              <th class="p-3 text-left">POS</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(report, index) in paginatedReports" :key="report.id" class="border-b">
              <td class="py-2 px-4">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
              <td class="p-3">{{ report.name }}</td>
              <td class="p-3">{{ report.plate }}</td>
              <td class="p-3">{{ new Date(report.time_in).toLocaleString() }}</td>
              <td class="p-3">{{ new Date(report.time_out).toLocaleString() }}</td>
              <td class="p-3">{{ getHours(report.time_in, report.time_out) }}</td>
              <td class="p-3">₱{{ report.total_amount?.toFixed(2) }}</td>
              <td class="p-3">
                <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${report.plate}`" alt="QR Code"
                  class="w-12 h-12" />
              </td>
              <td class="p-3">
                <button @click="downloadPOS(report)"
                  class="bg-yellow-400 text-white p-2 rounded hover:bg-yellow-500 transition">
                  <i class="fa-solid fa-file-arrow-down"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex justify-center mt-6 gap-2 text-sm">
        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
          class="px-3 py-1 rounded border border-maroon text-maroon transition duration-200"
          :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'">
          &lt;
        </button>
        <button v-for="page in visiblePages" :key="page" @click="changePage(page)"
          class="px-3 py-1 rounded border border-maroon transition duration-200"
          :class="currentPage === page ? 'bg-yellow-400 text-white' : 'text-maroon hover:bg-yellow-200'">
          {{ page }}
        </button>
        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
          class="px-3 py-1 rounded border border-maroon text-maroon transition duration-200"
          :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'">
          &gt;
        </button>
      </div>

      <div class="mt-4 text-right text-xl font-bold text-[#5F1213]">
        Total Sales: ₱{{ totalSales.toFixed(2) }}
      </div>
    </div>
  </div>
</template>

<script>
export default { name: 'admin_parking_reports' }
</script>
