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
  <div class="min-h-screen bg-[#f9f9f9] font-sans text-gray-800">
    <!-- HEADER -->
    <header class="bg-[#650000] text-white py-4 px-6 shadow-md">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
          <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
          <h1 class="text-xl font-semibold">CEDU <span class="text-yellow-400">iCentral</span></h1>
        </div>
        <nav class="space-x-4 text-sm">
          <button class="nav-link">Pay-to-Park</button>
          <button @click="omLandingPage" class="nav-link">Online Market</button>
          <button @click="goToFacilities" class="nav-link">Use-of-Facilities</button>
          <button class="nav-link">About</button>
          <button class="nav-link">Contact</button>
          <button class="nav-link">Account</button>
        </nav>
      </div>
    </header>

    <!-- HERO SECTION -->
    <section class="text-center py-14 bg-white shadow-sm">
      <h2 class="text-3xl font-bold text-[#650000] mb-2">Welcome to PAY-TO-PARK</h2>
      <p class="text-gray-600">Experience fast, secure, and seamless campus parking services</p>
    </section>

    <!-- FEATURES -->
    <section class="py-12 px-6 sm:px-10 lg:px-20">
      <div class="max-w-6xl mx-auto">
        <h3 class="text-2xl font-semibold text-[#650000] mb-6 text-center">Why Use Pay-to-Park?</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
          <div class="card">
            <img src="/images/PayToPark/Image1.png" alt="Rates" class="w-full h-auto object-contain mb-3 rounded-md" />
            <h4 class="text-lg font-semibold text-[#650000]">Affordable Rates</h4>
            <p class="text-sm text-gray-600">Budget-friendly parking for everyone</p>
          </div>
          <div class="card">
            <img src="/images/PayToPark/Image2.png" alt="Access" class="w-full h-auto object-contain mb-3 rounded-md" />
            <h4 class="text-lg font-semibold text-[#650000]">QR Access</h4>
            <p class="text-sm text-gray-600">Instant entry using secure QR code</p>
          </div>
          <div class="card">
            <img src="/images/PayToPark/Image3.png" alt="Security" class="w-full h-auto object-contain mb-3 rounded-md" />
            <h4 class="text-lg font-semibold text-[#650000]">Secure Area</h4>
            <p class="text-sm text-gray-600">Monitored 24/7 for peace of mind</p>
          </div>
        </div>
      </div>
    </section>

  

   <!-- Footer -->
<footer class="bg-[#650000] text-white mt-20 py-10 px-6 sm:px-12 grid grid-cols-1 sm:grid-cols-3 gap-8 text-sm">
  <div>
    <h3 class="font-bold text-yellow-400 mb-2"> Support</h3>
    <p>University of Southeastern Philippines<br>Tagum-Mabini Campus<br>Apokon, Tagum City</p>
    <p class="mt-2">osorio.jg@usep.edu.ph<br>+63915-8538-959</p>
  </div>
  <div>
    <h3 class="font-bold text-yellow-400 mb-2"> Account</h3>
    <p class="cursor-pointer hover:text-yellow-300 transition">My Account</p>
    <p class="cursor-pointer hover:text-yellow-300 transition">Login / Register</p>
  </div>
  <div>
    <h3 class="font-bold text-yellow-400 mb-2"> Quick Links</h3>
    <p class="cursor-pointer hover:text-yellow-300 transition">Privacy Policy</p>
    <p class="cursor-pointer hover:text-yellow-300 transition">Terms of Use</p>
    <p class="cursor-pointer hover:text-yellow-300 transition">FAQ</p>
    <p class="cursor-pointer hover:text-yellow-300 transition">Contact</p>
  </div>
</footer>

  </div>
</template>

<style scoped>
.nav-link {
  background-color: transparent;
  color: white;
  padding: 6px 10px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}
.nav-link:hover {
  background-color: #ffcc00;
  color: #650000;
}
@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-6px);
  }
}

.card {
  background-color: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  animation: float 3s ease-in-out infinite;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.card img {
  transition: transform 0.5s ease;
}

.card:hover img {
  transform: rotate(-1deg) scale(1.05);
}


</style>
