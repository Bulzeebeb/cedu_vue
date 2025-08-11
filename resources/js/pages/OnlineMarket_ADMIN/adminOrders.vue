<template>
    <div class="min-h-screen flex font-sans bg-gray-100">
        <AdminSidebar />

        <main class="ml-64 flex-1 p-6 text-[#5F1213]">

            <!-- Page Header -->
            <div class="bg-white rounded-xl p-6 mb-2 shadow border flex items-center space-x-4">
                <i class="i-icon-park-outline-shop text-3xl text-black"></i>
                <h1 class="text-2xl font-semibold">Order Management</h1>

            </div>

            <!-- Order Table -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold mb-4">All Orders</h3>

                <table class="w-full text-sm text-left table-auto">
                    <thead>
                        <tr class="border-b border-gray-200 font-semibold text-gray-700">
                            <th class="py-2 px-2 cursor-pointer">Customer ID</th>
                            <th class="py-2 px-2 cursor-pointer">Customer Name<span>{{ getSortIcon('full_name')
                                    }}</span></th>
                            <th class="py-2 px-2">Image</th>
                            <th class="py-2 px-2 cursor-pointer">Product Name</th>
                            <th class="py-2 px-2 cursor-pointer">Price</th>
                            <th class="py-2 px-2 cursor-pointer">Quantity</th>
                            <th class="py-2 px-2 cursor-pointer">Order Total</th>
                            <th class="py-2 px-2 cursor-pointer">Order Date</th>
                            <th class="py-2 px-2 cursor-pointer" @click="sortBy('status')">Status <span>{{
                                getSortIcon('status') }}</span></th>
                            <th class="py-2 px-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in paginatedOrders" :key="order.id"
                            class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-2 px-2">{{ order.user_id }}</td>
                            <td class="py-2 px-2">{{ order.first_name }} {{ order.last_name }}</td>
                            <td class="py-2 px-2">
                                <div v-for="item in order.order_items" :key="item.id">
                                    <img :src="getProductImage(item.product?.image)"
                                        class="w-10 h-10 object-cover rounded mb-1" />
                                </div>
                            </td>
                            <td class="py-2 px-2">
                                <div v-for="item in order.order_items" :key="item.id">
                                    {{ item.product ? item.product.name : 'N/A' }}
                                </div>
                            </td>
                            <td class="py-2 px-2">
                                <div v-for="item in order.order_items" :key="item.id">
                                    ₱{{ item.product ? item.product.price : 'N/A' }} <span
                                        class="text-xs text-gray-500">{{ item.product ? item.product.unit : '' }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-2 text-center">
                                <div v-for="item in order.order_items" :key="item.id">
                                    x{{ item.qty ?? item.quantity ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-2 px-2">
                                <div v-for="item in order.order_items" :key="item.id">
                                    {{ item.subtotal ?? item.subtotal ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-2 px-2">
                                {{ new Date(order.order_date).toISOString().split('T')[0] }}
                            </td>
                            <td class="py-2 px-2">
                                <span
                                    :class="order.status === 'Pending' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
                                    {{ order.status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <button @click="confirmOrder(order.id)"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-xs">
                                    <i class="fas fa-check"></i> Confirm
                                </button>

                                <button @click="declineOrder(order.id)"
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                                    <i class="fas fa-times"></i> Decline
                                </button>

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

const props = defineProps({
    orders: Array
})

const selectedIds = ref([])
const selectedProduct = ref(null)
const currentPage = ref(1)
const perPage = 5

const allSelected = computed(() => selectedIds.value.length === props.orders.length)
const toggleSelectAll = () => {
    selectedIds.value = allSelected.value ? [] : props.orders.map(o => o.id)
}

const totalPages = computed(() => Math.ceil(props.orders.length / perPage))

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

const reloadProducts = () => {
    router.reload({
        only: ['products'],
        onSuccess: () => {
            products.value = [...usePage().props.products]
        }
    })
}

// Confirm Order
const confirmOrder = async (orderId) => {
  try {
    await axios.patch(`/admins/orders/${orderId}/mark-as-paid`);
    alert('Order marked as paid!');
    location.reload();
  } catch (err) {
    alert('Error marking order as paid.');
    console.error(err);
  }
}

// Decline Order
const declineOrder = async (orderId) => {
  try {
    await axios.delete(`/admins/orders/${orderId}`);
    alert('Order deleted!');
    location.reload();
  } catch (err) {
    alert('Error deleting order.');
    console.error(err);
  }
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
