<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import Header from './header.vue'
import Footer from './footer.vue'

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

// Modal and Booking Controls
const activeTab = ref('all')
const showModal = ref(false)
const showBookingForm = ref(false)
const showBookingButtons = ref(true)
const showSuccessMessage = ref(false)
const selectedRoom = ref(null)
const currentImageIndex = ref(0)

// Form Inputs
const fullName = ref('')
const email = ref('')
const contact = ref('')
const numberOfGuests = ref(1)
const startDate = ref('')
const endDate = ref('')
const usepAffiliation = ref('')
const usepIdFile = ref(null)
const datetimeError = ref('')
const guestNames = ref([])

// Search and Filter
const searchQuery = ref('')
const priceRange = ref([0, 1000])
const capacityFilter = ref('')

// Transform facilities data to match the room structure
const rooms = computed(() => {
  return props.facilities.map(facility => {
    // Parse details to extract room information
    const details = facility.details || {}

    // Extract price and unit
    const priceRate = details['Price/Rate'] || '₱100.00'
    const unit = details['Unit'] || 'per night'
    const priceDisplay = `${priceRate} / ${unit}`

    return {
      id: facility.id,
      name: facility.name,
      price: priceDisplay,
      status: facility.is_active ? 'available' : 'unavailable',
      image: facility.image_urls && facility.image_urls.length > 0
        ? facility.image_urls[0]
        : '/images/Faci_Images/room1.jpg', // fallback image
      capacity: details['Capacity'] || '4 Pax',
      roomName: details['Room Name'] || facility.name,
      utilities: details['Utilities'] || 'N/A',
      ventilation: details['Ventilation'] || 'Air Conditioned',
      restroom: details['Restroom'] || 'Common',
      allImages: facility.image_urls || [],
      description: 'Welcome to the official rental facility of the University of Southeastern Philippines. Our spaces are clean, secure, and ideal for students and guests.'
    }
  })
})

const filteredRooms = computed(() => {
  if (activeTab.value === 'all') return rooms.value
  if (activeTab.value === 'available') {
    return rooms.value.filter(room => room.status === 'available')
  }
  return rooms.value
})

const roomMaxPax = computed(() => {
  if (!selectedRoom.value?.capacity) return 4
  const match = selectedRoom.value.capacity.match(/\d+/)
  return match ? parseInt(match[0]) : 4
})

const guestLimitExceeded = computed(() => numberOfGuests.value > roomMaxPax.value)
const idUploadError = computed(() => usepAffiliation.value === 'yes' && !usepIdFile.value)

// Validation Flags
const formErrors = ref({
  fullName: false,
  email: false,
  contact: false,
  startDateTime: false,
  endDateTime: false,
})

// Email validation regex
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

// Modal Logic
function openModal(room) {
  selectedRoom.value = room
  numberOfGuests.value = 1
  showModal.value = true
  showBookingForm.value = false
  showBookingButtons.value = true
  showSuccessMessage.value = false

  // Reset form
  fullName.value = ''
  email.value = ''
  contact.value = ''
  startDate.value = ''
  endDate.value = ''
  usepAffiliation.value = ''
  usepIdFile.value = null
  datetimeError.value = ''
  guestNames.value = []
  Object.keys(formErrors.value).forEach(key => formErrors.value[key] = false)
}

function closeModal() {
  showModal.value = false
  showBookingForm.value = false
  showBookingButtons.value = true
}

function confirmBooking() {
  showBookingForm.value = true
  showBookingButtons.value = false
}

function submitBooking() {
  // Validate inputs
  formErrors.value.fullName = !fullName.value.trim()
  formErrors.value.email = !emailRegex.test(email.value)
  formErrors.value.contact = contact.value.length !== 11 || !/^\d+$/.test(contact.value)
  formErrors.value.startDateTime = !startDate.value
  formErrors.value.endDateTime = !endDate.value
  datetimeError.value = ''

  // Validate datetime range
  if (startDate.value && endDate.value) {
    const start = new Date(startDate.value)
    const end = new Date(endDate.value)
    if (start >= end) {
      formErrors.value.startDateTime = true
      formErrors.value.endDateTime = true
      datetimeError.value = 'Start time must be earlier than end time.'
    }
  }

  const hasErrors = Object.values(formErrors.value).some(error => error)
  const fileMissing = usepAffiliation.value === 'yes' && !usepIdFile.value
  const guestOverflow = guestLimitExceeded.value

  if (hasErrors || guestOverflow || !usepAffiliation.value || fileMissing) {
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: 'Please fill all required fields correctly.'
    })
    return
  }

  // Show loading state
  Swal.fire({
    title: 'Processing...',
    allowOutsideClick: false,
    didOpen: async () => {
      Swal.showLoading()
      
      // Create FormData for file upload
      const formData = new FormData()
      formData.append('facility_id', selectedRoom.value.id)
      formData.append('customer_name', fullName.value)
      formData.append('customer_email', email.value)
      formData.append('customer_contact', contact.value)
      formData.append('check_in', startDate.value)
      formData.append('check_out', endDate.value)
      formData.append('booking_type', 'Commercial')
      formData.append('number_of_guests', numberOfGuests.value)
      formData.append('additional_notes', '')
      formData.append('usep_affiliation', usepAffiliation.value)
      if (usepIdFile.value) {
        formData.append('usep_id_file', usepIdFile.value)
      }
      
      // Use Inertia router to submit the form
      router.post('/bookings', formData, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Booking Successful!',
            text: 'Your commercial space booking has been submitted successfully.',
            confirmButtonColor: '#650000'
          }).then(() => {
            closeModal()
          })
        },
        onError: (errors) => {
          Swal.fire({
            icon: 'error',
            title: 'Booking Failed',
            text: errors.message || 'An error occurred while processing your booking.'
          })
        }
      })
    }
  })
}

// Real-time Validation
watch(fullName, val => {
  formErrors.value.fullName = !val.trim()
})

watch(email, val => {
  formErrors.value.email = !emailRegex.test(val)
})

watch(contact, val => {
  formErrors.value.contact = val.length !== 11 || !/^\d+$/.test(val)
})

watch([startDate, endDate], ([startVal, endVal]) => {
  formErrors.value.startDateTime = !startVal
  formErrors.value.endDateTime = !endVal
  datetimeError.value = ''
  if (startVal && endVal) {
    const start = new Date(startVal)
    const end = new Date(endVal)
    if (start >= end) {
      formErrors.value.startDateTime = true
      formErrors.value.endDateTime = true
      datetimeError.value = 'Start time must be earlier than end time.'
    }
  }
})

watch(numberOfGuests, (newCount) => {
  const validCount = Math.max(1, Math.min(roomMaxPax.value, newCount))
  guestNames.value = Array.from({ length: validCount }, (_, i) => guestNames.value[i] || '')
})

</script>

<script>
  window.addEventListener('scroll', () => {
    const indicator = document.getElementById('scrollIndicator');
    if (indicator && window.scrollY > 50) {
      indicator.classList.add('opacity-0', 'pointer-events-none');
    } else if (indicator) {
      indicator.classList.remove('opacity-0', 'pointer-events-none');
    }
  });
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <Header />

    <!-- Hero Section -->
    <section class="relative px-6 py-20 overflow-hidden bg-white">
      <div class="absolute top-0 left-0 w-72 h-72 bg-maroon opacity-5 rounded-full blur-3xl animate-ping pointer-events-none"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-300 opacity-10 rounded-full blur-2xl animate-pulse pointer-events-none"></div>

      <!-- Main Content Wrapper -->
      <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-20">

        <!-- Left: Illustration -->
        <div class="w-full md:w-1/2 flex justify-center animate-float-slow">
          <img src="/images/Faci_Images/space.svg" alt="Booking Illustration" class="w-full max-w-xs md:max-w-md drop-shadow-2xl" />
        </div>

        <!-- Right: Text -->
        <div class="w-full md:w-1/2 text-left space-y-5 animate-fade-in-up">
          <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 leading-snug tracking-wide">
            Rent Now Our
          </h2>
          <h1 class="text-6xl sm:text-7xl font-extrabold text-maroon leading-tight drop-shadow-sm">
            Commercial Spaces
          </h1>
          <p class="text-lg text-gray-600 leading-relaxed">
            Discover premium spaces for your business at the University of Southeastern Philippines. Our commercial facilities are designed to provide comfort, convenience, and a professional atmosphere for all your needs.
          </p>
        </div>
      </div>

      <!-- Scroll Down Indicator -->
      <div
        id="scrollIndicator"
        class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-20 flex flex-col items-center space-y-2 transition-opacity duration-300"
      >
        <div class="w-10 h-10 rounded-full border-2 border-gray-400 flex items-center justify-center animate-bounce">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
        <span class="text-xs text-gray-500 tracking-wide">Scroll Down</span>
      </div>

    </section>

    <!-- Filters -->
    <section class="px-6 py-12 bg-white">
      <hr class="w-full border-gray-300 mb-6" style="border-top-width: 0.5px;" />
      <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Introducing Our Commercial Spaces</h2>

      <div class="flex justify-center gap-4 mb-10">
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
          Available Spaces
        </button>
      </div>

      <!-- Room Cards -->
      <div v-if="filteredRooms.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <div
          v-for="room in filteredRooms"
          :key="room.id"
          class="bg-white border rounded-2xl shadow-lg hover:shadow-2xl transition-transform transform hover:scale-[1.03] overflow-hidden duration-300 cursor-pointer"
          @click="room.status === 'available' ? openModal(room) : null"
        >
          <div class="relative">
            <img :src="room.image" alt="Room" class="w-full h-44 object-cover" />
            <span
              v-if="room.status === 'available'"
              class="absolute top-2 left-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded shadow"
            >
              Available
            </span>
            <span
              v-else-if="room.status === 'unavailable'"
              class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded shadow"
            >
              Unavailable
            </span>
          </div>
          <div class="p-5 text-center">
            <h3 class="font-bold text-xl text-gray-800 mb-1">{{ room.name }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ room.price }} <span class="text-xs"></span></p>
            <button
              class="bg-maroon text-white px-4 py-2 rounded-lg w-full font-medium hover:bg-maroon-dark transition disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="room.status === 'unavailable'"
              @click.stop="openModal(room)"
            >
              Book Now
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Spaces Available</h3>
        <p class="text-gray-500">Check back later for available rental spaces.</p>
      </div>
    </section>

    <!-- Modal with Booking Form -->
    <div v-if="showModal && selectedRoom" class="fixed inset-0 z-50 overflow-auto backdrop-blur-sm" style="background-color: rgba(0, 0, 0, 0.2);">
      <div id="modal-top" class="bg-white max-w-4xl mx-auto mt-10 mb-20 rounded-2xl shadow-xl overflow-hidden animate-fade-in">
        <div class="p-6 relative">
          <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-black text-3xl font-semibold">&times;</button>
          <img :src="selectedRoom.image" class="w-full h-80 object-cover rounded mb-4" />
          <h2 class="text-3xl font-bold text-maroon">{{ selectedRoom?.name }}</h2>
          <p class="text-sm text-gray-500 italic mb-2">University of Southeastern Philippines Rental Facility</p>

          <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 15v2a2 2 0 002 2h8a2 2 0 002-2v-2" />
            </svg>
            <p class="text-lg text-gray-800 font-semibold">{{ selectedRoom?.price }} <span class="text-sm text-gray-500">per person</span></p>
          </div>

          <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 mb-6">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              <strong>Capacity:</strong> {{ selectedRoom?.capacity }}
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
              </svg>
              <strong>Utilities:</strong> {{ selectedRoom?.utilities }}
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 4v4m0 4v1m0 4v1m0-20a9 9 0 110 18 9 9 0 010-18z" />
              </svg>
              <strong>Ventilation:</strong> {{ selectedRoom?.ventilation }}
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a3 3 0 016 0v6m2 0h.01M5 17h.01" />
              </svg>
              <strong>Restroom:</strong> {{ selectedRoom?.restroom }}
            </div>
          </div>

          <hr class="my-4 border-gray-300" />
          <p class="text-sm text-gray-600 mb-6">
            {{ selectedRoom.description }}
          </p>

          <div v-if="showBookingButtons" class="flex justify-end gap-2">
            <button @click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
            <button @click="confirmBooking" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 text-white rounded hover:from-green-600 hover:to-green-800">
              Confirm Booking
            </button>
          </div>
        </div>

        <!-- Booking Form Extension -->
        <div v-if="showBookingForm" id="booking-form-section" class="px-6 py-8 border-t bg-gray-50">
          <h3 class="text-2xl font-bold text-maroon mb-4">Booking Request Form</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Booker's Name</label>
              <input
                type="text"
                v-model="fullName"
                :class="['mt-1 block w-full px-3 py-2 border rounded-md', formErrors.fullName ? 'border-red-500' : 'border-gray-300']"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email Address</label>
              <input
                type="email"
                v-model="email"
                :class="['mt-1 block w-full px-3 py-2 border rounded-md', formErrors.email ? 'border-red-500' : 'border-gray-300']"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contact Number</label>
              <input
                type="text"
                v-model="contact"
                maxlength="11"
                :class="['mt-1 block w-full px-3 py-2 border rounded-md', formErrors.contact ? 'border-red-500' : 'border-gray-300']"
              />
            </div>

            <!-- Number of Guests -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Number of Guests</label>
              <input
                type="number"
                v-model="numberOfGuests"
                :max="roomMaxPax"
                min="1"
                :class="[
                  'mt-1 block w-full px-3 py-2 border rounded-md',
                  guestLimitExceeded ? 'border-red-500' : 'border-gray-300'
                ]"
                :placeholder="`Max ${roomMaxPax} guests`"
              />
              <p class="text-xs mt-1" :class="guestLimitExceeded ? 'text-red-600' : 'text-gray-500'">
                {{ guestLimitExceeded
                  ? `Maximum capacity exceeded! Only ${roomMaxPax} guest(s) allowed.`
                  : `Maximum allowed: ${roomMaxPax} guest(s) for this room.` }}
              </p>
            </div>

            <!-- Guest Names Section -->
            

            <div>
              <label class="block text-sm font-medium text-gray-700">Start Date & Time</label>
              <input
                type="datetime-local"
                v-model="startDate"
                :class="['mt-1 block w-full px-3 py-2 border rounded-md', formErrors.startDateTime ? 'border-red-500' : 'border-gray-300']"
              />
              <p v-if="formErrors.startDateTime" class="text-red-500 text-xs mt-1">{{ datetimeError }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">End Date & Time</label>
              <input
                type="datetime-local"
                v-model="endDate"
                :class="['mt-1 block w-full px-3 py-2 border rounded-md', formErrors.endDateTime ? 'border-red-500' : 'border-gray-300']"
              />
              <p v-if="formErrors.endDateTime" class="text-red-500 text-xs mt-1">{{ datetimeError }}</p>
            </div>

            <div>
              <!-- Question with Inline Radio Buttons -->
              <div class="mb-4 flex flex-col md:flex-row md:items-center md:gap-4">
                <label class="block text-sm font-medium text-gray-700 mb-2 md:mb-0">
                  Are you a student or employee of USeP?
                </label>
                <div class="flex items-center gap-4">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="usep-affiliation"
                      value="yes"
                      class="mr-2"
                      v-model="usepAffiliation"
                    />
                    Yes
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="usep-affiliation"
                      value="no"
                      class="mr-2"
                      v-model="usepAffiliation"
                    />
                    No
                  </label>
                </div>
              </div>

              <!-- Upload ID Field (shown only if 'yes') -->
              <div v-if="usepAffiliation === 'yes'" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Upload your USeP ID <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-4">
                  <input
                    type="file"
                    accept="image/*,application/pdf"
                    @change="e => usepIdFile = e.target.files[0]"
                    :class="[
                      'block w-full text-sm text-gray-700 border rounded-md px-3 py-2',
                      idUploadError ? 'border-red-500' : 'border-gray-300'
                    ]"
                  />
                </div>
                <p class="text-xs text-gray-500 mt-1 italic">Accepted formats: JPG, PNG, or PDF. Max size: 2MB.</p>
                <p v-if="idUploadError" class="text-xs text-red-600 mt-1 italic">
                  Upload is required for USeP students or employees.
                </p>
              </div>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
              <textarea class="mt-1 block w-full px-3 py-2 border rounded-md"></textarea>
            </div>
          </div>

          <div class="flex justify-between items-center mt-6">
            <button @click="closeModal" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
            <button
              @click="submitBooking"
              class="px-6 py-2 rounded transition-colors duration-200 text-white bg-maroon hover:bg-red-800"
            >
              Submit Request
            </button>
          </div>

          <!-- Success Message -->
          <div v-if="showSuccessMessage" class="mt-4 text-green-600 font-medium">
            Booking requested successfully!
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
.animate-float-slow {
  animation: float-slow 4s ease-in-out infinite;
}
@keyframes float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
@keyframes fade-in {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>