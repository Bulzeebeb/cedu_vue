<template>
  <div>
    <SiteHeader :cart-count="orderItems.length" />

    <section class="py-12 bg-gray-100">
      <div class="max-w-3xl mx-auto px-4">
        <!-- Page Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-maroon mb-3 text-center">
          Confirm Your Order
        </h1>

        <p class="text-gray-600 mb-6 text-center text-sm md:text-base">
          Please review your order and fill out your details before placing your order.
        </p>

        <!-- Billing Info -->
        <div class="bg-white shadow rounded-lg p-5 mb-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-maroon mb-3">Billing Information</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
              <input
                v-model="billing.firstName"
                type="text"
                placeholder="First name"
                class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800"
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
              <input
                v-model="billing.lastName"
                type="text"
                placeholder="Last name"
                class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800"
              />
            </div>
          </div>

          <div class="mb-3">
            <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
            <input
              v-model="billing.contact"
              type="text"
              placeholder="09xxxxxxxxx"
              class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Order Date</label>
            <input
              v-model="billing.date"
              type="date"
              class="w-full border border-gray-300 rounded px-3 py-2 text-xs text-gray-800"
            />
          </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white shadow rounded-lg p-5 mb-6 border border-gray-200">
          <h2 class="text-lg font-semibold text-maroon mb-3">Order Summary</h2>

          <ul class="divide-y">
            <li
              v-for="item in orderItems"
              :key="item.id"
              class="flex justify-between py-2 text-sm text-gray-800"
            >
              <span>{{ item.title }} {{ item.qty }} {{ item.metric }}</span>
              <span class="font-semibold text-maroon">
                ₱{{ (item.qty * parseFloat(item.price)).toFixed(2) }}
              </span>
            </li>
          </ul>

          <div class="mt-4 border-t pt-4 flex justify-between font-bold text-maroon text-base">
            <span>Total:</span>
            <span>₱{{ totalPrice }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col md:flex-row justify-center md:justify-end gap-3">
          <button
            @click="goBackToCart"
            class="bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded hover:bg-gray-300 text-xs md:text-sm"
          >
            Back to Cart
          </button>

          <button
            @click="placeOrder"
            class="bg-yellow-500 text-maroon font-bold px-5 py-2 rounded hover:brightness-90 text-xs md:text-sm"
          >
            Place Order
          </button>
        </div>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
import { router } from '@inertiajs/vue3'

// ✅ Example cart
const orderItems = ref([
  { id: 1, title: 'Apple', qty: 2, price: '120.00', metric: 'kilo' },
  { id: 2, title: 'Pewee Eggs', qty: 1, price: '140.00', metric: 'tray' },
  { id: 3, title: 'Chicken Dung', qty: 1, price: '50.00', metric: 'sack' },
  { id: 4, title: 'Culled Chicken', qty: 2, price: '180.00', metric: 'pc (live)' }
])

// ✅ Billing details
const billing = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: new Date().toISOString().substr(0, 10)
})

// ✅ Total
const totalPrice = computed(() =>
  orderItems.value.reduce((sum, item) => sum + item.qty * parseFloat(item.price), 0).toFixed(2)
)

// ✅ Navigation
function goBackToCart() {
  router.visit('/cart')
}

function placeOrder() {
  if (!billing.value.firstName || !billing.value.lastName || !billing.value.contact) {
    alert('Please complete your billing information.')
    return
  }

  alert(
    `Order Placed!\nName: ${billing.value.firstName} ${billing.value.lastName}\nContact: ${billing.value.contact}\nDate: ${billing.value.date}\nTotal: ₱${totalPrice.value}`
  )
  router.visit('/')
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}
.bg-maroon {
  background-color: #651818;
}
</style>
