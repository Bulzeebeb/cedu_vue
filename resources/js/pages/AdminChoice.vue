<template>
  <div>
    <!-- HEADER -->
    <header class="bg-gradient-to-r from-[#650000] to-[#8b1e1e] text-white shadow-lg sticky top-0 z-50">
      <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center justify-between">
        <!-- Logo + Title -->
        <div class="flex items-center space-x-3">
          <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
          <h1 class="text-2xl font-bold tracking-tight">CEDU <span class="text-yellow-400">iCentral</span></h1>
        </div>


        <!-- Profile Button (Desktop Only) -->
        <div class="relative ml-4 hidden md:block">
          <button @click="showDropdown = !showDropdown"
            class="flex items-center gap-2 bg-white/20  hover:bg-yellow-400 text-white px-3 py-1.5 rounded-full border border-white/40 hover:scale-105 transition">
            <!-- Blue circular user icon -->
            <div class="bg-[#650000] p-1.5 rounded-full border-2 border-white">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
              </svg>
            </div>

            <!-- Down arrow icon -->
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 11.084l3.71-3.853a.75.75 0 011.1 1.02l-4.25 4.417a.75.75 0 01-1.1 0L5.21 8.27a.75.75 0 01.02-1.06z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div v-if="showDropdown"
            class="absolute right-0 mt-2 w-56 bg-white text-[#650000] font-medium border rounded-lg shadow-lg z-50 animate-fade-in p-4 space-y-3">
            <!-- Greeting -->
            <div class="bg-yellow-400 px-3 py-2 rounded text-[#650000] font-medium text-sm">
              Hello, <span class="font-bold">Admin</span>
            </div>

            <button @click="goToHome"
              class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition w-full text-left">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12h.01M12 15h.01M9 12h.01M12 9h.01M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
              </svg>
              <span class="text-sm">Logout</span>
            </button>


          </div>

        </div>
      </div>
    </header>

    <!-- SECTION 1: CEDU HIGHLIGHTS -->
    <section class="relative bg-cover bg-center text-white py-24 px-8 text-center"
      style="background-image: url('/images/eagle.jpg')">
      <div class="absolute inset-0 bg-[#650000]/80 backdrop-blur-sm"></div>
      <div class="relative z-10 max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold mb-4 animate-fade-in">Welcome to <span class="text-yellow-400">CEDU
            iCentral</span></h2>
        <p class="text-lg mb-12 max-w-2xl mx-auto animate-fade-in delay-200">Choose Admin</p>
        <div class="grid sm:grid-cols-3 gap-8 text-left">
          <div @click="goToOnlineMarketAdmin"
            class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/market-icon.png" class="h-50 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Online Market</h3>
          </div>
          <div @click="goToPayToParkAdmin"
            class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/park-icon.png" class="h-50 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Pay to Park</h3>
          </div>
          <div @click="goToRentalAdmin"
            class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/rent-icon.png" class="h-50 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Use of Facilities</h3>
          </div>
        </div>
      </div>
    </section>
    <!-- FOOTER -->
    <Footer />
  </div>
</template>


<script>
import Footer from './footer.vue'
import { router } from '@inertiajs/vue3'

export default {
  components: {
    Footer
  },
  data() {
    return {
      showDropdown: false,
      facilities: [
        { name: 'Classroom', image: '/images/classroom.jpg' },
        { name: 'Computer Lab', image: '/images/comlab.jpg' },
        { name: 'CEDU Hostel', image: '/images/hostel.jpg' },
        { name: 'Audio Visual Room', image: '/images/avr.jpg' },
        { name: 'Classroom', image: '/images/classroom.png' },
        { name: 'Computer Lab', image: '/images/lab.png' },
        { name: 'CEDU Hostel', image: '/images/hostel.png' },
        { name: 'Audio Visual Room', image: '/images/avr.png' },
        { name: 'CEDU Gymnasium', image: '/images/gym.png' },
        { name: 'Board Room', image: '/images/boardroom.png' }
      ]
    }
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 1s ease-in-out forwards;
}

.animate-fade-in.delay-200 {
  animation-delay: 0.2s;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<script setup>
import { router } from '@inertiajs/vue3'

function goToHome() {
  router.visit('/landingpage')
}
function goToOnlineMarketAdmin() {
  router.visit('/admin/dashboard')
}
function goToPayToParkAdmin() {
  router.visit('/adminDashboardPayToPark')
}
function goToRentalAdmin() {
  router.visit('/rentalfacility')
}

</script>