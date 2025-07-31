<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <AdminSidebar />

    <main class="ml-64 flex-1 p-6 text-[#5F1213]">

      <!-- Page Header -->
      <div class="bg-white rounded-xl p-6 mb-2 shadow border flex items-center space-x-4">
        <i class="i-icon-park-outline-shop text-3xl text-black"></i>
        <h1 class="text-2xl font-semibold">Product Management</h1>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end space-x-3 mb-4">
        <button @click="showAddModal = true" class="bg-[#5F1213] hover:bg-[#7d1a1e] text-white px-4 py-2 rounded-lg transition">
          + Add Product
        </button>
        <button
          @click="prepareDeleteSelected"
          class="bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 rounded-lg transition"
          :disabled="!selectedIds.length"
        >
          Delete Selected
        </button>
      </div>

      <!-- Product Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">All Products</h3>

        <table class="w-full text-sm text-left table-auto">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2">
                <input type="checkbox" @change="toggleSelectAll" :checked="allSelected" />
              </th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('id')">ID <span>{{ getSortIcon('id') }}</span></th>
              <th class="py-2 px-2">Image</th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('name')">Product Name <span>{{ getSortIcon('name') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('branch')">Branch <span>{{ getSortIcon('branch') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('category')">Category <span>{{ getSortIcon('category') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('price')">Price <span>{{ getSortIcon('price') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('stock')">Stock <span>{{ getSortIcon('stock') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('status')">Status <span>{{ getSortIcon('status') }}</span></th>
              <th class="py-2 px-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in paginatedProducts" :key="product.id" class="border-b border-gray-100 hover:bg-gray-50">
              <td class="py-2 px-2">
                <input type="checkbox" :value="product.id" v-model="selectedIds" />
              </td>
              <td class="py-2 px-2">{{ product.id }}</td>
              <td class="py-2 px-2">
                <img :src="product.image" class="w-10 h-10 object-cover rounded" />
              </td>
              <td class="py-2 px-2">{{ product.name }}</td>
              <td class="py-2 px-2">{{ product.branch }}</td>
              <td class="py-2 px-2">{{ product.category }}</td>
              <td class="py-2 px-2">₱{{ product.price }} <span class="text-xs text-gray-500">{{ product.unit }}</span></td>
              <td class="py-2 px-2">{{ product.stock }} <span class="text-xs text-gray-500">{{ product.unit }}</span></td>
              <td class="py-2 px-2">
                <span :class="product.status === 'Available' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
                  {{ product.status }}
                </span>
              </td>
              <td class="py-2 px-2 space-x-2">
                <button @click="editProduct(product)" class="text-blue-600 hover:underline text-xs">Edit</button>
                <button @click="confirmDelete(product)" class="text-red-600 hover:underline text-xs">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-end mt-4 space-x-2 text-sm">
          <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>
          <span class="px-3 py-1">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
        </div>
      </div>
    </main>

    <!-- Modals -->
    <AddProductModal v-if="showAddModal" @close="showAddModal = false" @add="fetchProducts" />
    <EditProductModal v-if="showEditModal" :product="selectedProduct" @close="showEditModal = false" @save="saveEdit" />
    <DeleteProductModal v-if="showDeleteModal" :product="selectedProduct" :selected-ids="selectedIds" @close="showDeleteModal = false" @delete="deleteProduct" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AddProductModal from './modals/AddProductModal.vue'
import EditProductModal from './modals/EditProductModal.vue'
import DeleteProductModal from './modals/DeleteProductModal.vue'
import AdminSidebar from './adminSidebar.vue'

// Modal state
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)

// Data state
const selectedProduct = ref(null)
const selectedIds = ref([])
const products = ref([])

// Fetch products from backend
const fetchProducts = async () => {
  try {
    const response = await axios.get('/admins/products') // Update URL if needed
    products.value = response.data
  } catch (error) {
    console.error('Failed to fetch products:', error)
  }
}
onMounted(fetchProducts)

// Selection logic
const allSelected = computed(() => selectedIds.value.length === products.value.length)
const toggleSelectAll = () => {
  selectedIds.value = allSelected.value ? [] : products.value.map(p => p.id)
}

// Pagination logic
const currentPage = ref(1)
const perPage = 5
const totalPages = computed(() => Math.ceil(products.value.length / perPage))

// Sorting logic
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
  if (!sortKey.value) return [...products.value]
  return [...products.value].sort((a, b) => {
    const aVal = a[sortKey.value]
    const bVal = b[sortKey.value]
    return typeof aVal === 'number'
      ? (sortAsc.value ? aVal - bVal : bVal - aVal)
      : (sortAsc.value ? String(aVal).localeCompare(bVal) : String(bVal).localeCompare(aVal))
  })
})

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedProducts.value.slice(start, start + perPage)
})

// CRUD functions
const editProduct = (product) => {
  selectedProduct.value = { ...product }
  showEditModal.value = true
}

const saveEdit = () => {
  showEditModal.value = false
  fetchProducts()
}

const confirmDelete = (product) => {
  selectedProduct.value = product
  showDeleteModal.value = true
}

const prepareDeleteSelected = () => {
  selectedProduct.value = null
  showDeleteModal.value = true
}

const deleteProduct = async (idsToDelete) => {
  try {
    await axios.post('/admins/products/bulk-delete', { ids: idsToDelete }) // Update route as needed
    fetchProducts()
    selectedIds.value = []
    showDeleteModal.value = false
  } catch (error) {
    console.error('Delete failed:', error)
  }
}
</script>