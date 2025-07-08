<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3' 

function goToHostel() {
  router.get('/use-of-facilities/hostel')
}

const goToCommercial = () => {
  router.get('/use-of-facilities/commercial') 
}

const goToRental = () => {
  router.get('/use-of-facilities/rental') 
}

</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    
    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <h1 class="text-lg font-bold text-white">CEDU <span class="text-yellow-300">iCentral</span></h1>
      </div>
      <nav class="flex gap-2">
        <button class="text-white bg-transparent hover:underline">Back to Home</button>
      </nav>
    </header>

    <!-- Use-of-Facilities Section -->
    <section class="mt-8 px-4">
      <h2 class="text-3xl font-bold text-yellow-500 mb-4 pl-3 border-l-8 rounded-l bg-opacity-0 border-[#650000] ml-6">
        Use-of-Facilities
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Hostel Rooms -->
        <div class="relative rounded-md overflow-hidden shadow-md ml-6 sm:ml-8">
          <img src="/images/hostel.jpg" alt="Hostel Rooms" class="w-full h-120 object-cover">
          <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-between p-4">
            <h3 class="text-white text-3xl font-bold text-center mt-4">Hostel Rooms</h3>
            <div class="text-center">
              <button
                class="bg-white text-green-600 font-semibold text-lg px-4 py-1 rounded-full mt-4 mb-4"
                @click="goToHostel"
              >
                Book Now →
              </button>
            </div>
          </div>
        </div>

        <!-- Commercial Space -->
        <div class="relative rounded-md overflow-hidden shadow-md">
        <img src="/images/commercial.svg" alt="Commercial Space" class="w-full h-120 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-between p-4">
            <h3 class="text-white text-3xl font-bold text-center mt-4">Commercial Space</h3>
            <div class="text-center">
            <button
                class="bg-white text-green-600 font-semibold text-lg px-4 py-1 rounded-full mt-4 mb-4"
                @click="goToCommercial"
            >
                Rent Now →
            </button>
            </div>
        </div>
        </div>


        <!-- Rental Facilities -->
        <div class="relative rounded-md overflow-hidden shadow-md mr-6 sm:mr-8">
          <img src="/images/rental.jpg" alt="Rental Facilities" class="w-full h-120 object-cover">
          <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-between p-4">
            <h3 class="text-white text-3xl font-bold text-center mt-4">Rental Facilities</h3>
            <div class="text-center">
              <button class="bg-white text-green-600 font-semibold text-lg px-4 py-1 rounded-full mt-4 mb-4"                 
              @click="goToRental"
            >
                Rent Now →
              </button>
            </div>
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

<script>
export default {
  name: 'UF_Home',
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.nav-btn {
  background: white;
  color: black;
  padding: 6px 12px;
  border-radius: 4px;
  font-weight: 500;
}
</style>
