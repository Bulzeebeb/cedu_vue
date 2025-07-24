<template>
  <div>
    <SiteHeader :cart-count="cart.length" />

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
                v-for="(item, index) in cart"
                :key="item.id"
                class="border-b hover:bg-gray-50 transition"
              >
                <td class="px-4 py-3 flex items-center gap-4">
                  <img :src="item.image" alt="" class="h-12 w-12 object-cover rounded" />
                  <div>
                    <div class="font-semibold">{{ item.title }}</div>
                    <div class="text-gray-500 text-xs">/ {{ item.metric }}</div>
                  </div>
                </td>
                <td class="px-4 py-3">₱{{ item.price }}</td>
                <td class="px-4 py-3">
                  <input
                    type="number"
                    min="1"
                    v-model.number="item.qty"
                    class="w-16 border border-gray-300 rounded px-2 py-1 text-sm text-gray-800"
                  />
                </td>
                <td class="px-4 py-3 font-semibold">₱{{ subtotal(item) }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="removeItem(index)"
                    class="text-red-600 hover:text-red-800 text-xs font-bold"
                  >
                    Remove
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty Cart Message -->
        <div v-else class="text-center py-12 text-gray-500">
          Your cart is empty.
        </div>

        <!-- Cart Summary & Actions -->
        <div
          v-if="cart.length > 0"
          class="flex flex-col md:flex-row justify-between items-center gap-4"
        >
          <div class="text-xl font-bold text-maroon">
            Total: ₱{{ totalPrice }}
          </div>
          <div class="flex gap-4">
            <button
              class="bg-yellow-500 text-maroon font-bold px-6 py-2 rounded hover:shadow hover:-translate-y-0.5 transition"
              @click="proceedToCheckout"
            >
              Proceed to Checkout
            </button>
            <button
              class="bg-maroon text-white font-bold px-6 py-2 rounded hover:bg-[#4d1212] transition"
              @click="returnToShop"
            >
              Return to Shop
            </button>
          </div>
        </div>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script>
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

export default {
  name: 'AddToCartPage',
  components: {
    SiteHeader,
    SiteFooter,
  },
  data() {
    return {
      cart: [
        {
          id: 1,
          title: 'Fresh Broccoli',
          price: '99.00',
          qty: 2,
          metric: 'kilo',
          image:
            'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80',
        },
        {
          id: 2,
          title: 'Pewee Eggs',
          price: '140.00',
          qty: 1,
          metric: 'tray',
          image:
            'https://images.unsplash.com/photo-1587049352841-4e60ec6dca1d?auto=format&fit=crop&w=800&q=80',
        },
      ],
    }
  },
  computed: {
    totalPrice() {
      return this.cart
        .reduce((sum, item) => {
          return sum + parseFloat(item.price) * item.qty
        }, 0)
        .toFixed(2)
    },
  },
  methods: {
    subtotal(item) {
      return (parseFloat(item.price) * item.qty).toFixed(2)
    },
    removeItem(index) {
      this.cart.splice(index, 1)
    },
    proceedToCheckout() {
      alert('Proceeding to checkout...')
    },
    returnToShop() {
      this.$router.push('/') // Or your shop route
    },
  },
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
