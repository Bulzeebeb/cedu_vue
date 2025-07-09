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
    <SiteHeader />

<!-- Hero Carousel Section -->
<section class="py-7 bg-white relative overflow-hidden">
  <div class="container mx-auto flex flex-col md:flex-row items-center md:items-start justify-center gap-10 px-10">
    <!-- Carousel Image -->
    <div class="w-full md:w-1/3">
      <img
        :src="slides[currentSlide].image"
        alt="Hero Slide"
        class="w-full h-auto rounded transition duration-500"
      />
    </div>

    <!-- Carousel Text -->
    <div class="w-full md:w-1/2 text-center md:text-left mt-6 md:mt-0">
      <h2 class="text-lg text-gray-500">{{ slides[currentSlide].subtitle }}</h2>
      <h1 class="text-3xl font-bold text-yellow-600 mt-2">
        {{ slides[currentSlide].title }}
      </h1>
      <p class="mt-2 text-gray-600">{{ slides[currentSlide].description }}</p>
      <button
        @click="handleShopNow"
        class="mt-4 px-6 py-2 bg-maroon text-white rounded-full"
      >
        Shop now →
      </button>
    </div>
    </div>

      <!-- Carousel Arrows -->
      <button
        @click="prevSlide"
        class="hidden md:block absolute top-1/2 left-4 transform -translate-y-1/2 text-gray-500 px-3 py-1 rounded"
      >
        ‹
      </button>
      <button
        @click="nextSlide"
        class="hidden md:block absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500 px-3 py-1 rounded"
      >
        ›
      </button>

      <!-- Carousel Dots -->
      <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <span
          v-for="(slide, index) in slides"
          :key="index"
          @click="currentSlide = index"
          :class="[
            'w-3 h-1 rounded-full cursor-pointer transition duration-300',
            currentSlide === index ? 'bg-yellow-600' : 'bg-gray-300'
          ]"
        ></span>
      </div>
    </section>

    <!-- Market Products -->
    <section class="bg-gray-100 py-12">
      <h3 class="text-2xl font-bold text-yellow-600 text-center mb-8">
        CEDU Market Products
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-4 md:px-8">
        <!-- VEGETABLE Card -->
        <div
          class="relative rounded-xl overflow-hidden shadow-lg h-[350px] bg-cover bg-center group"
          style="background-image: url('images/OnlineMarket/Vegetable_Card.png');"
        >
          <div
            class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"
          ></div>
          <div class="relative flex flex-col justify-between items-center h-full px-6 py-8">
            <h4 class="text-2xl font-bold text-white text-center">VEGETABLE</h4>
            <button
              @click="goToVegetable"
              class="px-4 py-2 bg-maroon text-white rounded-full hover:bg-maroon/90"
            >
              Shop now →
            </button>
          </div>
        </div>

        <!-- FRUITS Card -->
        <div
          class="relative rounded-xl overflow-hidden shadow-lg h-[350px] bg-cover bg-center group"
          style="background-image: url('images/OnlineMarket/Fruit_Card.png');"
        >
          <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
          <div class="relative flex flex-col justify-between items-center h-full px-6 py-8">
            <h4 class="text-2xl font-bold text-white text-center">FRUITS</h4>
            <button
              @click="goToFruit"
              class="px-4 py-2 bg-maroon text-white rounded-full hover:bg-maroon/90"
            >
              Shop now →
            </button>
          </div>
        </div>

        <!-- POULTRY Card -->
        <div
          class="relative rounded-xl overflow-hidden shadow-lg h-[350px] bg-cover bg-center group"
          style="background-image: url('images/OnlineMarket/Poultry_Card.png');"
        >
          <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
          <div class="relative flex flex-col justify-between items-center h-full px-6 py-8">
            <h4 class="text-2xl font-bold text-white text-center">POULTRY</h4>
            <button
              @click="goToPoultry"
              class="px-4 py-2 bg-maroon text-white rounded-full hover:bg-maroon/90"
            >
              Shop now →
            </button>
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
