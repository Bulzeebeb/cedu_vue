<template>
  <div>
    <SiteHeader />

    <section class="py-12 bg-gray-100">
      <div class="max-w-6xl mx-auto px-4 space-y-6">
        <!-- Page Title -->
        <div class="text-3xl font-bold text-maroon">Your Cart</div>

        <!-- Cart Items Table -->
        <div v-if="cart.length > 0" class="bg-white shadow rounded-lg overflow-x-auto">
          <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-maroon text-white">
              <tr>
                <th class="px-4 py-2 text-left">Product</th>
                <th class="px-4 py-2 text-left">Unit Price</th>
                <th class="px-4 py-2 text-left">Quantity</th>
                <th class="px-4 py-2 text-left">Subtotal</th>
                <th class="px-4 py-2 text-left">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in cart"
                :key="item.id"
                class="border-b hover:bg-gray-50 transition"
              >
                <td class="px-4 py-3 flex items-center gap-4">
                  <img
                    v-if="item.image"
                    :src="item.image"
                    :alt="item.title"
                    class="h-12 w-12 object-cover rounded"
                  />
                  <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs" v-else>
                    No Image
                  </div>
                  <div>
                    <div class="font-semibold">{{ item.title }}</div>
                    <div class="text-gray-500 text-xs">/ {{ item.metric }}</div>
                    <div v-if="item.stock <= 5" class="text-red-500 text-xs">
                      Only {{ item.stock }} left in stock
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3">₱{{ parseFloat(item.price).toFixed(2) }}</td>
                <td class="px-4 py-3">
                  <form @submit.prevent="updateQuantity(item.id, item.qty)" class="inline">
                    <input
                      type="number"
                      min="1"
                      :max="item.stock"
                      v-model.number="item.qty"
                      @change="updateQuantity(item.id, item.qty)"
                      class="w-16 border border-gray-300 rounded px-2 py-1 text-sm text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    />
                  </form>
                  <div class="text-xs text-gray-500 mt-1">Max: {{ item.stock }}</div>
                </td>
                <td class="px-4 py-3 font-semibold">₱{{ subtotal(item) }}</td>
                <td class="px-4 py-3">
                  <form @submit.prevent="removeItem(item.id)" class="inline">
                    <button
                      type="submit"
                      class="text-red-600 hover:text-red-800 text-xs font-bold hover:underline transition-colors"
                    >
                      Remove
                    </button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty Cart Message -->
        <div v-else class="text-center py-12 text-gray-500">
          <div class="mb-4">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10c4 0 6-1.8 6-4s-2-4-6-4H5.4m0 0L7 13m0 0l1.8 8.2c.2.9 1 1.8 2.2 1.8h8c1.2 0 2-.9 2.2-1.8L19 13" />
            </svg>
          </div>
          <p class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</p>
          <p class="text-gray-500 mb-4">Start shopping to add items to your cart</p>
          <button
            @click="returnToShop"
            class="bg-maroon text-white font-bold px-6 py-2 rounded hover:bg-[#4d1212] transition-colors"
          >
            Start Shopping
          </button>
        </div>

        <!-- Cart Summary & Actions -->
        <div
          v-if="cart.length > 0"
          class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-lg shadow"
        >
          <div class="text-xl font-bold text-maroon">
            Total: ₱{{ totalPrice }}
          </div>
          <div class="flex gap-4">
            <button
              class="bg-yellow-500 text-maroon font-bold px-6 py-2 rounded hover:shadow hover:-translate-y-0.5 transition-all duration-200 hover:brightness-110"
              @click="proceedToCheckout"
            >
              Proceed to Checkout
            </button>
            <button
              class="bg-maroon text-white font-bold px-6 py-2 rounded hover:bg-[#4d1212] transition-colors"
              @click="returnToShop"
            >
              Continue Shopping
            </button>
          </div>
        </div>

        <!-- Additional Cart Actions -->
        <div v-if="cart.length > 0" class="text-center">
          <button
            @click="clearCart"
            class="text-red-600 hover:text-red-800 text-sm underline transition-colors"
          >
            Clear entire cart
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

const props = defineProps({
  cart: {
    type: Array,
    default: () => []
  }
})

const totalPrice = computed(() => {
  return props.cart
    .reduce((sum, item) => {
      return sum + parseFloat(item.price) * item.qty
    }, 0)
    .toFixed(2)
})

function subtotal(item) {
  return (parseFloat(item.price) * item.qty).toFixed(2)
}

function updateQuantity(productId, quantity) {
  if (quantity < 1) {
    alert('Quantity must be at least 1')
    return
  }

  router.post('/cart/update', {
    product_id: productId,
    quantity: quantity
  }, {
    preserveState: true,
    preserveScroll: true,
    onError: (errors) => {
      console.error('Update failed:', errors)
    }
  })
}

function removeItem(productId) {
  if (confirm('Are you sure you want to remove this item from your cart?')) {
    router.post('/cart/remove', {
      product_id: productId
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

function clearCart() {
  if (confirm('Are you sure you want to clear your entire cart?')) {
    router.post('/cart/clear', {}, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

function proceedToCheckout() {
  router.visit(route('checkout.index')) // safer with named routes
}

function returnToShop() {
  router.visit('/om-landing')
}
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
.text-maroon {
  color: #651818;
}
</style>
