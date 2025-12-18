<template>
  <div v-if="props.isOpen" class="fixed inset-0 bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 overflow-auto">
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

        <!-- Header -->
        <div class="text-center mb-4">
          <h1 class="text-sm">Republic of the Philippines</h1>
          <h2 class="text-base font-bold">University of Southeastern Philippines</h2>
          <h3 class="text-sm italic">Resource Management Division (RMD)</h3>
          <h2 class="text-base font-bold mt-2">ORDER OF PAYMENT</h2>
          <p class="text-sm text-right font-semibold mt-1">Control No.: {{ controlNumber }}</p>
        </div>

        <!-- Basic Info -->
        <div class="text-sm mb-4 space-y-1 border p-3">
          <p><span class="font-semibold">Name:</span> {{ orderData?.customerName || '__________________________' }}</p>
          <p><span class="font-semibold">Organization:</span> ___________________________</p>
          <p><span class="font-semibold">Date Applied:</span> {{ orderData?.orderDate ? formatDate(orderData.orderDate) : currentDate }}</p>
          <p><span class="font-semibold">Validity Period:</span> {{ orderData?.validityDate ? formatDate(orderData.validityDate) : '________________________' }}</p>
        </div>

        <!-- Combined Items Table -->
        <div class="overflow-x-auto">
          <table class="w-full min-w-[600px] text-xs border border-gray-400 mb-4">
            <thead class="bg-gray-100 border-b border-gray-400">
              <tr>
                <th class="px-2 py-1 text-center border-r border-gray-400">Select</th>
                <th class="px-2 py-1 text-left border-r border-gray-400">Item</th>
                <th class="px-2 py-1 text-center">TOTAL COST</th>
              </tr>
            </thead>
            <tbody>
              <!-- FACILITY SECTION -->
              <tr class="bg-gray-50">
                <td colspan="3" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  FACILITY TO USE
                </td>
              </tr>
              <tr
                v-for="item in sections[0].products"
                :key="'facility-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
                </td>
                <td class="px-2 py-1 text-center">
                  {{ (item.qty * item.unitCost).toFixed(2) }}
                </td>
              </tr>

              <!-- PAYMENT SECTION -->
              <tr class="bg-gray-50">
                <td colspan="3" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  ADDITIONAL PAYMENT
                </td>
              </tr>
              <tr
                v-for="item in sections[1].products"
                :key="'payment-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
                </td>
                <td class="px-2 py-1 text-center">
                  {{ (item.qty * item.unitCost).toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Total Cost -->
        <div class="text-sm mb-4 font-semibold flex justify-between border-t border-gray-400 pt-2">
          <span>Total Cost: Php</span>
          <span>{{ grandTotal }}</span>
        </div>

        <!-- Footer -->
        <div class="text-sm space-y-1">
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
          Download POS
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
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const handleClose = () => {
  console.log('Closing modal')
  emit('close')
}

// Date formatting
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

// Function to generate products
function generateProducts(names) {
  return names.map(name => ({
    name,
    qty: 0,
    unitCost: 0,
    selected: false
  }))
}

// Sections for facilities and payments
const sections = reactive([
  {
    title: 'FACILITY TO USE',
    products: generateProducts([
      'Billboard Posting',
      'Classrooms',
      'Commercial Stall',
      'Consultancy',
      'Covered Court',
      'Farmer\'s Training Center',
      'Fitness Center',
      'Gymnasium',
      'Hostel Dining Hall',
      'Hostel Rooms',
      'Hostel Training Hall',
      'Open Space/Ground',
      'Printing Press',
      'Social Hall'
    ])
  },
  {
    title: 'ADDITIONAL PAYMENT',
    products: generateProducts([
      'Excess Hour/s',
      'Overtime Pay of Staff',
      'Use of Generator',
      'Others (specify): __________________'
    ])
  }
])

// Populate order data
const populateOrderData = () => {
  console.log('=== POPULATE ORDER DATA CALLED ===')

  if (typeof props.orderData === 'string') {
    console.error('orderData is a string, expected object')
    alert('Error: Invalid order data received. Please refresh and try again.')
    return
  }

  // Reset all sections
  sections.forEach(section => {
    section.products.forEach(product => {
      product.selected = false
      product.qty = 0
      product.unitCost = 0
    })
  })

  if (props.orderData && props.orderData.items && Array.isArray(props.orderData.items)) {
    console.log(`Found ${props.orderData.items.length} items to process`)
    props.orderData.items.forEach((orderItem, idx) => {
      const itemTitle = orderItem.title || ''
      const itemQty = parseInt(orderItem.qty) || 1
      const itemPrice = parseFloat(orderItem.price) || 0

      console.log(`Item ${idx}: title="${itemTitle}", qty=${itemQty}, price=${itemPrice}`)

      let found = false

      // Check all sections
      sections.forEach(section => {
        if (found) return

        section.products.forEach(product => {
          const productNameLower = product.name.toLowerCase()
          const orderItemLower = itemTitle.toLowerCase()

          if (productNameLower === orderItemLower ||
              (productNameLower !== 'others (specify): __________________' &&
               orderItemLower.includes(productNameLower)) ||
              (orderItemLower.includes(productNameLower) &&
               productNameLower !== 'others (specify): __________________')) {
            product.selected = true
            product.qty = itemQty
            product.unitCost = itemPrice
            found = true
            console.log(`Matched: "${itemTitle}" to "${product.name}" in section "${section.title}"`)
          }
        })
      })

      // If not found, put in "Others" if available
      if (!found) {
        sections.forEach(section => {
          const othersProduct = section.products.find(p => p.name === 'Others (specify): __________________')
          if (othersProduct && !othersProduct.selected) {
            othersProduct.selected = true
            othersProduct.qty = itemQty
            othersProduct.unitCost = itemPrice
            found = true
            console.log(`Put "${itemTitle}" in Others for section "${section.title}"`)
          }
        })
      }

      if (!found) {
        console.log(`No match found for item "${itemTitle}"`)
      }
    })
  } else {
    console.warn('orderData or items not available:', {
      hasOrderData: !!props.orderData,
      hasItems: props.orderData && !!props.orderData.items,
      isArray: props.orderData && Array.isArray(props.orderData.items),
      itemCount: props.orderData && props.orderData.items ? props.orderData.items.length : 0
    })
  }

  console.log('=== POPULATE ORDER DATA FINISHED ===')
}

// Download POS as PDF
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
    let yPos = 15
    const sectionsData = sections
    const isCropPage = false

    // Header
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text('Republic of the Philippines', 105, yPos, { align: 'center' })
    yPos += 5
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text('University of Southeastern Philippines', 105, yPos, { align: 'center' })
    yPos += 5
    doc.setFontSize(10)
    doc.setFont('helvetica', 'italic')
    doc.text('Resource Management Division (RMD)', 105, yPos, { align: 'center' })
    yPos += 7

    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.text('ORDER OF PAYMENT', 105, yPos, { align: 'center' })
    yPos += 7
    doc.setFontSize(9)
    doc.setFont('helvetica', 'normal')
    doc.text('Control No.: ' + (props.orderData?.orderId || '______'), 190, yPos, { align: 'right' })

    yPos += 5
    const boxHeight = 25
    doc.rect(15, yPos, 180, boxHeight)
    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Name: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(props.orderData?.customerName || '__________________________', 30, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Organization: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text('CEDU RENTAL FACILITIES', 40, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Date Applied: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(formatDate(props.orderData?.orderDate), 40, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Validity Period: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(formatDate(props.orderData?.validityDate), 42, yPos)

    yPos += 8

    // Table headers
    doc.setFillColor(240, 240, 240)
    doc.rect(15, yPos, 180, 7, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)

    // Draw vertical lines for header
    doc.line(15, yPos, 15, yPos + 7)
    doc.line(30, yPos, 30, yPos + 7)
    doc.line(115, yPos, 115, yPos + 7)
    doc.line(195, yPos, 195, yPos + 7)

    doc.text('Select', 22.5, yPos + 4.5, { align: 'center' })
    doc.text('Item', 72.5, yPos + 4.5, { align: 'center' })
    doc.text('TOTAL COST', 155, yPos + 4.5, { align: 'center' })

    yPos += 7

    // Draw sections
    sectionsData.forEach((section, sectionIndex) => {
      doc.setFillColor(245, 245, 245)
      doc.rect(15, yPos, 180, 6, 'FD')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(9)
      doc.text(section.title, 18, yPos + 4)

      // Draw vertical lines for section header
      doc.line(15, yPos, 15, yPos + 6)
      doc.line(195, yPos, 195, yPos + 6)

      yPos += 6

      doc.setFont('helvetica', 'normal')
      doc.setFontSize(8)
      section.products.forEach(item => {
        const rowHeight = 6

        // Draw all cell borders
        doc.line(15, yPos, 195, yPos) // Top horizontal line
        doc.line(15, yPos, 15, yPos + rowHeight) // Left vertical
        doc.line(30, yPos, 30, yPos + rowHeight) // After Select
        doc.line(115, yPos, 115, yPos + rowHeight) // After Item
        doc.line(195, yPos, 195, yPos + rowHeight) // Right vertical

        if (item.selected) {
          const checkboxX = 20
          const checkboxY = yPos + 1.5
          const checkboxSize = 3
          doc.rect(checkboxX, checkboxY, checkboxSize, checkboxSize)
          doc.setLineWidth(0.5)
          doc.line(checkboxX + 0.3, checkboxY + 1.5, checkboxX + 1.2, checkboxY + 2.5)
          doc.line(checkboxX + 1.2, checkboxY + 2.5, checkboxX + 2.7, checkboxY + 0.5)
          doc.setLineWidth(0.2)
        }

        let productName = item.name
        doc.text(productName, 33, yPos + 4)
        doc.text((item.qty * item.unitCost).toFixed(2), 155, yPos + 4, { align: 'center' })

        yPos += rowHeight
      })

      // Bottom line for last row
      doc.line(15, yPos, 195, yPos)
    })

    // Grand Total
    yPos += 8
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.text('GRAND TOTAL COST: Php ' + grandTotal.value, 190, yPos, { align: 'right' })

    // Signatures
    yPos += 12
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(9)
    doc.text('Prepared By: _________________________', 18, yPos)
    yPos += 6
    doc.text('Staff: _________________________', 18, yPos)
    yPos += 6
    doc.text('Cashier: _________________________', 18, yPos)
    yPos += 6
    doc.text('OR No.: _________________________', 18, yPos)

    // Save
    const name = props.orderData?.customerName || 'Unknown'
    doc.save(`POS_${name.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Failed to generate PDF. Please try again.')
  }
}

// Grand Total computed
const grandTotal = computed(() => {
  return sections.reduce((total, section) => {
    return total + section.products
      .filter(item => item.selected)
      .reduce((subtotal, item) => subtotal + (item.qty * item.unitCost), 0)
  }, 0).toFixed(2)
})

onMounted(() => {
  console.log('POS Modal mounted, isOpen:', props.isOpen)
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
  name: "useFaci_pos"
}
</script>
