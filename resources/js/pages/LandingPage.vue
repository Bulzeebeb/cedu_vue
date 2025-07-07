<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <img src="/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-lg font-bold text-white">CEDU | <span class="text-blue-300">Central</span></h1>
      </div>
      <nav class="flex gap-2">
        <button class="nav-btn">Pay-for-Print</button>
        <button class="nav-btn">Online Market</button>
        <button class="nav-btn">Use-of-Facilities</button>
        <button class="nav-btn">About CEDU</button>
        <button class="nav-btn">Contact Us</button>
        <button class="nav-btn">Account</button>
      </nav>
    </header>

    <!-- Carousel -->
    <section class="bg-black h-64 flex justify-between items-center text-white relative mt-2 mx-4 rounded overflow-hidden">
      <button class="text-2xl absolute left-4">&#8592;</button>
      <div class="mx-auto">[ Image Carousel Placeholder ]</div>
      <button class="text-2xl absolute right-4">&#8594;</button>
    </section>

    <!-- Online Market Section -->
    <section class="mt-6 px-4">
      <h2 class="text-lg font-semibold text-yellow-600 mb-2 border-l-4 border-maroon pl-2">Online Market’s Best</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div v-for="item in marketItems" :key="item.name" class="text-center">
          <img :src="item.image" alt="Product" class="w-full h-32 object-cover mb-2" />
          <p>{{ item.name }}</p>
          <p class="text-sm text-gray-600">₱{{ item.price }}</p>
        </div>
      </div>
    </section>

    <!-- Hostel Section -->
    <section class="mt-8 px-4">
      <h2 class="text-lg font-semibold text-yellow-600 mb-2 border-l-4 border-maroon pl-2">Our Hostel</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div v-for="i in 4" :key="i" class="bg-black text-white flex justify-center items-center h-32 sm:h-48 text-xs sm:text-sm">
          REPLACE PHOTO OF HOSTEL
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
      <div>
        <h3 class="font-bold mb-1">Support</h3>
        <p>University of Southeastern Philippines<br>Tagum-Mabini Campus<br>Apokon, Tagum City</p>
        <p>osorio.jg@usep.edu.ph<br>+63915-8538-959</p>
      </div>
      <div>
        <h3 class="font-bold mb-1">Account</h3>
        <p>My Account</p>
        <p>Login / Register</p>
      </div>
      <div>
        <h3 class="font-bold mb-1">Quick Link</h3>
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
  name: 'LandingPage',
  data() {
    return {
      marketItems: [
        { name: 'Egg', price: 300, image: '/images/egg.jpg' },
        { name: 'Watermelons', price: 500, image: '/images/watermelon.jpg' },
        { name: 'Cucumber', price: 150, image: '/images/cucumber.jpg' },
        { name: 'Mangoes', price: 250, image: '/images/mango.jpg' }
      ]
    }
  }
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
