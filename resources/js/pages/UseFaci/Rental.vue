<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import Header from "./header.vue";
import Footer from "./footer.vue";

// Props from backend
const props = defineProps({
  category: {
    type: Object,
    default: null
  },
  facilities: {
    type: Array,
    default: () => []
  }
})

const activeTab = ref('all')
const showModal = ref(false)
const showBookingForm = ref(false)
const selectedRoom = ref(null)

// Transform facilities data to match the room structure
const rooms = computed(() => {
  return props.facilities.map(facility => {
    // Parse details to extract room information
    const details = facility.details || {}

    // Extract price and unit
    const priceRate = details['Price/Rate'] || '₱2,000.00'
    const unit = details['Unit'] || 'per month'
    const priceDisplay = `${priceRate} / ${unit}`

    return {
      id: facility.id,
      name: facility.name,
      price: priceDisplay,
      status: facility.is_active ? 'available' : 'unavailable',
      size: details['Size'] || '20 sqm',
      image: facility.image_urls && facility.image_urls.length > 0
        ? facility.image_urls[0]
        : '/images/Faci_Images/rent1.jpg', // fallback image
      capacity: details['Capacity'] || '4 Pax',
      unitName: details['Unit Name'] || facility.name,
      utilities: details['Utilities'] || 'N/A',
      allImages: facility.image_urls || []
    }
  })
})

const filteredRooms = computed(() => {
  if (activeTab.value === 'all') return rooms.value
  return rooms.value.filter(room => room.status === activeTab.value)
})

function openModal(room) {
  if (room.status !== 'available') return
  selectedRoom.value = room
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function openBookingForm() {
  showModal.value = false
  showBookingForm.value = true
}

function closeBookingForm() {
  showBookingForm.value = false
}

const form = ref({
  customerName: '',
  email: '',
  contact: '',
  checkIn: '',
  checkOut: '',
  startTime: "",
  endTime: "",
  guests: 1,
  requests: "",
})

const times = [
  "06:00 AM","07:00 AM","08:00 AM","09:00 AM","10:00 AM",
  "11:00 AM","12:00 PM","01:00 PM","02:00 PM","03:00 PM",
  "04:00 PM","05:00 PM","06:00 PM","07:00 PM","08:00 PM",
  "09:00 PM","10:00 PM"
]

function submitBooking() {
  // Validate only essential fields
  if (!form.value.checkIn || !form.value.checkOut) {
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: 'Please fill check-in and check-out dates.'
    })
    return
  }

  const payload = {
    facility_id: selectedRoom.value.id,
    customer_name: form.value.customerName?.trim() || 'Guest User',
    customer_email: form.value.email?.trim() || 'guest@usep.edu.ph',
    customer_contact: form.value.contact?.trim() || '09000000000',
    check_in: form.value.checkIn,
    check_out: form.value.checkOut,
    booking_type: 'Rental',
    number_of_guests: parseInt(form.value.guests) || 1,
    additional_notes: form.value.requests?.trim() || 'No additional notes',
    usep_affiliation: 'no',
    usep_id_file: null
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  
  if (!csrfToken) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'CSRF token not found. Please refresh the page.'
    })
    return
  }

  Swal.fire({
    title: 'Processing...',
    allowOutsideClick: false,
    didOpen: async () => {
      Swal.showLoading()
      
      try {
        const res = await fetch('/bookings', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        
        const data = await res.json()
        
        if (res.ok || res.status === 201) {
          Swal.fire({
            icon: 'success',
            title: 'Booking Confirmed!',
            text: 'Your rental booking has been confirmed successfully.',
            confirmButtonColor: '#650000'
          }).then(() => {
            closeBookingForm()
            showModal.value = false
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Booking Failed',
            text: data.message || 'An error occurred while processing your booking.'
          })
        }
      } catch (error) {
        console.error('Booking error:', error)
        Swal.fire({
          icon: 'error',
          title: 'Network Error',
          text: 'Failed to connect to server. Please check your internet connection and try again.'
        })
      }
    }
  })
}
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <Header />

    <!-- Hero Section -->
    <section class="text-center px-6 py-12 bg-gradient-to-b from-maroon/10 to-white">
      <div class="flex flex-col md:flex-row items-center justify-center gap-10">
        <img src="/images/Faci_Images/rent.svg" alt="Rental" class="w-64 md:w-80 animate-float-slow drop-shadow-lg" />
        <div class="text-left">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Rent Our</h2>
          <h1 class="text-6xl font-extrabold text-maroon mt-2">Rental Facilities</h1>
          <p class="mt-3 text-gray-600 max-w-md">
            Experience comfort and functionality with our well-maintained rental spaces designed for productivity and leisure.
          </p>
        </div>
      </div>
    </section>

    <!-- Facilities Section -->
    <section class="px-6 py-12 max-w-7xl mx-auto">
      <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Our Rental Spaces</h2>

      <!-- Tabs -->
      <div class="flex justify-center gap-4 mb-10 flex-wrap">
        <button
          @click="activeTab = 'all'"
          :class="[activeTab === 'all' ? 'bg-maroon text-white shadow-md' : 'bg-gray-100 text-gray-800']"
          class="px-6 py-2 rounded-lg font-semibold hover:bg-maroon hover:text-white transition"
        >
          All
        </button>

        <button
          @click="activeTab = 'available'"
          :class="[activeTab === 'available' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-800']"
          class="px-6 py-2 rounded-lg font-semibold hover:bg-green-600 hover:text-white transition"
        >
          Available Rooms
        </button>

        <button
          @click="activeTab = 'unavailable'"
          :class="[activeTab === 'unavailable' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 text-gray-800']"
          class="px-6 py-2 rounded-lg font-semibold hover:bg-red-600 hover:text-white transition"
        >
          Unavailable
        </button>
      </div>

      <!-- Empty State -->
      <div v-if="filteredRooms.length === 0" class="text-center py-20">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Rental Spaces Available</h3>
        <p class="text-gray-500">Check back later for available rental facilities.</p>
      </div>

      <!-- Rooms Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="room in filteredRooms"
          :key="room.id"
          class="relative rounded-2xl overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-2"
          :class="room.status === 'unavailable' ? 'opacity-60 pointer-events-none' : 'cursor-pointer hover:shadow-2xl'"
          @click="openModal(room)"
        >
          <img :src="room.image" alt="Room" class="w-full h-56 object-cover" />
          <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold text-white"
               :class="room.status === 'available' ? 'bg-green-600' : 'bg-red-600'">
            {{ room.status.charAt(0).toUpperCase() + room.status.slice(1) }}
          </div>
          <div class="p-5 bg-white">
            <h3 class="text-xl font-bold text-maroon">{{ room.name }}</h3>
            <p class="text-gray-600 text-sm">Size: {{ room.size }}</p>
            <p class="text-gray-800 font-semibold mt-2">{{ room.price }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Room Modal -->
    <div v-if="showModal && selectedRoom" class="fixed inset-0 z-50 overflow-auto backdrop-blur-sm flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.2);">
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden relative">
        <button @click="closeModal" class="absolute top-4 right-6 text-3xl text-gray-500 hover:text-gray-800 z-10">&times;</button>
        <img :src="selectedRoom.image" alt="Room" class="w-full h-64 object-cover" />
        <div class="p-6">
          <h2 class="text-3xl font-bold text-maroon mb-2">{{ selectedRoom.name }}</h2>
          <p class="text-gray-800 font-semibold">{{ selectedRoom.price }}</p>
          <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
            <p><strong>Unit Name:</strong> {{ selectedRoom.unitName }}</p>
            <p><strong>Size:</strong> {{ selectedRoom.size }}</p>
            <p><strong>Capacity:</strong> {{ selectedRoom.capacity }}</p>
            <p><strong>Utilities:</strong> {{ selectedRoom.utilities }}</p>
          </div>
          <div class="flex justify-end mt-6 gap-3">
            <button @click="closeModal" class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
            <button @click="openBookingForm" class="px-6 py-2 bg-maroon text-white rounded-lg hover:bg-red-800">Book Now</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Form -->
    <div v-if="showBookingForm && selectedRoom" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <div class="bg-white w-full max-w-xl rounded-2xl p-8 relative shadow-2xl my-8">
        <button @click="closeBookingForm" class="absolute top-4 right-5 text-3xl text-gray-500 hover:text-gray-800">&times;</button>

        <h2 class="text-2xl font-bold text-maroon mb-6">Book {{ selectedRoom?.name }}</h2>

        <form @submit.prevent="submitBooking" class="space-y-5">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-1">Check-in Date</label>
              <input
                type="date"
                v-model="form.checkIn"
                class="w-full border rounded-md p-2"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-semibold mb-1">Check-out Date</label>
              <input
                type="date"
                v-model="form.checkOut"
                class="w-full border rounded-md p-2"
                required
              />
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-1">Start Time</label>
              <select v-model="form.startTime" class="w-full border rounded-md p-2" required>
                <option value="">Select time</option>
                <option v-for="time in times" :key="time" :value="time">{{ time }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-1">End Time</label>
              <select v-model="form.endTime" class="w-full border rounded-md p-2" required>
                <option value="">Select time</option>
                <option v-for="time in times" :key="time" :value="time">{{ time }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Guests / Attendees</label>
            <input
              type="number"
              v-model="form.guests"
              min="1"
              class="w-full border rounded-md p-2"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1">Special Requests</label>
            <textarea v-model="form.requests" rows="3" class="w-full border rounded-md p-2"></textarea>
          </div>

          <div class="flex justify-end gap-3">
            <button
              type="button"
              @click="closeBookingForm"
              class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-maroon text-white px-6 py-2 rounded-lg hover:bg-red-800"
            >
              Confirm Booking
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<style scoped>
.bg-maroon { background-color: #650000; }
.text-maroon { color: #650000; }
@keyframes float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
.animate-float-slow {
  animation: float-slow 4s ease-in-out infinite;
}
</style>
