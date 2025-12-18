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
            <!-- PAGE 1: CROP PRODUCTS -->
            <template v-if="currentPage === 1">
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
            </template>

            <!-- PAGE 2: POULTRY PRODUCTS -->
            <template v-if="currentPage === 2">
              <!-- CEDU POULTRY EGGS Section -->
              <tr class="bg-gray-50">
                <td colspan="5" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  CEDU – POULTRY PRODUCTS (EGG SIZES)
                </td>
              </tr>
              <tr
                v-for="item in poultryProducts[0].products"
                :key="'eggs-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
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

              <!-- CEDU OTHER POULTRY Section -->
              <tr class="bg-gray-50">
                <td colspan="5" class="px-2 py-1 font-semibold text-sm border-b border-gray-400">
                  CEDU – OTHER POULTRY ITEMS
                </td>
              </tr>
              <tr
                v-for="item in poultryProducts[1].products"
                :key="'poultry-' + item.name"
                class="border-b border-gray-300"
              >
                <td class="px-2 py-1 text-center border-r border-gray-300">
                  <span class="text-lg">{{ item.selected ? '☑' : '☐' }}</span>
                </td>
                <td class="px-2 py-1 border-r border-gray-300">
                  {{ item.name }}
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
            </template>
            </tbody>
          </table>
          <p>Cashier: _________________________</p>
          <p>OR No.: _________________________</p>
        </div>
      </div>

      <!-- Buttons -->
      <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
        <button
          v-if="currentPage > 1"
          @click="prevPage"
          class="w-full sm:w-auto bg-blue-600 text-white py-2 px-6 rounded-full hover:bg-blue-700 font-medium text-sm sm:text-base transition"
        >
          ← Previous Page
        </button>
        <button
          @click="downloadPOS"
          class="w-full sm:w-auto bg-maroon text-white py-2 px-6 rounded-full hover:bg-red-800 font-medium text-sm sm:text-base transition"
        >
          Download POS
        </button>
        <button
          v-if="currentPage < 2"
          @click="nextPage"
          class="w-full sm:w-auto bg-blue-600 text-white py-2 px-6 rounded-full hover:bg-blue-700 font-medium text-sm sm:text-base transition"
        >
          Next Page →
        </button>
        <button
          @click="handleClose"
          class="w-full sm:w-auto bg-gray-600 text-white py-2 px-6 rounded-full hover:bg-gray-700 font-medium text-sm sm:text-base transition"
        >
          Back to Market
        </button>
      </div>

      <!-- Page Indicator -->
      <div class="text-center mt-4 text-sm text-gray-600">
        Page {{ currentPage }} of 2
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

// Page management
const currentPage = ref(1)

const handleClose = () => {
  console.log('Closing modal')
  emit('close')
}

const nextPage = () => {
  if (currentPage.value < 2) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
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
  console.log('Poultry Products:', JSON.stringify(poultryProducts, null, 2))
  if (props.orderData?.items) {
    props.orderData.items.forEach((item, idx) => {
      console.log(`Item ${idx}:`, item.title, '- isPoultry:', isPoultryProduct(item.title), '- section:', getPoultrySection(item.title))
    })
  }
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

    // PAGE 1: CROP PRODUCTS
    generatePDFPage(doc, 1, sections, true)

    // PAGE 2: POULTRY PRODUCTS
    doc.addPage()
    generatePDFPage(doc, 2, poultryProducts, false)

    doc.save(`POS_${props.orderData?.orderId || 'Order'}_${new Date().toISOString().split('T')[0]}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Failed to generate PDF. Please try again.')
  }
}

// Helper function to generate PDF pages
const generatePDFPage = (doc, pageNumber, sectionsData, isCropPage) => {
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
  doc.text(isCropPage ? 'OTHER BUSINESS INCOME' : 'POULTRY PRODUCTS', 18, yPos)

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

// Function to normalize product names for better matching
function normalizeProductName(name) {
  return name.toLowerCase().replace(/\s+/g, ' ').trim()
}

// Function to extract egg size from item title
function extractEggSize(itemTitle) {
  const lower = normalizeProductName(itemTitle)
  
  // Mapping of possible input formats to standard POS egg sizes
  const eggSizeMap = {
    'under size pewee': 'Under size Pewee',
    'under size peewee': 'Under size Pewee',
    'usp': 'Under size Pewee',
    'peewee': 'Peewee',
    'pewee': 'Peewee',
    'p - peewee': 'Peewee',
    'p - pewee': 'Peewee',
    'p-peewee': 'Peewee',
    'p-pewee': 'Peewee',
    'small': 'Small',
    's - small': 'Small',
    's-small': 'Small',
    'medium': 'Medium',
    'm - medium': 'Medium',
    'm-medium': 'Medium',
    'large': 'Large',
    'l - large': 'Large',
    'l-large': 'Large',
    'extra-large': 'Extra-Large',
    'extra large': 'Extra-Large',
    'xl - extra-large': 'Extra-Large',
    'xl - extra large': 'Extra-Large',
    'xl-extra-large': 'Extra-Large',
    'xl-extra large': 'Extra-Large',
    'jumbo': 'Jumbo',
    'j - jumbo': 'Jumbo',
    'j-jumbo': 'Jumbo',
    'egg': 'Peewee' // Default to Peewee if just "Egg" is provided
  }
  
  // Try direct matches first
  if (eggSizeMap[lower]) {
    return eggSizeMap[lower]
  }
  
  // Try partial matches for common patterns
  for (const [key, value] of Object.entries(eggSizeMap)) {
    if (lower.includes(key)) {
      return value
    }
  }
  
  return null
}

// Function to identify poultry products
function getPoultrySection(productName) {
  const normalizedName = productName.toLowerCase()

  // Check for egg size patterns
  const eggSizePatterns = [
    /pewee|peewee/,
    /small/,
    /medium/,
    /large|xl/,
    /jumbo/,
    /under size|usp/,
    /egg/
  ]

  const isEgg = eggSizePatterns.some(pattern => pattern.test(normalizedName))
  if (isEgg) return 'eggs'

  // Check for other poultry items
  const otherPoultryPatterns = [
    /chicken\s*dung/,
    /culled\s*chicken/,
    /chicken/,
    /poultry/
  ]

  const isOtherPoultry = otherPoultryPatterns.some(pattern => pattern.test(normalizedName))
  if (isOtherPoultry) return 'other'

  return null
}

function isPoultryProduct(productName) {
  return getPoultrySection(productName) !== null
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

const poultryProducts = reactive([
  {
    title: 'CEDU – POULTRY PRODUCTS (EGG SIZES)',
    products: generateProducts(['Under size Pewee', 'Peewee', 'Small', 'Medium', 'Large', 'Extra-Large', 'Jumbo'])
  },
  {
    title: 'CEDU – OTHER POULTRY ITEMS',
    products: generateProducts(['Chicken Dung', 'Culled Chicken'])
  }
])

const populateOrderData = () => {
  console.log('=== POPULATE ORDER DATA CALLED ===')
  console.log('Props orderData:', props.orderData)
  console.log('Props orderData type:', typeof props.orderData)
  
  // Validate orderData is an object, not HTML string
  if (typeof props.orderData === 'string') {
    console.error('orderData is a string, expected object. Possible HTML response:', props.orderData)
    alert('Error: Invalid order data received. Please refresh and try again.')
    return
  }

  // Reset all sections
  sections.forEach(section => {
    section.products.forEach(product => {
      product.selected = false
      product.qty = 0
      product.unitCost = 0
      product.bananaType = product.name === 'Banana' ? '' : undefined
      product.otherDetails = product.name === 'Others:' ? '' : undefined
    })
  })

  // Reset poultry sections
  poultryProducts.forEach(section => {
    section.products.forEach(product => {
      product.selected = false
      product.qty = 0
      product.unitCost = 0
      product.otherDetails = product.name === 'Others:' ? '' : undefined
    })
  })

  if (props.orderData && props.orderData.items && Array.isArray(props.orderData.items)) {
    console.log(`Found ${props.orderData.items.length} items to process`)
    props.orderData.items.forEach((orderItem, idx) => {
      const itemTitle = orderItem.title || ''
      const itemQty = parseInt(orderItem.qty) || 0
      const itemPrice = parseFloat(orderItem.price) || 0
      
      console.log(`Item ${idx}: title="${itemTitle}", qty=${itemQty}, price=${itemPrice}`)
      
      // Check if it's a poultry product
      const isPoultry = isPoultryProduct(itemTitle)
      console.log(`  -> isPoultry: ${isPoultry}`)
      
      if (isPoultry) {
        const poultrySection = getPoultrySection(itemTitle)
        let sectionIndex = poultrySection === 'eggs' ? 0 : 1

        let productFound = false
        const itemTitleNormalized = normalizeProductName(itemTitle)

        console.log(`  -> Processing poultry item: "${itemTitle}" -> section: ${poultrySection}, index: ${sectionIndex}`)

        // Special handling for egg sizes - use simplified matching
        if (poultrySection === 'eggs') {
          const posEggSize = extractEggSize(itemTitle)
          console.log(`  -> extractEggSize("${itemTitle}") returned: "${posEggSize}"`)
          
          if (posEggSize) {
            // Find exact match in POS egg products
            const eggProduct = poultryProducts[sectionIndex].products.find(p =>
              normalizeProductName(p.name) === normalizeProductName(posEggSize)
            )
            
            if (eggProduct) {
              console.log(`  -> Matched egg: "${itemTitle}" to "${eggProduct.name}"`)
              eggProduct.selected = true
              eggProduct.qty = itemQty
              eggProduct.unitCost = itemPrice
              productFound = true
            } else {
              console.log(`  -> ERROR: Could not find eggProduct matching "${posEggSize}" in egg products`)
              console.log(`  -> Available egg products: ${poultryProducts[sectionIndex].products.map(p => p.name).join(', ')}`)
            }
          } else {
            console.log(`  -> ERROR: extractEggSize returned null for "${itemTitle}"`)
          }
        }


        // If not found with special egg logic, try general matching in determined section
        if (!productFound) {
          poultryProducts[sectionIndex].products.forEach(product => {
            const productNameNormalized = normalizeProductName(product.name)

            // Check for exact match or close match
            if (productNameNormalized === itemTitleNormalized ||
                itemTitleNormalized === productNameNormalized ||
                (productNameNormalized.includes(itemTitleNormalized) && productNameNormalized !== 'others:') ||
                (itemTitleNormalized.includes(productNameNormalized) && productNameNormalized !== 'others:')) {

              console.log(`Matched: "${itemTitle}" to product "${product.name}"`)
              product.selected = true
              product.qty = itemQty
              product.unitCost = itemPrice
              productFound = true
            }
          })
        }

        // Poultry products no longer have "Others:" option, so unmatched items are skipped
        if (!productFound) {
          console.log(`No match found for poultry item "${itemTitle}", skipping`)
        }
      } else {
        // Handle crop products (original logic)
        const sectionType = getProductSection(itemTitle)
        const sectionIndex = sectionType === 'tagum' ? 0 : 1

        let productFound = false

        sections[sectionIndex].products.forEach(product => {
          const productNameLower = product.name.toLowerCase()
          const orderItemLower = itemTitle.toLowerCase()

          if (productNameLower === orderItemLower ||
              (productNameLower !== 'others:' && orderItemLower.includes(productNameLower))) {
            product.selected = true
            product.qty = itemQty
            product.unitCost = itemPrice
            productFound = true

            if (product.name === 'Banana' && orderItemLower !== 'banana') {
              product.bananaType = itemTitle.replace(/banana/i, '').trim()
            }
          }
        })

        if (!productFound) {
          const othersProduct = sections[sectionIndex].products.find(p => p.name === 'Others:')
          if (othersProduct) {
            othersProduct.selected = true
            othersProduct.qty = itemQty
            othersProduct.unitCost = itemPrice
            othersProduct.otherDetails = itemTitle
          }
        }
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
  console.log('Crop sections after populate:', JSON.stringify(sections, null, 2))
  console.log('Poultry sections after populate:', JSON.stringify(poultryProducts, null, 2))
}

onMounted(() => {
  console.log('POS Modal mounted, isOpen:', props.isOpen)
  console.log('Initial props.orderData:', props.orderData)
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
  // Calculate from both crop and poultry sections
  let cropTotal = sections.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + (item.qty * item.unitCost), 0)
    )
  }, 0)

  let poultryTotal = poultryProducts.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + (item.qty * item.unitCost), 0)
    )
  }, 0)

  return (cropTotal + poultryTotal).toFixed(2)
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
