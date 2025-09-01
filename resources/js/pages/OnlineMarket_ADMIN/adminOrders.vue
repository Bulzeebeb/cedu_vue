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
                            <td class="py-2 px-2 space-x-2">
                                
                                <button @click="viewOrder(order)"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-medium py-1 px-3 rounded">
                                    View
                                </button>
                                <button v-if="order.status === 'pending'" @click="markAsPaid(order)"
                                    class="bg-green-500 hover:bg-green-600 text-white text-xs font-medium py-1 px-3 rounded">
                                    Mark Paid
                                </button>
                                <button v-if="order.status === 'pending'" @click="deleteOrder(order)"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                    Delete
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

            <!-- Print Tab -->
            <div v-if="showPrintTab" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow-lg w-96">
                    <h2 class="text-xl font-bold mb-4">Print Order #{{ printOrderData?.id }}</h2>
                    <!-- Print Details Here -->
                    <pre class="text-xs">{{ printOrderData }}</pre>
                    <div class="flex justify-end mt-4">
                        <button @click="closePrintTab" class="bg-gray-300 px-3 py-1 rounded mr-2">Close</button>
                        <button @click="doPrint" class="bg-blue-500 text-white px-3 py-1 rounded">Print</button>
                    </div>
                </div>
            </div>

            <!-- View Modal with blur effect -->
            <div v-if="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Blur overlay -->
                <div class="absolute inset-0 bg-black/20 bg-opacity-15 backdrop-blur-sm"></div>
                <!-- Modal card -->
                <div class="relative bg-white p-6 rounded-xl shadow-2xl w-full max-w-md mx-auto z-10">
                    <h2 class="text-xl font-bold mb-4">Order Details #{{ viewOrderData?.id }}</h2>
                    <div v-if="viewOrderData">
                        <div class="mb-2 font-semibold">
                            Customer: {{ viewOrderData.first_name }} {{ viewOrderData.last_name }}
                        </div>
                        <div>
                            <div v-for="item in viewOrderData.order_items" :key="item.id"
                                class="flex items-center mb-4 space-x-3 border-b pb-3">
                                <img :src="getProductImage(item.product?.image)"
                                    class="w-16 h-16 rounded object-cover border" />
                                <div>
                                    <div class="font-bold">{{ item.product?.name || 'N/A' }}</div>
                                    <div class="text-gray-600">₱{{ item.product?.price || 'N/A' }}</div>
                                    <div class="text-gray-600">Qty: {{ item.qty ?? item.quantity ?? 'N/A' }}</div>
                                    <div class="text-gray-600">Subtotal: ₱{{ item.subtotal ?? ((item.product?.price || 0) * (item.qty ?? item.quantity ?? 1)) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-right text-lg font-bold">
                            Total Price: ₱{{ getOrderTotal(viewOrderData) }}
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button @click="closeViewModal"
                            class="bg-indigo-500 text-white px-4 py-2 rounded">Close</button>
                    </div>
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
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()
const admin = computed(() => page.props.admin)

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

const sortedOrders = computed(() => {
    const list = [...props.orders]
    if (!sortKey.value) return list
    return list.sort((a, b) => {
        if (sortKey.value === 'full_name') {
            const nameA = `${a.firstname} ${a.lastname}`.toLowerCase()
            const nameB = `${b.firstname} ${b.lastname}`.toLowerCase()
            return sortAsc.value ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA)
        }
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

// Print Tab state
const showPrintTab = ref(false)
const printOrderData = ref(null)

// View Modal state
const showViewModal = ref(false)
const viewOrderData = ref(null)

function printOrder(order) {
    printOrderData.value = order
    showPrintTab.value = true
}

function closePrintTab() {
    showPrintTab.value = false
    printOrderData.value = null
}

// View functionality
function viewOrder(order) {
    viewOrderData.value = order
    showViewModal.value = true
}

function getOrderTotal(order) {
    if (!order || !order.order_items) return 0;
    return order.order_items.reduce((sum, item) => {
        // Use item.subtotal if present, otherwise compute price * qty
        const price = item.product?.price || 0;
        const qty = item.qty ?? item.quantity ?? 1;
        const subtotal = item.subtotal ?? (price * qty);
        return sum + Number(subtotal);
    }, 0);
}

function closeViewModal() {
    showViewModal.value = false
    viewOrderData.value = null
}

// Mark as Paid with SweetAlert, change status to "Completed"
function markAsPaid(order) {
    Swal.fire({
        title: `Mark order #${order.id} as paid?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark as paid',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send a request to update the order status to "Completed"
            router.put(`/admin/orders/${order.id}/mark-paid`, { status: "Completed" }, {
                onSuccess: () => {
                    refreshOrders()
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

// Delete with SweetAlert
function deleteOrder(order) {
    Swal.fire({
        title: `Delete order #${order.id}?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/orders/${order.id}`, {
                onSuccess: () => {
                    refreshOrders()
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

function getProductImage(image) {
    if (!image) return '/images/no-image.png'
    if (image.startsWith('http') || image.startsWith('/storage/')) return image
    if (image.startsWith('./storage/')) return image.replace('./', '/')
    return '/images/no-image.png'
}

// Use this to refresh the orders list after updates
function refreshOrders() {
    router.reload({ only: ['orders'] })
}
</script>