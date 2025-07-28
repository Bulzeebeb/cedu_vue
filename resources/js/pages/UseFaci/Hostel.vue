<script setup>
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'


// Modal and Booking Controls
const activeTab = ref('all')
const showModal = ref(false)
const showBookingForm = ref(false)
const showBookingButtons = ref(true)
const showSuccessMessage = ref(false)
const selectedRoom = ref(null)

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

// Room Data
const rooms = [
  { id: 1, name: 'Room 1', price: '₱100.00', status: 'unavailable', image: '/room1.jpg', capacity: '4 Pax', bedType: 'Two Double Deck', ventilation: 'Air Condition', restroom: 'Common' },
  { id: 2, name: 'Room 2', price: '₱100.00', status: 'available', image: '/room2.jpg', capacity: '4 Pax', bedType: 'Two Double Deck', ventilation: 'Air Condition', restroom: 'Common' },
  { id: 3, name: 'Room 3', price: '₱100.00', status: 'special', image: '/room3.jpg', capacity: '3 Pax', bedType: 'Single Beds', ventilation: 'Fan Only', restroom: 'Private' },
  { id: 4, name: 'Room 4', price: '₱100.00', status: 'available', image: '/room4.jpg', capacity: '2 Pax', bedType: 'One Double Bed', ventilation: 'Air Condition', restroom: 'Private' }
]

const filteredRooms = computed(() => {
  return activeTab.value === 'all' ? rooms : rooms.filter(room => room.status === activeTab.value)
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
  formErrors.value.email = !email.value.includes('@gmail.com')
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

  if (hasErrors || guestOverflow || !usepAffiliation.value || fileMissing) return

  showSuccessMessage.value = true
  setTimeout(() => closeModal(), 2000)
}

// Real-time Validation
watch(fullName, val => {
  formErrors.value.fullName = !val.trim()
})

watch(email, val => {
  formErrors.value.email = !val.includes('@gmail.com')
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

// Clear ID upload error in real-time
watch(usepIdFile, newVal => {
  if (newVal) {
    // No need to manually clear, it's computed
  }
})

const guestNames = ref([])

watch(numberOfGuests, (newCount) => {
  const validCount = Math.max(1, Math.min(roomMaxPax.value, newCount))
  // Resize guestNames array based on number of guests
  guestNames.value = Array.from({ length: validCount }, (_, i) => guestNames.value[i] || '')
})

</script>
<script>
  window.addEventListener('scroll', () => {
    const indicator = document.getElementById('scrollIndicator');
    if (window.scrollY > 50) {
      indicator.classList.add('opacity-0', 'pointer-events-none');
    } else {
      indicator.classList.remove('opacity-0', 'pointer-events-none');
    }
  });
</script>




<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <h1 class="text-lg font-bold">CEDU <span class="text-yellow-300">iCentral</span></h1>
      <Link href="/facilities" class="text-white bg-transparent hover:underline"> Back to Menu </Link>
    </header>

    <!-- Hero Section -->
    <section class="relative px-6 py-20 overflow-hidden bg-white">
      <div class="absolute top-0 left-0 w-72 h-72 bg-maroon opacity-5 rounded-full blur-3xl animate-ping pointer-events-none"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-300 opacity-10 rounded-full blur-2xl animate-pulse pointer-events-none"></div>

      <!-- Main Content Wrapper -->
      <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-20">
        
        <!-- Left: Illustration -->
        <div class="w-full md:w-1/2 flex justify-center animate-float-slow">
          <img src="/images/Faci_Images/HotelBooking.svg" alt="Booking Illustration" class="w-full max-w-xs md:max-w-md drop-shadow-2xl" />
        </div>

        <!-- Right: Text -->
        <div class="w-full md:w-1/2 text-left space-y-5 animate-fade-in-up">
          <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 leading-snug tracking-wide">
            Book Now Our
          </h2>
          <h1 class="text-6xl sm:text-7xl font-extrabold text-maroon leading-tight drop-shadow-sm">
            Hostel Rooms
          </h1>
          <p class="text-lg text-gray-600 leading-relaxed">
            Enjoy affordable, clean, and secure accommodations designed to make your stay feel like home. We’ve prepared everything — all you need to do is book.
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
  <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Introducing Our Rooms</h2>
  
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
      Available Rooms
    </button>
    <button
      @click="activeTab = 'special'"
      :class="[activeTab === 'special' ? 'bg-yellow-600 text-white shadow-md' : 'bg-gray-100 text-gray-800']"
      class="px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 hover:text-white transition"
    >
      Special Rooms
    </button>
  </div>

  <!-- Room Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    <div
      v-for="room in filteredRooms"
      :key="room.id"
      class="bg-white border rounded-2xl shadow-lg hover:shadow-2xl transition-transform transform hover:scale-[1.03] overflow-hidden duration-300"
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
        <span
          v-else-if="room.status === 'special'"
          class="absolute top-2 left-2 bg-yellow-600 text-white text-xs font-semibold px-2 py-1 rounded shadow"
        >
          Special
        </span>
      </div>
      <div class="p-5 text-center">
        <h3 class="font-bold text-xl text-gray-800 mb-1">{{ room.name }}</h3>
        <p class="text-sm text-gray-600 mb-4">{{ room.price }} <span class="text-xs">/ per Pax</span></p>
        <button
          class="bg-maroon text-white px-4 py-2 rounded-lg w-full font-medium hover:bg-maroon-dark transition disabled:bg-gray-400"
          :disabled="room.status === 'unavailable'"
          @click="openModal(room)"
        >
          Book Now
        </button>
      </div>
    </div>
  </div>
</section>


    <!-- Modal with Booking Form -->
    <div v-if="showModal && selectedRoom" class="fixed inset-0 bg-black bg-opacity-40 z-50 overflow-auto">
      <div id="modal-top" class="bg-white max-w-4xl mx-auto mt-10 mb-20 rounded-2xl shadow-xl overflow-hidden animate-fade-in">
        <div class="p-6 relative">
          <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-black text-3xl font-semibold">&times;</button>
          <img src="/images/Faci_Images/room1.jpg" class="w-full h-80 object-cover rounded mb-4" />
          <h2 class="text-3xl font-bold text-maroon">{{ selectedRoom?.name }}</h2>
          <p class="text-sm text-gray-500 italic mb-2">University of Southeastern Philippines Hostel</p>

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
              <strong>Bed Type:</strong> {{ selectedRoom?.bedType }}
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
            Welcome to the official hostel of the <strong>University of Southeastern Philippines</strong>. Our rooms are clean, secure, and ideal for students and guests.
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
              <input type="text" class="mt-1 block w-full px-3 py-2 border rounded-md" />
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
            <div class="col-span-2" v-if="guestNames.length > 0">
              <label class="block text-sm font-medium text-gray-700 mb-2">Guest Names</label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="(guest, index) in guestNames" :key="index">
                  <input
                    type="text"
                    v-model="guestNames[index]"
                    :placeholder="`Guest ${index + 1} Name`"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md"
                  />
                </div>
              </div>
            </div>



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
                @change="e => usepIdFile.value = e.target.files[0]"
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
            class="px-6 py-2 rounded transition-colors duration-200
                  text-white bg-maroon hover:bg-red-800"
          > Submit Request </button>
          </div>
           <!-- Success Message -->
            <div v-if="showSuccessMessage" class="mt-4 text-green-600 font-medium">
              Booking requested successfully!
            </div>

        </div>

      </div>
    </div>



    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
      <div class="ml-6">
        <h3 class="font-bold mb-1 text-yellow-500">Support</h3>
        <p>University of Southeastern Philippines<br />Tagum-Mabini Campus<br />Apokon, Tagum City</p>
        <p>osorio.jg@usep.edu.ph<br />+63915-8538-959</p>
      </div>
      <div>
        <h3 class="font-bold mb-1 text-yellow-500">Account</h3>
        <p>My Account</p>
        <p>Login / Register</p>
      </div>
      <div>
        <h3 class="font-bold mb-1 text-yellow-500">Quick Link</h3>
        <p>Privacy Policy</p>
        <p>Terms of Use</p>
        <p>FAQ</p>
        <p>Contact</p>
      </div>
    </footer>
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
