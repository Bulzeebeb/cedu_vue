<template>
  <div>
    <SiteHeader />

    <!-- Hero Section -->
    <section class="py-10 bg-white">
  <div class="container mx-auto flex flex-col md:flex-row items-center md:items-start gap-10 px-4">

    <!-- Image -->
    <div class="w-full md:w-1/3">
      <img src="/images/OnlineMarket/carosel.png" alt="Fruits Basket" class="w-full h-auto rounded" />
    </div>

    <!-- Text -->
    <div class="w-full md:w-1/2 text-left">
 =    <h2 class="text-lg text-gray-500">WELCOME TO CEDU ONLINE MARKET!</h2>
      <h1 class="text-3xl font-bold text-yellow-600 mt-2">Fresh & Healthy Organic Goods</h1>
      <p class="mt-2 text-gray-600">Free shipping on all your order, we deliver, you enjoy</p>
      <button class="mt-4 px-6 py-2 bg-maroon text-white rounded">Shop now</button>
    </div>

  </div>
</section>


    <!-- Market Products -->
    <section class="bg-gray-100 py-12">
      <h3 class="text-2xl font-bold text-yellow-600 text-center mb-8">CEDU Market Products</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <!-- Products go here -->
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
    SiteFooter
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
</style>
