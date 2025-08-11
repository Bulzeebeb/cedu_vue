<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white text-black rounded-xl shadow-lg w-full max-w-4xl max-h-[95vh] overflow-y-auto p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4">

      <!-- Header Title and Close -->
      <div class="flex items-center gap-2 mb-4 print:hidden">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">GENERATED POS</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">
          &times;
        </button>
      </div>

      <!-- POS FORM CONTENT START -->
      <div class="pos-form border border-gray-400 rounded-lg p-3 sm:p-4 md:p-6 shadow-sm print:border-none print:shadow-none print:rounded-none print:p-0">

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
      <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4 print:hidden">
        <button
          @click="downloadPOS"
          class="w-full sm:w-auto bg-maroon text-white py-2 px-6 rounded-full hover:bg-red-800 font-medium text-sm sm:text-base"
        >
          Download POS
        </button>
        <button
          @click="printPOS"
          class="w-full sm:w-auto bg-blue-600 text-white py-2 px-6 rounded-full hover:bg-blue-700 font-medium text-sm sm:text-base"
        >
          Print POS
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

// Print function
const printPOS = () => {
  window.print()
}

// Download as Word document function
const downloadPOS = () => {
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
        <p class="control-no">Control No.: ${props.orderData?.orderId || '______'}</p>
      </div>

      <div class="basic-info">
        <p><strong>Payor/Name:</strong> ${props.orderData?.customerName || '__________________________'}</p>
        <p><strong>Organization:</strong> CEDU CROP PRODUCTION PROJECT</p>
        <p><strong>Date:</strong> ${props.orderData?.orderDate || '__________________________'}</p>
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
          <tr class="section-header">
            <td colspan="5">CEDU TAGUM – CROP PRODUCTION</td>
          </tr>
          ${sections[0].products.map(item => `
            <tr>
              <td>${item.selected ? '☑' : '☐'}</td>
              <td class="product-cell">
                ${item.name}${item.bananaType ? ' ' + item.bananaType : ''}${item.otherDetails ? ' ' + item.otherDetails : ''}
              </td>
              <td>${item.qty || 0}</td>
              <td>${item.unitCost || 0}</td>
              <td>${(item.qty * item.unitCost).toFixed(2)}</td>
            </tr>
          `).join('')}
          <tr class="section-header">
            <td colspan="5">CEDU MABINI – CROP PRODUCTION</td>
          </tr>
          ${sections[1].products.map(item => `
            <tr>
              <td>${item.selected ? '☑' : '☐'}</td>
              <td class="product-cell">
                ${item.name}${item.bananaType ? ' ' + item.bananaType : ''}${item.otherDetails ? ' ' + item.otherDetails : ''}
              </td>
              <td>${item.qty || 0}</td>
              <td>${item.unitCost || 0}</td>
              <td>${(item.qty * item.unitCost).toFixed(2)}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>

      <div class="grand-total">
        GRAND TOTAL COST: Php ${grandTotal.value}
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
  link.download = `POS_${props.orderData?.orderId || 'Order'}_${new Date().toISOString().split('T')[0]}.doc`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
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

@media print {
  @page {
    size: A4;
    margin: 0.5in;
  }

  .fixed {
    position: static !important;
  }

  .print\:hidden {
    display: none !important;
  }

  .bg-black {
    background: transparent !important;
  }

  .print\:border-none {
    border: none !important;
  }

  .print\:shadow-none {
    box-shadow: none !important;
  }

  .print\:rounded-none {
    border-radius: 0 !important;
  }

  .print\:p-0 {
    padding: 0 !important;
  }

  /* Ensure table fits on one page */
  table {
    page-break-inside: avoid;
  }

  .pos-form {
    font-size: 11px !important;
  }

  .pos-form table {
    font-size: 10px !important;
    line-height: 1.2;
  }

  .pos-form th,
  .pos-form td {
    padding: 2px 4px !important;
  }

  /* Compact spacing for print */
  .pos-form .mb-4 {
    margin-bottom: 8px !important;
  }

  .pos-form .mt-6 {
    margin-top: 12px !important;
  }

  .pos-form .space-y-2 > * + * {
    margin-top: 4px !important;
  }
}
</style>

<script>
export default {
  name: "onlineMart_pos"
}
</script>
