<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <AdminSidebar />

    <main class="ml-64 flex-1 p-6 text-[#5F1213]">

      <!-- Page Header -->
      <div class="bg-white rounded-xl p-6 mb-2 shadow border flex items-center space-x-4">
        <i class="i-icon-park-outline-shop text-3xl text-black"></i>
        <h1 class="text-2xl font-semibold">Inventory</h1>
      </div>

      <!-- Product Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">All Products</h3>

        <table class="w-full text-sm text-left table-auto">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('id')">ID <span>{{ getSortIcon('id') }}</span></th>
              <th class="py-2 px-2">Image</th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('name')">Product Name <span>{{ getSortIcon('name')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('branch')">Branch <span>{{ getSortIcon('branch')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('category')">Category <span>{{ getSortIcon('category')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('price')">Price <span>{{ getSortIcon('price')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('stock')">Stock <span>{{ getSortIcon('stock')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('status')">Status <span>{{ getSortIcon('status')
                  }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('dateAdded')">Date Added <span>{{
                getSortIcon('dateAdded') }}</span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in paginatedProducts" :key="product.id"
              class="border-b border-gray-100 hover:bg-gray-50">

              <td class="py-2 px-2">{{ product.id }}</td>
              <td class="py-2 px-2">
                <img :src="getProductImage(product.image)" class="w-10 h-10 object-cover rounded" />
              </td>
              <td class="py-2 px-2">{{ product.name }}</td>
              <td class="py-2 px-2">{{ product.branch }}</td>
              <td class="py-2 px-2">{{ product.category }}</td>
              <td class="py-2 px-2">₱{{ product.price }} <span class="text-xs text-gray-500">{{ product.unit }}</span>
              </td>
              <td class="py-2 px-2">{{ product.stock }} <span class="text-xs text-gray-500">{{ product.unit }}</span>
              </td>
              <td class="py-2 px-2">
                <span
                  :class="product.status === 'Available' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
                  {{ product.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                {{ new Date(product.created_at).toLocaleString() }}
              </td>

            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-end mt-4 space-x-2 text-sm">
          <button @click="currentPage--" :disabled="currentPage === 1"
            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>
          <span class="px-3 py-1">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="currentPage++" :disabled="currentPage === totalPages"
            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
        </div>
      </div>
    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AdminSidebar from './adminSidebar.vue'

// Make admin info reactive for sidebar
const page = usePage()
const admin = page.props.admin

const props = defineProps({
  products: Array
})

const selectedIds = ref([])

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
