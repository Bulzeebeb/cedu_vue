<template>
  <div class="flex flex-col min-h-screen bg-gray-100">
    <SiteHeader />

    <main class="flex-grow py-8">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-3xl font-bold text-maroon">Purchase History</h1>
          <button
            @click="goBack"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
          >
            ← Back to Profile
          </button>
        </div>

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
            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors disabled:opacity-50"
          >
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
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

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

// Updated Download POS function to match onlineMart_pos format
const downloadOrderPOS = (order) => {
  // Helper function to determine product section
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

    // Try to find matching product in the section
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

        // Handle banana type specification
        if (product.name === 'Banana' && orderItem.product_name.toLowerCase() !== 'banana') {
          product.bananaType = orderItem.product_name.replace(/banana/i, '').trim()
        }
      }
    })

    // If no matching product found, put it in "Others:"
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

  // Create Word document content in HTML format that Word can read
  const wordContent = `
    <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word'>
    <head>
      <meta charset="utf-8">
      <title>ORDER PAYMENT SLIP</title>
      <!--[if gte mso 9]>
      <xml>
        <w:WordDocument>
          <w:View>Print</w:View>
          <w:Zoom>90</w:Zoom>
          <w:DoNotPromptForConvert/>
          <w:DoNotShowInsertionsAndDeletions/>
        </w:WordDocument>
      </xml>
      <![endif]-->
      <style>
        @page {
          size: 8.5in 11in;
          margin: 0.3in 0.4in;
        }
        body {
          font-family: Arial, sans-serif;
          font-size: 9pt;
          line-height: 1.1;
          color: black;
          margin: 0;
          padding: 0;
        }
        .header {
          text-align: center;
          margin-bottom: 8pt;
        }
        h1 {
          font-size: 10pt;
          margin: 1pt 0;
          font-weight: normal;
        }
        h2 {
          font-size: 11pt;
          margin: 1pt 0;
          font-weight: bold;
        }
        h3 {
          font-size: 9pt;
          margin: 1pt 0;
          font-style: italic;
          font-weight: normal;
        }
        .control-no {
          text-align: right;
          font-weight: bold;
          margin-top: 2pt;
          font-size: 9pt;
        }
        .basic-info {
          border: 1pt solid black;
          padding: 4pt;
          margin-bottom: 8pt;
          font-size: 9pt;
        }
        .basic-info p {
          margin: 1pt 0;
        }
        table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 8pt;
          font-size: 8pt;
        }
        th, td {
          border: 1pt solid #666;
          padding: 1pt 3pt;
          text-align: center;
          vertical-align: middle;
          line-height: 1.0;
        }
        th {
          background-color: #f0f0f0;
          font-weight: bold;
          font-size: 8pt;
        }
        .product-cell {
          text-align: left;
        }
        .section-header {
          background-color: #f5f5f5;
          font-weight: bold;
          text-align: left;
          font-size: 8pt;
        }
        .grand-total {
          text-align: right;
          font-weight: bold;
          font-size: 11pt;
          margin: 8pt 0;
        }
        .signatures {
          margin-top: 10pt;
          font-size: 9pt;
          line-height: 1.3;
        }
        .signatures p {
          margin: 4pt 0;
        }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Republic of the Philippines</h1>
        <h2>University of Southeastern Philippines</h2>
        <h3>Resource Management Division (RMD)</h3>
        <h2>ORDER PAYMENT SLIP (OPS/POS)</h2>
        <p class="control-no">Control No.: ${order.id}</p>
      </div>

      <div class="basic-info">
        <p><strong>Payor/Name:</strong> ${order.full_name}</p>
        <p><strong>Organization:</strong> CEDU CROP PRODUCTION PROJECT</p>
        <p><strong>Date:</strong> ${formatDate(order.order_date)}</p>
        <p>OTHER BUSINESS INCOME</p>
      </div>

      <table>
        <thead>
          <tr>
            <th style="width: 8%;">Select</th>
            <th style="width: 35%;">Product</th>
            <th style="width: 12%;">QTY</th>
            <th style="width: 20%;">UNIT COST</th>
            <th style="width: 25%;">TOTAL COST</th>
          </tr>
        </thead>
        <tbody>
          ${sections.map(section => `
            <tr class="section-header">
              <td colspan="5">${section.title}</td>
            </tr>
            ${section.products.map(item => `
              <tr>
                <td>${item.selected ? '☑' : '☐'}</td>
                <td class="product-cell">
                  ${item.name}${item.bananaType ? ' ' + item.bananaType : ''}${item.otherDetails ? ' ' + item.otherDetails : ''}
                </td>
                <td>${item.qty || 0}</td>
                <td>₱${(item.unitCost || 0).toFixed(2)}</td>
                <td>₱${(item.qty * item.unitCost).toFixed(2)}</td>
              </tr>
            `).join('')}
          `).join('')}
        </tbody>
      </table>

      <div class="grand-total">
        GRAND TOTAL COST: Php ${grandTotal}
      </div>

      <div class="signatures">
        <p>Prepared By: _________________________</p>
        <p>Staff: _________________________</p>
        <p>Cashier: _________________________</p>
        <p>OR No.: _________________________</p>
      </div>
    </body>
    </html>
  `

  // Create and download as Word document
  const blob = new Blob(['\ufeff', wordContent], {
    type: 'application/msword'
  })

  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `POS_Order_${order.id}_${new Date().toISOString().split('T')[0]}.doc`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
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
