<template>
  <div class="min-h-screen flex font-sans bg-gradient-to-br from-gray-50 to-gray-100">
    <AdminSidebar />

    <main class="ml-64 flex-1 p-8 text-[#5F1213]">

      <!-- Page Header -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200 flex items-center space-x-4">
        <i class="i-icon-park-outline-shop text-4xl text-[#5F1213]"></i>
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Product Management</h1>
          <p class="text-gray-500 text-sm mt-1">Manage your online market products efficiently</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <div class="text-gray-500 text-sm font-medium">Total Products</div>
          <div class="text-3xl font-bold text-[#5F1213] mt-2">{{ props.products.length }}</div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <div class="text-gray-500 text-sm font-medium">Available</div>
          <div class="text-3xl font-bold text-green-600 mt-2">{{ availableCount }}</div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <div class="text-gray-500 text-sm font-medium">Out of Stock</div>
          <div class="text-3xl font-bold text-red-600 mt-2">{{ outOfStockCount }}</div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <div class="text-gray-500 text-sm font-medium">Low Stock</div>
          <div class="text-3xl font-bold text-yellow-600 mt-2">{{ lowStockCount }}</div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
          <button @click="exportToCSV" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition shadow-sm hover:shadow-md font-medium">
            <i class="i-icon-park-outline-download text-lg mr-2"></i>Export CSV
          </button>
          <div class="relative" v-if="selectedIds.length">
            <button @click="showBulkActions = !showBulkActions" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm hover:shadow-md font-medium">
              Bulk Actions ({{ selectedIds.length }}) ▼
            </button>
            <div v-if="showBulkActions" class="absolute top-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10 min-w-56">
              <button @click="bulkUpdateStatus('Available')" class="block w-full text-left px-4 py-3 hover:bg-gray-50 text-green-600 border-b font-medium">
                ✓ Mark as Available
              </button>
              <button @click="bulkUpdateStatus('Out of Stock')" class="block w-full text-left px-4 py-3 hover:bg-gray-50 text-red-600 font-medium">
                ✗ Mark as Out of Stock
              </button>
            </div>
          </div>
        </div>
        <div class="flex space-x-3">
          <button @click="showAddModal = true" class="bg-[#5F1213] hover:bg-[#7d1a1e] text-white px-6 py-2 rounded-lg transition shadow-sm hover:shadow-md font-medium">
            + Add Product
          </button>
          <button
            @click="prepareDeleteSelected"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed font-medium"
            :disabled="!selectedIds.length"
          >
            🗑 Delete Selected ({{ selectedIds.length }})
          </button>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by name, category, or branch..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
            <select v-model="filterCategory" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F1213]">
              <option value="">All Categories</option>
              <option value="Fruits">Fruits</option>
              <option value="Vegetables">Vegetables</option>
              <option value="Poultry">Poultry</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
            <select v-model="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F1213]">
              <option value="">All Status</option>
              <option value="Available">Available</option>
              <option value="Out of Stock">Out of Stock</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Product Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">All Products</h3>
          <span class="text-sm text-gray-500">Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="py-4 px-6 w-12">
                  <input type="checkbox" @change="toggleSelectAll" :checked="allSelected" class="rounded w-4 h-4" />
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700" @click="sortBy('id')">
                  ID <span class="text-xs ml-1">{{ getSortIcon('id') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700">Image</th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700" @click="sortBy('name')">
                  Product Name <span class="text-xs ml-1">{{ getSortIcon('name') }}</span>
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700" @click="sortBy('branch')">
                  Branch <span class="text-xs ml-1">{{ getSortIcon('branch') }}</span>
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700" @click="sortBy('category')">
                  Category <span class="text-xs ml-1">{{ getSortIcon('category') }}</span>
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700 text-right" @click="sortBy('price')">
                  Price <span class="text-xs ml-1">{{ getSortIcon('price') }}</span>
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700 text-right" @click="sortBy('stock')">
                  Stock <span class="text-xs ml-1">{{ getSortIcon('stock') }}</span>
                </th>
                <th class="py-4 px-6 cursor-pointer hover:bg-gray-100 transition font-semibold text-gray-700" @click="sortBy('status')">
                  Status <span class="text-xs ml-1">{{ getSortIcon('status') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="paginatedProducts.length === 0" class="border-b border-gray-200">
                <td colspan="10" class="py-12 px-6 text-center text-gray-500">
                  <i class="i-icon-park-outline-empty text-4xl mb-3"></i>
                  <p>No products found. Try adjusting your filters or create a new product.</p>
                </td>
              </tr>
              <tr
                v-for="(product, index) in paginatedProducts"
                :key="product.id"
                :class="[
                  index % 2 === 0 ? 'bg-white' : 'bg-gray-50',
                  'border-b border-gray-200 hover:bg-blue-50 transition'
                ]"
              >
                <td class="py-4 px-6">
                  <input type="checkbox" :value="product.id" v-model="selectedIds" class="rounded w-4 h-4" />
                </td>
                <td class="py-4 px-6 font-semibold text-gray-800">#{{ String(product.id).padStart(4, '0') }}</td>
                <td class="py-4 px-6">
                  <img :src="getProductImage(product.image)" :alt="product.name" class="w-14 h-14 object-cover rounded-lg border border-gray-200 shadow-sm" />
                </td>
                <td class="py-4 px-6 font-medium text-gray-800">{{ product.name }}</td>
                <td class="py-4 px-6 text-gray-600">{{ product.branch }}</td>
                <td class="py-4 px-6">
                  <span :class="getCategoryBadgeClass(product.category)" class="px-3 py-1 rounded-full text-xs font-semibold">
                    {{ product.category }}
                  </span>
                </td>
                <td class="py-4 px-6 font-semibold text-[#5F1213] text-right">₱{{ formatPrice(product.price) }}</td>
                <td class="py-4 px-6 text-right">
                  <span :class="getStockBadgeClass(product.stock)" class="px-3 py-1 rounded-full text-xs font-semibold">
                    {{ product.stock }} {{ product.unit }}
                  </span>
                </td>
                <td class="py-4 px-6">
                  <span :class="getStatusBadgeClass(product.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                    {{ product.status }}
                  </span>
                </td>
                <td class="py-4 px-6">
                  <div class="flex space-x-3">
                    <button @click="editProduct(product)" class="text-blue-600 hover:text-blue-800 hover:underline font-medium text-sm transition">
                      ✎ Edit
                    </button>
                    <button @click="confirmDelete(product)" class="text-red-600 hover:text-red-800 hover:underline font-medium text-sm transition">
                      🗑 Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center p-6 bg-gray-50 border-t border-gray-200">
          <span class="text-sm text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>
          <div class="flex space-x-2">
            <button
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium"
            >
              ← Previous
            </button>
            <button
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium"
            >
              Next →
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Modals -->
    <AddProductModal v-if="showAddModal" :category="selectedCategory" @close="showAddModal = false" @add="handleAddProduct" />
    <EditProductModal v-if="showEditModal" :product="selectedProduct" @close="showEditModal = false" @save="saveEdit" />
    <DeleteProductModal
        v-if="showDeleteModal"
        :product="selectedProduct"
        :is-bulk="!selectedProduct"
        :selected-count="selectedIds.length"
        @close="showDeleteModal = false"
        @delete="deleteProduct"
        @bulk-delete="bulkDeleteSelected"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AddProductModal from './modals/AddProductModal.vue'
import EditProductModal from './modals/EditProductModal.vue'
import DeleteProductModal from './modals/DeleteProductModal.vue'
import AdminSidebar from './adminSidebar.vue'
import { router } from '@inertiajs/vue3'

const page = usePage()
const admin = computed(() => page.props.admin)

const props = defineProps({
  products: Array
})

const selectedIds = ref([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const showBulkActions = ref(false)
const selectedCategory = ref('')
const searchQuery = ref('')
const filterCategory = ref('')
const filterStatus = ref('')

const selectedProduct = ref(null)
const currentPage = ref(1)
const perPage = 10

// Computed: Stats
const availableCount = computed(() => props.products.filter(p => p.status === 'Available').length)
const outOfStockCount = computed(() => props.products.filter(p => p.status === 'Out of Stock').length)
const lowStockCount = computed(() => props.products.filter(p => p.stock <= 5 && p.status === 'Available').length)

// Computed: Selection
const allSelected = computed(() => 
  props.products.length > 0 && selectedIds.value.length === filteredProducts.value.length
)

const toggleSelectAll = () => {
  selectedIds.value = allSelected.value ? [] : filteredProducts.value.map(p => p.id)
}

// Computed: Filtering
const filteredProducts = computed(() => {
  return props.products.filter(product => {
    const matchesSearch = !searchQuery.value || 
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      product.category.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      product.branch.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesCategory = !filterCategory.value || product.category === filterCategory.value
    const matchesStatus = !filterStatus.value || product.status === filterStatus.value
    
    return matchesSearch && matchesCategory && matchesStatus
  })
})

// Computed: Sorting
const sortKey = ref('')
const sortAsc = ref(true)

const sortBy = (key) => {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value
  } else {
    sortKey.value = key
    sortAsc.value = true
  }
  currentPage.value = 1
}

const getSortIcon = (key) => {
  if (sortKey.value !== key) return '⇅'
  return sortAsc.value ? '↑' : '↓'
}

const sortedProducts = computed(() => {
  const list = [...filteredProducts.value]
  if (!sortKey.value) return list
  
  return list.sort((a, b) => {
    const aVal = a[sortKey.value]
    const bVal = b[sortKey.value]
    
    if (typeof aVal === 'number' && typeof bVal === 'number') {
      return sortAsc.value ? aVal - bVal : bVal - aVal
    }
    
    const aStr = String(aVal).toLowerCase()
    const bStr = String(bVal).toLowerCase()
    return sortAsc.value ? aStr.localeCompare(bStr) : bStr.localeCompare(aStr)
  })
})

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage))

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedProducts.value.slice(start, start + perPage)
})

// Helper Functions
function getProductImage(image) {
  if (!image) return '/images/no-image.png'
  if (image.startsWith('http') || image.startsWith('/storage/')) return image
  if (image.startsWith('./storage/')) return image.replace('./', '/')
  return '/images/no-image.png'
}

function getCategoryBadgeClass(category) {
  const classes = {
    'Fruits': 'bg-green-100 text-green-800',
    'Vegetables': 'bg-orange-100 text-orange-800',
    'Poultry': 'bg-blue-100 text-blue-800'
  }
  return classes[category] || 'bg-gray-100 text-gray-800'
}

function getStatusBadgeClass(status) {
  return status === 'Available' 
    ? 'bg-green-100 text-green-800' 
    : 'bg-red-100 text-red-800'
}

function getStockBadgeClass(stock) {
  if (stock <= 0) return 'bg-red-100 text-red-800'
  if (stock <= 5) return 'bg-yellow-100 text-yellow-800'
  return 'bg-green-100 text-green-800'
}

function formatPrice(price) {
  return parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// CRUD Actions
const editProduct = (product) => {
  selectedProduct.value = { ...product }
  showEditModal.value = true
}

const saveEdit = () => {
  showEditModal.value = false
  reloadProducts()
}

const confirmDelete = (product) => {
  selectedProduct.value = product
  showDeleteModal.value = true
}

const prepareDeleteSelected = () => {
  if (selectedIds.value.length === 0) return
  selectedProduct.value = null
  showDeleteModal.value = true
}

const bulkDeleteSelected = () => {
  deleteProduct(selectedIds.value)
}

const deleteProduct = (idsToDelete) => {
  const ids = Array.isArray(idsToDelete) ? idsToDelete : [idsToDelete]
  
  router.post('/admins/products/bulk-delete', { ids }, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
      selectedIds.value = []
      currentPage.value = 1
      reloadProducts()
    },
    onError: (e) => {
      console.error('Delete failed:', e)
    }
  })
}

const reloadProducts = () => {
  router.reload({
    only: ['products'],
    preserveScroll: true
  })
}

const handleCategorySelect = (category) => {
  selectedCategory.value = category
  showAddModal.value = true
}

const handleAddProduct = () => {
  showAddModal.value = false
  selectedCategory.value = ''
  currentPage.value = 1
  reloadProducts()
}

const bulkUpdateStatus = (status) => {
  if (!selectedIds.value.length) return
  
  router.post('/admins/products/bulk-update-status', {
    ids: selectedIds.value,
    status: status
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showBulkActions.value = false
      selectedIds.value = []
      currentPage.value = 1
      reloadProducts()
    },
    onError: (e) => {
      console.error('Bulk update failed:', e)
    }
  })
}

const exportToCSV = () => {
  const headers = ['ID', 'Name', 'Branch', 'Category', 'Price', 'Stock', 'Unit', 'Status']
  const csvContent = [
    headers.join(','),
    ...filteredProducts.value.map(product => [
      product.id,
      `"${product.name}"`,
      product.branch,
      product.category,
      product.price,
      product.stock,
      product.unit,
      product.status
    ].join(','))
  ].join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  const url = URL.createObjectURL(blob)
  link.setAttribute('href', url)
  link.setAttribute('download', `products-${new Date().toISOString().split('T')[0]}.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
