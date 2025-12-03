<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white text-black rounded-xl shadow-lg w-full max-w-4xl max-h-[95vh] overflow-y-auto p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4">

      <!-- Header Title and Close -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">GENERATED POS</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">
          &times;
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
          <p><span class="font-semibold">Date:</span> {{ orderData?.orderDate || '__________________________' }}</p>
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
                  {{ item.unitCost || 0 }}
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
                  {{ item.unitCost || 0 }}
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
          class="w-full sm:w-auto bg-maroon text-white py-2 px-6 rounded-full hover:bg-red-800 font-medium text-sm sm:text-base"
        >
          Download POS
        </button>
        <button
          @click="goToLanding"
          class="w-full sm:w-auto bg-gray-600 text-white py-2 px-6 rounded-full hover:bg-gray-700 font-medium text-sm sm:text-base"
        >
          Back to Market
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  orderData: {
    type: Object,
    default: () => ({})
  }
})

const close = () => router.visit('/om-landing')
const goToLanding = () => router.visit('/om-landing')

// Download as PDF function using jsPDF
const downloadPOS = async () => {
  try {
    // Check if jsPDF is already loaded
    if (!window.jspdf) {
      // Load jsPDF library
      await new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'
        script.onload = resolve
        script.onerror = reject
        document.head.appendChild(script)
      })
    }

    const { jsPDF } = window.jspdf

    // Create new PDF document
    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4'
    })

    // Set font
    doc.setFont('helvetica')

    // Header
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
    doc.text('Control No.: ' + (props.orderData?.orderId || '______'), 190, yPos, { align: 'right' })

    // Basic Info Box
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
    doc.text(props.orderData?.orderDate || '__________________________', 30, yPos)

    yPos += 6
    doc.text('OTHER BUSINESS INCOME', 18, yPos)

    // Table
    yPos += 8
    const tableStartY = yPos

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
    doc.text('CEDU TAGUM - CROP PRODUCTION', 18, yPos + 4)

    // Draw vertical lines for section header
    doc.line(15, yPos, 15, yPos + 6)
    doc.line(195, yPos, 195, yPos + 6)

    yPos += 6

    doc.setFont('helvetica', 'normal')
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

      // Draw checkbox
      const checkboxX = 20
      const checkboxY = yPos + 1.5
      const checkboxSize = 3
      doc.rect(checkboxX, checkboxY, checkboxSize, checkboxSize)

      // Draw checkmark if selected
      if (item.selected) {
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
      doc.text(String(item.unitCost || 0), 150, yPos + 4, { align: 'center' })
      doc.text((item.qty * item.unitCost).toFixed(2), 180, yPos + 4, { align: 'center' })

      yPos += rowHeight
    })

    // Bottom line for last row of TAGUM
    doc.line(15, yPos, 195, yPos)

    // CEDU MABINI Section
    doc.setFillColor(245, 245, 245)
    doc.rect(15, yPos, 180, 6, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.text('CEDU MABINI - CROP PRODUCTION', 18, yPos + 4)

    // Draw vertical lines for section header
    doc.line(15, yPos, 15, yPos + 6)
    doc.line(195, yPos, 195, yPos + 6)

    yPos += 6

    doc.setFont('helvetica', 'normal')
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

      // Draw checkbox
      const checkboxX = 20
      const checkboxY = yPos + 1.5
      const checkboxSize = 3
      doc.rect(checkboxX, checkboxY, checkboxSize, checkboxSize)

      // Draw checkmark if selected
      if (item.selected) {
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
      doc.text(String(item.unitCost || 0), 150, yPos + 4, { align: 'center' })
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

    // Save the PDF
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

  // Normalize product name for comparison
  const normalizedName = productName.toLowerCase()

  // Check if it matches any known products
  const matchesTagum = tagumProducts.some(product => normalizedName.includes(product.toLowerCase()))
  const matchesMabini = mabiniProducts.some(product => normalizedName.includes(product.toLowerCase()))

  if (matchesTagum) return 'tagum'
  if (matchesMabini) return 'mabini'

  // Default to tagum for unknown products
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
    products: generateProducts([
      'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
      'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra', 'Others:'
    ])
  },
  {
    title: 'CEDU MABINI – CROP PRODUCTION',
    products: generateProducts([
      'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
      'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo', 'Others:'
    ])
  }
])

// Populate order data when component mounts
onMounted(() => {
  if (props.orderData && props.orderData.items) {
    props.orderData.items.forEach(orderItem => {
      const sectionType = getProductSection(orderItem.title)
      const sectionIndex = sectionType === 'tagum' ? 0 : 1

      // Try to find matching product in the section
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

          // Handle banana type specification
          if (product.name === 'Banana' && orderItem.title.toLowerCase() !== 'banana') {
            product.bananaType = orderItem.title.replace(/banana/i, '').trim()
          }
        }
      })

      // If no matching product found, put it in "Others:"
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
})

const grandTotal = computed(() => {
  return sections.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + item.qty * item.unitCost, 0)
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
