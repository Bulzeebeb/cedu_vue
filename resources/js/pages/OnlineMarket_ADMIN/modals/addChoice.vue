<template>
  <div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-2xl border border-gray-200 max-h-[90vh] overflow-y-auto">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-[#5F1213]">Choose Category to Add New Product</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl leading-none">&times;</button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="flex flex-col space-y-3">

        <button @click="showAddModal = true" class="px-4 py-2 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded">
          Fruits
        </button>

        <button @click="showAddModal = true" class="px-4 py-2 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded">
          Vegetables
        </button>

        <button @click="showAddModal = true" class="px-4 py-2 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded">
          Poultry
        </button>

        <!-- Buttons -->
        <div class="flex justify-end pt-3">
          <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded"
            @click="$emit('close')">
            Cancel
          </button>
        </div>
      </form>

    </div>

    <!-- Modals -->
    <AddProductModal v-if="showAddModal" @close="showAddModal = false" @add="fetchProducts" />
    <AddProductModal v-if="showAddModal" @close="showAddModal = false" @add="handleAddProduct" />
    <EditProductModal v-if="showEditModal" :product="selectedProduct" @close="showEditModal = false" @save="saveEdit" />
    <DeleteProductModal v-if="showDeleteModal" :product="selectedProduct" :selected-ids="selectedIds.value"
      @close="showDeleteModal = false" @delete="deleteProduct" />

  </div>
</template>


<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import axios from 'axios'
import AddProductModal from './AddProductModal.vue'
import EditProductModal from './EditProductModal.vue'
import DeleteProductModal from './DeleteProductModal.vue'
import { router } from '@inertiajs/vue3'

// Make admin info reactive for sidebar
const page = usePage()
const admin = computed(() => page.props.admin)

const props = defineProps({
  products: Array
})


// Computed: Sorted Orders
const sortedOrders = computed(() => {
  const list = [...props.orders]
  if (!sortKey.value) return list

  return list.sort((a, b) => {
    // Special case: sort by full name
    if (sortKey.value === 'full_name') {
      const nameA = `${a.firstname} ${a.lastname}`.toLowerCase()
      const nameB = `${b.firstname} ${b.lastname}`.toLowerCase()
      return sortAsc.value ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA)
    }

    // Default sorting
    const aVal = a[sortKey.value]
    const bVal = b[sortKey.value]
    return typeof aVal === 'number'
      ? (sortAsc.value ? aVal - bVal : bVal - aVal)
      : (sortAsc.value
        ? String(aVal).localeCompare(String(bVal))
        : String(bVal).localeCompare(String(aVal)))
  })
})


const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedOrders.value.slice(start, start + perPage)
})

const selectedIds = ref([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)

const selectedProduct = ref(null)
const currentPage = ref(1)
const perPage = 5

const allSelected = computed(() => selectedIds.value.length === props.products.length)
const toggleSelectAll = () => {
  selectedIds.value = allSelected.value ? [] : props.products.map(p => p.id)
}

const totalPages = computed(() => Math.ceil(props.products.length / perPage))

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
  const list = [...props.products]
  if (!sortKey.value) return list
  return list.sort((a, b) => {
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
  selectedProduct.value = null
  showDeleteModal.value = true
}

const deleteProduct = (idsToDelete) => {
  router.post('/admins/products/bulk-delete', {
    ids: idsToDelete
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
      selectedIds.value = []
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
    onSuccess: () => {
      products.value = [...usePage().props.products]
    }
  })
  // ...existing code...
}
const handleAddProduct = () => {
  showAddModal.value = false
  reloadProducts()
}

function getProductImage(image) {
  if (!image) return '/images/no-image.png'
  // If image is a full URL or Laravel Storage::url output
  if (image.startsWith('http') || image.startsWith('/storage/')) return image
  // If image is a relative path like './storage/products/...'
  if (image.startsWith('./storage/')) return image.replace('./', '/')
  // Otherwise, fallback to default
  return '/images/no-image.png'
}

</script>
