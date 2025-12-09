<template>
  <div class="bg-gray-50 min-h-screen">
    <div class="pt-24 px-4"></div>
    <SiteHeader :cart-count="cartCount" @search="handleSearch" />

    <!-- Shop Page Content -->
    <section class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 space-y-8">
        <!-- Banner -->
        <div
          class="bg-cover bg-center rounded-2xl shadow-lg overflow-hidden h-40 sm:h-48 md:h-56 relative"
          style="background-image: url('images/OnlineMarket/fruits_banner.png');"
        >
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="relative flex items-end h-full p-6 md:p-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg">FRUITS</h2>
          </div>
        </div>

        <!-- Search Results Info -->
        <div v-if="searchQuery" class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
          <p class="text-gray-700">Showing results for: <span class="font-semibold text-blue-600">"{{ searchQuery }}"</span></p>
          <p class="text-sm text-gray-600 mt-1">{{ filteredProducts.length }} product(s) found</p>
        </div>

        <!-- Sorting & Show Controls -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div class="text-sm text-gray-600 font-medium">
            Total: <span class="text-maroon font-bold">{{ filteredProducts.length }}</span> products
          </div>
          <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <label class="flex items-center gap-2 text-sm">
              Sort by:
              <select v-model="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="asc">Price (Low - High)</option>
                <option value="desc">Price (High - Low)</option>
              </select>
            </label>
            <label class="flex items-center gap-2 text-sm">
              Show:
              <select v-model.number="itemsPerPage" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="6">6</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
              </select>
              per page
            </label>
          </div>
        </div>

        <!-- Product Grid -->
        <div v-if="paginatedProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
          <div
            v-for="product in paginatedProducts"
            :key="product.id"
            class="bg-white rounded-2xl shadow-md overflow-hidden transform transition duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer group"
          >
            <!-- Image Container -->
            <div class="relative overflow-hidden bg-gray-200 h-56">
              <img
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                @error="handleImageError"
              />
              <div v-if="product.status === 'Out of Stock'" class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="text-white font-bold text-lg">Out of Stock</span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-4 space-y-3">
              <div>
                <h5 class="text-sm font-bold text-gray-800 line-clamp-2">{{ product.name }}</h5>
                <p class="text-xs text-gray-500 mt-1">{{ product.branch }}</p>
              </div>

              <div class="space-y-1 text-xs text-gray-600">
                <p><span class="font-medium">Stock:</span> {{ product.stock }} {{ product.unit }}</p>
                <p class="text-lg font-bold text-maroon">₱{{ parseFloat(product.price).toFixed(2) }}/{{ product.unit }}</p>
              </div>

              <!-- Quantity & Button -->
              <div class="space-y-2 pt-2">
                <div class="flex items-center gap-2">
                  <label class="text-xs text-gray-600 font-medium">Qty:</label>
                  <input
                    v-model.number="productQuantities[product.id]"
                    type="number"
                    min="1"
                    :max="product.stock"
                    :disabled="product.status === 'Out of Stock'"
                    class="w-14 px-2 py-1 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 disabled:bg-gray-100"
                  />
                </div>
                <button
                  @click.stop="addToCart(product.id)"
                  :disabled="product.status === 'Out of Stock' || loadingProductId === product.id"
                  :class="[
                    'w-full text-maroon text-xs font-bold py-2.5 rounded-lg transition duration-300 disabled:cursor-not-allowed disabled:opacity-50',
                    product.status === 'Out of Stock' || loadingProductId === product.id
                      ? 'bg-gray-300 cursor-not-allowed'
                      : 'bg-yellow-500 hover:bg-yellow-600 hover:shadow-md'
                  ]"
                >
                  {{ loadingProductId === product.id ? 'Adding...' : 'Add to Cart' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- No products message -->
        <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4" />
          </svg>
          <p class="text-gray-500 text-lg mb-4">
            {{ searchQuery ? `No fruits found for "${searchQuery}".` : 'No fruits available at the moment.' }}
          </p>
          <button
            @click="clearSearch"
            v-if="searchQuery"
            class="bg-yellow-500 hover:bg-yellow-600 text-maroon font-bold px-6 py-2 rounded-lg transition"
          >
            Clear Search
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-8">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="w-10 h-10 flex items-center justify-center bg-maroon text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition"
          >
            &laquo;
          </button>

          <div class="flex gap-1">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'w-10 h-10 flex items-center justify-center rounded-lg font-medium transition',
                currentPage === page
                  ? 'bg-yellow-500 text-maroon font-bold'
                  : 'bg-maroon text-white hover:bg-red-700'
              ]"
            >
              {{ page }}
            </button>
          </div>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="w-10 h-10 flex items-center justify-center bg-maroon text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition"
          >
            &raquo;
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
import { ref, computed } from 'vue'

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
const itemsPerPage = ref(10)
const searchQuery = ref('')
const currentPage = ref(1)
const loadingProductId = ref(null)
const productQuantities = ref({})

// Initialize quantities
props.products.forEach(product => {
  if (!productQuantities.value[product.id]) {
    productQuantities.value[product.id] = 1
  }
})

const filteredProducts = computed(() => {
  if (!searchQuery.value) return props.products

  const query = searchQuery.value.toLowerCase()
  return props.products.filter(product =>
    product.name.toLowerCase().includes(query) ||
    product.branch.toLowerCase().includes(query) ||
    product.unit.toLowerCase().includes(query)
  )
})

const sortedProducts = computed(() => {
  return filteredProducts.value.slice().sort((a, b) => {
    const priceA = parseFloat(a.price)
    const priceB = parseFloat(b.price)
    return sortBy.value === 'asc' ? priceA - priceB : priceB - priceA
  })
})

const totalPages = computed(() => Math.ceil(sortedProducts.value.length / itemsPerPage.value))

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return sortedProducts.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - 2)
  let end = Math.min(totalPages.value, start + maxVisible - 1)

  if (end - start < maxVisible - 1) {
    start = Math.max(1, end - maxVisible + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

function handleSearch(query) {
  searchQuery.value = query
  currentPage.value = 1
}

function clearSearch() {
  searchQuery.value = ''
  currentPage.value = 1
}

async function addToCart(productId) {
  loadingProductId.value = productId
  try {
    await router.post('/cart/add', {
      product_id: productId,
      quantity: productQuantities.value[productId] || 1
    })
    productQuantities.value[productId] = 1
  } catch (error) {
    console.error('Error adding to cart:', error)
  } finally {
    loadingProductId.value = null
  }
}

function goToPage(page) {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function previousPage() {
  if (currentPage.value > 1) {
    currentPage.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

function handleImageError(event) {
  event.target.src = 'https://via.placeholder.com/400x300?text=No+Image'
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}

.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}
</style>
