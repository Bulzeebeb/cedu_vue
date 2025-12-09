<template>
  <div class="bg-gray-50 min-h-screen">
    <SiteHeader :cart-count="cartCount" />

    <section class="py-8 md:py-12">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-3xl md:text-4xl font-bold text-maroon mb-2">
            Review Your Order
          </h1>
          <p class="text-gray-600 text-sm md:text-base">
            Complete your purchase by reviewing order details and providing delivery information
          </p>
        </div>

        <!-- Progress Indicator -->
        <div class="flex items-center justify-between mb-8 text-xs md:text-sm">
          <div class="flex items-center">
            <div class="bg-maroon text-white rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center font-bold">1</div>
            <span class="ml-2 font-semibold text-maroon">Review</span>
          </div>
          <div class="flex-1 h-1 bg-maroon mx-2 md:mx-4"></div>
          <div class="flex items-center">
            <div class="bg-yellow-500 text-maroon rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center font-bold">2</div>
            <span class="ml-2 font-semibold text-gray-400">Payment</span>
          </div>
        </div>

        <form @submit.prevent="placeOrder">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Forms -->
            <div class="lg:col-span-2 space-y-6">
              <!-- User Account Info -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                  </svg>
                  Account Information
                </h2>
                <div class="bg-gray-50 rounded p-4 border border-gray-100">
                  <p class="text-sm text-gray-600 mb-1">Email Address</p>
                  <p class="text-base font-medium text-gray-800">{{ user.email }}</p>
                </div>
              </div>

              <!-- Billing Information -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                  Billing Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">First Name *</label>
                    <input
                      v-model="billing.firstName"
                      type="text"
                      placeholder="Enter first name"
                      required
                      @blur="validateFirstName"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.firstName, 'border-gray-300': !errors.firstName}"
                    />
                    <p v-if="errors.firstName" class="text-xs text-red-600 mt-1">{{ errors.firstName }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Last Name *</label>
                    <input
                      v-model="billing.lastName"
                      type="text"
                      placeholder="Enter last name"
                      required
                      @blur="validateLastName"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.lastName, 'border-gray-300': !errors.lastName}"
                    />
                    <p v-if="errors.lastName" class="text-xs text-red-600 mt-1">{{ errors.lastName }}</p>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Contact Number *</label>
                  <input
                    v-model="billing.contact"
                    type="tel"
                    placeholder="09xxxxxxxxx"
                    maxlength="11"
                    required
                    @blur="validateContact"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    :class="{'border-red-500 bg-red-50': errors.contact, 'border-gray-300': !errors.contact}"
                  />
                  <p class="text-xs text-gray-500 mt-1">Format: 09123456789 (11 digits starting with 09)</p>
                  <p v-if="errors.contact" class="text-xs text-red-600 mt-1">{{ errors.contact }}</p>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Preferred Order Date *</label>
                  <input
                    v-model="billing.date"
                    type="date"
                    :min="todayDate"
                    required
                    @blur="validateDate"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    :class="{'border-red-500 bg-red-50': errors.date, 'border-gray-300': !errors.date}"
                  />
                  <p v-if="errors.date" class="text-xs text-red-600 mt-1">{{ errors.date }}</p>
                </div>
              </div>

              <!-- Delivery Address -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M12.075 10.812a27.77 27.77 0 01-7.807-1.969M12.075 10.812c1.497.991 2.783 2.031 3.779 3.188M12.075 10.812l1.563-3.125m-7.807 1.969c-.39.166-.756.35-1.098.553M5.268 12.753c.5.531.982 1.09 1.435 1.666m5.305-4.904l-1.563 3.125m-1.872 3.338c-.397.527-.8 1.04-1.214 1.537m4.535-2.875c1.08 1.156 2.02 2.45 2.79 3.878M7.268 13.419c.89-.99 1.7-2.041 2.376-3.159m3.431 4.814c.88.916 1.598 1.898 2.121 2.932m-4.552-2.932l2.376 3.159m-2.376-3.159c-.676 1.118-1.486 2.169-2.376 3.159m4.552 2.932c.523 1.034 1.241 2.016 2.121 2.932m-7.432-6.865c-.39.166-.756.35-1.098.553m1.098-.553l-1.563 3.125m8.307-7.557l1.563-3.125m-3.431 4.814l2.121 2.932m-5.305-4.904l1.872 3.338"></path>
                  </svg>
                  Delivery Address
                </h2>
                <div class="mb-4">
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Street Address *</label>
                  <input
                    v-model="delivery.street"
                    type="text"
                    placeholder="Enter street address"
                    required
                    @blur="validateStreet"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    :class="{'border-red-500 bg-red-50': errors.street, 'border-gray-300': !errors.street}"
                  />
                  <p v-if="errors.street" class="text-xs text-red-600 mt-1">{{ errors.street }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">City/Municipality *</label>
                    <input
                      v-model="delivery.city"
                      type="text"
                      placeholder="Enter city"
                      required
                      @blur="validateCity"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.city, 'border-gray-300': !errors.city}"
                    />
                    <p v-if="errors.city" class="text-xs text-red-600 mt-1">{{ errors.city }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Province/State *</label>
                    <input
                      v-model="delivery.province"
                      type="text"
                      placeholder="Enter province"
                      required
                      @blur="validateProvince"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.province, 'border-gray-300': !errors.province}"
                    />
                    <p v-if="errors.province" class="text-xs text-red-600 mt-1">{{ errors.province }}</p>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Postal Code *</label>
                    <input
                      v-model="delivery.postalCode"
                      type="text"
                      placeholder="Enter postal code"
                      required
                      @blur="validatePostalCode"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.postalCode, 'border-gray-300': !errors.postalCode}"
                    />
                    <p v-if="errors.postalCode" class="text-xs text-red-600 mt-1">{{ errors.postalCode }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Country</label>
                    <input
                      v-model="delivery.country"
                      type="text"
                      placeholder="Enter country"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    />
                  </div>
                </div>
              </div>

              <!-- Order Notes -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"></path>
                    <path d="M12.075 12.075h4.85A2.075 2.075 0 0019 14.15v4.85a2.075 2.075 0 01-2.075 2.075h-4.85a2.075 2.075 0 01-2.075-2.075v-4.85a2.075 2.075 0 012.075-2.075z"></path>
                  </svg>
                  Order Notes (Optional)
                </h2>
                <textarea
                  v-model="billing.notes"
                  placeholder="Add any special instructions or delivery notes..."
                  rows="3"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Include any special requests or delivery instructions</p>
              </div>

              <!-- Payment Method -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                  Payment Method
                </h2>
                <div class="space-y-3">
                  <label class="flex items-center p-3 border rounded-lg cursor-pointer transition" :class="billing.paymentMethod === 'cop' ? 'bg-yellow-50 border-yellow-500' : 'border-gray-300 hover:border-gray-400'">
                    <input
                      v-model="billing.paymentMethod"
                      type="radio"
                      value="cop"
                      class="w-4 h-4 text-maroon"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                      Cash on Pick-up (COP)
                    </span>
                    <span class="ml-auto text-xs text-gray-500">Pay at the CEDU office or University cashier.</span>
                  </label>
                  <label class="flex items-center p-3 border rounded-lg cursor-pointer transition" :class="billing.paymentMethod === 'cod' ? 'bg-yellow-50 border-yellow-500' : 'border-gray-300 hover:border-gray-400'">
                    <input
                      v-model="billing.paymentMethod"
                      type="radio"
                      value="cod"
                      class="w-4 h-4 text-maroon"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                      Cash on Delivery (COD) </span><span class="ml-1 text-xs text-gray-500">(Currently Not Available)
                    </span>
                    <span class="ml-auto text-xs text-gray-500">Pay when order arrives</span>
                  </label>
                  <label class="flex items-center p-3 border rounded-lg cursor-pointer transition" :class="billing.paymentMethod === 'gcash' ? 'bg-yellow-50 border-yellow-500' : 'border-gray-300 hover:border-gray-400'">
                    <input
                      v-model="billing.paymentMethod"
                      type="radio"
                      value="gcash"
                      class="w-4 h-4 text-maroon"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                      GCash / Online Payment <span class="ml-1 text-xs text-gray-500">(Currently Not Available)</span>
                    </span>
                    <span class="ml-auto text-xs text-gray-500">Pay now securely</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-1">
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                <h2 class="text-lg font-bold text-maroon mb-4">Order Summary</h2>

                <!-- Items List -->
                <div v-if="orderItems.length === 0" class="py-8 text-center text-gray-500">
                  <p class="text-sm">Your cart is empty.</p>
                </div>

                <div v-else class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                  <div
                    v-for="item in orderItems"
                    :key="item.id"
                    class="flex gap-3 pb-3 border-b"
                  >
                    <img
                      v-if="item.image"
                      :src="item.image"
                      :alt="item.title"
                      class="h-12 w-12 object-cover rounded-lg flex-shrink-0"
                    />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800 truncate">{{ item.title }}</p>
                      <p class="text-xs text-gray-500">
                        {{ item.qty }} × {{ item.metric }} @ ₱{{ parseFloat(item.price).toFixed(2) }}
                      </p>
                    </div>
                    <p class="text-sm font-semibold text-maroon whitespace-nowrap">
                      ₱{{ (item.qty * parseFloat(item.price)).toFixed(2) }}
                    </p>
                  </div>
                </div>

                <!-- Pricing Summary -->
                <div v-if="orderItems.length > 0" class="space-y-3 pt-4 border-t">
                  <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span>₱{{ subtotal }}</span>
                  </div>
                  <div class="flex justify-between text-sm text-gray-600">
                    <span>Delivery Fee:</span>
                    <span>₱{{ deliveryFee }}</span>
                  </div>
                  <div v-if="discount > 0" class="flex justify-between text-sm text-green-600 font-medium">
                    <span>Discount:</span>
                    <span>-₱{{ discount }}</span>
                  </div>
                  <div class="flex justify-between text-base font-bold text-maroon pt-3 border-t">
                    <span>Total:</span>
                    <span>₱{{ totalAmount }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
            <button
              type="button"
              @click="goBackToCart"
              class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors text-sm"
            >
              ← Back to Cart
            </button>

            <button
              type="submit"
              :disabled="isPlacingOrder || orderItems.length === 0 || !isFormValid"
              class="px-6 py-3 rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-500 text-maroon font-bold hover:shadow-lg transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isPlacingOrder" class="flex items-center justify-center">
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Processing...
              </span>
              <span v-else>Proceed to Payment</span>
            </button>
          </div>
        </form>
      </div>
    </section>

    <SiteFooter />
  </div>

  <!-- POS Modal rendered outside main structure -->
  <Teleport to="body">
    <OnlineMartPOS
      :order-data="generatedOrderData"
      :is-open="showPOSModal"
      @close="handlePOSModalClose"
    />
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, Teleport } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
import OnlineMartPOS from './onlineMart_pos.vue'
import { router } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  orderItems: {
    type: Array,
    default: () => []
  },
  totalPrice: {
    type: String,
    default: '0.00'
  },
  user: {
    type: Object,
    required: true
  },
  cartCount: {
    type: Number,
    default: 0
  }
})

// State
const isPlacingOrder = ref(false)
const showPOSModal = ref(false)
const generatedOrderData = ref({
  orderId: '',
  customerName: '',
  orderDate: '',
  items: []
})

const billing = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: new Date().toISOString().substr(0, 10),
  notes: '',
  paymentMethod: 'cop'
})

const delivery = ref({
  street: '',
  city: '',
  province: '',
  postalCode: '',
  country: 'Philippines'
})

const errors = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: '',
  street: '',
  city: '',
  province: '',
  postalCode: ''
})

// Computed properties
const todayDate = computed(() => {
  return new Date().toISOString().substr(0, 10)
})

const subtotal = computed(() => {
  return parseFloat(props.totalPrice).toFixed(2)
})

const deliveryFee = computed(() => {
  return '0.00'
})

const discount = computed(() => {
  return '0.00'
})

const totalAmount = computed(() => {
  const sub = parseFloat(subtotal.value)
  const delivery = parseFloat(deliveryFee.value)
  const disc = parseFloat(discount.value)
  return (sub + delivery - disc).toFixed(2)
})

const isFormValid = computed(() => {
  return !Object.values(errors.value).some(err => err !== '')
})

// Validation functions
function validateFirstName() {
  if (!billing.value.firstName.trim()) {
    errors.value.firstName = 'First name is required'
  } else if (billing.value.firstName.trim().length < 2) {
    errors.value.firstName = 'First name must be at least 2 characters'
  } else {
    errors.value.firstName = ''
  }
}

function validateLastName() {
  if (!billing.value.lastName.trim()) {
    errors.value.lastName = 'Last name is required'
  } else if (billing.value.lastName.trim().length < 2) {
    errors.value.lastName = 'Last name must be at least 2 characters'
  } else {
    errors.value.lastName = ''
  }
}

function validateContact() {
  const contactPattern = /^09\d{9}$/
  if (!billing.value.contact) {
    errors.value.contact = 'Contact number is required'
  } else if (!contactPattern.test(billing.value.contact)) {
    errors.value.contact = 'Invalid format. Use 09xxxxxxxxx (11 digits)'
  } else {
    errors.value.contact = ''
  }
}

function validateDate() {
  const selectedDate = new Date(billing.value.date)
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  if (!billing.value.date) {
    errors.value.date = 'Preferred date is required'
  } else if (selectedDate < today) {
    errors.value.date = 'Cannot select past dates'
  } else {
    errors.value.date = ''
  }
}

function validateStreet() {
  if (!delivery.value.street.trim()) {
    errors.value.street = 'Street address is required'
  } else if (delivery.value.street.trim().length < 5) {
    errors.value.street = 'Street address is too short'
  } else {
    errors.value.street = ''
  }
}

function validateCity() {
  if (!delivery.value.city.trim()) {
    errors.value.city = 'City is required'
  } else if (delivery.value.city.trim().length < 2) {
    errors.value.city = 'City name is invalid'
  } else {
    errors.value.city = ''
  }
}

function validateProvince() {
  if (!delivery.value.province.trim()) {
    errors.value.province = 'Province is required'
  } else if (delivery.value.province.trim().length < 2) {
    errors.value.province = 'Province name is invalid'
  } else {
    errors.value.province = ''
  }
}

function validatePostalCode() {
  if (!delivery.value.postalCode.trim()) {
    errors.value.postalCode = 'Postal code is required'
  } else if (!/^\d{4}$/.test(delivery.value.postalCode)) {
    errors.value.postalCode = 'Postal code must be 4 digits'
  } else {
    errors.value.postalCode = ''
  }
}

function validateAllFields() {
  validateFirstName()
  validateLastName()
  validateContact()
  validateDate()
  validateStreet()
  validateCity()
  validateProvince()
  validatePostalCode()

  return !Object.values(errors.value).some(err => err !== '')
}

// Navigation functions
function goBackToCart() {
  router.visit('/cart')
}

function handlePOSModalClose() {
  console.log('POS Modal closed')
  showPOSModal.value = false
}

function placeOrder() {
  if (!validateAllFields()) {
    alert('Please correct the errors in the form.')
    return
  }

  if (props.orderItems.length === 0) {
    alert('Your cart is empty.')
    return
  }

  isPlacingOrder.value = true

  // Prepare order data
  const orderData = {
    orderId: 'ORD-' + Date.now(),
    customerName: `${billing.value.firstName} ${billing.value.lastName}`,
    orderDate: billing.value.date,
    items: props.orderItems.map(item => ({
      title: item.title,
      qty: item.qty,
      price: item.price,
      metric: item.metric
    }))
  }

  console.log('Placing order with data:', orderData)

  // Set the order data
  generatedOrderData.value = orderData

  // Show the modal
  showPOSModal.value = true

  console.log('showPOSModal set to:', showPOSModal.value)

  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

  // Submit order to backend
  fetch('/checkout', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': csrfToken || '',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
      firstName: billing.value.firstName,
      lastName: billing.value.lastName,
      contact: billing.value.contact,
      date: billing.value.date,
      notes: billing.value.notes,
      paymentMethod: billing.value.paymentMethod,
      street: delivery.value.street,
      city: delivery.value.city,
      province: delivery.value.province,
      postalCode: delivery.value.postalCode,
      country: delivery.value.country
    })
  })
    .then(response => {
      console.log('Response status:', response.status)
      console.log('Response headers:', response.headers.get('content-type'))

      // Check if response is JSON
      const contentType = response.headers.get('content-type')
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Server returned non-JSON response. Please check your server configuration.')
      }

      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.error || `HTTP error! status: ${response.status}`)
        })
      }

      return response.json()
    })
    .then(data => {
      console.log('Order placed successfully:', data)
      if (data.orderId) {
        generatedOrderData.value.orderId = data.orderId
      }
    })
    .catch(error => {
      console.error('Order placement failed:', error)
      showPOSModal.value = false
      alert('Order placement failed: ' + error.message + '. Please try again.')
    })
    .finally(() => {
      isPlacingOrder.value = false
    })
}

// Initialize form
onMounted(() => {
  billing.value.date = todayDate.value

  if (props.user) {
    billing.value.firstName = props.user.firstName || props.user.first_name || ''
    billing.value.lastName = props.user.lastName || props.user.last_name || ''
    billing.value.contact = props.user.contactNum ||
                           props.user.contact_num ||
                           props.user.contact ||
                           props.user.phone ||
                           props.user.contact_number ||
                           ''
  }
})
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}

.from-yellow-400 {
  --tw-gradient-from: #FBBF24;
}

.to-yellow-500 {
  --tw-gradient-to: #EAB308;
}

/* Smooth scrollbar for items list */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
