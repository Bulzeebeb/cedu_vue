<template>
    <div class="min-h-screen flex font-sans bg-gray-50">
        <AdminSidebar />

        <main class="ml-64 flex-1 p-8 text-[#5F1213]">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#5F1213] p-3 rounded-lg">
                            <i class="i-icon-park-outline-shop text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                            <p class="text-sm text-gray-600 mt-1">Manage and track all customer orders</p>
                        </div>
                    </div>
                    <button @click="exportToCSV" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <i class="icon-download"></i> Export CSV
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ filteredOrders.length }}</p>
                            </div>
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="text-blue-600 text-xl">📦</i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Pending Orders</p>
                                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ getPendingCount() }}</p>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <i class="text-yellow-600 text-xl">⏳</i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                                <p class="text-2xl font-bold text-green-600 mt-1">₱{{ getTotalRevenue().toLocaleString() }}</p>
                            </div>
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="text-green-600 text-xl">💰</i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Completed Orders</p>
                                <p class="text-2xl font-bold text-green-600 mt-1">{{ getCompletedCount() }}</p>
                            </div>
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="text-green-600 text-xl">✓</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Controls -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters & Search</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Orders</label>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by ID, customer name, or email..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select
                            v-model="statusFilter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
                        >
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select
                            v-model="dateRangeFilter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
                        >
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="resetFilters" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors">
                            Reset Filters
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div v-if="selectedOrders.length > 0" class="mt-4 pt-4 border-t border-gray-200 flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-700">{{ selectedOrders.length }} selected</span>
                    <button @click="bulkMarkAsPaid" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Mark as Paid
                    </button>
                    <button @click="bulkMarkAsShipped" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Mark as Shipped
                    </button>
                    <button @click="bulkDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Delete Selected
                    </button>
                </div>
            </div>

            <!-- Order Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-4 px-6 text-left">
                                    <input type="checkbox" @change="toggleSelectAll" v-model="selectAll" class="rounded border-gray-300 text-[#5F1213] focus:ring-[#5F1213] cursor-pointer">
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900 cursor-pointer hover:bg-gray-100 transition-colors" @click="sortBy('id')">
                                    Order ID {{ getSortIcon('id') }}
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900 cursor-pointer hover:bg-gray-100 transition-colors" @click="sortBy('full_name')">
                                    Customer {{ getSortIcon('full_name') }}
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900">Items</th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900 cursor-pointer hover:bg-gray-100 transition-colors" @click="sortBy('total')">
                                    Total {{ getSortIcon('total') }}
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900 cursor-pointer hover:bg-gray-100 transition-colors" @click="sortBy('order_date')">
                                    Date {{ getSortIcon('order_date') }}
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-gray-900 cursor-pointer hover:bg-gray-100 transition-colors" @click="sortBy('status')">
                                    Status {{ getSortIcon('status') }}
                                </th>
                                <th class="py-4 px-6 text-center font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="paginatedOrders.length === 0" class="border-b border-gray-200">
                                <td colspan="8" class="py-8 px-6 text-center text-gray-500">
                                    <p class="text-lg">No orders found</p>
                                    <p class="text-sm">Try adjusting your filters or search criteria</p>
                                </td>
                            </tr>
                            <tr v-for="(order, index) in paginatedOrders" :key="order.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'" class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                <td class="py-4 px-6">
                                    <input type="checkbox" :value="order.id" v-model="selectedOrders" class="rounded border-gray-300 text-[#5F1213] focus:ring-[#5F1213] cursor-pointer">
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-900">#{{ order.id }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-semibold text-gray-700">
                                            {{ order.first_name.charAt(0) }}{{ order.last_name.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ order.first_name }} {{ order.last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ order.user_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ order.order_items?.length || 0 }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-green-600">₱{{ getOrderTotal(order).toLocaleString() }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-gray-700">{{ formatDate(order.order_date) }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span :class="getStatusClass(order.status)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ capitalizeStatus(order.status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex gap-2 justify-center">
                                        <button @click="viewOrder(order)" class="bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors" title="View Details">
                                            View
                                        </button>
                                        <button v-if="canUpdateStatus(order.status)" @click="openStatusModal(order)" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors" title="Update Status">
                                            Update
                                        </button>
                                        <button @click="printInvoice(order)" class="bg-purple-500 hover:bg-purple-600 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors" title="Print Invoice">
                                            Print
                                        </button>
                                        <div class="relative group">
                                            <button class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors" title="More Options">
                                                ⋮
                                            </button>
                                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                                                <button @click="deleteOrder(order)" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm font-medium rounded-lg">
                                                    Delete Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-semibold">{{ (currentPage - 1) * perPage + 1 }}</span> to <span class="font-semibold">{{ Math.min(currentPage * perPage, filteredOrders.length) }}</span> of <span class="font-semibold">{{ filteredOrders.length }}</span> orders
                    </p>
                    <div class="flex gap-2">
                        <button @click="currentPage--" :disabled="currentPage === 1" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition-colors">
                            ← Previous
                        </button>
                        <div class="flex items-center gap-1">
                            <span class="text-sm text-gray-700">Page <span class="font-semibold">{{ currentPage }}</span> of <span class="font-semibold">{{ totalPages }}</span></span>
                        </div>
                        <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition-colors">
                            Next →
                        </button>
                    </div>
                </div>
            </div>

            <!-- View Modal -->
            <div v-if="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeViewModal"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto z-10">
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                        <h2 class="text-2xl font-bold text-gray-900">Order #{{ viewOrderData?.id }}</h2>
                        <button @click="closeViewModal" class="text-gray-500 hover:text-gray-700 text-2xl transition-colors">✕</button>
                    </div>

                    <div v-if="viewOrderData" class="p-6 space-y-6">
                        <!-- Order Status & Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Customer Info</h3>
                                <p class="text-lg font-medium text-gray-900">{{ viewOrderData.first_name }} {{ viewOrderData.last_name }}</p>
                                <p class="text-sm text-gray-600 mt-1">ID: {{ viewOrderData.user_id }}</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Order Info</h3>
                                <p class="text-sm text-gray-700">{{ formatDate(viewOrderData.order_date) }}</p>
                                <p class="text-sm text-gray-700 mt-1">Items: {{ viewOrderData.order_items?.length || 0 }}</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Status</h3>
                                <span :class="getStatusClass(viewOrderData.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ capitalizeStatus(viewOrderData.status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                <div v-for="item in viewOrderData.order_items" :key="item.id" class="flex gap-4 p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                    <img :src="getProductImage(item.product?.image)" class="w-24 h-24 rounded-lg object-cover border border-gray-300" alt="Product" />
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">{{ item.product?.name || 'N/A' }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">Category: {{ item.product?.category || 'N/A' }}</p>
                                        <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                                            <div>
                                                <p class="text-gray-600">Price</p>
                                                <p class="font-semibold text-gray-900">₱{{ item.product?.price || '0' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Quantity</p>
                                                <p class="font-semibold text-gray-900">{{ item.qty ?? item.quantity ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Subtotal</p>
                                                <p class="font-semibold text-green-600">₱{{ item.subtotal ?? ((item.product?.price || 0) * (item.qty ?? item.quantity ?? 1)) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-gradient-to-r from-[#5F1213] to-red-700 text-white p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium">Total Amount</span>
                                <span class="text-3xl font-bold">₱{{ getOrderTotal(viewOrderData).toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button @click="printInvoice(viewOrderData)" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            🖨 Print Invoice
                        </button>
                        <button @click="closeViewModal" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Update Modal -->
            <div v-if="showStatusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showStatusModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Update Order Status</h2>
                    </div>
                    <div v-if="statusUpdateOrder" class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Order Number</p>
                            <p class="text-lg font-bold text-gray-900">#{{ statusUpdateOrder.id }}</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Current Status</p>
                            <span :class="getStatusClass(statusUpdateOrder.status)" class="inline-flex mt-1 items-center px-3 py-1 rounded-full text-sm font-semibold">
                                {{ capitalizeStatus(statusUpdateOrder.status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">New Status</label>
                            <select
                                v-model="newStatus"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5F1213] focus:border-transparent transition-all"
                            >
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50 rounded-b-2xl">
                        <button @click="showStatusModal = false" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Cancel
                        </button>
                        <button @click="updateOrderStatus" class="bg-[#5F1213] hover:bg-[#7d1a1e] text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminSidebar from './adminSidebar.vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()
const admin = computed(() => page.props.admin)

const props = defineProps({
    orders: Array
})

const currentPage = ref(1)
const perPage = 10

// Search and Filter
const searchQuery = ref('')
const statusFilter = ref('')
const dateRangeFilter = ref('')

// Bulk Actions
const selectedOrders = ref([])
const selectAll = ref(false)

// Sorting
const sortKey = ref('')
const sortAsc = ref(true)

// Modal states
const showStatusModal = ref(false)
const statusUpdateOrder = ref(null)
const newStatus = ref('')

const showViewModal = ref(false)
const viewOrderData = ref(null)

const totalPages = computed(() => Math.ceil(filteredOrders.value.length / perPage))

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

// Filtered orders with date range support
const filteredOrders = computed(() => {
    let filtered = [...props.orders]

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(order =>
            order.id.toString().includes(query) ||
            `${order.first_name} ${order.last_name}`.toLowerCase().includes(query) ||
            order.user_id.toString().includes(query)
        )
    }

    // Apply status filter
    if (statusFilter.value) {
        filtered = filtered.filter(order =>
            order.status.toLowerCase() === statusFilter.value.toLowerCase()
        )
    }

    // Apply date range filter
    if (dateRangeFilter.value) {
        const today = new Date()
        today.setHours(0, 0, 0, 0)
        
        filtered = filtered.filter(order => {
            const orderDate = new Date(order.order_date)
            orderDate.setHours(0, 0, 0, 0)
            
            switch (dateRangeFilter.value) {
                case 'today':
                    return orderDate.getTime() === today.getTime()
                case 'week':
                    const weekAgo = new Date(today)
                    weekAgo.setDate(weekAgo.getDate() - 7)
                    return orderDate >= weekAgo && orderDate <= today
                case 'month':
                    return orderDate.getMonth() === today.getMonth() &&
                           orderDate.getFullYear() === today.getFullYear()
                case 'year':
                    return orderDate.getFullYear() === today.getFullYear()
                default:
                    return true
            }
        })
    }

    return filtered
})

const sortedOrders = computed(() => {
    const list = [...filteredOrders.value]
    if (!sortKey.value) return list

    return list.sort((a, b) => {
        let aVal, bVal

        switch (sortKey.value) {
            case 'id':
                aVal = a.id
                bVal = b.id
                break
            case 'full_name':
                aVal = `${a.first_name} ${a.last_name}`.toLowerCase()
                bVal = `${b.first_name} ${b.last_name}`.toLowerCase()
                return sortAsc.value ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal)
            case 'total':
                aVal = getOrderTotal(a)
                bVal = getOrderTotal(b)
                break
            case 'order_date':
                aVal = new Date(a.order_date).getTime()
                bVal = new Date(b.order_date).getTime()
                break
            case 'status':
                aVal = a.status.toLowerCase()
                bVal = b.status.toLowerCase()
                return sortAsc.value ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal)
            default:
                aVal = a[sortKey.value]
                bVal = b[sortKey.value]
        }

        if (typeof aVal === 'number' && typeof bVal === 'number') {
            return sortAsc.value ? aVal - bVal : bVal - aVal
        }

        return sortAsc.value
            ? String(aVal).localeCompare(String(bVal))
            : String(bVal).localeCompare(String(aVal))
    })
})

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return sortedOrders.value.slice(start, start + perPage)
})

// Watch for changes to reset pagination
watch([searchQuery, statusFilter, dateRangeFilter], () => {
    currentPage.value = 1
    selectedOrders.value = []
    selectAll.value = false
})

// Helper functions
function isPending(status) {
    return status && status.toLowerCase() === 'pending'
}

function capitalizeStatus(status) {
    if (!status) return 'Unknown'
    return status.charAt(0).toUpperCase() + status.slice(1)
}

function getStatusClass(status) {
    if (!status) return 'bg-gray-100 text-gray-800'
    const statusLower = status.toLowerCase()
    
    switch (statusLower) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800'
        case 'paid':
            return 'bg-blue-100 text-blue-800'
        case 'shipped':
            return 'bg-purple-100 text-purple-800'
        case 'delivered':
            return 'bg-indigo-100 text-indigo-800'
        case 'completed':
            return 'bg-green-100 text-green-800'
        case 'cancelled':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

function canUpdateStatus(status) {
    if (!status) return false
    const statusLower = status.toLowerCase()
    return !['completed', 'cancelled'].includes(statusLower)
}

function formatDate(dateString) {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

function getOrderTotal(order) {
    if (!order || !order.order_items) return 0
    return order.order_items.reduce((sum, item) => {
        const price = item.product?.price || 0
        const qty = item.qty ?? item.quantity ?? 1
        const subtotal = item.subtotal ?? (price * qty)
        return sum + Number(subtotal)
    }, 0)
}

function getProductImage(image) {
    if (!image) return '/images/no-image.png'
    if (image.startsWith('http') || image.startsWith('/storage/')) return image
    if (image.startsWith('./storage/')) return image.replace('./', '/')
    return '/images/no-image.png'
}

// Stats functions
function getPendingCount() {
    return filteredOrders.value.filter(o => o.status.toLowerCase() === 'pending').length
}

function getCompletedCount() {
    return filteredOrders.value.filter(o => o.status.toLowerCase() === 'completed').length
}

function getTotalRevenue() {
    return filteredOrders.value.reduce((sum, order) => sum + getOrderTotal(order), 0)
}

// View Modal functions
function viewOrder(order) {
    viewOrderData.value = order
    showViewModal.value = true
}

function closeViewModal() {
    showViewModal.value = false
    viewOrderData.value = null
}

// Mark as Paid
function markAsPaid(order) {
    Swal.fire({
        title: `Mark order #${order.id} as paid?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark as paid',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#5F1213'
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(`/admin/orders/${order.id}/mark-paid`, {}, {
                onSuccess: () => {
                    Swal.fire('Success!', 'Order marked as paid successfully!', 'success')
                },
                onError: (errors) => {
                    console.error('Update failed:', errors)
                    Swal.fire('Error', 'Failed to update order status. Please try again.', 'error')
                }
            })
        }
    })
}

// Delete Order
function deleteOrder(order) {
    Swal.fire({
        title: `Delete order #${order.id}?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/orders/${order.id}`, {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'Order deleted successfully!', 'success')
                },
                onError: (errors) => {
                    console.error('Delete failed:', errors)
                    Swal.fire('Error', 'Failed to delete order. Please try again.', 'error')
                }
            })
        }
    })
}

// Bulk Actions
function toggleSelectAll() {
    if (selectAll.value) {
        selectedOrders.value = paginatedOrders.value.map(order => order.id)
    } else {
        selectedOrders.value = []
    }
}

function bulkMarkAsPaid() {
    if (selectedOrders.value.length === 0) return

    Swal.fire({
        title: `Mark ${selectedOrders.value.length} orders as paid?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark as paid',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#5F1213'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post('/admin/orders/bulk-mark-paid', { order_ids: selectedOrders.value }, {
                onSuccess: () => {
                    Swal.fire('Success!', `${selectedOrders.value.length} orders marked as paid successfully!`, 'success')
                    selectedOrders.value = []
                    selectAll.value = false
                },
                onError: (errors) => {
                    console.error('Bulk update failed:', errors)
                    Swal.fire('Error', 'Failed to update orders. Please try again.', 'error')
                }
            })
        }
    })
}

function bulkMarkAsShipped() {
    if (selectedOrders.value.length === 0) return

    Swal.fire({
        title: `Mark ${selectedOrders.value.length} orders as shipped?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark as shipped',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#5F1213'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post('/admin/orders/bulk-mark-shipped', { order_ids: selectedOrders.value }, {
                onSuccess: () => {
                    Swal.fire('Success!', `${selectedOrders.value.length} orders marked as shipped!`, 'success')
                    selectedOrders.value = []
                    selectAll.value = false
                },
                onError: (errors) => {
                    console.error('Bulk update failed:', errors)
                    Swal.fire('Error', 'Failed to update orders. Please try again.', 'error')
                }
            })
        }
    })
}

function bulkDelete() {
    if (selectedOrders.value.length === 0) return

    Swal.fire({
        title: `Delete ${selectedOrders.value.length} orders?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete them',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post('/admin/orders/bulk-delete', { order_ids: selectedOrders.value }, {
                onSuccess: () => {
                    Swal.fire('Deleted!', `${selectedOrders.value.length} orders deleted successfully!`, 'success')
                    selectedOrders.value = []
                    selectAll.value = false
                },
                onError: (errors) => {
                    console.error('Bulk delete failed:', errors)
                    Swal.fire('Error', 'Failed to delete orders. Please try again.', 'error')
                }
            })
        }
    })
}

function resetFilters() {
    searchQuery.value = ''
    statusFilter.value = ''
    dateRangeFilter.value = ''
    currentPage.value = 1
    selectedOrders.value = []
    selectAll.value = false
    sortKey.value = ''
    sortAsc.value = true
}

// Status Update Modal
function openStatusModal(order) {
    statusUpdateOrder.value = order
    newStatus.value = order.status
    showStatusModal.value = true
}

function updateOrderStatus() {
    if (!statusUpdateOrder.value || !newStatus.value) return

    router.put(`/admin/orders/${statusUpdateOrder.value.id}/status`, { status: newStatus.value }, {
        onSuccess: () => {
            Swal.fire('Success!', 'Order status updated successfully!', 'success')
            showStatusModal.value = false
            statusUpdateOrder.value = null
            newStatus.value = ''
        },
        onError: (errors) => {
            console.error('Status update failed:', errors)
            Swal.fire('Error', 'Failed to update order status. Please try again.', 'error')
        }
    })
}

// Print Invoice
function printInvoice(order) {
    const printWindow = window.open('', '_blank')
    const invoiceHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice #${order.id}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
                .invoice-container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; border-bottom: 3px solid #5F1213; padding-bottom: 20px; margin-bottom: 30px; }
                .header h1 { color: #5F1213; font-size: 28px; }
                .header h2 { color: #666; font-size: 18px; margin-top: 5px; }
                .info-section { margin-bottom: 30px; }
                .info-section h3 { color: #333; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
                .info-section p { color: #666; font-size: 13px; line-height: 1.6; }
                .items { width: 100%; border-collapse: collapse; margin: 30px 0; }
                .items th { background: #5F1213; color: white; padding: 12px; text-align: left; font-weight: 600; }
                .items td { border-bottom: 1px solid #eee; padding: 12px; font-size: 13px; }
                .items tr:nth-child(even) { background: #f9f9f9; }
                .total-section { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; }
                .total-row { display: flex; justify-content: flex-end; margin: 10px 0; }
                .total-label { font-weight: 600; margin-right: 20px; min-width: 100px; }
                .grand-total { font-size: 20px; color: #5F1213; font-weight: bold; margin-top: 15px; }
                .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px; }
                @media print { body { padding: 0; background: white; } .invoice-container { box-shadow: none; } }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                <div class="header">
                    <h1>🛒 Online Market</h1>
                    <h2>Invoice #${order.id}</h2>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                    <div class="info-section">
                        <h3>CUSTOMER INFORMATION</h3>
                        <p><strong>${order.first_name} ${order.last_name}</strong></p>
                        <p>Customer ID: ${order.user_id}</p>
                    </div>
                    <div class="info-section">
                        <h3>ORDER INFORMATION</h3>
                        <p>Order Date: ${formatDate(order.order_date)}</p>
                        <p>Status: <strong>${capitalizeStatus(order.status)}</strong></p>
                    </div>
                </div>

                <table class="items">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${order.order_items.map(item => `
                            <tr>
                                <td>${item.product?.name || 'N/A'}</td>
                                <td>${item.product?.category || 'N/A'}</td>
                                <td>₱${(item.product?.price || 0).toLocaleString()}</td>
                                <td>${item.qty ?? item.quantity ?? 'N/A'}</td>
                                <td>₱${(item.subtotal ?? ((item.product?.price || 0) * (item.qty ?? item.quantity ?? 1))).toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total Amount:</span>
                        <span class="grand-total">₱${getOrderTotal(order).toLocaleString()}</span>
                    </div>
                </div>

                <div class="footer">
                    <p>Thank you for your order! For inquiries, please contact our support team.</p>
                    <p>Printed on ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                </div>
            </div>
        </body>
        </html>
    `
    printWindow.document.write(invoiceHTML)
    printWindow.document.close()
    setTimeout(() => printWindow.print(), 250)
}

// Export to CSV
function exportToCSV() {
    const headers = ['Order ID', 'Customer Name', 'Customer ID', 'Items Count', 'Total Amount', 'Order Date', 'Status']
    const csvContent = [
        headers.join(','),
        ...filteredOrders.value.map(order => [
            order.id,
            `"${order.first_name} ${order.last_name}"`,
            order.user_id,
            order.order_items?.length || 0,
            getOrderTotal(order),
            formatDate(order.order_date),
            capitalizeStatus(order.status)
        ].join(','))
    ].join('\n')

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', `orders_${new Date().toISOString().split('T')[0]}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}
</script>
