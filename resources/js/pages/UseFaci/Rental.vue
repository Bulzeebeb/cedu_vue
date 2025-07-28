<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('all')
const showModal = ref(false)
const selectedRoom = ref(null)

// Room data
const rooms = [
  {
    id: 1,
    name: 'Rent 1',
    price: '₱2,000.00 / per month',
    status: 'unavailable',
    image: '/images/Faci_Images/rent1.jpg',
    capacity: '4 Pax',
    bedType: 'Two Double Deck',
    ventilation: 'Air Conditioned',
    restroom: 'Common'
  },
  {
    id: 2,
    name: 'Rent 2',
    price: '₱2,000.00 / per month',
    status: 'available',
    image: '/images/Faci_Images/rent2.jpg',
    capacity: '3 Pax',
    bedType: 'Single Bed',
    ventilation: 'Fan Only',
    restroom: 'Private'
  },
  {
    id: 4,
    name: 'Rent 4',
    price: '₱2,000.00 / per month',
    status: 'available',
    image: '/images/Faci_Images/rent4.jpg',
    capacity: '2 Pax',
    bedType: 'Double Bed',
    ventilation: 'Air Conditioned',
    restroom: 'Private'
  }
]

const filteredRooms = computed(() => {
  if (activeTab.value === 'all') return rooms
  return rooms.filter(room => room.status === activeTab.value)
})

function openModal(room) {
  selectedRoom.value = room
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <h1 class="text-lg font-bold">CEDU <span class="text-yellow-300">iCentral</span></h1>
      <button class="text-white bg-transparent hover:underline">Back to Menu</button>
    </header>

    <section class="text-center px-6 py-10">
      <div class="flex flex-col md:flex-row items-center justify-center gap-10">
        <img src="/images/Faci_Images/rent.svg" alt="Rental" class="w-150 animate-float-slow" />
        <div>
          <h2 class="text-3xl font-bold text-black text-left">Rent Now Our</h2>
          <h1 class="text-6xl font-bold text-maroon text-left">Rental Facilities</h1>
        </div>
      </div>
    </section>

    <section class="px-6 py-10">
      <hr class="w-full border-gray-300 mt-0 mb-10" style="border-top-width: 0.5px;" />
      <h2 class="text-2xl font-bold text-center mb-6">Introducing Our Rental Facilities</h2>

      <div class="flex justify-center gap-4 mb-8">
        <button @click="activeTab = 'all'" :class="['px-4 py-2 rounded transition-colors', activeTab === 'all' ? 'bg-maroon text-white hover:bg-red-800' : 'bg-gray-200 hover:bg-gray-300']">All</button>
        <button @click="activeTab = 'available'" :class="['px-4 py-2 rounded transition-colors', activeTab === 'available' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-200 hover:bg-gray-300']">Available Rental Facilities</button>
        <button @click="activeTab = 'coming'" :class="['px-4 py-2 rounded transition-colors', activeTab === 'coming' ? 'bg-yellow-600 text-white hover:bg-yellow-700' : 'bg-gray-200 hover:bg-gray-300']">Ending Soon</button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div
          v-for="room in filteredRooms"
          :key="room.id"
          class="border rounded-lg shadow overflow-hidden transform transition-transform duration-300 hover:scale-105 hover:shadow-xl"
        >
          <div class="relative">
            <img :src="room.image" alt="Room" class="w-full h-40 object-cover" />
            <span v-if="room.status === 'available'" class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-green-600 text-white">Available</span>
            <span v-else-if="room.status === 'unavailable'" class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-red-600 text-white">Unavailable</span>
            <span v-else-if="room.status === 'coming'" class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-yellow-600 text-white">Coming Soon</span>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-lg">{{ room.name }}</h3>
            <p class="text-sm mb-4">{{ room.price }}</p>
            <button
              class="bg-maroon text-white px-4 py-2 rounded w-full disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="room.status !== 'available'"
              @click="openModal(room)"
            >
              Rent Now
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg overflow-hidden relative">
        <button @click="closeModal" class="absolute top-4 right-4 text-2xl text-gray-700 hover:text-black">
          &times;
        </button>
        <img :src="selectedRoom.image" class="w-full h-60 object-cover" />
        <div class="p-6">
          <h2 class="text-2xl font-bold text-maroon mb-2">{{ selectedRoom.name }}</h2>
          <p class="text-lg text-gray-700 mb-4">{{ selectedRoom.price }}</p>
          <div class="text-sm text-gray-600 space-y-1 mb-4">
            <p><strong>Capacity:</strong> {{ selectedRoom.capacity }}</p>
            <p><strong>Bed Type:</strong> {{ selectedRoom.bedType }}</p>
            <p><strong>Ventilation:</strong> {{ selectedRoom.ventilation }}</p>
            <p><strong>Restroom:</strong> {{ selectedRoom.restroom }}</p>
          </div>
          <div class="flex justify-end gap-2">
            <button @click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Confirm Rent</button>
          </div>
        </div>
      </div>
    </div>

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
  0%, 100% {
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
