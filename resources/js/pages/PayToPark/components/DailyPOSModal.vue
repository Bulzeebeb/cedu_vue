<template>
  <!-- Debug: Show if modal should be visible -->
  <div v-if="props.isVisible" class="fixed inset-0 bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 overflow-auto">
    <div class="bg-white text-black rounded-xl shadow-lg w-full max-w-4xl my-8 p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4 max-h-[90vh] overflow-y-auto">

      <!-- Header Title and Close -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">GENERATED POS</h2>
        <button @click="handleClose" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500 transition">
          ✕
        </button>
      </div>

      <!-- POS FORM CONTENT START -->
      <div class="pos-form border border-gray-400 rounded-lg p-3 sm:p-4 md:p-6 shadow-sm">

        <!-- Logo and Title -->
        <div class="flex flex-col sm:flex-row items-center sm:items-stretch mb-6 gap-4">
          <div class="w-full sm:w-1/5 flex items-center justify-center">
            <img src="/images/PayToPark/logo.png" alt="Logo" class="h-full max-h-[120px] object-contain" />
          </div>
          <div class="w-full sm:w-4/5 text-center px-4">
            <h1 class="text-sm">Republic of the Philippines</h1>
            <h2 class="text-base font-bold">University of Southeastern Philippines</h2>
            <h3 class="text-sm italic">Resource Management Division (RMD)</h3><br />
            <h2 class="text-xl font-bold tracking-wide mt-1">ORDER OF PAYMENT</h2>
          </div>
        </div>

        <!-- Info -->
        <div class="border border-gray-300 rounded-md p-4 text-sm mb-6 space-y-2 shadow-sm">
          <div><span class="font-semibold">Control No.:</span> {{ controlNumber }}</div>
          <div><span class="font-semibold">Name:</span> {{ orderData?.customerName || 'Loading...' }}</div>
          <div><span class="font-semibold">Organization:</span> ___________________________</div>
          <div><span class="font-semibold">Date Applied:</span> {{ currentDate }}</div>
          <div><span class="font-semibold">Validity Period:</span> ________________________</div>
        </div>

        <!-- Facility to Use -->
        <div class="text-sm mb-6">
          <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
            <span>Facility</span>
            <span>Cost</span>
          </div>
          <div v-for="(facility, index) in facilities" :key="index" class="flex justify-between">
            <span>☐ {{ facility.name }}</span>
            <span>_____________</span>
          </div>
          <div class="flex justify-between mt-2">
            <span>☑ Others (specify): PAY TO PARK</span>
            <span>{{ grandTotal }}</span>
          </div>
        </div>

        <!-- Additional Payment -->
        <div class="text-sm mb-6">
          <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
            <span>Payment</span>
            <span>Cost</span>
          </div>
          <div v-for="(payment, index) in payments" :key="index" class="flex justify-between">
            <span>{{ payment.selected ? '☑' : '☐' }} {{ payment.name }}</span>
            <span>{{ payment.selected ? payment.cost.toLocaleString() : '_____________' }}</span>
          </div>
        </div>

        <!-- Total -->
        <div class="text-sm mb-6 flex justify-between font-semibold">
          <span>Total Cost: Php</span>
          <span>{{ grandTotal }}</span>
        </div>

        <!-- Footer Signature -->
        <div class="text-sm mt-6 space-y-2">
          <p>Prepared By: _________________________</p>
          <p>Staff: _________________________</p>
          <p>Cashier: _________________________</p>
          <p>OR No.: _________________________</p>
        </div>
      </div>

      <!-- Buttons -->
      <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
        <button
          @click="downloadPOS"
          class="w-full sm:w-auto bg-maroon text-white py-2 px-6 rounded-full hover:bg-red-800 font-medium text-sm sm:text-base transition"
        >
          Download
        </button>
        <button
          @click="handleClose"
          class="w-full sm:w-auto bg-gray-600 text-white py-2 px-6 rounded-full hover:bg-gray-700 font-medium text-sm sm:text-base transition"
        >
          Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  orderData: {
    type: Object,
    default: () => ({})
  },
  isVisible: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

// Remove the local isOpen ref - use prop directly

const handleClose = () => {
  console.log('Closing modal')
  emit('close')
}

const closeModal = () => {
  handleClose()
}

const goToLanding = () => {
  handleClose()
  setTimeout(() => {
    router.visit('/om-landing')
  }, 300)
}

// Add this new function to help debug
const logOrderData = () => {
  console.log('Current orderData:', JSON.stringify(props.orderData, null, 2))
  console.log('Sections:', JSON.stringify(sections, null, 2))
}

// Date formatting function
const formatDate = (date) => {
  if (!date) return ''
  try {
    const d = new Date(date)
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
  } catch (e) {
    return ''
  }
}

// Current date for Date Applied
const currentDate = new Date().toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
})

// Control Number computed
const controlNumber = computed(() => {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const paddedId = String(props.orderData?.orderId || 0).padStart(3, '0')
  return `${year}-${month}-000${paddedId}`
})

// Download as PDF function using jsPDF
const downloadPOS = async () => {
  try {
    if (!window.jspdf) {
      await new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'
        script.onload = resolve
        script.onerror = reject
        document.head.appendChild(script)
      })
    }

    const { jsPDF } = window.jspdf

    const doc = new jsPDF('p', 'mm', 'a4')
    const marginLeft = 14
    let y = 15

    const name = props.orderData?.customerName || 'Unknown'
    const dateToday = currentDate
    const controlNo = controlNumber.value || '2025-08-000063'
    const totalCost = grandTotal.value

    // === HEADER ===
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text('Republic of the Philippines', 105, y, { align: 'center' })
    y += 5
    doc.setFont('helvetica', 'bold')
    doc.text('University of Southeastern Philippines', 105, y, { align: 'center' })
    y += 5
    doc.setFont('helvetica', 'italic')
    doc.text('Resource Management Division (RMD)', 105, y, { align: 'center' })
    y += 6

    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.text('ORDER OF PAYMENT', 105, y, { align: 'center' })
    y += 10

    // === INFO BOX ===
    doc.setFontSize(9)
    doc.setFont('helvetica', 'normal')
    doc.rect(marginLeft, y, 182, 32)

    doc.text(`Control No.: ${controlNo}`, marginLeft + 2, y + 6)
    doc.text(`Name: ${name}`, marginLeft + 2, y + 12)
    doc.text(`Organization: ____________________________`, marginLeft + 2, y + 18)
    doc.text(`Date Applied: ${dateToday}`, marginLeft + 2, y + 24)
    doc.text(`Validity Period: ____________________________`, marginLeft + 2, y + 30)
    y += 38

    // === FACILITY TABLE ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.text('FACILITY', marginLeft, y)
    y += 4

    const facilityRows = facilities.map(item => ({
      select: item.selected ? '☑' : '☐',
      facility: item.name,
      cost: item.selected ? item.cost.toFixed(2) : ''
    }))

    // Import autoTable if not already imported
    if (!window.jspdfAutoTable) {
      await new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js'
        script.onload = resolve
        script.onerror = reject
        document.head.appendChild(script)
      })
    }

    doc.autoTable({
      startY: y,
      head: [['', 'Facility', 'Cost']],
      body: facilityRows.map(row => [row.select, row.facility, row.cost]),
      theme: 'grid',
      headStyles: {
        fillColor: [200, 200, 200],
        textColor: 20,
        fontSize: 9,
        fontStyle: 'bold',
        halign: 'center'
      },
      bodyStyles: {
        fontSize: 9,
        valign: 'middle'
      },
      columnStyles: {
        0: { cellWidth: 10, halign: 'center' },
        1: { cellWidth: 120 },
        2: { cellWidth: 52, halign: 'right' }
      }
    })

    y = doc.lastAutoTable.finalY + 6

    // === PAYMENT TABLE ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.text('PAYMENT', marginLeft, y)
    y += 4

    const paymentRows = payments.map(item => ({
      select: item.selected ? '☑' : '☐',
      description: item.name,
      cost: item.selected ? item.cost.toFixed(2) : ''
    }))

    doc.autoTable({
      startY: y,
      head: [['', 'Description', 'Cost']],
      body: paymentRows.map(row => [row.select, row.description, row.cost]),
      theme: 'grid',
      headStyles: {
        fillColor: [200, 200, 200],
        textColor: 20,
        fontSize: 9,
        fontStyle: 'bold',
        halign: 'center'
      },
      bodyStyles: {
        fontSize: 9,
        valign: 'middle'
      },
      columnStyles: {
        0: { cellWidth: 10, halign: 'center' },
        1: { cellWidth: 120 },
        2: { cellWidth: 52, halign: 'right' }
      }
    })

    y = doc.lastAutoTable.finalY + 6

    // === TOTAL COST ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.text(`Total Cost: Php`, 135, y)
    doc.text(totalCost, 195, y, { align: 'right' })
    y += 18

    // === SIGNATURE SECTION ===
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(10)
    doc.text('By: Staff', marginLeft, y)
    doc.text('Noted by: CEDU Head / RMD Director', 130, y)
    y += 6
    doc.text('_____________________', marginLeft, y)
    doc.text('_____________________', 130, y)

    // === DOWNLOAD PDF ===
    doc.save(`POS_${name.replace(/\s+/g, '_')}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Failed to generate PDF. Please try again.')
  }
}



const facilities = reactive([
  { name: 'Billboard Posting', selected: false, cost: 0 },
  { name: 'Classrooms', selected: false, cost: 0 },
  { name: 'Commercial Stall', selected: false, cost: 0 },
  { name: 'Consultancy', selected: false, cost: 0 },
  { name: 'Covered Court', selected: false, cost: 0 },
  { name: 'Farmer\'s Training Center', selected: false, cost: 0 },
  { name: 'Fitness Center', selected: false, cost: 0 },
  { name: 'Gymnasium', selected: false, cost: 0 },
  { name: 'Hostel Dining Hall', selected: false, cost: 0 },
  { name: 'Hostel Rooms', selected: false, cost: 0 },
  { name: 'Hostel Training Hall', selected: false, cost: 0 },
  { name: 'Open Space/Ground', selected: false, cost: 0 },
  { name: 'Printing Press', selected: false, cost: 0 },
  { name: 'Social Hall', selected: false, cost: 0 }
])

const payments = reactive([
  { name: 'Excess Hour/s', selected: false, cost: 0 },
  { name: 'Overtime Pay of Staff', selected: false, cost: 0 },
  { name: 'Use of Generator', selected: false, cost: 0 },
  { name: 'Others (specify): __________________', selected: false, cost: 0 }
])

const populateOrderData = () => {
  // Validate orderData is an object, not HTML string
  if (typeof props.orderData === 'string') {
    console.error('orderData is a string, expected object. Possible HTML response:', props.orderData)
    alert('Error: Invalid order data received. Please refresh and try again.')
    return
  }

  // Reset facilities and payments
  facilities.forEach(facility => {
    facility.selected = false
    facility.cost = 0
  })
  payments.forEach(payment => {
    payment.selected = false
    payment.cost = 0
  })

  if (props.orderData && props.orderData.items && Array.isArray(props.orderData.items)) {
    props.orderData.items.forEach(orderItem => {
      const itemTitle = orderItem.title.toLowerCase()
      let found = false

      // Check facilities
      facilities.forEach(facility => {
        if (facility.name.toLowerCase().includes(itemTitle) || itemTitle.includes(facility.name.toLowerCase())) {
          facility.selected = true
          facility.cost = parseFloat(orderItem.price) || 0
          found = true
        }
      })

      // Check payments
      payments.forEach(payment => {
        if (payment.name.toLowerCase().includes(itemTitle) || itemTitle.includes(payment.name.toLowerCase())) {
          payment.selected = true
          payment.cost = parseFloat(orderItem.price) || 0
          found = true
        }
      })

      // If not found, assign to "Others" in facilities
      if (!found) {
        const othersFacility = facilities.find(f => f.name.includes('Others'))
        if (othersFacility) {
          othersFacility.selected = true
          othersFacility.cost = parseFloat(orderItem.price) || 0
        }
      }
    })
  }
}

onMounted(() => {
  console.log('POS Modal mounted, isVisible:', props.isVisible)
  populateOrderData()
})

watch(() => props.orderData, () => {
  console.log('OrderData changed:', props.orderData)
  if (typeof props.orderData === 'object' && props.orderData !== null) {
    populateOrderData()
  } else {
    console.error('Invalid orderData type:', typeof props.orderData)
  }
}, { deep: true })

const grandTotal = computed(() => {
  const facilityTotal = facilities
    .filter(facility => facility.selected)
    .reduce((total, facility) => total + facility.cost, 0)
  const paymentTotal = payments
    .filter(payment => payment.selected)
    .reduce((total, payment) => total + payment.cost, 0)
  return (facilityTotal + paymentTotal).toFixed(2)
})
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
}

.bg-maroon:hover {
  background-color: #a30000;
}

div {
  color: black;
}

@media (max-width: 640px) {
  h2.text-xl {
    font-size: 1.125rem;
  }

  .text-sm {
    font-size: 0.875rem;
  }

  .text-lg {
    font-size: 1rem;
  }
}
</style>

<script>
export default {
  name: "onlineMart_pos"
}
</script>
