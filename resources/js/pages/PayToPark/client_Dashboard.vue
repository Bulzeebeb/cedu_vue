<script setup>
import { router } from '@inertiajs/vue3'

function client_Form() {
  router.visit('/omLandingPage')
}

function omLandingPage() {
  router.visit('/omLandingPage')
}

function goToFacilities() {
  router.visit('/facilities')
}
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-maroon text-white py-3 px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
      <div class="flex items-center gap-2">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-300">iCentral</span></h1>
      </div>
      <nav class="flex flex-wrap justify-center sm:justify-end gap-2">
        <button class="nav-btn">Pay-to-Park</button>
        <button @click="omLandingPage" class="nav-btn">Online Market</button>
        <button @click="goToFacilities" class="nav-btn">Use-of-Facilities</button>
        <button class="nav-btn">About CEDU</button>
        <button class="nav-btn">Contact Us</button>
        <button class="nav-btn">Account</button>
      </nav>
    </header>

    <!-- Pay-to-Park Section -->
    <section class="mt-8 px-4 sm:px-10">
      <h2 class="text-lg font-semibold text-yellow-600 mb-4 border-l-4 border-maroon pl-2">Pay-to-Park</h2>
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <div class="p-4 bg-white rounded shadow-sm flex flex-col items-center text-center text-sm text-gray-700">
            <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
              [ P2P Image Placeholder ]
            </div>
            <p class="mt-2 font-bold text-maroon">Affordable Parking Rates</p>
          </div>
          <div class="p-4 bg-white rounded shadow-sm flex flex-col items-center text-center text-sm text-gray-700">
            <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
              [ Hostel Image Placeholder ]
            </div>
            <p class="mt-2 font-bold text-maroon">Easy Parking Access</p>
          </div>
          <div class="p-4 bg-white rounded shadow-sm flex flex-col items-center text-center text-sm text-gray-700">
            <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
              [ POS Image Placeholder ]
            </div>
            <p class="mt-2 font-bold text-maroon">Fast & Secure POS Transaction</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 sm:px-10 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
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
  name: 'client_Dashboard',
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
.nav-btn {
  background: transparent;
  color: white;
  padding: 6px 12px;
  border-radius: 4px;
  font-weight: 500;
  transition: background-color 0.3s, color 0.3s;
  white-space: nowrap;
}
.nav-btn:hover {
  color: #facc15;
}
</style>
