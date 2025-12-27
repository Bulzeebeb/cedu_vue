<template>
  <div class="flex flex-col min-h-screen py-32 bg-gray-100">
    <SiteHeader />

    <main class="flex-1 p-6 pt-4 text-[#5F1213] flex flex-col items-center justify-center">
      <div class="bg-gray-100 w-full max-w-7xl rounded-xl p-6 mb-6 shadow border text-left">
        <h1 class="text-3xl font-semibold mb-1">Purchase History</h1>
        <p class="text-gray-500">You may download your POS.</p>
        <button
            @click="goBack"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
          >
            ← Back to Profile
          </button>
      </div>
      <div class="max-w-6xl mx-auto px-4">
        <!-- No Orders Message -->
        <div v-if="orders.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
          <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No Orders Yet</h3>
          <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
          <button
            @click="goToMarket"
            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors"
          >
            Start Shopping
          </button>
        </div>

        <!-- Orders List -->
        <div v-else class="space-y-4">
          <div
            v-for="order in orders"
            :key="order.id"
            class="bg-white rounded-lg shadow hover:shadow-md transition-shadow"
          >
            <!-- Order Header -->
            <div class="p-4 border-b border-gray-200">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                  <div>
                    <h3 class="font-semibold text-lg text-gray-800">
                      Order #{{ order.id }}
                    </h3>
                    <p class="text-sm text-gray-600">
                      Placed on {{ formatDate(order.created_at) }}
                    </p>
                  </div>
                  <div class="mt-2 md:mt-0">
                    <span
                      :class="getStatusClass(order.status)"
                      class="px-3 py-1 rounded-full text-xs font-medium"
                    >
                      {{ getStatusText(order.status) }}
                    </span>
                  </div>
                </div>
                <div class="mt-2 md:mt-0 text-right">
                  <p class="text-lg font-bold text-maroon">₱{{ order.total_amount }}</p>
                  <p class="text-sm text-gray-600">{{ order.order_items_count }} item(s)</p>
                </div>
              </div>
            </div>

            <!-- Order Details -->
            <div class="p-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <h4 class="font-medium text-gray-700 mb-2">Customer Information</h4>
                  <p class="text-sm text-gray-600">{{ order.full_name }}</p>
                  <p class="text-sm text-gray-600">{{ order.contact }}</p>
                </div>
                <div>
                  <h4 class="font-medium text-gray-700 mb-2">Order Date</h4>
                  <p class="text-sm text-gray-600">{{ formatDate(order.order_date) }}</p>
                </div>
              </div>

              <!-- Order Items -->
              <div>
                <h4 class="font-medium text-gray-700 mb-3">Items Ordered</h4>
                <div class="space-y-2">
                  <div
                    v-for="item in order.order_items"
                    :key="item.id"
                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                      </div>
                      <div>
                        <h5 class="font-medium text-gray-800">{{ item.product_name }}</h5>
                        <p class="text-sm text-gray-600">
                          {{ item.quantity }} {{ item.metric }} × ₱{{ parseFloat(item.unit_price).toFixed(2) }}
                        </p>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="font-semibold text-maroon">₱{{ parseFloat(item.subtotal).toFixed(2) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                  Order Status: <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-medium ml-1">
                    {{ getStatusText(order.status) }}
                  </span>
                </div>
                <button
                  @click="downloadOrderPOS(order)"
                  class="px-4 py-2 bg-maroon text-white text-sm rounded-lg hover:bg-red-800 transition-colors"
                >
                  Download POS
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Load More Button -->
        <div v-if="hasMoreOrders" class="text-center mt-6">
          <button
            @click="loadMoreOrders"
            :disabled="isLoadingMore"
            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors disabled:opacity-50">
            <span v-if="isLoadingMore">Loading...</span>
            <span v-else>Load More Orders</span>
          </button>
        </div>
      </div>
    </main>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import SiteHeader from '../zClientChoicePage/cHeader.vue'
import SiteFooter from '../Footer.vue'

// Props from backend
const props = defineProps({
  orders: {
    type: Array,
    default: () => []
  },
  hasMoreOrders: {
    type: Boolean,
    default: false
  },
  currentPage: {
    type: Number,
    default: 1
  }
})

// State
const isLoadingMore = ref(false)

// Methods
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'ready': 'bg-green-100 text-green-800',
    'completed': 'bg-gray-100 text-gray-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    'pending': 'Pending',
    'processing': 'Processing',
    'ready': 'Ready for Pickup',
    'completed': 'Completed',
    'cancelled': 'Cancelled'
  }
  return texts[status] || 'Unknown'
}

const goBack = () => {
  router.visit('/clientSetting')
}

const goToMarket = () => {
  router.visit('/om-landing')
}

const loadMoreOrders = () => {
  if (isLoadingMore.value) return

  isLoadingMore.value = true

  router.get('/buy-history', {
    page: props.currentPage + 1
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isLoadingMore.value = false
    }
  })
}

// Download POS as PDF - Same format as onlineMart_pos.vue
const downloadOrderPOS = async (order) => {
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

    // Helper function to determine product section
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

    // Initialize sections with all products
    const sections = [
      {
        title: 'CEDU TAGUM – CROP PRODUCTION',
        products: [
          'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
          'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra', 'Others:'
        ].map(name => ({
          name,
          qty: 0,
          unitCost: 0,
          selected: false,
          bananaType: name === 'Banana' ? '' : undefined,
          otherDetails: name === 'Others:' ? '' : undefined
        }))
      },
      {
        title: 'CEDU MABINI – CROP PRODUCTION',
        products: [
          'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
          'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo', 'Others:'
        ].map(name => ({
          name,
          qty: 0,
          unitCost: 0,
          selected: false,
          bananaType: name === 'Banana' ? '' : undefined,
          otherDetails: name === 'Others:' ? '' : undefined
        }))
      }
    ]

    // Populate sections with order items
    order.order_items.forEach(orderItem => {
      const sectionType = getProductSection(orderItem.product_name)
      const sectionIndex = sectionType === 'tagum' ? 0 : 1

      let productFound = false

      sections[sectionIndex].products.forEach(product => {
        const productNameLower = product.name.toLowerCase()
        const orderItemLower = orderItem.product_name.toLowerCase()

        if (productNameLower === orderItemLower ||
            (productNameLower !== 'others:' && orderItemLower.includes(productNameLower))) {
          product.selected = true
          product.qty = parseInt(orderItem.quantity) || 0
          product.unitCost = parseFloat(orderItem.unit_price) || 0
          productFound = true

          if (product.name === 'Banana' && orderItem.product_name.toLowerCase() !== 'banana') {
            product.bananaType = orderItem.product_name.replace(/banana/i, '').trim()
          }
        }
      })

      if (!productFound) {
        const othersProduct = sections[sectionIndex].products.find(p => p.name === 'Others:')
        if (othersProduct) {
          othersProduct.selected = true
          othersProduct.qty = parseInt(orderItem.quantity) || 0
          othersProduct.unitCost = parseFloat(orderItem.unit_price) || 0
          othersProduct.otherDetails = orderItem.product_name
        }
      }
    })

    // Calculate grand total
    const grandTotal = sections.reduce((total, section) => {
      return (
        total +
        section.products
          .filter(item => item.selected)
          .reduce((subtotal, item) => subtotal + item.qty * item.unitCost, 0)
      )
    }, 0).toFixed(2)

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
    doc.text('Control No.: ' + order.id, 190, yPos, { align: 'right' })

    // Basic Info Box
    yPos += 5
    const boxHeight = 25
    doc.rect(15, yPos, 180, boxHeight)
    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Payor/Name: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(order.full_name || '', 42, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Organization: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text('CEDU CROP PRODUCTION PROJECT', 45, yPos)

    yPos += 6
    doc.setFont('helvetica', 'bold')
    doc.text('Date: ', 18, yPos)
    doc.setFont('helvetica', 'normal')
    doc.text(formatDate(order.order_date), 30, yPos)

    yPos += 6
    doc.text('OTHER BUSINESS INCOME', 18, yPos)

    // Table
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

    // Render both sections
    sections.forEach((section, sectionIdx) => {
      // Section Header
      doc.setFillColor(245, 245, 245)
      doc.rect(15, yPos, 180, 6, 'FD')
      doc.setFont('helvetica', 'bold')
      doc.text(section.title, 18, yPos + 4)

      doc.line(15, yPos, 15, yPos + 6)
      doc.line(195, yPos, 195, yPos + 6)

      yPos += 6

      doc.setFont('helvetica', 'normal')
      section.products.forEach(item => {
        const rowHeight = 6

        // Draw all cell borders
        doc.line(15, yPos, 195, yPos)
        doc.line(15, yPos, 15, yPos + rowHeight)
        doc.line(30, yPos, 30, yPos + rowHeight)
        doc.line(115, yPos, 115, yPos + rowHeight)
        doc.line(135, yPos, 135, yPos + rowHeight)
        doc.line(165, yPos, 165, yPos + rowHeight)
        doc.line(195, yPos, 195, yPos + rowHeight)

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

      // Bottom line for section
      doc.line(15, yPos, 195, yPos)
    })

    // Grand Total
    yPos += 8
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.text('GRAND TOTAL COST: Php ' + grandTotal, 190, yPos, { align: 'right' })

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
    doc.save(`POS_Order_${order.id}_${new Date().toISOString().split('T')[0]}.pdf`)
  } catch (error) {
    console.error('Error generating PDF:', error)
    alert('Failed to generate PDF. Please try again.')
  }
}

// Protect route
onMounted(() => {
  // Add any initialization logic here
})
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}
</style>
