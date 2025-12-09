<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

const searchQuery = ref('')
const newsletterEmail = ref('')

function goToVegetable() {
  router.visit('/vegetable')
}
function goToFruit() {
  router.visit('/fruit')
}
function goToPoultry() {
  router.visit('/poultry')
}
function handleSearch() {
  if (searchQuery.value.trim()) {
    router.visit(`/search?q=${encodeURIComponent(searchQuery.value)}`)
  }
}
function subscribeNewsletter() {
  if (newsletterEmail.value.trim()) {
    // Mock subscription - in real app, send to backend
    alert('Thank you for subscribing!')
    newsletterEmail.value = ''
  }
}
</script>

<template>
  <div>
    <div class="pt-20 px-4"></div>
    <SiteHeader />

    <!-- HERO SECTION -->
    <section class="relative bg-cover bg-center text-white py-32 px-8 text-center overflow-hidden"
      style="background-image: url('/images/image.png')">
      <div class="absolute inset-0 bg-[#650000]/5 backdrop-blur-[4px] animate-zoom-bg"></div>

      <div class="relative z-10 max-w-4xl mx-auto">
        <h2 class="text-5xl font-extrabold mb-6 animate-fade-in leading-tight drop-shadow-md">
          Welcome to <span class="text-yellow-400">CEDU Online Market</span>
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-fade-in delay-200 text-gray-100">
          Fresh produce and poultry straight from the campus – supporting local farmers and student-led initiatives.
        </p>
        
        <button @click="goToVegetable"
          class="bg-yellow-400 text-[#5F1213] font-semibold px-6 py-3 rounded-full shadow-lg hover:bg-yellow-300 transition duration-300">
          Shop Now
        </button>
      </div>
    </section>

    <!-- PRODUCT CATEGORY SECTION -->
    <section class="bg-[#fff3e6] py-24 px-6 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-[#650000] mb-6">Shop by Category</h2>
        <p class="mb-12 text-gray-700 max-w-xl mx-auto text-lg">
          Choose from high-quality fruits, vegetables, and poultry grown and raised with care.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 px-4 md:px-8">

          <!-- VEGETABLE Card -->
          <div
            class="relative rounded-2xl overflow-hidden shadow-lg h-[250px] sm:h-[300px] md:h-[350px] bg-cover bg-center group transition-transform duration-300 transform hover:scale-105"
            style="background-image: url('/images/OnlineMarket/Vegetable_Card.png');">
            <div class="relative z-10 flex flex-col justify-between items-center h-full px-4 py-10">
              <h4 class="text-2xl sm:text-3xl font-bold text-white text-center">VEGETABLE</h4>
              <button @click="goToVegetable"
                class="px-6 py-2 bg-yellow-400 text-[#5F1213] font-semibold rounded-full shadow-md hover:bg-yellow-300 transition duration-300">
                Shop Now →
              </button>
            </div>
          </div>

          <!-- FRUITS Card -->
          <div
            class="relative rounded-2xl overflow-hidden shadow-lg h-[250px] sm:h-[300px] md:h-[350px] bg-cover bg-center group transition-transform duration-300 transform hover:scale-105"
            style="background-image: url('/images/OnlineMarket/Fruit_Card.png');">
            <div class="relative z-10 flex flex-col justify-between items-center h-full px-4 py-10">
              <h4 class="text-2xl sm:text-3xl font-bold text-white text-center">FRUITS</h4>
              <button @click="goToFruit"
                class="px-6 py-2 bg-yellow-400 text-[#5F1213] font-semibold rounded-full shadow-md hover:bg-yellow-300 transition duration-300">
                Shop Now →
              </button>
            </div>
          </div>

          <!-- POULTRY Card -->
          <div
            class="relative rounded-2xl overflow-hidden shadow-lg h-[250px] sm:h-[300px] md:h-[350px] bg-cover bg-center group transition-transform duration-300 transform hover:scale-105"
            style="background-image: url('/images/OnlineMarket/Poultry_Card.png');">
            <div class="relative z-10 flex flex-col justify-between items-center h-full px-4 py-10">
              <h4 class="text-2xl sm:text-3xl font-bold text-white text-center">POULTRY</h4>
              <button @click="goToPoultry"
                class="px-6 py-2 bg-yellow-400 text-[#5F1213] font-semibold rounded-full shadow-md hover:bg-yellow-300 transition duration-300">
                Shop Now →
              </button>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="bg-[#fff3e6] py-24 px-6 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-[#650000] mb-6">Why Choose Us?</h2>
        <p class="mb-12 text-gray-700 max-w-xl mx-auto text-lg">
          Discover the benefits of shopping with CEDU Online Market – fresh, local, and convenient.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-orange-100 p-8 rounded-2xl shadow-lg">
            <div class="text-6xl mb-4">🚚</div>
            <h3 class="text-2xl font-bold text-[#650000] mb-4">Fast Delivery</h3>
            <p class="text-gray-700">Get your fresh produce delivered right to your campus residence within hours.</p>
          </div>
          <div class="bg-orange-100 p-8 rounded-2xl shadow-lg">
            <div class="text-6xl mb-4">💰</div>
            <h3 class="text-2xl font-bold text-[#650000] mb-4">Affordable Prices</h3>
            <p class="text-gray-700">Student-friendly pricing on all our organic fruits, vegetables, and poultry.</p>
          </div>
          <div class="bg-orange-100 p-8 rounded-2xl shadow-lg">
            <div class="text-6xl mb-4">🌱</div>
            <h3 class="text-2xl font-bold text-[#650000] mb-4">Support Local Farmers</h3>
            <p class="text-gray-700">Every purchase helps sustain our campus farming community and initiatives.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- HOW IT WORKS SECTION -->
    <section class="bg-white py-24 px-6 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-[#650000] mb-6">How It Works</h2>
        <p class="mb-12 text-gray-700 max-w-xl mx-auto text-lg">
          Ordering fresh produce has never been easier. Follow these simple steps.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-yellow-400 text-[#5F1213] rounded-full flex items-center justify-center text-2xl font-bold mb-4">1</div>
            <h3 class="text-xl font-bold text-[#650000] mb-2">Browse</h3>
            <p class="text-gray-700 text-center">Explore our categories and find your favorite fresh items.</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-yellow-400 text-[#5F1213] rounded-full flex items-center justify-center text-2xl font-bold mb-4">2</div>
            <h3 class="text-xl font-bold text-[#650000] mb-2">Order</h3>
            <p class="text-gray-700 text-center">Add items to your cart and place your order securely.</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-yellow-400 text-[#5F1213] rounded-full flex items-center justify-center text-2xl font-bold mb-4">3</div>
            <h3 class="text-xl font-bold text-[#650000] mb-2">Pay</h3>
            <p class="text-gray-700 text-center">Complete payment through our secure checkout system.</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-yellow-400 text-[#5F1213] rounded-full flex items-center justify-center text-2xl font-bold mb-4">4</div>
            <h3 class="text-xl font-bold text-[#650000] mb-2">Enjoy</h3>
            <p class="text-gray-700 text-center">Receive fresh delivery and enjoy your healthy meal!</p>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="bg-[#fff3e6] py-24 px-6 text-center">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-[#650000] mb-6">What Our Customers Say</h2>
        <p class="mb-12 text-gray-700 max-w-xl mx-auto text-lg">
          Hear from students who love shopping with CEDU Online Market.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-yellow-400 text-4xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-4">"The freshest vegetables I've ever had! Delivery is always on time."</p>
            <p class="font-bold text-[#650000]">- Sarah, Computer Science Student</p>
          </div>
          <div class="bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-yellow-400 text-4xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-4">"Great variety of fruits and the prices are unbeatable for students."</p>
            <p class="font-bold text-[#650000]">- Mike, Engineering Student</p>
          </div>
          <div class="bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-yellow-400 text-4xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-4">"Supporting local farmers while getting quality poultry – perfect!"</p>
            <p class="font-bold text-[#650000]">- Emma, Business Student</p>
          </div>
        </div>
      </div>
    </section>

    <!-- NEWSLETTER SECTION -->
    <section class="bg-[#650000] py-24 px-6 text-center text-white">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-4xl font-bold mb-6">Stay Updated</h2>
        <p class="mb-8 text-xl max-w-2xl mx-auto">
          Subscribe to our newsletter for the latest offers, seasonal specials, and campus farming updates.
        </p>
        <form @submit.prevent="subscribeNewsletter" class="flex flex-col sm:flex-row gap-4 justify-center max-w-lg mx-auto">
          <input v-model="newsletterEmail" type="email" placeholder="Enter your email address" class="flex-1 px-4 py-3 rounded-full text-white shadow-lg" required>
          <button type="submit" class="bg-yellow-400 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:bg-yellow-300 transition duration-300">
            Subscribe
          </button>
        </form>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>



<style scoped>
.bg-maroon {
  background-color: #651818;
}

button {
  cursor: pointer;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

.delay-200 {
  animation-delay: 0.2s;
}

.animate-zoom-bg {
  animation: zoomBg 10s ease-in-out infinite alternate;
}

@keyframes zoomBg {
  from { transform: scale(1); }
  to { transform: scale(1.05); }
}
</style>
