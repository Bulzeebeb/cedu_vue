<template>
  <div>
    <div class="pt-24 px-4"></div>
    <SiteHeader :cart-count="cartCount" @search="handleSearch" />

    <!-- Shop Page Content -->
    <section class="py-12 bg-gray-100">
      <div class="max-w-6xl mx-auto px-4 space-y-6">
        <!-- Banner -->
        <div
          class="bg-cover bg-center rounded-xl shadow-md text-center relative overflow-hidden h-28 md:h-38"
          style="background-image: url('images/OnlineMarket/fruits_banner.png');"
        >
          <div class="relative flex items-center justify-start h-full p-8">
            <h2 class="text-6xl font-bold text-white text-left">FRUITS</h2>
          </div>
        </div>

        <!-- Search Results Info -->
        <div v-if="searchQuery" class="text-sm text-gray-600">
          <p>Showing results for: <span class="font-semibold">"{{ searchQuery }}"</span></p>
          <p>{{ filteredProducts.length }} product(s) found</p>
        </div>

        <!-- Sorting -->
        <div class="flex justify-end items-center gap-4 text-gray-600 text-sm">
          <label class="flex items-center gap-2">
            Sort by:
            <select v-model="sortBy" class="border border-gray-300 rounded px-2 py-1 text-sm">
              <option value="asc">Price (low - high)</option>
              <option value="desc">Price (high - low)</option>
            </select>
          </label>
          <label class="flex items-center gap-2">
            Show:
            <select v-model="itemsToShow" class="border border-gray-300 rounded px-2 py-1 text-sm">
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
            </select>
            Items
          </label>
        </div>

        <!-- Products -->
        <div v-if="sortedProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <div
            v-for="product in sortedProducts"
            :key="product.id"
            class="bg-white rounded-2xl shadow-sm overflow-hidden transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer"
            @click="viewProduct(product.id)"
          >
            <div class="relative">
              <img
                :src="product.image"
                :alt="product.name"
                class="w-full h-52 object-cover transition duration-500 transform hover:scale-105"
                @error="handleImageError"
              />
            </div>

            <div class="p-4">
              <h5 class="text-sm font-bold mb-1 text-gray-800">{{ product.name }}</h5>
              <p class="text-gray-500 text-[10px] mb-1">Available: {{ product.stock }} {{ product.unit }}</p>
              <p class="text-xs text-gray-600 mb-2">Branch: {{ product.branch }}</p>
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-gray-700">
                  ₱{{ parseFloat(product.price).toFixed(2) }} / {{ product.unit }}
                </span>
                <form @submit.prevent="addToCart(product.id)" class="inline">
                  <button
                    type="submit"
                    :disabled="product.status === 'Out of Stock'"
                    :class="[
                      'text-maroon text-[10px] font-semibold px-2 py-1 rounded-full transition duration-300',
                      product.status === 'Out of Stock'
                        ? 'bg-gray-300 cursor-not-allowed'
                        : 'bg-yellow-500 hover:shadow-md hover:-translate-y-0.5'
                    ]"
                  >
                    {{ product.status === 'Out of Stock' ? 'Out of Stock' : 'Add to Cart' }}
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- No products message -->
        <div v-else class="text-center py-12">
          <div v-if="searchQuery">
            <p class="text-gray-500 text-lg">No fruits found for "{{ searchQuery }}".</p>
            <button
              @click="clearSearch"
              class="mt-4 bg-yellow-500 text-maroon px-4 py-2 rounded hover:bg-yellow-400 transition"
            >
              Clear Search
            </button>
          </div>
          <p v-else class="text-gray-500 text-lg">No fruits available at the moment.</p>
        </div>

        <!-- Pagination -->
        <nav v-if="sortedProducts.length > 0" aria-label="Page navigation" class="mt-8 flex justify-center">
          <ul class="flex space-x-1">
            <li>
              <a href="#" class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">&laquo;</a>
            </li>
            <li>
              <a href="#" class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">1</a>
            </li>
            <li>
              <a href="#" class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">2</a>
            </li>
            <li>
              <a href="#" class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">3</a>
            </li>
            <li>
              <a href="#" class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">&raquo;</a>
            </li>
          </ul>
        </nav>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script setup>
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
import { router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Receive props from Laravel controller
const props = defineProps({
  products: {
    type: Array,
    default: () => []
  },
  cartCount: {
    type: Number,
    default: 0
  }
})

const sortBy = ref('asc')
const itemsToShow = ref(10)
const searchQuery = ref('')

// Filter products based on search query
const filteredProducts = computed(() => {
  if (!searchQuery.value) {
    return props.products
  }

  const query = searchQuery.value.toLowerCase()
  return props.products.filter(product =>
    product.name.toLowerCase().includes(query) ||
    product.branch.toLowerCase().includes(query) ||
    product.unit.toLowerCase().includes(query)
  )
})

// Sort and limit filtered products
const sortedProducts = computed(() => {
  return filteredProducts.value
    .slice()
    .sort((a, b) => {
      const priceA = parseFloat(a.price)
      const priceB = parseFloat(b.price)
      return sortBy.value === 'asc' ? priceA - priceB : priceB - priceA
    })
    .slice(0, itemsToShow.value)
})

function handleSearch(query) {
  searchQuery.value = query
}

function clearSearch() {
  searchQuery.value = ''
}

function addToCart(productId) {
  router.post('/cart/add', {
    product_id: productId
  })
}

function viewProduct(id) {
  router.visit(`/viewproduct/${id}`)
}

function handleImageError(event) {
  // Fallback image if product image fails to load
  event.target.src = 'https://via.placeholder.com/400x300?text=No+Image'
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}
</style>
