<template>
  <div>
    <SiteHeader :cart-count="cartCount" />

    <section class="py-12 bg-gray-100">
      <div class="max-w-3xl mx-auto px-4">
        <!-- Page Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-maroon mb-3 text-center">
          Confirm Your Order
        </h1>

        <p class="text-gray-600 mb-6 text-center text-sm md:text-base">
          Please review your order and fill out your details before placing your order.
        </p>

        <!-- User Information Display -->
        <div class="bg-white shadow rounded-lg p-5 mb-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-maroon mb-3">Account Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
              <p class="text-sm text-gray-800 bg-gray-50 p-2 rounded">{{ user.email }}</p>
            </div>
          </div>
        </div>

        <!-- Billing Info -->
        <form @submit.prevent="placeOrder">
          <div class="bg-white shadow rounded-lg p-5 mb-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-maroon mb-3">Billing Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">First Name *</label>
                <input
                  v-model="billing.firstName"
                  type="text"
                  placeholder="First name"
                  required
                  class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Last Name *</label>
                <input
                  v-model="billing.lastName"
                  type="text"
                  placeholder="Last name"
                  required
                  class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="mb-3">
              <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number *</label>
              <input
                v-model="billing.contact"
                type="text"
                placeholder="09xxxxxxxxx"
                pattern="[0-9]{11}"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
              />
              <p class="text-xs text-gray-500 mt-1">Format: 09123456789</p>
            </div>

            <div class="mb-4">
              <label class="block text-xs font-medium text-gray-700 mb-1">Preferred Order Date *</label>
              <input
                v-model="billing.date"
                type="date"
                :min="todayDate"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
              />
            </div>
          </div>

        <!-- Order Summary -->
        <div class="bg-white shadow rounded-lg p-5 mb-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-maroon mb-3">Order Summary</h2>

          <div v-if="orderItems.length === 0" class="text-center py-8 text-gray-500">
            Your cart is empty.
          </div>

          <ul v-else class="divide-y">
            <li
              v-for="item in orderItems"
              :key="item.id"
              class="flex justify-between py-3 text-sm text-gray-800"
            >
              <div class="flex items-center gap-3">
                <img
                  v-if="item.image"
                  :src="item.image"
                  :alt="item.title"
                  class="h-10 w-10 object-cover rounded"
                />
                <div>
                  <span class="font-medium">{{ item.title }}</span>
                  <div class="text-xs text-gray-500">
                    {{ item.qty }} {{ item.metric }} × ₱{{ parseFloat(item.price).toFixed(2) }}
                  </div>
                </div>
              </div>
              <span class="font-semibold text-maroon">
                ₱{{ (item.qty * parseFloat(item.price)).toFixed(2) }}
              </span>
            </li>
          </ul>

          <div v-if="orderItems.length > 0" class="mt-4 border-t pt-4">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Subtotal:</span>
              <span class="text-sm">₱{{ totalPrice }}</span>
            </div>
            <div class="flex justify-between items-center mt-2 font-bold text-maroon text-lg">
              <span>Total:</span>
              <span>₱{{ totalPrice }}</span>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col md:flex-row justify-center md:justify-end gap-3">
          <button
            type="button"
            @click="goBackToCart"
            class="bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded hover:bg-gray-300 transition-colors text-xs md:text-sm"
          >
            Back to Cart
          </button>

          <button
            type="submit"
            :disabled="isPlacingOrder || orderItems.length === 0"
            class="bg-yellow-500 text-maroon font-bold px-5 py-2 rounded hover:brightness-90 transition-all text-xs md:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isPlacingOrder">Placing Order...</span>
            <span v-else>Place Order</span>
          </button>
        </div>
        </form>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
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

// Billing details with user info pre-filled where appropriate
const billing = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: new Date().toISOString().substr(0, 10)
})

// Get today's date for minimum date validation
const todayDate = computed(() => {
  return new Date().toISOString().substr(0, 10)
})

// Navigation functions
function goBackToCart() {
  router.visit('/cart')
}

function placeOrder() {
  if (!billing.value.firstName || !billing.value.lastName || !billing.value.contact) {
    alert('Please complete your billing information.')
    return
  }

  // Validate contact number format
  const contactPattern = /^09\d{9}$/
  if (!contactPattern.test(billing.value.contact)) {
    alert('Please enter a valid contact number (09xxxxxxxxx)')
    return
  }

  if (props.orderItems.length === 0) {
    alert('Your cart is empty.')
    return
  }

  isPlacingOrder.value = true

  // Submit the order
  router.post('/checkout', {
    firstName: billing.value.firstName,
    lastName: billing.value.lastName,
    contact: billing.value.contact,
    date: billing.value.date
  }, {
    onSuccess: () => {
      // The controller will handle redirecting to POS page
      // No need to manually redirect here
    },
    onFinish: () => {
      isPlacingOrder.value = false
    },
    onError: (errors) => {
      console.error('Order placement failed:', errors)
      isPlacingOrder.value = false
      alert('Order placement failed. Please try again.')
    }
  })
}

// Initialize with current date and user data
onMounted(() => {
  billing.value.date = todayDate.value

  // Pre-fill user information if available
  if (props.user) {
    // Split full name if available
    if (props.user.full_name) {
      const nameParts = props.user.full_name.trim().split(' ')
      billing.value.firstName = nameParts[0] || ''
      billing.value.lastName = nameParts.slice(1).join(' ') || ''
    } else {
      // Try individual fields
      billing.value.firstName = props.user.first_name || props.user.firstName || ''
      billing.value.lastName = props.user.last_name || props.user.lastName || ''
    }

    // Pre-fill contact number
    billing.value.contact = props.user.contact || props.user.phone || props.user.contact_number || ''
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
</style>
