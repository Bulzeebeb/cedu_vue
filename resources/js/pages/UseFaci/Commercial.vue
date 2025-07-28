<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

// Tabs and modal controls
const activeTab = ref('all')
const showModal = ref(false) // preview modal for room details
const showCommercialBookingForm = ref(false)
const showCommercialModal = ref(false) // main commercial booking modal

// Room selection
const selectedRoom = ref(null)
const selectedCommercialRoom = ref(null)

// Form input states
const commercialEmail = ref('')
const commercialContact = ref('')
const eventType = ref('')
const commercialStartDate = ref('')
const commercialEndDate = ref('')
const showCommercialSuccessMessage = ref(false)

// Rooms data
const rooms = [
  {
    id: 1,
    name: 'Space 1',
    price: '₱2,000.00 / per month',
    status: 'unavailable',
    image: '/images/Faci_Images/rent1.jpg',
    capacity: '5-8 people',
    size: '20 sqm',
    type: 'Commercial',
    ventilation: 'Air-conditioned',
    restroom: 'Shared',
    description: 'Ideal small commercial space with shared restroom and AC.'
  },
  {
    id: 2,
    name: 'Space 2',
    price: '₱2,000.00 / per month',
    status: 'available',
    image: '/images/Faci_Images/rent2.jpg',
    capacity: '10-15 people',
    size: '35 sqm',
    type: 'Office Space',
    ventilation: 'Air-conditioned',
    restroom: 'Private',
    description: 'Spacious office space with private restroom and AC.'
  },
  {
    id: 4,
    name: 'Space 4',
    price: '₱2,000.00 / per month',
    status: 'available',
    image: '/images/Faci_Images/rent4.jpg',
    capacity: '15-20 people',
    size: '50 sqm',
    type: 'Retail',
    ventilation: 'Natural Ventilation',
    restroom: 'Shared',
    description: 'Large retail space with natural ventilation and shared restroom.'
  }
]

// Filtered list based on tab
const filteredRooms = computed(() => {
  if (activeTab.value === 'all') return rooms
  return rooms.filter(room => room.status === activeTab.value)
})

// Open preview modal for selected room
function openModal(room) {
  selectedRoom.value = room
  showModal.value = true
}

// Close preview modal
function closeModal() {
  showModal.value = false
  selectedRoom.value = null
}

// Confirm booking from preview modal
function confirmBooking() {
  if (selectedRoom.value) {
    selectedCommercialRoom.value = selectedRoom.value
    showCommercialModal.value = true
    showCommercialBookingForm.value = true
    showModal.value = false
  } else {
    alert('No room selected.')
  }
}

// Close commercial booking modal and form
function closeCommercialBookingForm() {
  showCommercialModal.value = false
  showCommercialBookingForm.value = false
  resetForm()
}

// Confirm commercial booking (dummy alert)
function confirmCommercialBooking() {
  alert('Room booking confirmed!')
  closeCommercialBookingForm()
}

// Submit booking form (dummy alert + show success)
function submitCommercialBooking() {
  alert('Booking submitted successfully!')
  showCommercialSuccessMessage.value = true

  setTimeout(() => {
    showCommercialSuccessMessage.value = false
    closeCommercialBookingForm()
  }, 2000)
}

function openCommercialModal(room) {
  selectedCommercialRoom.value = room
  showCommercialModal.value = true
  showCommercialBookingForm.value = true
}


// Reset form inputs
function resetForm() {
  commercialEmail.value = ''
  commercialContact.value = ''
  eventType.value = ''
  commercialStartDate.value = ''
  commercialEndDate.value = ''
}
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <h1 class="text-lg font-bold">CEDU <span class="text-yellow-300">iCentral</span></h1>
      <Link href="/facilities" class="text-white bg-transparent hover:underline"> Back to Menu </Link>
    </header>

    <!-- Hero Section -->
    <section class="relative px-6 py-24 overflow-hidden bg-white">
      <div class="absolute top-0 left-0 w-72 h-72 bg-maroon opacity-5 rounded-full blur-3xl animate-ping pointer-events-none"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-300 opacity-10 rounded-full blur-2xl animate-pulse pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-20">
        <div class="w-full md:w-1/2 flex justify-center animate-float-slow">
          <img src="/images/Faci_Images/space.svg" alt="Space Illustration" class="w-full max-w-xs md:max-w-md drop-shadow-2xl" />
        </div>

        <div class="w-full md:w-1/2 text-left space-y-5 animate-fade-in-up">
          <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 leading-snug tracking-wide">Rent Now Our</h2>
          <h1 class="text-6xl sm:text-7xl font-extrabold text-maroon leading-tight drop-shadow-sm">Commercial Spaces</h1>
          <p class="text-lg text-gray-600 leading-relaxed">
            Discover premium spaces for your business or team. Flexible options, modern amenities, and great locations await.
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
      <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Introducing Our Rental Spaces</h2>

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
        <button
          @click="activeTab = 'special'"
          :class="[activeTab === 'special' ? 'bg-yellow-600 text-white shadow-md' : 'bg-gray-100 text-gray-800']"
          class="px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 hover:text-white transition"
        >
          Special Spaces
        </button>
      </div>

      <!-- Space Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <div
          v-for="room in filteredRooms"
          :key="room.id"
          class="bg-white border rounded-2xl shadow-lg hover:shadow-2xl transition-transform transform hover:scale-[1.03] overflow-hidden duration-300"
        >
          <div class="relative">
            <img :src="room.image" alt="Space" class="w-full h-44 object-cover" />
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
            <p class="text-sm text-gray-600 mb-4">{{ room.price }}</p>
            <button
              class="bg-maroon text-white px-4 py-2 rounded-lg w-full font-medium hover:bg-maroon-dark transition disabled:bg-gray-400"
              :disabled="room.status === 'unavailable'"
              @click="openCommercialModal(room)"
            >
              Rent Now
            </button>
          </div>
        </div>
      </div>
    </section>


    <!-- Commercial Modal (Booking Form) -->
    <div v-if="showCommercialModal && selectedCommercialRoom" class="fixed inset-0 bg-black bg-opacity-40 z-50 overflow-auto">
      <div id="modal-top" class="bg-white max-w-4xl mx-auto mt-10 mb-20 rounded-2xl shadow-xl overflow-hidden animate-fade-in">
        <div class="p-6 relative">
          <button
            @click="closeCommercialBookingForm"
            class="absolute top-4 right-4 text-gray-500 hover:text-black text-3xl font-semibold"
            aria-label="Close booking modal"
          >
            &times;
          </button>
          <img :src="selectedCommercialRoom.image" class="w-full h-80 object-cover rounded mb-4" />
          <h2 class="text-3xl font-bold text-maroon">{{ selectedCommercialRoom?.name }}</h2>
          <p class="text-sm text-gray-500 italic mb-2">University of Southeastern Philippines Commercial Facilities</p>

          <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 15v2a2 2 0 002 2h8a2 2 0 002-2v-2" />
            </svg>
            <p class="text-lg text-gray-800 font-semibold">{{ selectedCommercialRoom?.price }} <span class="text-sm text-gray-500">per hour</span></p>
          </div>

          <p class="text-sm text-gray-600 mb-6">{{ selectedCommercialRoom?.description }}</p>

          <div class="flex justify-end gap-2">
            <button @click="closeCommercialBookingForm" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
            <button @click="confirmCommercialBooking" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
              Confirm Rent
            </button>
          </div>
        </div>

        <!-- Commercial Booking Form -->
        <div v-if="showCommercialBookingForm" class="px-6 py-8 border-t bg-gray-50">
          <h3 class="text-2xl font-bold text-maroon mb-4">Rental Request Form</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Renter's Name</label>
              <input type="text" class="mt-1 block w-full px-3 py-2 border rounded-md" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email Address</label>
              <input
                type="email"
                class="mt-1 block w-full px-3 py-2 border rounded-md"
                v-model="commercialEmail"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contact Number</label>
              <input
                type="text"
                maxlength="11"
                class="mt-1 block w-full px-3 py-2 border rounded-md"
                v-model="commercialContact"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Event Type</label>
              <input
                type="text"
                class="mt-1 block w-full px-3 py-2 border rounded-md"
                v-model="eventType"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Start Date & Time</label>
              <input
                type="datetime-local"
                class="mt-1 block w-full px-3 py-2 border rounded-md"
                v-model="commercialStartDate"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">End Date & Time</label>
              <input
                type="datetime-local"
                class="mt-1 block w-full px-3 py-2 border rounded-md"
                v-model="commercialEndDate"
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
              <textarea class="mt-1 block w-full px-3 py-2 border rounded-md"></textarea>
            </div>
          </div>
          <div class="flex justify-between items-center mt-6">
            <button @click="closeCommercialBookingForm" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
            <button @click="submitCommercialBooking" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
              Confirm Rent
            </button>
          </div>
          <div v-if="showCommercialSuccessMessage" class="mt-4 text-green-600 font-medium">
            Rental request submitted successfully!
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

@keyframes float-slow {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}
.animate-float-slow {
  animation: float-slow 4s ease-in-out infinite;
}
</style>

<script>
window.addEventListener('scroll', () => {
  const indicator = document.getElementById('scrollIndicator')
  if (window.scrollY > 50) {
    indicator.classList.add('opacity-0', 'pointer-events-none')
  } else {
    indicator.classList.remove('opacity-0', 'pointer-events-none')
  }
})
</script>
