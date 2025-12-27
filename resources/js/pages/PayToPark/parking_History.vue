<template>
  <div class="bg-gray-50 text-gray-900 min-h-screen font-sans">
    <SiteHeader />

    <!-- Parking History Section -->
    <section class="px-4 py-6 sm:px-8">
      <h2 class="text-2xl font-bold text-yellow-600 mb-6 border-l-4 border-maroon pl-3">
        Parking History
      </h2>

      <div class="bg-white rounded-xl shadow-xl p-6">
        <!-- Search + Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-start gap-2 mb-4">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name or plate"
            class="px-3 py-2 rounded border border-gray-300 text-sm w-full sm:w-64"
          />
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-maroon text-white text-left">
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Name</th>
                <th class="py-3 px-4">Plate</th>
                <th class="py-3 px-4">Time In</th>
                <th class="py-3 px-4">Time Out</th>
                <th class="py-3 px-4">Total Hours</th>
                <th class="py-3 px-4">Amount</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">QR Code</th>
                <th class="py-3 px-4 text-center">POS</th>
              </tr>
            </thead>
            <tbody class="text-gray-800">
              <tr
                v-for="(entry, index) in paginatedEntries"
                :key="entry.id"
                class="border-t hover:bg-yellow-50 even:bg-gray-50 transition"
              >
                <td class="py-2 px-4">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="py-2 px-4">{{ entry.name }}</td>
                <td class="py-2 px-4">{{ entry.plate }}</td>
                <td class="py-2 px-4">{{ formatTime(entry.time_in) }}</td>
                <td class="py-2 px-4">{{ formatTime(entry.time_out) }}</td>
                <td class="py-2 px-4">{{ computeHours(entry.time_in, entry.time_out) }}</td>
                <td class="py-2 px-4">{{ entry.total_amount || '—' }}</td>
                <td class="py-2 px-4">
                  <span
                    :class="entry.status === 'Paid' ? 'text-green-600 font-semibold' : 'text-yellow-600 font-semibold'"
                  >
                    {{ entry.status }}
                  </span>
                </td>
                <td class="py-2 px-4">
                  <img
                    :src="`https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${entry.plate}`"
                    alt="QR Code"
                    class="w-12 h-12"
                  />
                </td>
                <td class="py-2 px-4 text-center">
                  <button
                    @click="downloadPOS(entry)"
                    title="Download POS"
                    class="bg-yellow-400 text-white p-2 rounded hover:bg-yellow-500 transition"
                  >
                    <i class="fa-solid fa-file-arrow-down"></i>
                  </button>
                  <button
                      @click="openEditModal(client.id)"
                      title="Edit"
                      class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                </td>
              </tr> 

              <tr v-for="n in emptyRowCount" :key="'blank-' + n" class="border-t even:bg-gray-50">
                <td colspan="10" class="py-2 px-4 text-center text-gray-300">—</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center mt-6 gap-2 text-sm">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-3 py-1 rounded border border-maroon text-maroon transition duration-200"
          :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'"
        >
          &lt;
        </button>
        <button
          v-for="page in visiblePages"
          :key="page"
          @click="changePage(page)"
          class="px-3 py-1 rounded border border-maroon transition duration-200"
          :class="currentPage === page
            ? 'bg-yellow-400 text-white'
            : 'text-maroon hover:bg-yellow-200'"
        >
          {{ page }}
        </button>
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 rounded border border-maroon text-maroon transition duration-200"
          :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-yellow-400 hover:text-white'"
        >
          &gt;
        </button>
      </div>
    </section>

    <SiteFooter />
  </div>
  <ParkingHistory :entries="parkingHistoryData" />

</template>

<script setup>
import { ref, computed, toRef } from 'vue'
import jsPDF from 'jspdf'
import SiteHeader from './staffHeader.vue'
import SiteFooter from '../Footer.vue'

const props = defineProps({
  entries: {
    type: Array,
    default: () => []
  }
})

const entries = toRef(props, 'entries')  // make entries reactive

const currentPage = ref(1)
const itemsPerPage = ref(5)
const searchQuery = ref('')

// Filter entries based on search query (name or plate)
const history = computed(() => {
  const term = searchQuery.value.toLowerCase().trim()
  if (!term) return entries.value

  return entries.value.filter(entry =>
    entry.name?.toLowerCase().includes(term) ||
    entry.plate?.toLowerCase().includes(term)
  )
})

// Paginate the filtered results
const paginatedEntries = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return history.value.slice(start, start + itemsPerPage.value)
})

// Calculate how many empty rows to add to maintain table height
const emptyRowCount = computed(() => {
  return itemsPerPage.value - paginatedEntries.value.length
})

const totalPages = computed(() => Math.ceil(history.value.length / itemsPerPage.value))

const visiblePages = computed(() =>
  Array.from({ length: totalPages.value }, (_, i) => i + 1)
)

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

function formatTime(time) {
  return time ? new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '—'
}

function computeHours(start, end) {
  if (!start || !end) return '—'
  const s = new Date(start)
  const e = new Date(end)
  const diff = (e - s) / (1000 * 60 * 60)
  return diff.toFixed(1)
}

function downloadPOS(entry) {
  const pdf = new jsPDF()
  pdf.setFontSize(12)
  pdf.setFont('helvetica', 'bold')
  pdf.text('Republic of the Philippines', 105, 20, { align: 'center' })
  pdf.text('University of Southeastern Philippines', 105, 28, { align: 'center' })
  pdf.text('ORDER PAYMENT SLIP (OPS/POS)', 105, 36, { align: 'center' })
  pdf.setLineWidth(0.5)
  pdf.line(20, 40, 190, 40)

  pdf.setFont('helvetica', 'normal')
  pdf.setFontSize(11)
  pdf.text(`Payer Name: ${entry.name}`, 20, 50)
  pdf.text(`Plate No.: ${entry.plate}`, 20, 58)
  pdf.text(`Time In: ${formatTime(entry.time_in)}`, 20, 66)
  pdf.text(`Time Out: ${formatTime(entry.time_out)}`, 20, 74)
  pdf.text(`Total Hours: ${computeHours(entry.time_in, entry.time_out)}`, 20, 82)
  pdf.text(`Total Amount: ${entry.total_amount || '—'}`, 20, 90)

  pdf.setFont('helvetica', 'bold')
  pdf.text(`Prepared by: _________________________`, 20, 110)
  pdf.text('Staff: ________________________', 20, 120)
  pdf.text('Cashier: ________________________', 20, 130)

  pdf.save(`${entry.plate}_POS.pdf`)
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
