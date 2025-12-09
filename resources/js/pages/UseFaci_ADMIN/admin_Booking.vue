<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      
      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border flex items-center space-x-4">
        <i class="fas fa-calendar-check text-3xl text-[#FFA600]"></i>
        <h1 class="text-2xl font-semibold">Bookings Management</h1>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Total Bookings</p>
              <p class="text-2xl font-bold text-[#5F1213]">{{ totalBookings }}</p>
            </div>
            <i class="fas fa-calendar-alt text-2xl text-[#FFA600]"></i>
          </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Pending</p>
              <p class="text-2xl font-bold text-yellow-600">{{ pendingBookings }}</p>
            </div>
            <i class="fas fa-clock text-2xl text-yellow-500"></i>
          </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Approved</p>
              <p class="text-2xl font-bold text-green-600">{{ approvedBookings }}</p>
            </div>
            <i class="fas fa-check-circle text-2xl text-green-500"></i>
          </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Rejected</p>
              <p class="text-2xl font-bold text-red-600">{{ rejectedBookings }}</p>
            </div>
            <i class="fas fa-times-circle text-2xl text-red-500"></i>
          </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Cancelled</p>
              <p class="text-2xl font-bold text-gray-600">{{ cancelledBookings }}</p>
            </div>
            <i class="fas fa-ban text-2xl text-gray-500"></i>
          </div>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="bg-white rounded-xl p-6 mb-4 shadow border">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
          <!-- Search -->
          <div>
            <label class="block text-sm font-medium mb-1">Search</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by facility, customer..."
              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
            />
          </div>

          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select v-model="categoryFilter" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]">
              <option value="">All Categories</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select v-model="statusFilter" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]">
              <option value="">All Status</option>
              <option value="Pending">Pending</option>
              <option value="Approved">Approved</option>
              <option value="Rejected">Rejected</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Sort Options -->
          <div>
            <label class="block text-sm font-medium mb-1">Sort by</label>
            <select v-model="sortOption" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]">
              <option value="checkIn">Check-In Date</option>
              <option value="duration">Duration</option>
              <option value="created_at">Created Date</option>
            </select>
          </div>
        </div>

        <!-- Date Range Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium mb-1">Check-In From</label>
            <input
              v-model="checkInFrom"
              type="date"
              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Check-In To</label>
            <input
              v-model="checkInTo"
              type="date"
              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
            />
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
          <button
            @click="clearFilters"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
          >
            <i class="fas fa-eraser mr-2"></i>Clear Filters
          </button>
          <button
            @click="exportToCSV"
            class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition"
          >
            <i class="fas fa-download mr-2"></i>Export CSV
          </button>
          <button
            v-if="selectedBookings.length > 0"
            @click="bulkApprove"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
          >
            <i class="fas fa-check mr-2"></i>Bulk Approve ({{ selectedBookings.length }})
          </button>
          <button
            v-if="selectedBookings.length > 0"
            @click="bulkReject"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
          >
            <i class="fas fa-times mr-2"></i>Bulk Reject ({{ selectedBookings.length }})
          </button>
          <button
            v-if="selectedBookings.length > 0"
            @click="bulkCancel"
            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition"
          >
            <i class="fas fa-ban mr-2"></i>Bulk Cancel ({{ selectedBookings.length }})
          </button>
        </div>
      </div>

      <!-- Booking Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">All Bookings</h2>
          <div class="text-sm text-gray-600">
            Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, totalFilteredBookings) }} of {{ totalFilteredBookings }} bookings
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-8">
          <i class="fas fa-spinner fa-spin text-2xl text-[#5F1213]"></i>
          <p class="mt-2 text-gray-600">Loading bookings...</p>
        </div>

        <!-- No Data State -->
        <div v-else-if="paginatedBookings.length === 0" class="text-center py-8 text-gray-500">
          <i class="fas fa-inbox text-4xl mb-2"></i>
          <p>No bookings found matching your criteria.</p>
        </div>

        <!-- Table -->
        <table v-else class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700 bg-gray-50">
              <th class="py-3 px-4">
                <input
                  type="checkbox"
                  :checked="selectAll"
                  @change="toggleSelectAll"
                  class="rounded border-gray-300 focus:ring-[#5F1213]"
                />
              </th>
              <th class="py-3 px-4">ID</th>
              <th class="py-3 px-4">Facility</th>
              <th class="py-3 px-4">Category</th>
              <th class="py-3 px-4">Customer</th>
              <th class="py-3 px-4">Check-In</th>
              <th class="py-3 px-4">Check-Out</th>
              <th class="py-3 px-4">Duration</th>
              <th class="py-3 px-4">Total Amount</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(booking, index) in paginatedBookings"
              :key="booking.id"
              :class="[
                'border-b border-gray-100 hover:bg-gray-50 transition-colors',
                index % 2 === 0 ? 'bg-white' : 'bg-gray-25'
              ]"
            >
              <td class="py-3 px-4">
                <input
                  type="checkbox"
                  :checked="selectedBookings.includes(booking.id)"
                  @change="toggleBookingSelection(booking.id)"
                  class="rounded border-gray-300 focus:ring-[#5F1213]"
                />
              </td>
              <td class="py-3 px-4 font-medium">{{ booking.id }}</td>
              <td class="py-3 px-4">
                <div class="font-medium">{{ booking.facility }}</div>
                <div class="text-xs text-gray-500">{{ booking.facility_details || 'No details' }}</div>
              </td>
              <td class="py-3 px-4">
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                  {{ booking.category }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="font-medium">{{ booking.customer }}</div>
                <div class="text-xs text-gray-500">{{ booking.customer_email || '' }}</div>
              </td>
              <td class="py-3 px-4">
                <div class="font-medium">{{ formatDate(booking.checkIn) }}</div>
                <div class="text-xs text-gray-500">{{ formatTime(booking.checkIn) }}</div>
              </td>
              <td class="py-3 px-4">
                <div class="font-medium">{{ formatDate(booking.checkOut) }}</div>
                <div class="text-xs text-gray-500">{{ formatTime(booking.checkOut) }}</div>
              </td>
              <td class="py-3 px-4">
                <span class="font-medium">{{ booking.duration }}</span> days
              </td>
              <td class="py-3 px-4 font-medium text-green-600">
                ₱{{ booking.total_amount ? booking.total_amount.toLocaleString() : '0' }}
              </td>
              <td class="py-3 px-4">
                <span
                  :class="{
                    'px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': true,
                    'bg-yellow-100 text-yellow-800': booking.status === 'Pending',
                    'bg-green-100 text-green-800': booking.status === 'Approved',
                    'bg-red-100 text-red-800': booking.status === 'Rejected',
                    'bg-gray-100 text-gray-800': booking.status === 'Cancelled'
                  }"
                >
                  <i
                    :class="{
                      'fas mr-1': true,
                      'fa-clock': booking.status === 'Pending',
                      'fa-check-circle': booking.status === 'Approved',
                      'fa-times-circle': booking.status === 'Rejected',
                      'fa-ban': booking.status === 'Cancelled'
                    }"
                  ></i>
                  {{ booking.status }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex space-x-1">
                  <button
                    v-if="booking.status === 'Pending'"
                    class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition tooltip"
                    @click="approveBooking(booking.id)"
                    title="Approve Booking"
                  >
                    <i class="fas fa-check"></i>
                  </button>
                  <button
                    v-if="booking.status === 'Pending'"
                    class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition tooltip"
                    @click="rejectBooking(booking.id)"
                    title="Reject Booking"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                  <button
                    v-if="booking.status === 'Pending' || booking.status === 'Approved'"
                    class="px-2 py-1 bg-orange-600 text-white rounded text-xs hover:bg-orange-700 transition tooltip"
                    @click="cancelBooking(booking.id)"
                    title="Cancel Booking"
                  >
                    <i class="fas fa-ban"></i>
                  </button>
                  <button
                    class="px-2 py-1 bg-[#FFA600] text-white rounded text-xs hover:bg-[#e69500] transition tooltip"
                    @click="viewDetails(booking)"
                    title="View Details"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button
                    class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition tooltip"
                    @click="editBooking(booking)"
                    title="Edit Booking"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex items-center justify-between mt-6">
          <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-700">Show:</label>
            <select
              v-model="itemsPerPage"
              @change="resetPagination"
              class="border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
            >
              <option :value="10">10</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
            <span class="text-sm text-gray-700">per page</span>
          </div>

          <div class="flex items-center space-x-2">
            <button
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i class="fas fa-chevron-left mr-1"></i>Previous
            </button>

            <button
              v-for="page in visiblePages"
              :key="page"
              @click="typeof page === 'number' ? goToPage(page) : null"
              :class="[
                'px-3 py-1 text-sm border rounded hover:bg-gray-50',
                page === currentPage ? 'bg-[#5F1213] text-white border-[#5F1213]' : '',
                typeof page !== 'number' ? 'cursor-default' : ''
              ]"
              :disabled="typeof page !== 'number'"
            >
              {{ page }}
            </button>

            <button
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next<i class="fas fa-chevron-right ml-1"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Booking Details Modal -->
      <div
        v-if="showDetailsModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative max-h-[90vh] overflow-y-auto">
          <!-- Close Button -->
          <button
            @click="closeDetailsModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
          >
            <i class="fas fa-times text-lg"></i>
          </button>

          <h2 class="text-xl font-semibold mb-6 flex items-center">
            <i class="fas fa-calendar-check text-[#FFA600] mr-2"></i>
            Booking Details
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Basic Information</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Booking ID:</span>
                  <span class="font-medium">#{{ selectedBooking.id }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Facility:</span>
                  <span class="font-medium">{{ selectedBooking.facility }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Category:</span>
                  <span class="font-medium">{{ selectedBooking.category }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Status:</span>
                  <span
                    :class="{
                      'px-2 py-1 rounded-full text-xs font-semibold': true,
                      'bg-yellow-100 text-yellow-800': selectedBooking.status === 'Pending',
                      'bg-green-100 text-green-800': selectedBooking.status === 'Approved',
                      'bg-red-100 text-red-800': selectedBooking.status === 'Rejected',
                      'bg-gray-100 text-gray-800': selectedBooking.status === 'Cancelled'
                    }"
                  >
                    {{ selectedBooking.status }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Customer Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Customer Information</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Name:</span>
                  <span class="font-medium">{{ selectedBooking.customer }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Email:</span>
                  <span class="font-medium">{{ selectedBooking.customer_email || 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Phone:</span>
                  <span class="font-medium">{{ selectedBooking.customer_phone || 'N/A' }}</span>
                </div>
              </div>
            </div>

            <!-- Booking Dates -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Booking Dates</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Check-In:</span>
                  <span class="font-medium">{{ formatDate(selectedBooking.checkIn) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Check-Out:</span>
                  <span class="font-medium">{{ formatDate(selectedBooking.checkOut) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Duration:</span>
                  <span class="font-medium">{{ selectedBooking.duration }} days</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Created:</span>
                  <span class="font-medium">{{ formatDate(selectedBooking.created_at) }}</span>
                </div>
              </div>
            </div>

            <!-- Payment Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Payment Information</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total Amount:</span>
                  <span class="font-medium text-green-600 text-lg">₱{{ selectedBooking.total_amount ? selectedBooking.total_amount.toLocaleString() : '0' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Payment Status:</span>
                  <span class="font-medium">{{ selectedBooking.payment_status || 'Pending' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Facility Details -->
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mb-3">Facility Details</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-gray-700">{{ selectedBooking.facility_details || 'No additional details available.' }}</p>
            </div>
          </div>

          <!-- Special Requests -->
          <div v-if="selectedBooking.special_requests" class="mt-6">
            <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mb-3">Special Requests</h3>
            <div class="bg-blue-50 rounded-lg p-4">
              <p class="text-blue-800">{{ selectedBooking.special_requests }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-3 mt-8 pt-4 border-t">
            <button
              @click="closeDetailsModal"
              class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition"
            >
              <i class="fas fa-times mr-2"></i>Close
            </button>
            <button
              v-if="selectedBooking.status === 'Pending'"
              @click="approveBooking(selectedBooking.id); closeDetailsModal()"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
            >
              <i class="fas fa-check mr-2"></i>Approve
            </button>
            <button
              v-if="selectedBooking.status === 'Pending'"
              @click="rejectBooking(selectedBooking.id); closeDetailsModal()"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
            >
              <i class="fas fa-times mr-2"></i>Reject
            </button>
            <button
              v-if="selectedBooking.status === 'Pending' || selectedBooking.status === 'Approved'"
              @click="cancelBooking(selectedBooking.id); closeDetailsModal()"
              class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition"
            >
              <i class="fas fa-ban mr-2"></i>Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- Edit Booking Modal -->
      <div
        v-if="showEditModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative max-h-[90vh] overflow-y-auto">
          <!-- Close Button -->
          <button
            @click="closeEditModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
          >
            <i class="fas fa-times text-lg"></i>
          </button>

          <h2 class="text-xl font-semibold mb-6 flex items-center">
            <i class="fas fa-edit text-[#FFA600] mr-2"></i>
            Edit Booking
          </h2>

          <form @submit.prevent="updateBooking" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Check-In Date</label>
                <input
                  v-model="editForm.checkIn"
                  type="datetime-local"
                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Check-Out Date</label>
                <input
                  v-model="editForm.checkOut"
                  type="datetime-local"
                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
                  required
                />
              </div>
            </div>

            <!-- Status Update -->
            <div>
              <label class="block text-sm font-medium mb-1">Status</label>
              <select
                v-model="editForm.status"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
                required
              >
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>

            <!-- Special Requests -->
            <div>
              <label class="block text-sm font-medium mb-1">Special Requests</label>
              <textarea
                v-model="editForm.special_requests"
                rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
                placeholder="Any special requests or notes..."
              ></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition"
              >
                <i class="fas fa-times mr-2"></i>Cancel
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition disabled:opacity-50"
              >
                <i class="fas fa-save mr-2"></i>{{ loading ? 'Updating...' : 'Update Booking' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminSidebar from './adminSidebar.vue'
import Swal from 'sweetalert2'

// Reactive variables
const loading = ref(false)
const bookings = ref([])
const selectedBooking = ref({})
const selectedBookings = ref([])
const selectAll = ref(false)

// Modal states
const showDetailsModal = ref(false)
const showEditModal = ref(false)

// Filters and search
const searchQuery = ref('')
const categoryFilter = ref('')
const statusFilter = ref('')
const sortOption = ref('checkIn')
const checkInFrom = ref('')
const checkInTo = ref('')

// Pagination
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Edit form
const editForm = ref({
  checkIn: '',
  checkOut: '',
  status: '',
  special_requests: ''
})

// Statistics computed properties
const totalBookings = computed(() => bookings.value.length)
const pendingBookings = computed(() => bookings.value.filter(b => b.status === 'Pending').length)
const approvedBookings = computed(() => bookings.value.filter(b => b.status === 'Approved').length)
const rejectedBookings = computed(() => bookings.value.filter(b => b.status === 'Rejected').length)
const cancelledBookings = computed(() => bookings.value.filter(b => b.status === 'Cancelled').length)

// Categories computed property
const categories = computed(() => [...new Set(bookings.value.map(b => b.category))])

// Filtered bookings with all filters applied
const filteredBookings = computed(() => {
  let filtered = [...bookings.value]

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(booking =>
      booking.facility.toLowerCase().includes(query) ||
      booking.customer.toLowerCase().includes(query) ||
      booking.customer_email?.toLowerCase().includes(query) ||
      booking.id.toString().includes(query)
    )
  }

  // Category filter
  if (categoryFilter.value) {
    filtered = filtered.filter(booking => booking.category === categoryFilter.value)
  }

  // Status filter
  if (statusFilter.value) {
    filtered = filtered.filter(booking => booking.status === statusFilter.value)
  }

  // Date range filter
  if (checkInFrom.value) {
    const fromDate = new Date(checkInFrom.value)
    filtered = filtered.filter(booking => new Date(booking.checkIn) >= fromDate)
  }
  if (checkInTo.value) {
    const toDate = new Date(checkInTo.value)
    filtered = filtered.filter(booking => new Date(booking.checkIn) <= toDate)
  }

  // Sorting
  if (sortOption.value === 'checkIn') {
    filtered.sort((a, b) => new Date(a.checkIn) - new Date(b.checkIn))
  } else if (sortOption.value === 'duration') {
    filtered.sort((a, b) => a.duration - b.duration)
  } else if (sortOption.value === 'created_at') {
    filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
  }

  return filtered
})

// Pagination computed properties
const totalFilteredBookings = computed(() => filteredBookings.value.length)
const totalPages = computed(() => Math.ceil(totalFilteredBookings.value / itemsPerPage.value))
const paginatedBookings = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredBookings.value.slice(start, end)
})

// Visible pages for pagination
const visiblePages = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const delta = 2
  const range = []
  const rangeWithDots = []

  for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    rangeWithDots.push(1, '...')
  } else {
    rangeWithDots.push(1)
  }

  rangeWithDots.push(...range)

  if (current + delta < total - 1) {
    rangeWithDots.push('...', total)
  } else if (total > 1) {
    rangeWithDots.push(total)
  }

  return rangeWithDots.filter(item => item !== '...' || rangeWithDots.indexOf(item) === rangeWithDots.lastIndexOf(item))
})

// Fetch bookings on mount
onMounted(() => {
  fetchBookings()
})

// API functions
function fetchBookings() {
  loading.value = true
  return fetch('/bookings', {
    credentials: 'same-origin'
  })
    .then(res => res.json())
    .then(data => {
      bookings.value = Array.isArray(data) ? data : []
      loading.value = false
    })
    .catch(error => {
      console.error('Error fetching bookings:', error)
      loading.value = false
      Swal.fire({
        title: 'Error!',
        text: 'Failed to load bookings',
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    })
}

// Utility functions
function formatDate(dateString) {
  if (!dateString) return 'N/A'
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  } catch {
    return 'N/A'
  }
}

function formatTime(dateString) {
  if (!dateString) return ''
  try {
    return new Date(dateString).toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch {
    return ''
  }
}

function formatDateForInput(dateString) {
  if (!dateString) return ''
  try {
    const date = new Date(dateString)
    // Format for datetime-local input (YYYY-MM-DDTHH:MM)
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')
    return `${year}-${month}-${day}T${hours}:${minutes}`
  } catch {
    return ''
  }
}

// Booking status functions
function approveBooking(id) {
  Swal.fire({
    title: 'Approve Booking?',
    text: 'This will approve the booking and notify the customer.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10B981',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, approve it!'
  }).then((result) => {
    if (result.isConfirmed) {
      updateBookingStatus(id, 'Approved')
    }
  })
}

function rejectBooking(id) {
  Swal.fire({
    title: 'Reject Booking?',
    text: 'This will reject the booking and notify the customer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, reject it!'
  }).then((result) => {
    if (result.isConfirmed) {
      updateBookingStatus(id, 'Rejected')
    }
  })
}

function cancelBooking(id) {
  Swal.fire({
    title: 'Cancel Booking?',
    text: 'This will cancel the approved booking.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#F59E0B',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, cancel it!'
  }).then((result) => {
    if (result.isConfirmed) {
      updateBookingStatus(id, 'Cancelled')
    }
  })
}

function updateBookingStatus(id, status) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

  if (!csrfToken) {
    console.error('CSRF token not found')
    Swal.fire({
      title: 'Error!',
      text: 'CSRF token not found. Please refresh the page.',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
    return
  }

  fetch(`/bookings/${id}/status`, {
    method: 'PATCH',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ status })
  })
  .then(res => {
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: ${res.statusText}`)
    }
    return res.json()
  })
  .then(data => {
    if (!data.booking) {
      throw new Error(data.message || 'Failed to update booking status')
    }
    console.log('Status update successful:', data)
    // Show success alert immediately
    Swal.fire({
      title: 'Success!',
      text: `Booking ${status.toLowerCase()} successfully!`,
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    })

    // Create notification
    const booking = bookings.value.find(b => b.id === id)
    if (booking) {
      createNotification(id, status, booking)
    }

    // Refresh the bookings list (don't fail the operation if this fails)
    fetchBookings().catch(error => {
      console.error('Error refreshing bookings list:', error)
      // Optionally show a warning, but don't override the success message
    })
  })
  .catch(error => {
    console.error('Error updating booking:', error)
    Swal.fire({
      title: 'Error!',
      text: `Failed to update booking status: ${error.message}`,
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  })
}

// Bulk operations
function bulkApprove() {
  if (selectedBookings.value.length === 0) return

  Swal.fire({
    title: 'Bulk Approve?',
    text: `Approve ${selectedBookings.value.length} selected booking(s)?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10B981',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, approve all!'
  }).then((result) => {
    if (result.isConfirmed) {
      bulkUpdateStatus('Approved')
    }
  })
}

function bulkReject() {
  if (selectedBookings.value.length === 0) return

  Swal.fire({
    title: 'Bulk Reject?',
    text: `Reject ${selectedBookings.value.length} selected booking(s)?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, reject all!'
  }).then((result) => {
    if (result.isConfirmed) {
      bulkUpdateStatus('Rejected')
    }
  })
}

function bulkCancel() {
  if (selectedBookings.value.length === 0) return

  Swal.fire({
    title: 'Bulk Cancel?',
    text: `Cancel ${selectedBookings.value.length} selected booking(s)?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#F59E0B',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, cancel all!'
  }).then((result) => {
    if (result.isConfirmed) {
      bulkUpdateStatus('Cancelled')
    }
  })
}

function bulkUpdateStatus(status) {
  loading.value = true
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

  if (!csrfToken) {
    console.error('CSRF token not found')
    Swal.fire({
      title: 'Error!',
      text: 'CSRF token not found. Please refresh the page.',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
    loading.value = false
    return
  }

  fetch('/bookings/bulk-status', {
    method: 'PATCH',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      booking_ids: selectedBookings.value,
      status: status
    })
  })
  .then(res => {
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: ${res.statusText}`)
    }
    return res.json()
  })
  .then(data => {
    if (!data.updated_count && data.updated_count !== 0) {
      throw new Error(data.message || 'Failed to update bookings')
    }
    console.log('Bulk status update successful:', data)
    // Refresh the bookings list and wait for it to complete
    return fetchBookings().then(() => {
      selectedBookings.value = []
      selectAll.value = false
      Swal.fire({
        title: 'Success!',
        text: `Bulk ${status.toLowerCase()} completed!`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      })
    })
  })
  .catch(error => {
    console.error('Error bulk updating:', error)
    Swal.fire({
      title: 'Error!',
      text: `Failed to update bookings: ${error.message}`,
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  })
  .finally(() => {
    loading.value = false
  })
}

// Selection functions
function toggleSelectAll() {
  selectAll.value = !selectAll.value
  if (selectAll.value) {
    selectedBookings.value = paginatedBookings.value.map(b => b.id)
  } else {
    selectedBookings.value = []
  }
}

function toggleBookingSelection(bookingId) {
  const index = selectedBookings.value.indexOf(bookingId)
  if (index > -1) {
    selectedBookings.value.splice(index, 1)
  } else {
    selectedBookings.value.push(bookingId)
  }
  selectAll.value = selectedBookings.value.length === paginatedBookings.value.length && paginatedBookings.value.length > 0
}

// Filter and pagination functions
function clearFilters() {
  searchQuery.value = ''
  categoryFilter.value = ''
  statusFilter.value = ''
  checkInFrom.value = ''
  checkInTo.value = ''
  currentPage.value = 1
  selectedBookings.value = []
  selectAll.value = false
}

function exportToCSV() {
  const headers = ['ID', 'Facility', 'Category', 'Customer', 'Check-In', 'Check-Out', 'Duration', 'Status', 'Total Amount']
  const csvData = filteredBookings.value.map(booking => [
    booking.id,
    booking.facility,
    booking.category,
    booking.customer,
    formatDate(booking.checkIn),
    formatDate(booking.checkOut),
    booking.duration,
    booking.status,
    booking.total_amount || 0
  ])

  const csvContent = [headers, ...csvData]
    .map(row => row.map(field => `"${field}"`).join(','))
    .join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  const url = URL.createObjectURL(blob)
  link.setAttribute('href', url)
  link.setAttribute('download', `bookings_${new Date().toISOString().split('T')[0]}.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

// Pagination functions
function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    selectedBookings.value = []
    selectAll.value = false
  }
}

function resetPagination() {
  currentPage.value = 1
  selectedBookings.value = []
  selectAll.value = false
}

// Modal functions
function viewDetails(booking) {
  selectedBooking.value = booking
  showDetailsModal.value = true
}

function closeDetailsModal() {
  showDetailsModal.value = false
  selectedBooking.value = {}
}

function editBooking(booking) {
  selectedBooking.value = booking
  editForm.value = {
    checkIn: booking.checkIn ? formatDateForInput(booking.checkIn) : '',
    checkOut: booking.checkOut ? formatDateForInput(booking.checkOut) : '',
    status: booking.status,
    special_requests: booking.special_requests || ''
  }
  showEditModal.value = true
}

function closeEditModal() {
  showEditModal.value = false
  selectedBooking.value = {}
  editForm.value = {
    checkIn: '',
    checkOut: '',
    status: '',
    special_requests: ''
  }
}

function updateBooking() {
  // Validate date range
  const checkInDate = new Date(editForm.value.checkIn)
  const checkOutDate = new Date(editForm.value.checkOut)

  if (checkOutDate <= checkInDate) {
    Swal.fire({
      title: 'Validation Error!',
      text: 'Check-out date must be after check-in date',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
    return
  }

  loading.value = true
  fetch(`/bookings/${selectedBooking.value.id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(editForm.value)
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      throw new Error(data.message || 'Failed to update booking')
    }
    fetchBookings()
    closeEditModal()
    Swal.fire({
      title: 'Success!',
      text: 'Booking updated successfully!',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    })
  })
  .catch(error => {
    console.error('Error updating booking:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to update booking',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  })
  .finally(() => {
    loading.value = false
  })
}

// Notification function
function createNotification(bookingId, status, booking) {
  const userId = booking.user_id

  if (!userId) {
    console.warn('Cannot create notification: no user_id found in booking', booking)
    return
  }

  const notificationData = {
    booking_id: bookingId,
    user_id: userId,
    title: `Booking ${status}`,
    message: `Your booking for ${booking.facility} has been ${status.toLowerCase()}.`,
    type: 'booking_status',
    read: false
  }

  fetch('/notifications', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(notificationData)
  })
  .then(res => {
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`)
    return res.json()
  })
  .then(data => {
    console.log('✓ Notification created successfully:', data)
  })
  .catch(error => {
    console.error('✗ Error creating notification:', error)
  })
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');

/* Custom styles */
.tooltip {
  position: relative;
}

.tooltip:hover::after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: #333;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
  z-index: 1000;
}

.bg-gray-25 {
  background-color: #f9f9f9;
}

/* Responsive table */
@media (max-width: 768px) {
  .table-responsive {
    font-size: 0.875rem;
  }

  .table-responsive th,
  .table-responsive td {
    padding: 0.5rem;
  }
}

/* Loading animation */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.fa-spin {
  animation: spin 1s linear infinite;
}

/* Status badges */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

/* Modal improvements */
.modal-content {
  max-height: 90vh;
  overflow-y: auto;
}

/* Button hover effects */
.btn-hover:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Table row hover */
.table-row-hover:hover {
  background-color: #f8f9fa;
  transition: background-color 0.2s ease;
}

/* Pagination styles */
.pagination-btn {
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background-color: #5F1213;
  color: white;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
