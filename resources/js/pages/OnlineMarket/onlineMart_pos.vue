<template>
  <!-- Debug: Show if modal should be visible -->
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
          <h2 class="text-base font-bold mt-2">ORDER PAYMENT SLIP (OPS/POS)</h2>
          <p class="text-sm text-right font-semibold mt-1">Control No.: {{ orderData?.orderId || '______' }}</p>
        </div>

        <!-- Basic Info -->
        <div class="text-sm mb-4 space-y-1 border p-3">
          <p><span class="font-semibold">Payor/Name:</span> {{ orderData?.customerName || '__________________________' }}</p>
          <p><span class="font-semibold">Organization:</span> CEDU CROP PRODUCTION PROJECT</p>
          <p><span class="font-semibold">Date:</span> {{ formatDate(orderData?.orderDate) || '__________________________' }}</p>
          <p>OTHER BUSINESS INCOME</p>
        </div>

        <!-- Combined Products Table -->
        <div class="overflow-x-auto">
          <table class="w-full min-w-[600px] text-xs border border-gray-400 mb-4">
            <thead class="bg-gray-100 border-b border-gray-400">
              <tr>
                <th class="px-2 py-1 text-center border-r border-gray-400">Select</th>
                <th class="px-2 py-1 text-left border-r border-gray-400">Product</th>
                <th class="px-2 py-1 text-center border-r border-gray-400">QTY</th>
                <th class="px-2 py-1 text-center border-r border-gray-400">UNIT COST</th>
                <th class="px-2 py-1 text-center">TOTAL COST</th>
              </tr>
            </thead>
            <tbody>
              <!-- CEDU TAGUM Section -->
              <tr class="bg-gray-50">
                <td colspan="5" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  CEDU TAGUM – CROP PRODUCTION
                </td>
              </tr>
              <tr
                v-for="item in sections[0].products"
                :key="'tagum-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
                  <template v-if="item.selected && item.name === 'Banana' && item.bananaType">
                    {{ item.bananaType }}
                  </template>
                  <template v-if="item.selected && item.name === 'Others:' && item.otherDetails">
                    {{ item.otherDetails }}
                  </template>
                </td>
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  {{ item.qty || 0 }}
                </td>
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  {{ Number(item.unitCost || 0).toFixed(2) }}
                </td>
                <td class="px-2 py-1 text-center">
                  {{ (item.qty * item.unitCost).toFixed(2) }}
                </td>
              </tr>

              <!-- CEDU MABINI Section -->
              <tr class="bg-gray-50">
                <td colspan="5" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  CEDU MABINI – CROP PRODUCTION
                </td>
              </tr>
              <tr
                v-for="item in sections[1].products"
                :key="'mabini-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
                  <template v-if="item.selected && item.name === 'Banana' && item.bananaType">
                    {{ item.bananaType }}
                  </template>
                  <template v-if="item.selected && item.name === 'Others:' && item.otherDetails">
                    {{ item.otherDetails }}
                  </template>
                </td>
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  {{ item.qty || 0 }}
                </td>
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  {{ Number(item.unitCost || 0).toFixed(2) }}
                </td>
                <td class="px-2 py-1 text-center">
                  {{ (item.qty * item.unitCost).toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Grand Total -->
        <div class="text-right font-semibold text-base mt-3 mb-4">
          GRAND TOTAL COST: Php {{ grandTotal }}
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
          Download POS
        </button>
        <button
          @click="handleClose"
          class="w-full sm:w-auto bg-gray-600 text-white py-2 px-6 rounded-full hover:bg-gray-700 font-medium text-sm sm:text-base transition"
        >
          Back to Market
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

    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4'
    })

    doc.setFont('helvetica')

    let yPos = 15
    doc.setFontSize(10)
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
    doc.text('ORDER PAYMENT SLIP (OPS/POS)', 105, yPos, { align: 'center' })

    yPos += 7
    doc.setFontSize(9)
    doc.setFont('helvetica', 'normal')
    doc.text('Control No.: ' + (props.orderData?.orderId || '______'), 190, yPos, { align: 'right' })

    yPos += 5
    const boxHeight = 25
    doc.rect(15, yPos, 180, boxHeight)
    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Payor/Name: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(props.orderData?.customerName || '__________________________', 42, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Organization: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text('CEDU CROP PRODUCTION PROJECT', 45, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Date: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(formatDate(props.orderData?.orderDate) || '__________________________', 30, yPos)

    yPos += 6
    doc.setFont('helvetica', 'normal')
    doc.text('OTHER BUSINESS INCOME', 18, yPos)

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
    doc.line(135, yPos, 135, yPos + 7)
    doc.line(165, yPos, 165, yPos + 7)
    doc.line(195, yPos, 195, yPos + 7)

    doc.text('Select', 22.5, yPos + 4.5, { align: 'center' })
    doc.text('Product', 72.5, yPos + 4.5, { align: 'center' })
    doc.text('QTY', 125, yPos + 4.5, { align: 'center' })
    doc.text('UNIT COST', 150, yPos + 4.5, { align: 'center' })
    doc.text('TOTAL COST', 180, yPos + 4.5, { align: 'center' })

    yPos += 7

    // CEDU TAGUM Section
    doc.setFillColor(245, 245, 245)
    doc.rect(15, yPos, 180, 6, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text('CEDU TAGUM - CROP PRODUCTION', 18, yPos + 4)

    // Draw vertical lines for section header
    doc.line(15, yPos, 15, yPos + 6)
    doc.line(195, yPos, 195, yPos + 6)

    yPos += 6

    doc.setFont('helvetica', 'normal')
    doc.setFontSize(8)
    sections[0].products.forEach(item => {
      const rowHeight = 6

      // Draw all cell borders
      doc.line(15, yPos, 195, yPos) // Top horizontal line
      doc.line(15, yPos, 15, yPos + rowHeight) // Left vertical
      doc.line(30, yPos, 30, yPos + rowHeight) // After Select
      doc.line(115, yPos, 115, yPos + rowHeight) // After Product
      doc.line(135, yPos, 135, yPos + rowHeight) // After QTY
      doc.line(165, yPos, 165, yPos + rowHeight) // After Unit Cost
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
      if (item.bananaType) productName += ' ' + item.bananaType
      if (item.otherDetails) productName += ' ' + item.otherDetails
      
      doc.text(productName, 33, yPos + 4)
      doc.text(String(item.qty || 0), 125, yPos + 4, { align: 'center' })
      doc.text(Number(item.unitCost || 0).toFixed(2), 150, yPos + 4, { align: 'center' })
      doc.text((item.qty * item.unitCost).toFixed(2), 180, yPos + 4, { align: 'center' })

      yPos += rowHeight
    })

    // Bottom line for last row of TAGUM
    doc.line(15, yPos, 195, yPos)

    // CEDU MABINI Section
    doc.setFillColor(245, 245, 245)
    doc.rect(15, yPos, 180, 6, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text('CEDU MABINI - CROP PRODUCTION', 18, yPos + 4)

    // Draw vertical lines for section header
    doc.line(15, yPos, 15, yPos + 6)
    doc.line(195, yPos, 195, yPos + 6)

    yPos += 6

    doc.setFont('helvetica', 'normal')
    doc.setFontSize(8)
    sections[1].products.forEach(item => {
      const rowHeight = 6

      // Draw all cell borders
      doc.line(15, yPos, 195, yPos) // Top horizontal line
      doc.line(15, yPos, 15, yPos + rowHeight) // Left vertical
      doc.line(30, yPos, 30, yPos + rowHeight) // After Select
      doc.line(115, yPos, 115, yPos + rowHeight) // After Product
      doc.line(135, yPos, 135, yPos + rowHeight) // After QTY
      doc.line(165, yPos, 165, yPos + rowHeight) // After Unit Cost
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
      if (item.bananaType) productName += ' ' + item.bananaType
      if (item.otherDetails) productName += ' ' + item.otherDetails
      
      doc.text(productName, 33, yPos + 4)
      doc.text(String(item.qty || 0), 125, yPos + 4, { align: 'center' })
      doc.text(Number(item.unitCost || 0).toFixed(2), 150, yPos + 4, { align: 'center' })
      doc.text((item.qty * item.unitCost).toFixed(2), 180, yPos + 4, { align: 'center' })

      yPos += rowHeight
    })

    // Bottom line for last row of MABINI
    doc.line(15, yPos, 195, yPos)

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

    doc.save(`POS_${props.orderData?.orderId || 'Order'}_${new Date().toISOString().split('T')[0]}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Failed to generate PDF. Please try again.')
  }
}

// Product mapping function
function getProductSection(productName) {
  const tagumProducts = ['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra']
  const mabiniProducts = ['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo']

  const normalizedName = productName.toLowerCase()
  const matchesTagum = tagumProducts.some(product => normalizedName.includes(product.toLowerCase()))
  const matchesMabini = mabiniProducts.some(product => normalizedName.includes(product.toLowerCase()))

  if (matchesTagum) return 'tagum'
  if (matchesMabini) return 'mabini'
  return 'tagum'
}

function generateProducts(names) {
  return names.map(name => ({
    name,
    qty: 0,
    unitCost: 0,
    selected: false,
    bananaType: name === 'Banana' ? '' : undefined,
    otherDetails: name === 'Others:' ? '' : undefined
  }))
}

const sections = reactive([
  {
    title: 'CEDU TAGUM – CROP PRODUCTION',
    products: generateProducts(['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra', 'Others:'])
  },
  {
    title: 'CEDU MABINI – CROP PRODUCTION',
    products: generateProducts(['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo', 'Others:'])
  }
])

const populateOrderData = () => {
  // Validate orderData is an object, not HTML string
  if (typeof props.orderData === 'string') {
    console.error('orderData is a string, expected object. Possible HTML response:', props.orderData)
    alert('Error: Invalid order data received. Please refresh and try again.')
    return
  }

  sections.forEach(section => {
    section.products.forEach(product => {
      product.selected = false
      product.qty = 0
      product.unitCost = 0
      product.bananaType = product.name === 'Banana' ? '' : undefined
      product.otherDetails = product.name === 'Others:' ? '' : undefined
    })
  })

  if (props.orderData && props.orderData.items && Array.isArray(props.orderData.items)) {
    props.orderData.items.forEach(orderItem => {
      const sectionType = getProductSection(orderItem.title)
      const sectionIndex = sectionType === 'tagum' ? 0 : 1

      let productFound = false

      sections[sectionIndex].products.forEach(product => {
        const productNameLower = product.name.toLowerCase()
        const orderItemLower = orderItem.title.toLowerCase()

        if (productNameLower === orderItemLower ||
            (productNameLower !== 'others:' && orderItemLower.includes(productNameLower))) {
          product.selected = true
          product.qty = parseInt(orderItem.qty) || 0
          product.unitCost = parseFloat(orderItem.price) || 0
          productFound = true

          if (product.name === 'Banana' && orderItemLower !== 'banana') {
            product.bananaType = orderItem.title.replace(/banana/i, '').trim()
          }
        }
      })

      if (!productFound) {
        const othersProduct = sections[sectionIndex].products.find(p => p.name === 'Others:')
        if (othersProduct) {
          othersProduct.selected = true
          othersProduct.qty = parseInt(orderItem.qty) || 0
          othersProduct.unitCost = parseFloat(orderItem.price) || 0
          othersProduct.otherDetails = orderItem.title
        }
      }
    })
  }
}

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

const grandTotal = computed(() => {
  return sections.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + (item.qty * item.unitCost), 0)
    )
  }, 0).toFixed(2)
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
