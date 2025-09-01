<script setup>
import { router } from '@inertiajs/vue3'


function goToVegetable() {
  router.visit('/vegetable')
}
function goToFruit() {
  router.visit('/fruit')
}
function goToPoultry() {
  router.visit('/poultry')
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

    <SiteFooter />
  </div>
</template>

<script>
import SiteHeader from './header.vue';
import SiteFooter from './footer.vue';

export default {
  name: 'omLandingPage',
  components: {
    SiteHeader,
    SiteFooter,
  },
  data() {
    return {
      slides: [
        {
          image: '/images/OnlineMarket/Carousel1.png',
          subtitle: 'WELCOME TO CEDU ONLINE MARKET!',
          title: 'Fresh & Healthy Organic Goods',
          description: 'Free shipping on all your order, we deliver, you enjoy',
        },
        {
          image: '/images/OnlineMarket/Carousel2.png',
          subtitle: 'JOIN THE HEALTHY CHOICE',
          title: 'Organic Fruits & Veggies Delivered',
          description: 'Shop seasonal picks and enjoy doorstep delivery.',
        },
        {
          image: '/images/OnlineMarket/Carousel3.png',
          subtitle: 'SUPPORT LOCAL FARMERS',
          title: 'Eat Fresh, Eat Local',
          description: 'Your trusted source for farm-to-table goodness.',
        },
      ],
      currentSlide: 0,
    };
  },
  methods: {
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length;
    },
    prevSlide() {
      this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
    },
    handleShopNow() {
      if (this.currentSlide === 0) {
        this.goToVegetable();
      } else if (this.currentSlide === 1) {
        this.goToFruit();
      } else if (this.currentSlide === 2) {
        this.goToPoultry();
      }
    },
    goToVegetable() {
      this.$inertia.visit('/vegetable');
    },
    goToFruit() {
      this.$inertia.visit('/fruit');
    },
    goToPoultry() {
      this.$inertia.visit('/poultry');
    },
  },
};
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}

button {
  cursor: pointer;
}
</style>
