<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('all')

// Room data
const rooms = [
  { id: 1, name: 'Room 1', price: '₱100.00 / per hour', status: 'unavailable', image: '/room1.jpg' },
  { id: 2, name: 'Room 2', price: '₱100.00 / per hour', status: 'available', image: '/room2.jpg' },
  { id: 3, name: 'Room 3', price: '₱100.00 / per hour', status: 'special', image: '/room3.jpg' },
  { id: 4, name: 'Room 4', price: '₱100.00 / per hour', status: 'available', image: '/room4.jpg' }
]

// Filter rooms for tab
const filteredRooms = computed(() => {
  if (activeTab.value === 'all') return rooms
  return rooms.filter(room => room.status === activeTab.value)
})
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">

    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <h1 class="text-lg font-bold text-white">CEDU <span class="text-yellow-300">iCentral</span></h1>
      </div>
      <nav class="flex gap-2">
        <button class="text-white bg-transparent hover:underline">Back to Menu</button>
      </nav>
    </header>

    <!-- Hero -->
    <section class="text-center px-6 py-10">
      <div class="flex flex-col md:flex-row items-center justify-center gap-10">
        <img src="/images/HotelBooking.svg" alt="Booking" class="w-150" />
        <div>
          <h2 class="text-2xl font-bold text-black text-left">Book Now Our</h2>
          <h1 class="text-5xl font-bold text-maroon">Hostel Rooms</h1>
        </div>
      </div>
    </section>

    <!-- Room Section -->
    <section class="px-6 py-10">
      <hr class="w-full border-gray-300" style="border-top-width: 0.5px;" />
      <h2 class="text-2xl font-bold text-center mb-6 mt-10">Introducing Our Rooms</h2>

      <!-- Filter Buttons -->
      <div class="flex justify-center gap-4 mb-8">
        <button @click="activeTab = 'all'" :class="['px-4 py-2 rounded', activeTab === 'all' ? 'bg-maroon text-white' : 'bg-gray-200']">All</button>
        <button @click="activeTab = 'available'" :class="['px-4 py-2 rounded', activeTab === 'available' ? 'bg-green-600 text-white' : 'bg-gray-200']">Available Rooms</button>
        <button @click="activeTab = 'special'" :class="['px-4 py-2 rounded', activeTab === 'special' ? 'bg-yellow-600 text-white' : 'bg-gray-200']">Special Rooms</button>
      </div>

      <!-- Mixed Room Display -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div v-for="room in filteredRooms" :key="room.id" class="border rounded-lg shadow hover:shadow-lg overflow-hidden">
          <div class="relative">
            <img :src="room.image" alt="Room" class="w-full h-40 object-cover" />
            <span
              v-if="room.status === 'available'"
              class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-green-600 text-white">Available</span>
            <span
              v-else-if="room.status === 'unavailable'"
              class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-red-600 text-white">Unavailable</span>
            <span
              v-else-if="room.status === 'special'"
              class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded bg-yellow-600 text-white">Special Room</span>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-lg">{{ room.name }}</h3>
            <p class="text-sm mb-4">{{ room.price }}</p>
            <button class="bg-maroon text-white px-4 py-2 rounded w-full" :disabled="room.status !== 'available'">
              Book Now
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
      <div class="ml-6">
        <h3 class="font-bold mb-1 text-yellow-500">Support</h3>
        <p>University of Southeastern Philippines<br>Tagum-Mabini Campus<br>Apokon, Tagum City</p>
        <p>osorio.jg@usep.edu.ph<br>+63915-8538-959</p>
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
</style>
