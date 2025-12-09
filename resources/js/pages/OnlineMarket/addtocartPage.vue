<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <SiteHeader />

    <section class="flex-grow py-8 md:py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
          <p class="text-gray-600">
            <span v-if="cart.length > 0">{{ cart.length }} item{{ cart.length !== 1 ? 's' : '' }} in your cart</span>
            <span v-else>Your cart is empty</span>
          </p>
        </div>

        <!-- Main Content -->
        <div v-if="cart.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Cart Items Section -->
          <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
              <!-- Cart Items -->
              <div class="divide-y divide-gray-200">
                <div
                  v-for="item in cart"
                  :key="item.id"
                  class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200"
                >
                  <div class="flex gap-4 sm:gap-6">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                      <div class="relative w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                        <img
                          v-if="item.image"
                          :src="item.image"
                          :alt="item.title"
                          class="w-full h-full object-cover"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-xs text-center p-2">
                          No Image
                        </div>
                      </div>
                    </div>

                    <!-- Product Details -->
                    <div class="flex-grow">
                      <div class="flex justify-between items-start mb-2">
                        <div>
                          <h3 class="text-lg font-semibold text-gray-900">{{ item.title }}</h3>
                          <p class="text-sm text-gray-500 mt-1">{{ item.metric }}</p>
                        </div>
                        <button
                          @click="removeItem(item.id)"
                          class="text-gray-400 hover:text-red-600 transition-colors"
                          title="Remove item"
                        >
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>

                      <!-- Stock Status -->
                      <div v-if="item.stock <= 5" class="mb-3 inline-block">
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1.5 rounded">
                          Only {{ item.stock }} left in stock
                        </span>
                      </div>

                      <!-- Price and Quantity Controls -->
                      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-4">
                        <div class="flex items-center gap-4">
                          <div>
                            <p class="text-sm text-gray-600">Unit Price</p>
                            <p class="text-xl font-bold text-maroon">₱{{ parseFloat(item.price).toFixed(2) }}</p>
                          </div>
                        </div>

                        <div class="flex items-center gap-3">
                          <label class="text-sm text-gray-600">Qty:</label>
                          <div class="flex items-center border border-gray-300 rounded-lg">
                            <button
                              @click="decrementQuantity(item)"
                              :disabled="item.qty <= 1"
                              class="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                              −
                            </button>
                            <input
                              type="text"
                              inputmode="numeric"
                              v-model="item.qty"
                              @input="handleQuantityInput(item, $event)"
                              @blur="validateAndUpdate(item)"
                              @keypress="onlyNumbers($event)"
                              @keyup.enter="validateAndUpdate(item)"
                              class="w-16 text-center py-2 border-l border-r border-gray-300 text-gray-900 focus:outline-none no-arrows"
                            />
                            <button
                              @click="incrementQuantity(item)"
                              :disabled="item.qty >= item.stock"
                              class="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                              +
                            </button>
                          </div>
                        </div>

                        <div class="text-right">
                          <p class="text-sm text-gray-600">Subtotal</p>
                          <p class="text-2xl font-bold text-maroon">₱{{ subtotal(item) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Continue Shopping Button -->
            <button
              @click="returnToShop"
              class="mt-6 text-maroon hover:text-[#4d1212] font-medium text-sm transition-colors flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Continue Shopping
            </button>
          </div>

          <!-- Order Summary Section -->
          <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 sticky top-24">
              <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

              <!-- Summary Details -->
              <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                <div class="flex justify-between text-gray-600">
                  <span>Subtotal</span>
                  <span>₱{{ subtotalAmount }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                  <span class="italic text-sm">Note: Pick-up your order at the USeP CEDU office.</span>
                </div>
                <div v-if="taxAmount > 0" class="flex justify-between text-gray-600">
                  <span>Tax ({{ taxRate }}%)</span>
                  <span>₱{{ taxAmount }}</span>
                </div>
              </div>

              <!-- Total -->
              <div class="mb-6 p-4 bg-maroon bg-opacity-5 rounded-lg">
                <div class="flex justify-between items-center">
                  <span class="text-lg font-semibold text-white">Total</span>
                  <span class="text-3xl font-bold text-white">₱{{ totalPrice }}</span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="space-y-3">
                <button
                  @click="proceedToCheckout"
                  class="w-full bg-maroon text-white font-bold px-6 py-3 rounded-lg hover:bg-[#4d1212] transition-all duration-200 hover:shadow-lg"
                >
                  Proceed to Checkout
                </button>
                <button
                  @click="clearCart"
                  class="w-full bg-red-50 text-red-600 font-semibold px-6 py-2 rounded-lg hover:bg-red-100 transition-colors"
                >
                  Clear Cart
                </button>
              </div>

              <!-- Security Badge -->
              <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-center gap-2 text-xs text-gray-500">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Secure Checkout
              </div>
            </div>
          </div>
        </div>

        <!-- Empty Cart State -->
        <div v-else class="text-center py-16">
          <div class="mb-6">
            <svg class="mx-auto h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10c4 0 6-1.8 6-4s-2-4-6-4H5.4m0 0L7 13m0 0l1.8 8.2c.2.9 1 1.8 2.2 1.8h8c1.2 0 2-.9 2.2-1.8L19 13" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
          <p class="text-gray-500 mb-8">Start shopping to add items to your cart</p>
          <button
            @click="returnToShop"
            class="inline-flex items-center gap-2 bg-maroon text-white font-bold px-8 py-3 rounded-lg hover:bg-[#4d1212] transition-all duration-200 hover:shadow-lg"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Start Shopping
          </button>
        </div>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script setup>
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
  cart: {
    type: Array,
    default: () => []
  }
})

// Constants
const TAX_RATE = 0 // Set to 0 if no tax, adjust as needed
const SHIPPING_COST = 0 // Free shipping

// Computed Properties
const subtotalAmount = computed(() => {
  return props.cart
    .reduce((sum, item) => sum + parseFloat(item.price) * item.qty, 0)
    .toFixed(2)
})

const taxAmount = computed(() => {
  return (parseFloat(subtotalAmount.value) * (TAX_RATE / 100)).toFixed(2)
})

const totalPrice = computed(() => {
  return (parseFloat(subtotalAmount.value) + parseFloat(taxAmount.value) + SHIPPING_COST).toFixed(2)
})

const taxRate = TAX_RATE

// Methods
function subtotal(item) {
  return (parseFloat(item.price) * item.qty).toFixed(2)
}

function onlyNumbers(event) {
  const charCode = event.which ? event.which : event.keyCode
  if (charCode < 48 || charCode > 57) {
    event.preventDefault()
  }
}

function handleQuantityInput(item, event) {
  let value = event.target.value
  value = value.replace(/[^0-9]/g, '')

  if (value === '') {
    item.qty = ''
    return
  }

  let numValue = parseInt(value, 10)

  if (numValue > item.stock) {
    numValue = item.stock
    event.target.value = numValue
  }

  if (numValue < 1) {
    numValue = 1
    event.target.value = numValue
  }

  item.qty = numValue
}

function validateAndUpdate(item) {
  let quantity = item.qty

  if (!quantity || quantity === '' || isNaN(quantity)) {
    quantity = 1
  } else {
    quantity = parseInt(quantity, 10)
  }

  if (quantity < 1) {
    quantity = 1
  }

  if (quantity > item.stock) {
    quantity = item.stock
    alert(`Maximum available stock for this item is ${item.stock}`)
  }

  item.qty = quantity
  updateQuantity(item.id, quantity)
}

function incrementQuantity(item) {
  if (item.qty < item.stock) {
    item.qty = parseInt(item.qty, 10) + 1
    updateQuantity(item.id, item.qty)
  }
}

function decrementQuantity(item) {
  if (item.qty > 1) {
    item.qty = parseInt(item.qty, 10) - 1
    updateQuantity(item.id, item.qty)
  }
}

function updateQuantity(productId, quantity) {
  router.post('/cart/update', {
    product_id: productId,
    quantity: quantity
  }, {
    preserveState: true,
    preserveScroll: true,
    onError: (errors) => {
      console.error('Update failed:', errors)
      alert('Failed to update quantity. Please try again.')
    }
  })
}

function removeItem(productId) {
  Swal.fire({
    title: 'Remove Item?',
    text: 'Are you sure you want to remove this item from your cart?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#651818',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, remove it',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      router.post('/cart/remove', {
        product_id: productId
      }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            title: 'Removed!',
            text: 'Item has been removed from your cart.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          })
        },
        onError: (errors) => {
          Swal.fire({
            title: 'Error!',
            text: 'Failed to remove item. Please try again.',
            icon: 'error'
          })
        }
      })
    }
  })
}

function clearCart() {
  Swal.fire({
    title: 'Clear Cart?',
    text: 'Are you sure you want to clear your entire cart? This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DC2626',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, clear it',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      router.post('/cart/clear', {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            title: 'Cleared!',
            text: 'Your cart has been cleared.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          })
        },
        onError: (errors) => {
          Swal.fire({
            title: 'Error!',
            text: 'Failed to clear cart. Please try again.',
            icon: 'error'
          })
        }
      })
    }
  })
}

function proceedToCheckout() {
  router.visit(route('checkout.index'))
}

function returnToShop() {
  router.visit('/onlinemarketlandingpageclient')
}
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}

.text-maroon {
  color: #651818;
}

.no-arrows::-webkit-outer-spin-button,
.no-arrows::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.no-arrows[type=number] {
  -moz-appearance: textfield;
  appearance: textfield;
}
</style>
