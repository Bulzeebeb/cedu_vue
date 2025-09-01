<template>
  <div class="flex flex-col min-h-screen" style="background-color: #f3f4f6;">
    <SiteHeader />

    <main class="flex-grow flex justify-center items-center">
      <div
        class="w-full max-w-6xl bg-white shadow-xl rounded-2xl px-12 py-16 flex flex-col relative"
        style="min-height: 600px;"
      >
        <!-- Sign Out Button - Top Right -->
        <div class="absolute top-6 right-6">
          <button
            @click="handleSignOut"
            class="px-5 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition"
          >
            Sign Out
          </button>
        </div>

        <!-- Profile Info -->
        <div class="flex items-center space-x-10 mb-12 mt-6">
          <div class="w-30 h-30 rounded-full overflow-hidden border-4 border-yellow-500 shadow">
            <img
              :src="client.image ? '/storage/' + client.image : '/images/Client/default_profile.png'"
              alt="Profile Picture"
              class="w-full h-full object-cover"
            />
          </div>

          <div class="flex flex-col justify-center h-20">
            <h2 class="text-3xl font-bold text-yellow-600">
              {{ client.firstName }} {{ client.lastName }}
            </h2>
            <p class="text-gray-700 text mt-1">{{ client.email }}</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-start space-x-6 mb-10">
          <button
            @click="showProfileModal = true"
            class="w-48 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition"
          >
            Edit Profile
          </button>
          <button
            @click="openHistoryModal"
            class="w-48 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
          >
            Buy History
          </button>
        </div>

        <!-- Back Button - Bottom Right -->
        <div class="absolute bottom-6 right-6">
          <button
            @click="goBack"
            class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition"
          >
            ← Back to Market
          </button>
        </div>
      </div>
    </main>

    <!-- Profile Modal -->
    <div v-if="showProfileModal" class="fixed inset-0 flex items-center justify-center z-50 p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px);">
      <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
          <button
            @click="closeProfileModal"
            class="text-gray-400 hover:text-gray-600 text-2xl"
          >
            ×
          </button>
        </div>

        <div class="p-6">
          <div class="flex flex-col items-center space-y-2 mb-6">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-yellow-500">
              <img
                :src="previewImage || (client.image ? '/storage/' + client.image : '/images/default-profile.png')"
                alt="Profile"
                class="w-full h-full object-cover"
              />
            </div>
            <label class="cursor-pointer bg-yellow-500 text-maroon px-3 py-1 rounded hover:shadow transition text-sm">
              Upload Photo
              <input type="file" class="hidden" @change="handleImageUpload" accept="image/*" />
            </label>
          </div>

          <form @submit.prevent="saveProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm text-gray-600 mb-1">First Name</label>
                <input v-model="form.firstName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Middle Name</label>
                <input v-model="form.middleName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                <input v-model="form.lastName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Name Extension</label>
                <input v-model="form.extension" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input v-model="form.email" type="email" class="w-full border px-3 py-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" disabled />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Contact Number</label>
                <input v-model="form.contactNum" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Gender</label>
                <select v-model="form.gender" class="w-full border px-3 py-2 rounded text-black">
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Age</label>
                <input v-model="form.age" type="number" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Address</label>
                <textarea v-model="form.address" rows="3" class="w-full border px-3 py-2 rounded text-black"></textarea>
              </div>
            </div>

            <div class="mt-4 text-left">
              <button type="button" @click="handleChangePassword" class="text-yellow-600 text-sm hover:underline">Change Password?</button>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeProfileModal" class="px-5 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Cancel
              </button>
              <button type="submit" class="bg-yellow-500 text-maroon px-5 py-2 rounded hover:shadow hover:scale-105 transition">
                Save Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Purchase History Modal -->
    <div v-if="showHistoryModal" class="fixed inset-0 flex items-center justify-center z-50 p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px);">
      <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-maroon">Purchase History</h2>
          <button
            @click="showHistoryModal = false"
            class="text-gray-400 hover:text-gray-600 text-2xl"
          >
            ×
          </button>
        </div>

        <div class="p-6">
          <!-- Loading State -->
          <div v-if="historyLoading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading your orders...</p>
          </div>

          <!-- No Orders Message -->
          <div v-else-if="purchaseHistory.length === 0" class="text-center py-8">
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
              v-for="order in purchaseHistory"
              :key="order.id"
              class="bg-white border rounded-lg shadow hover:shadow-md transition-shadow"
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
                    <p class="text-sm text-gray-600">{{ order.order_items ? order.order_items.length : (order.order_items_count || 0) }} item(s)</p>
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
                <div v-if="order.order_items && order.order_items.length > 0">
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
        </div>
      </div>
    </div>

    <SiteFooter />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

// Props from backend
const props = defineProps({
  client: Object
})

// Modal states
const showProfileModal = ref(false)
const showHistoryModal = ref(false)
const historyLoading = ref(false)

// Purchase history data
const purchaseHistory = ref([])

// Profile form data
const previewImage = ref(null)
const imageFile = ref(null)
const form = ref({
  firstName: props.client?.firstName || '',
  middleName: props.client?.middleName || '',
  lastName: props.client?.lastName || '',
  extension: props.client?.extension || '',
  email: props.client?.email || '',
  contactNum: props.client?.contactNum || '',
  gender: props.client?.gender || '',
  age: props.client?.age || '',
  address: props.client?.address || '',
})

// Protect route: redirect to /signin if not authenticated
onMounted(() => {
  if (!props.client) {
    router.visit('/signin')
  }
})

// Fetch purchase history function
const fetchPurchaseHistory = async () => {
  historyLoading.value = true

  try {
    // Using Inertia router to get data instead of fetch
    router.get('/buy-history', {}, {
      onSuccess: (page) => {
        // Extract orders from the response
        if (page.props && page.props.orders) {
          purchaseHistory.value = page.props.orders
        }
        historyLoading.value = false
      },
      onError: (error) => {
        console.error('Error fetching purchase history:', error)
        historyLoading.value = false
      }
    })
  } catch (error) {
    console.error('Error fetching purchase history:', error)
    historyLoading.value = false
  }
}

// Open history modal and fetch data
const openHistoryModal = () => {
  showHistoryModal.value = true
  fetchPurchaseHistory()
}

// Profile Modal Functions
const closeProfileModal = () => {
  showProfileModal.value = false
  // Reset form and image preview
  form.value = {
    firstName: props.client?.firstName || '',
    middleName: props.client?.middleName || '',
    lastName: props.client?.lastName || '',
    extension: props.client?.extension || '',
    email: props.client?.email || '',
    contactNum: props.client?.contactNum || '',
    gender: props.client?.gender || '',
    age: props.client?.age || '',
    address: props.client?.address || '',
  }
  previewImage.value = null
  imageFile.value = null
}

const saveProfile = () => {
  const formData = new FormData()
  for (const key in form.value) {
    formData.append(key, form.value[key])
  }

  if (imageFile.value) {
    formData.append('image', imageFile.value)
  }

  router.post('/client/setting', formData, {
    forceFormData: true,
    onSuccess: () => {
      showProfileModal.value = false
      // Show SweetAlert success message
      Swal.fire({
        icon: 'success',
        title: 'Profile Updated!',
        text: 'Your profile has been updated successfully.',
        confirmButtonColor: '#EAB308', // Yellow color to match your theme
        confirmButtonText: 'OK'
      }).then(() => {
        // Refresh the page to show updated data
        window.location.reload()
      })
    },
    onError: (errors) => {
      console.error(errors)
      // Show SweetAlert error message
      Swal.fire({
        icon: 'error',
        title: 'Update Failed',
        text: 'There were validation errors. Please check your input and try again.',
        confirmButtonColor: '#EF4444',
        confirmButtonText: 'OK'
      })
    }
  })
}

const handleImageUpload = (e) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    previewImage.value = URL.createObjectURL(file)
  }
}

const handleChangePassword = () => {
  // Navigate to change password page or open another modal
  showProfileModal.value = false
  router.visit('/change-password')
}

// History Modal Functions
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

const downloadOrderPOS = (order) => {
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

  // Only proceed if order has items
  if (!order.order_items || order.order_items.length === 0) {
    alert('No items found in this order to generate POS.')
    return
  }

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

  // Create and download as Word document (.doc format)
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

// Navigation Functions
const goToMarket = () => {
  showHistoryModal.value = false
  router.visit('/om-landing')
}

const handleSignOut = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/signin')
  })
}

const goBack = () => {
  router.get('/om-landing')
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}

/* Ensure proper background */
.flex.flex-col.min-h-screen {
  background-color: #f3f4f6 !important;
}

/* Modal animations and backdrop */
.fixed[style*="backdrop-filter"] {
  /* Backdrop filter is handled in inline styles for better browser support */
}

/* Scrollbar styling for modal content */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Loading animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
