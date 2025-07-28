<template>
  <div>
    <!-- HEADER -->
    <Header />

    <!-- SECTION 1: CEDU HIGHLIGHTS -->
    <section class="relative bg-cover bg-center text-white py-24 px-8 text-center" style="background-image: url('/images/bg-campus.jpg')">
      <div class="absolute inset-0 bg-[#650000]/80 backdrop-blur-sm"></div>
      <div class="relative z-10 max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold mb-4 animate-fade-in">Welcome to <span class="text-yellow-400">CEDU iCentral</span></h2>
        <p class="text-lg mb-12 max-w-2xl mx-auto animate-fade-in delay-200">Explore student-powered digital services designed for USeP Tagum-Mabini Campus.</p>
        <div class="grid sm:grid-cols-3 gap-8 text-left">
          <div class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/market-icon.png" class="h-12 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Online Market</h3>
            <p class="text-center">Shop fruits, vegetables, and poultry grown by local farmers.</p>
          </div>
          <div class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/parking-icon.png" class="h-12 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Pay to Park</h3>
            <p class="text-center">Affordable parking with digital convenience.</p>
          </div>
          <div class="bg-white text-[#650000] rounded-lg p-6 shadow-lg hover:scale-105 hover:shadow-2xl transition duration-300 ease-in-out">
            <img src="/images/facility-icon.png" class="h-12 mb-4 mx-auto" />
            <h3 class="text-xl font-bold mb-1 text-center">Use of Facilities</h3>
            <p class="text-center">Easily reserve available school facilities.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 2: ONLINE MARKET -->
    <section class="bg-[#fff3e6] py-20 px-8 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-[#650000] mb-4">Online Market</h2>
        <p class="mb-10 text-gray-700 max-w-xl mx-auto">Fresh produce and poultry straight from the campus – accessible anytime.</p>
        <div class="grid sm:grid-cols-3 gap-6">
          <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transition">
            <img src="/images/fruits.jpg" class="h-40 w-full object-cover rounded mb-4" />
            <h3 class="text-lg font-semibold text-[#650000]">Fruits</h3>
          </div>
          <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transition">
            <img src="/images/vegetables.jpg" class="h-40 w-full object-cover rounded mb-4" />
            <h3 class="text-lg font-semibold text-[#650000]">Vegetables</h3>
          </div>
          <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transition">
            <img src="/images/poultry.jpg" class="h-40 w-full object-cover rounded mb-4" />
            <h3 class="text-lg font-semibold text-[#650000]">Poultry</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 3: PAY TO PARK -->
    <section class="bg-white py-20 px-8 text-center">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-[#650000] mb-4">Pay to Park</h2>
        <p class="mb-10 text-gray-700 max-w-2xl mx-auto">Take advantage of our spacious parking lots at affordable rates. Reserved slots and walk-in payments welcome.</p>
        <img src="/images/parking-space.jpg" class="rounded-lg shadow-lg w-full h-80 object-cover hover:scale-105 transition" />
      </div>
    </section>

    <!-- SECTION 4: USE OF FACILITIES (CAROUSEL) -->
    <section class="bg-[#f9f9f9] py-20 px-8 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-[#650000] mb-6">Use of Facilities</h2>
        <p class="mb-10 text-gray-700 max-w-xl mx-auto">Book rooms, labs, and more for student-led activities, research, or events.</p>

        <swiper
          :slides-per-view="1.5"
          :space-between="20"
          :breakpoints="{
            640: { slidesPerView: 2.5 },
            1024: { slidesPerView: 4 }
          }"
          grabCursor
          class="px-4"
        >
          <swiper-slide v-for="facility in facilities" :key="facility.name">
  <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
    <img :src="facility.image" class="w-full h-44 object-cover group-hover:scale-110 transition-transform duration-300" />
    <div class="absolute inset-0 bg-gradient-to-t from-[#650000]/80 via-transparent to-transparent group-hover:backdrop-blur-sm transition" />
    <div class="absolute bottom-3 left-3 right-3 text-white font-semibold text-lg drop-shadow-md">
      {{ facility.name }}
    </div>
  </div>
</swiper-slide>

        </swiper>
      </div>
    </section>

    <!-- FOOTER -->
    <Footer />
  </div>
</template>

<script>
import Header from './Header2.vue'
import Footer from './Footer.vue'

// Swiper imports
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'

export default {
  components: {
    Header,
    Footer,
    swiper: Swiper,
    swiperSlide: SwiperSlide
  },
  data() {
    return {
      facilities: [
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
