<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <Sidebar />

    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      <SiteHeader />

      <!-- Page Header -->
      <div class="bg-white rounded-xl p-6 mb-2 shadow border flex items-center space-x-4">
        <i class="i-icon-park-outline-shop text-3xl text-black"></i>
        <h1 class="text-2xl font-semibold">Inventory</h1>
      </div>

      <!-- Inventory Table -->
      <div class="bg-white rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">All Inventory Items</h3>

        <table class="w-full text-sm text-left table-auto">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('id')">ID <span>{{ getSortIcon('id') }}</span></th>
              <th class="py-2 px-2">Image</th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('name')">Product Name <span>{{ getSortIcon('name') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('branch')">Branch <span>{{ getSortIcon('branch') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('category')">Category <span>{{ getSortIcon('category') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('price')">Price <span>{{ getSortIcon('price') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('stock')">Stock <span>{{ getSortIcon('stock') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('status')">Status <span>{{ getSortIcon('status') }}</span></th>
              <th class="py-2 px-2 cursor-pointer" @click="sortBy('dateAdded')">Date Added <span>{{ getSortIcon('dateAdded') }}</span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in paginatedProducts" :key="product.id" class="border-b border-gray-100 hover:bg-gray-50">
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
              <td class="py-2 px-2">{{ product.dateAdded }}</td>
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
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import SiteHeader from './adminHeaderbar.vue'
import Sidebar from './adminSidebar.vue'

const products = ref([
  {
    id: 'P001',
    name: 'Tomatoes',
    branch: 'Tagum',
    category: 'Vegetables',
    price: 120,
    stock: 34,
    status: 'Available',
    unit: '/kilo',
    image: '/images/vegetables/tomatoes.jpg',
    dateAdded: '2025-07-26'
  },
  {
    id: 'P002',
    name: 'Egg Tray',
    branch: 'Mabini',
    category: 'Poultry',
    price: 160,
    stock: 12,
    status: 'Out of Stock',
    unit: '/tray',
    image: '/images/poultry/eggs.jpg',
    dateAdded: '2025-07-25'
  },
  {
    id: 'P003',
    name: 'Apple',
    branch: 'Tagum',
    category: 'Fruits',
    price: 95,
    stock: 53,
    status: 'Available',
    unit: '/kilo',
    image: '/images/fruits/apple.jpg',
    dateAdded: '2025-07-24'
  },
  {
    id: 'P004',
    name: 'Chicken Dung',
    branch: 'Mabini',
    category: 'Poultry',
    price: 70,
    stock: 40,
    status: 'Available',
    unit: '/sack',
    image: '/images/poultry/chicken_dung.jpg',
    dateAdded: '2025-07-23'
  },
  {
    id: 'P005',
    name: 'Culled Chicken',
    branch: 'Tagum',
    category: 'Poultry',
    price: 220,
    stock: 18,
    status: 'Out of Stock',
    unit: '/pc',
    image: '/images/poultry/culled_chicken.jpg',
    dateAdded: '2025-07-22'
  }
])

// Pagination
const currentPage = ref(1)
const perPage = 3
const totalPages = computed(() => Math.ceil(products.value.length / perPage))

// Sorting
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

    // Handle date sorting separately
    if (sortKey.value === 'dateAdded') {
      return sortAsc.value
        ? new Date(aVal) - new Date(bVal)
        : new Date(bVal) - new Date(aVal)
    }

    return typeof aVal === 'number'
      ? (sortAsc.value ? aVal - bVal : bVal - aVal)
      : (sortAsc.value ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal))
  })
})

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedProducts.value.slice(start, start + perPage)
})
</script>
