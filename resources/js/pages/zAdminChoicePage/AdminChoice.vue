<template>
  <div>
    <!-- HEADER -->
     <SiteHeader />
    <!-- SECTION 1: CEDU HIGHLIGHTS -->
    <section class="relative bg-cover bg-center text-white py-35 px-8 text-center"
      style="background-image: url('/images/eagle.jpg')">
      <div class="absolute inset-0 bg-[#650000]/80 backdrop-blur-sm"></div>
      <div class="relative z-10 max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold mb-4 animate-fade-in">Welcome to <span class="text-yellow-400">CEDU
            iCentral,</span> <span class="font-bold">{{($page.props.admin?.first_name || 'User') + '!' }}</span></h2>
        <p class="text-lg mb-12 max-w-2xl mx-auto animate-fade-in delay-200">Choose CEDU Site</p>
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
import Footer from '../Footer.vue'
import SiteHeader from './AdminChoiceHeader.vue'
import { router } from '@inertiajs/vue3'

export default {
  components: {
    Footer, SiteHeader
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
  router.visit('/dashboard')
}
function goToRentalAdmin() {
  router.visit('/use/facidashboard')
}


</script>