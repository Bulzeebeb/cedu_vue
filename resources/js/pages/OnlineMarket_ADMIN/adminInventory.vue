<template>
  <div class="min-h-screen flex font-sans bg-gray-50">
    <AdminSidebar />

    <main class="ml-64 flex-1 p-8 text-[#5F1213]">

      <!-- Page Header -->
      <div class="bg-gradient-to-r from-[#5F1213] to-[#7d1a1e] rounded-xl p-8 mb-8 shadow-lg text-white">
        <div class="flex items-center space-x-4">
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <i class="i-icon-park-outline-shop text-4xl"></i>
          </div>
          <div>
            <h1 class="text-3xl font-bold">Inventory Management</h1>
            <p class="text-gray-100 mt-1">Manage your products, stock, and availability</p>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Total Products</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ totalProducts }}</p>
            </div>
            <i class="i-icon-park-outline-shop text-4xl text-blue-500 opacity-20"></i>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">In Stock</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ productsInStock }}</p>
            </div>
            <i class="i-icon-park-outline-check-one text-4xl text-green-500 opacity-20"></i>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-red-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Out of Stock</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ productsOutOfStock }}</p>
            </div>
            <i class="i-icon-park-outline-close text-4xl text-red-500 opacity-20"></i>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-purple-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Low Stock</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ productsLowStock }}</p>
            </div>
            <i class="i-icon-park-outline-warning text-4xl text-purple-500 opacity-20"></i>
          </div>
        </div>
      </div>

      <!-- Product Management Section -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Section Header -->
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Products</h2>
              <p class="text-gray-600 text-sm mt-1">{{ filteredProducts.length }} products found</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
              <button
                v-if="selectedIds.length > 0"
                @click="openBulkDeleteModal"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-md hover:shadow-lg"
              >
                <i class="i-icon-park-outline-delete mr-2"></i>
                Delete {{ selectedIds.length }}
              </button>
              
              <!-- Export Dropdown -->
              <div class="relative group">
                <button
                  class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#5F1213] hover:bg-[#7d1a1e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5F1213] transition-all duration-200 shadow-md hover:shadow-lg"
                >
                  <i class="i-icon-park-outline-download mr-2"></i>
                  Export
                  <i class="i-icon-park-outline-down ml-2"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-gray-200">
                  <button
                    @click="exportToCSV"
                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors duration-150 border-b border-gray-100"
                  >
                    <i class="i-icon-park-outline-file-csv mr-2 text-green-600"></i>
                    Export to CSV
                  </button>
                  <button
                    @click="exportToPDF"
                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors duration-150 border-b border-gray-100"
                  >
                    <i class="i-icon-park-outline-file-pdf mr-2 text-red-600"></i>
                    Export to PDF
                  </button>
                  <button
                    @click="exportToDocx"
                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors duration-150"
                  >
                    <i class="i-icon-park-outline-file-word mr-2 text-blue-600"></i>
                    Export to DOCX
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Search Products</label>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by name..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all duration-200"
              />
            </div>

            <!-- Category Filter -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
              <select
                v-model="filterCategory"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent bg-white transition-all duration-200"
              >
                <option value="">All Categories</option>
                <option>Vegetables</option>
                <option>Fruits</option>
                <option>Poultry</option>
              </select>
            </div>

            <!-- Branch Filter -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Branch</label>
              <select
                v-model="filterBranch"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent bg-white transition-all duration-200"
              >
                <option value="">All Branches</option>
                <option>Mabini</option>
                <option>Tagum</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
              <select
                v-model="filterStatus"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213] focus:border-transparent bg-white transition-all duration-200"
              >
                <option value="">All Status</option>
                <option>Available</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>
              </select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end">
              <button
                @click="clearFilters"
                :disabled="!hasActiveFilters"
                class="w-full px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5F1213] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Clear Filters
              </button>
            </div>
          </div>
          <!-- Active Filters Indicator -->
          <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
              <i class="i-icon-park-outline-filter mr-1"></i>
              {{ activeFiltersCount }} filter(s) applied
            </span>
          </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead>
              <tr class="bg-gray-100 border-b-2 border-gray-200 sticky top-0">
                <th class="py-4 px-6 font-semibold text-gray-700">
                  <input
                    type="checkbox"
                    :checked="allSelected"
                    @change="toggleSelectAll"
                    class="w-4 h-4 text-[#5F1213] focus:ring-[#5F1213] border-gray-300 rounded cursor-pointer"
                  />
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('id')">
                  ID <span class="text-xs ml-1">{{ getSortIcon('id') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700">Image</th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('name')">
                  Product Name <span class="text-xs ml-1">{{ getSortIcon('name') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('branch')">
                  Branch <span class="text-xs ml-1">{{ getSortIcon('branch') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('category')">
                  Category <span class="text-xs ml-1">{{ getSortIcon('category') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('price')">
                  Price <span class="text-xs ml-1">{{ getSortIcon('price') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('stock')">
                  Stock <span class="text-xs ml-1">{{ getSortIcon('stock') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('status')">
                  Status <span class="text-xs ml-1">{{ getSortIcon('status') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" @click="sortBy('created_at')">
                  Date Added <span class="text-xs ml-1">{{ getSortIcon('created_at') }}</span>
                </th>
                <th class="py-4 px-6 font-semibold text-gray-700 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="product in paginatedProducts" :key="product.id" class="hover:bg-gray-50 transition-colors">
                <td class="py-4 px-6">
                  <input
                    type="checkbox"
                    :value="product.id"
                    v-model="selectedIds"
                    class="w-4 h-4 text-[#5F1213] focus:ring-[#5F1213] border-gray-300 rounded cursor-pointer"
                  />
                </td>
                <td class="py-4 px-6 font-medium text-gray-900">#{{ product.id }}</td>
                <td class="py-4 px-6">
                  <img
                    :src="getProductImage(product.image)"
                    :alt="product.name"
                    class="w-14 h-14 object-cover rounded-lg shadow-sm border border-gray-200"
                  />
                </td>
                <td class="py-4 px-6 font-semibold text-gray-900">{{ product.name }}</td>
                <td class="py-4 px-6 text-gray-700">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ product.branch }}
                  </span>
                </td>
                <td class="py-4 px-6 text-gray-700">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ product.category }}
                  </span>
                </td>
                <td class="py-4 px-6 font-semibold text-gray-900">₱{{ formatPrice(product.price) }}</td>
                <td class="py-4 px-6">
                  <div class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-900">{{ product.stock }}</span>
                    <span class="text-xs text-gray-500">{{ product.unit }}</span>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <span :class="getStatusBadgeClass(product.stock)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">
                    {{ getStatusText(product.stock) }}
                  </span>
                </td>
                <td class="py-4 px-6 text-sm text-gray-600">
                  {{ formatDate(product.created_at) }}
                </td>
                <td class="py-4 px-6">
                  <div class="flex justify-center space-x-2">
                    <button
                      @click="openEditModal(product)"
                      class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                      title="Edit product"
                    >
                      <i class="i-icon-park-outline-edit mr-1"></i>
                      Edit
                    </button>
                    <button
                      @click="openDeleteModal(product)"
                      class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                      title="Delete product"
                    >
                      <i class="i-icon-park-outline-delete mr-1"></i>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <!-- Empty State -->
              <tr v-if="paginatedProducts.length === 0">
                <td colspan="11" class="py-12 px-6 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <i class="i-icon-park-outline-box text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg font-medium">No products found</p>
                    <p class="text-gray-400 text-sm">Try adjusting your filters or search query</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 border-t border-gray-200 px-8 py-6">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
              Showing <span class="font-semibold">{{ startIndex + 1 }}</span> to
              <span class="font-semibold">{{ Math.min(endIndex, filteredProducts.length) }}</span> of
              <span class="font-semibold">{{ filteredProducts.length }}</span> products
            </div>
            <div class="flex items-center space-x-2">
              <button
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5F1213] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="i-icon-park-outline-left"></i>
                Previous
              </button>
              <div class="flex items-center space-x-1">
                <span class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white">
                  Page {{ currentPage }} of {{ totalPages }}
                </span>
              </div>
              <button
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5F1213] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Next
                <i class="i-icon-park-outline-right ml-1"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <AddProductModal
        v-if="showAddModal"
        @close="closeAddModal"
        @product-added="handleProductAdded"
      />

      <EditProductModal
        v-if="showEditModal"
        :product="productToEdit"
        @close="closeEditModal"
        @product-updated="handleProductUpdated"
      />

      <DeleteProductModal
        v-if="showDeleteModal"
        :is-bulk="selectedIds.length > 0"
        :product="productToDelete"
        :selected-ids="selectedIds"
        :selected-count="selectedIds.length"
        @close="closeDeleteModal"
        @delete="handleProductDeleted"
        @bulk-delete="handleProductDeleted"
      />
    </main>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AdminSidebar from './adminSidebar.vue'
import AddProductModal from './modals/AddProductModal.vue'
import EditProductModal from './modals/EditProductModal.vue'
import DeleteProductModal from './modals/DeleteProductModal.vue'
import Swal from 'sweetalert2'
import { jsPDF } from 'jspdf'
import { Document, Packer, Table, TableRow, TableCell, Paragraph, TextRun, WidthType } from 'docx'

const page = usePage()
const admin = page.props.admin

const props = defineProps({
  products: Array
})

const selectedIds = ref([])

// Modal states
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const productToEdit = ref(null)
const productToDelete = ref(null)

// Filter states
const searchQuery = ref('')
const filterCategory = ref('')
const filterBranch = ref('')
const filterStatus = ref('')

const currentPage = ref(1)
const perPage = 5

const allSelected = computed(() => selectedIds.value.length === filteredProducts.value.length && filteredProducts.value.length > 0)

const toggleSelectAll = () => {
  selectedIds.value = allSelected.value ? [] : filteredProducts.value.map(p => p.id)
}

// Filtered products based on search and filters
const filteredProducts = computed(() => {
  let filtered = [...props.products]

  if (searchQuery.value) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  if (filterCategory.value) {
    filtered = filtered.filter(product => product.category === filterCategory.value)
  }

  if (filterBranch.value) {
    filtered = filtered.filter(product => product.branch === filterBranch.value)
  }

  if (filterStatus.value) {
    filtered = filtered.filter(product => getStatusText(product.stock) === filterStatus.value)
  }

  return filtered
})

const hasActiveFilters = computed(() => !!(searchQuery.value || filterCategory.value || filterBranch.value || filterStatus.value))

const activeFiltersCount = computed(() => {
  let count = 0
  if (searchQuery.value) count++
  if (filterCategory.value) count++
  if (filterBranch.value) count++
  if (filterStatus.value) count++
  return count
})

// Stats
const totalProducts = computed(() => props.products.length)
const productsInStock = computed(() => props.products.filter(p => p.stock > 5).length)
const productsOutOfStock = computed(() => props.products.filter(p => p.stock === 0).length)
const productsLowStock = computed(() => props.products.filter(p => p.stock > 0 && p.stock <= 5).length)

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage))

const sortKey = ref('')
const sortAsc = ref(true)

const sortBy = (key) => {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value
  } else {
    sortKey.value = key
    sortAsc.value = true
  }
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
    return typeof aVal === 'number'
      ? (sortAsc.value ? aVal - bVal : bVal - aVal)
      : (sortAsc.value ? String(aVal).localeCompare(bVal) : String(bVal).localeCompare(aVal))
  })
})

const startIndex = computed(() => (currentPage.value - 1) * perPage)
const endIndex = computed(() => startIndex.value + perPage)

const paginatedProducts = computed(() => {
  return sortedProducts.value.slice(startIndex.value, endIndex.value)
})

// Utility functions
function getProductImage(image) {
  if (!image) return '/images/no-image.png'
  if (image.startsWith('http') || image.startsWith('/storage/')) return image
  if (image.startsWith('./storage/')) return image.replace('./', '/')
  return '/images/no-image.png'
}

function formatPrice(price) {
  return parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

function getStatusText(stock) {
  if (stock === 0) return 'Out of Stock'
  if (stock <= 5) return 'Low Stock'
  return 'Available'
}

function getStatusBadgeClass(stock) {
  const baseClass = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium'
  if (stock === 0) return `${baseClass} bg-red-100 text-red-800`
  if (stock <= 5) return `${baseClass} bg-yellow-100 text-yellow-800`
  return `${baseClass} bg-green-100 text-green-800`
}

// Modal handlers
function openAddModal() {
  showAddModal.value = true
}

function closeAddModal() {
  showAddModal.value = false
}

function openEditModal(product) {
  productToEdit.value = product
  showEditModal.value = true
}

function closeEditModal() {
  showEditModal.value = false
  productToEdit.value = null
}

function openDeleteModal(product) {
  productToDelete.value = product
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
  productToDelete.value = null
}

function openBulkDeleteModal() {
  if (selectedIds.value.length === 0) {
    Swal.fire('Warning', 'Please select products to delete.', 'warning')
    return
  }
  showDeleteModal.value = true
}

// CRUD handlers
function handleProductAdded() {
  window.location.reload()
}

function handleProductUpdated() {
  window.location.reload()
}

function handleProductDeleted() {
  selectedIds.value = []
  window.location.reload()
}

// Filter handlers
function clearFilters() {
  searchQuery.value = ''
  filterCategory.value = ''
  filterBranch.value = ''
  filterStatus.value = ''
  currentPage.value = 1
}

function resetPagination() {
  currentPage.value = 1
}

watch([searchQuery, filterCategory, filterBranch, filterStatus], () => {
  resetPagination()
})

// Export functions
function exportToCSV() {
  const headers = ['ID', 'Product Name', 'Branch', 'Category', 'Price', 'Stock', 'Unit', 'Status', 'Date Added']
  const rows = filteredProducts.value.map(product => [
    product.id,
    product.name,
    product.branch,
    product.category,
    `₱${formatPrice(product.price)}`,
    product.stock,
    product.unit,
    getStatusText(product.stock),
    formatDate(product.created_at)
  ])

  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
  ].join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  const url = URL.createObjectURL(blob)
  link.setAttribute('href', url)
  link.setAttribute('download', `inventory_${new Date().toISOString().split('T')[0]}.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)
  
  Swal.fire('Success', 'Inventory exported to CSV successfully!', 'success')
}

function exportToPDF() {
  try {
    const doc = new jsPDF('l', 'mm', 'a4')
    
    // Header styling
    doc.setFillColor(95, 18, 19)
    doc.rect(0, 0, 297, 20, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFontSize(18)
    doc.setFont(undefined, 'bold')
    doc.text('Inventory Report', 148, 14, { align: 'center' })
    
    doc.setFontSize(10)
    doc.setFont(undefined, 'normal')
    doc.setTextColor(100, 100, 100)
    doc.text(`Generated: ${formatDate(new Date())}`, 148, 22, { align: 'center' })
    
    // Table data
    const tableData = filteredProducts.value.map(product => [
      product.id.toString(),
      product.name.substring(0, 15),
      product.branch,
      product.category,
      `₱${formatPrice(product.price)}`,
      product.stock.toString(),
      getStatusText(product.stock)
    ])

    // Create table manually
    const startY = 30
    const rowHeight = 8
    const colWidths = [15, 60, 25, 25, 25, 20, 25]
    const headers = ['ID', 'Product', 'Branch', 'Category', 'Price', 'Stock', 'Status']
    const pageWidth = doc.internal.pageSize.getWidth()
    const margin = 10

    // Draw header
    let xPos = margin
    doc.setFillColor(95, 18, 19)
    doc.setTextColor(255, 255, 255)
    doc.setFont(undefined, 'bold')
    doc.setFontSize(10)
    
    headers.forEach((header, index) => {
      doc.rect(xPos, startY, colWidths[index], rowHeight, 'F')
      doc.text(header, xPos + 2, startY + 6)
      xPos += colWidths[index]
    })

    // Draw rows
    doc.setTextColor(0, 0, 0)
    doc.setFont(undefined, 'normal')
    doc.setFontSize(9)
    
    let currentY = startY + rowHeight
    tableData.forEach((row, rowIndex) => {
      xPos = margin
      
      // Alternate row background
      if (rowIndex % 2 === 0) {
        xPos = margin
        doc.setFillColor(240, 240, 240)
        colWidths.forEach(width => {
          doc.rect(xPos, currentY, width, rowHeight, 'F')
          xPos += width
        })
      }
      
      // Draw row border
      xPos = margin
      doc.setDrawColor(200, 200, 200)
      doc.setLineWidth(0.1)
      colWidths.forEach(width => {
        doc.rect(xPos, currentY, width, rowHeight)
        xPos += width
      })
      
      // Draw row data
      xPos = margin
      doc.setTextColor(0, 0, 0)
      row.forEach((cell, colIndex) => {
        doc.text(String(cell), xPos + 2, currentY + 6)
        xPos += colWidths[colIndex]
      })
      
      currentY += rowHeight
      
      // Check if we need a new page
      if (currentY > doc.internal.pageSize.getHeight() - 20) {
        doc.addPage()
        currentY = 20
        
        // Redraw header on new page
        xPos = margin
        doc.setFillColor(95, 18, 19)
        doc.setTextColor(255, 255, 255)
        doc.setFont(undefined, 'bold')
        doc.setFontSize(10)
        
        headers.forEach((header, index) => {
          doc.rect(xPos, currentY, colWidths[index], rowHeight, 'F')
          doc.text(header, xPos + 2, currentY + 6)
          xPos += colWidths[index]
        })
        
        currentY += rowHeight
        doc.setFont(undefined, 'normal')
        doc.setFontSize(9)
      }
    })

    // Add footer with page numbers
    const totalPages = doc.internal.pages.length - 1
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i)
      doc.setTextColor(100, 100, 100)
      doc.setFontSize(9)
      doc.text(
        `Page ${i} of ${totalPages}`,
        pageWidth / 2,
        doc.internal.pageSize.getHeight() - 5,
        { align: 'center' }
      )
    }

    doc.save(`inventory_${new Date().toISOString().split('T')[0]}.pdf`)
    Swal.fire('Success', 'Inventory exported to PDF successfully!', 'success')
  } catch (error) {
    console.error('PDF Export Error:', error)
    Swal.fire('Error', `Failed to export PDF: ${error.message}`, 'error')
  }
}

function exportToDocx() {
  try {
    const tableRows = [
      // Header row
      new TableRow({
        height: { value: 400, rule: 'atLeast' },
        children: [
          new TableCell({
            width: { size: 5, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'ID', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 20, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Product Name', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 12, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Branch', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 12, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Category', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 10, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Price', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 10, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Stock', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 15, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Status', color: 'FFFFFF', bold: true }))]
          }),
          new TableCell({
            width: { size: 16, type: WidthType.PERCENTAGE },
            shading: { fill: '5F1213' },
            children: [new Paragraph(new TextRun({ text: 'Date Added', color: 'FFFFFF', bold: true }))]
          })
        ]
      }),
      // Data rows
      ...filteredProducts.value.map(product => 
        new TableRow({
          children: [
            new TableCell({
              width: { size: 5, type: WidthType.PERCENTAGE },
              children: [new Paragraph(product.id.toString())]
            }),
            new TableCell({
              width: { size: 20, type: WidthType.PERCENTAGE },
              children: [new Paragraph(product.name)]
            }),
            new TableCell({
              width: { size: 12, type: WidthType.PERCENTAGE },
              children: [new Paragraph(product.branch)]
            }),
            new TableCell({
              width: { size: 12, type: WidthType.PERCENTAGE },
              children: [new Paragraph(product.category)]
            }),
            new TableCell({
              width: { size: 10, type: WidthType.PERCENTAGE },
              children: [new Paragraph(`₱${formatPrice(product.price)}`)]
            }),
            new TableCell({
              width: { size: 10, type: WidthType.PERCENTAGE },
              children: [new Paragraph(product.stock.toString())]
            }),
            new TableCell({
              width: { size: 15, type: WidthType.PERCENTAGE },
              children: [new Paragraph(getStatusText(product.stock))]
            }),
            new TableCell({
              width: { size: 16, type: WidthType.PERCENTAGE },
              children: [new Paragraph(formatDate(product.created_at))]
            })
          ]
        })
      )
    ]

    const table = new Table({
      width: { size: 100, type: WidthType.PERCENTAGE },
      rows: tableRows
    })

    const doc = new Document({
      sections: [{
        children: [
          new Paragraph({
            text: 'Inventory Report',
            run: new TextRun({ size: 32, bold: true, color: '5F1213' })
          }),
          new Paragraph({
            text: `Generated: ${formatDate(new Date())}`,
            run: new TextRun({ size: 20, color: '666666' })
          }),
          new Paragraph({ text: '' }),
          table
        ]
      }]
    })

    Packer.toBlob(doc).then(blob => {
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = `inventory_${new Date().toISOString().split('T')[0]}.docx`
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      URL.revokeObjectURL(url)
      
      Swal.fire('Success', 'Inventory exported to DOCX successfully!', 'success')
    }).catch(error => {
      console.error('DOCX Export Error:', error)
      Swal.fire('Error', `Failed to export DOCX: ${error.message}`, 'error')
    })
  } catch (error) {
    console.error('DOCX Creation Error:', error)
    Swal.fire('Error', `Failed to create DOCX: ${error.message}`, 'error')
  }
}
</script>
