<template>
  <div v-if="props.isVisible" class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center">
    <div
      class="bg-white text-black rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4"
    >
      <!-- Header -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-inherit" style="color: #d97706 !important">GENERATED POS</h2>
        <button
          @click="close"
          class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500"
          aria-label="Close modal"
        >
          ×
        </button>
      </div>

      <div v-if="!loading">
        <div ref="printArea" class="print-area">
          <!-- Logo and Title Section -->
          <div class="flex flex-col sm:flex-row items-center sm:items-stretch mb-6 gap-4">
            <div class="w-full sm:w-1/5 flex items-center justify-center">
              <img src="/images/PayToPark/logo.png" alt="Logo" class="h-full max-h-[120px] object-contain" />
            </div>
            <div class="w-full sm:w-4/5 text-center px-4">
              <h1 class="text-base">Republic of the Philippines</h1>
              <h2 class="text-xl font-bold">University of Southeastern Philippines</h2>
              <h3 class="text-sm italic">Resource Management Division (RMD)</h3><br />
              <h2 class="text-2xl font-bold tracking-wide mt-1">ORDER OF PAYMENT</h2>
            </div>
          </div>

          <!-- Payment Info -->
          <section class="border border-gray-300 rounded-md p-4 text-sm mb-6 space-y-2 shadow-sm">
            <div class="text-right">
              <span class="text-red-600 font-semibold">Control no.:</span>
              <span class="border-b border-black inline-block w-24 ml-2">{{ controlNumber }}</span>
            </div>

            <div>
  <span class="font-semibold">Name:</span>
  {{ clientData.name }}
  <span v-if="transactions.length > 1"> et al.</span>
</div>


            <div><span class="font-semibold">Organization:</span> ___________________________</div>
            <div><span class="font-semibold">Date Applied:</span> {{ currentDate }}</div>
            <div><span class="font-semibold">Validity Period:</span> ________________________</div>
          </section>

          <!-- Facility to Use -->
          <section class="text-sm mb-6">
            <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
              <span>Facility</span>
              <span>Cost</span>
            </div>
            <div v-for="(facility, index) in facilities" :key="index" class="flex justify-between">
              <span>☐ {{ facility }}</span>
              <span>_____________</span>
            </div>
            <div class="flex justify-between mt-2 font-semibold">
              <span>☑ Others (specify): PAY TO PARK</span>
              <span>{{ totalAmount.toLocaleString() }}</span>
            </div>
          </section>

          <!-- Additional Payment -->
          <section class="text-sm mb-6">
            <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
              <span>Payment</span>
              <span>Cost</span>
            </div>
            <div class="flex justify-between"><span>☐ Excess Hour/s</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Overtime Pay of Staff</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Use of Generator</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Others (specify): __________________</span><span>_____________</span></div>
          </section>

          <!-- Total Cost -->
          <div class="text-sm mb-6 flex justify-between font-semibold">
            <span>Total Cost: Php</span>
            <span>{{ totalAmount.toLocaleString() }}</span>
          </div>

          <!-- Signature Section -->
          <section class="flex flex-col sm:flex-row justify-between mt-8 text-sm gap-8">
            <div class="text-center">
              <span class="block mb-6">By: Staff</span>
              <div class="border-t border-gray-400 w-40 mx-auto"></div>
            </div>
            <div class="text-center">
              <span class="block mb-6">Noted by: CEDU Head / RMD Director</span>
              <div class="border-t border-gray-400 w-60 mx-auto"></div>
            </div>
          </section>
        </div>
      </div>

      <!-- Download Button -->
      <div class="mt-10 flex justify-center gap-4">
        <button
          @click="downloadPOS"
          :disabled="isDownloading"
          class="w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
          type="button"
        >
          {{ isDownloading ? 'Downloading...' : 'Download POS' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const props = defineProps({ isVisible: Boolean })
const emit = defineEmits(['close'])

const printArea = ref(null)
const loading = ref(true)
const clientData = ref({ name: '' })
const totalAmount = ref(0)
const paidClients = ref([])
const transactions = ref([])
const isDownloading = ref(false)

const currentDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })

const controlNumber = computed(() => {
  if (transactions.value.length === 0) return 'N/A'
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const paddedId = String(transactions.value[0]?.id).padStart(3, '0')
  return `${year}-${month}-000${paddedId}`
})

const facilities = [
  'Billboard Posting', 'Classrooms', 'Commercial Stall', 'Consultancy', 'Covered Court',
  'Farmer’s Training Center', 'Fitness Center', 'Gymnasium', 'Hostel Dining Hall', 'Hostel Rooms',
  'Hostel Training Hall', 'Open Space/Ground', 'Printing Press', 'Social Hall'
]
onMounted(async () => {
  try {
    const res = await axios.get('/paypark_transactions/today-with-clients')
    
    // Use the backend's values directly
    clientData.value.name = res.data.first_client || 'Loading...'
    paidClients.value = res.data.transactions.map(tx => tx.client_id) // just IDs if you need them
    totalAmount.value = res.data.total_payment_sum
    transactions.value = res.data.transactions
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
})

const close = () => emit('close')

const downloadPOS = async () => {
  if (!clientData.value) return
  isDownloading.value = true
  try {
    const pdf = new jsPDF('p', 'mm', 'a4')
    const marginLeft = 14
    let y = 15

    pdf.setFont('helvetica', 'normal').setFontSize(10)
    pdf.text('Republic of the Philippines', 105, y, { align: 'center' })
    y += 5
    pdf.setFont('helvetica', 'bold').text('University of Southeastern Philippines', 105, y, { align: 'center' })
    y += 5
    pdf.setFont('helvetica', 'italic').text('Resource Management Division (RMD)', 105, y, { align: 'center' })
    y += 6
    pdf.setFont('helvetica', 'bold').setFontSize(12).text('ORDER OF PAYMENT', 105, y, { align: 'center' })
    y += 10

    pdf.setFont('helvetica', 'normal').setFontSize(9)
    pdf.rect(marginLeft, y, 182, 32)
    pdf.text(`Control No.: ${controlNumber.value}`, marginLeft + 2, y + 6)
    pdf.text(`Name: ${clientData.value.name}${paidClients.value.length > 1 ? ' et al.' : ''}`, marginLeft + 2, y + 12)
    pdf.text('Organization: ____________________________', marginLeft + 2, y + 18)
    pdf.text(`Date Applied: ${currentDate}`, marginLeft + 2, y + 24)
    pdf.text('Validity Period: ____________________________', marginLeft + 2, y + 30)
    y += 38

    const facilityRows = facilities.map(item => ['☐', item, ''])
    facilityRows.push(['☑', 'Others (specify): PAY TO PARK', totalAmount.value.toFixed(2)])
    autoTable(pdf, {
      startY: y,
      head: [['', 'Facility', 'Cost']],
      body: facilityRows,
      theme: 'grid',
      headStyles: { fillColor: [200, 200, 200], fontSize: 9, halign: 'center' },
      bodyStyles: { fontSize: 9 },
      columnStyles: { 0: { cellWidth: 10, halign: 'center' }, 1: { cellWidth: 120 }, 2: { cellWidth: 52, halign: 'right' } }
    })
    y = pdf.lastAutoTable.finalY + 6

    const paymentRows = [
      ['☐', 'Excess Hour/s', ''],
      ['☐', 'Overtime Pay of Staff', ''],
      ['☐', 'Use of Generator', ''],
      ['☐', 'Others (specify): ______________________', '']
    ]
    autoTable(pdf, {
      startY: y,
      head: [['', 'Payment', 'Cost']],
      body: paymentRows,
      theme: 'grid',
      headStyles: { fillColor: [200, 200, 200], fontSize: 9, halign: 'center' },
      bodyStyles: { fontSize: 9 },
      columnStyles: { 0: { cellWidth: 10, halign: 'center' }, 1: { cellWidth: 120 }, 2: { cellWidth: 52, halign: 'right' } }
    })
    y = pdf.lastAutoTable.finalY + 6

    pdf.setFont('helvetica', 'bold').setFontSize(10)
    pdf.text('Total Cost: Php', 135, y)
    pdf.text(totalAmount.value.toFixed(2), 195, y, { align: 'right' })
    y += 18

    pdf.setFont('helvetica', 'normal').setFontSize(10)
    pdf.text('By: Staff', marginLeft, y)
    pdf.text('Noted by: CEDU Head / RMD Director', 130, y)
    y += 6
    pdf.text('_____________________', marginLeft, y)
    pdf.text('_____________________', 130, y)

    pdf.save(`POS_${controlNumber.value}.pdf`)
  } catch (err) {
    console.error('Error generating PDF:', err)
  } finally {
    isDownloading.value = false
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
}
.bg-maroon:hover {
  background-color: #a30000;
}
</style>
