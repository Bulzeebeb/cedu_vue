<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">
      
      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border flex items-center space-x-4">
        <i class="fas fa-calendar-check text-3xl text-[#FFA600]"></i>
        <h1 class="text-2xl font-semibold">Bookings</h1>
      </div>

      <!-- Filters & Sorting -->
      <div class="bg-white rounded-xl p-4 mb-4 shadow border flex items-center gap-4">
        <label class="font-medium text-sm">Category:</label>
        <select v-model="categoryFilter" class="border rounded px-3 py-2 focus:ring-2 focus:ring-[#5F1213]">
          <option value="">All</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>

        <label class="font-medium text-sm">Sort by:</label>
        <select v-model="sortOption" class="border rounded px-3 py-2 focus:ring-2 focus:ring-[#5F1213]">
          <option value="checkIn">Check-In Date</option>
          <option value="duration">Duration</option>
        </select>
      </div>

      <!-- Booking Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">All Bookings</h2>
        <table class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-3">ID</th>
              <th class="py-2 px-3">Facility</th>
              <th class="py-2 px-3">Category</th>
              <th class="py-2 px-3">Customer</th>
              <th class="py-2 px-3">Check-In</th>
              <th class="py-2 px-3">Check-Out</th>
              <th class="py-2 px-3">Duration</th>
              <th class="py-2 px-3">Status</th>
              <th class="py-2 px-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="booking in filteredSortedBookings"
              :key="booking.id"
              class="border-b border-gray-100 hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ booking.id }}</td>
              <td class="py-2 px-3">{{ booking.facility }}</td>
              <td class="py-2 px-3">{{ booking.category }}</td>
              <td class="py-2 px-3">{{ booking.customer }}</td>
              <td class="py-2 px-3">{{ booking.checkIn }}</td>
              <td class="py-2 px-3">{{ booking.checkOut }}</td>
              <td class="py-2 px-3">{{ booking.duration }} days</td>
              <td class="py-2 px-3">
                <span
                  :class="{
                    'px-2 py-1 rounded-full text-xs font-semibold': true,
                    'bg-yellow-200 text-yellow-800': booking.status === 'Pending',
                    'bg-green-200 text-green-800': booking.status === 'Approved',
                    'bg-red-200 text-red-800': booking.status === 'Rejected'
                  }"
                >
                  {{ booking.status }}
                </span>
              </td>
              <td class="py-2 px-3 space-x-2">
                <button
                  class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition"
                  @click="approveBooking(booking.id)"
                >
                  <i class="fas fa-check"></i>
                </button>
                <button
                  class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                  @click="rejectBooking(booking.id)"
                >
                  <i class="fas fa-times"></i>
                </button>
                <button
                  class="px-3 py-1 bg-[#FFA600] text-white rounded hover:bg-[#e69500] transition"
                  @click="viewDetails(booking)"
                >
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Booking Details Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
          <!-- Close Button -->
          <button
            @click="closeModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
          >
            <i class="fas fa-times text-lg"></i>
          </button>

          <h2 class="text-xl font-semibold mb-4">Booking Details</h2>
          <div class="space-y-2 text-sm">
            <p><strong>Facility:</strong> {{ selectedBooking.facility }}</p>
            <p><strong>Category:</strong> {{ selectedBooking.category }}</p>
            <p><strong>Customer:</strong> {{ selectedBooking.customer }}</p>
            <p><strong>Check-In:</strong> {{ selectedBooking.checkIn }}</p>
            <p><strong>Check-Out:</strong> {{ selectedBooking.checkOut }}</p>
            <p><strong>Duration:</strong> {{ selectedBooking.duration }} days</p>
            <p><strong>Status:</strong> {{ selectedBooking.status }}</p>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-2 mt-6">
            <button
              @click="closeModal"
              class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminSidebar from './adminSidebar.vue'

const showModal = ref(false)
const selectedBooking = ref({})
const sortOption = ref('checkIn')
const categoryFilter = ref('')
const bookings = ref([])

onMounted(() => {
  fetchBookings()
})

function fetchBookings() {
  return fetch('/bookings')
    .then(res => res.json())
    .then(data => {
      bookings.value = data
    })
    .catch(error => console.error('Error fetching bookings:', error))
}

const categories = computed(() => [...new Set(bookings.value.map(b => b.category))])

// Filter by category, then sort
const filteredSortedBookings = computed(() => {
  let filtered = categoryFilter.value
    ? bookings.value.filter(b => b.category === categoryFilter.value)
    : [...bookings.value]

  if (sortOption.value === 'checkIn') {
    filtered.sort((a, b) => new Date(a.checkIn) - new Date(b.checkIn))
  } else if (sortOption.value === 'duration') {
    filtered.sort((a, b) => a.duration - b.duration)
  }

  return filtered
})

function approveBooking(id) {
  updateBookingStatus(id, 'Approved')
}

function rejectBooking(id) {
  updateBookingStatus(id, 'Rejected')
}

function updateBookingStatus(id, status) {
  fetch(`/bookings/${id}/status`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ status })
  })
  .then(res => res.json())
  .then(data => {
    fetchBookings() // Refresh the bookings list
  })
  .catch(error => console.error('Error updating booking:', error))
}

function createNotification(bookingId, status, booking) {
  const userId = booking.user_id;
  
  if (!userId) {
    console.warn('Cannot create notification: no user_id found in booking', booking);
    return;
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
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    return res.json();
  })
  .then(data => {
    console.log('✓ Notification created successfully:', data);
  })
  .catch(error => {
    console.error('✗ Error creating notification:', error);
  })
}

function viewDetails(booking) {
  selectedBooking.value = booking
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  selectedBooking.value = {}
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
