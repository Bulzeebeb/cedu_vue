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

   <!--  -->
   <!-- Pay-to-Park Section -->
<section class="mt-8 mx-10">
  <h2 class="text-lg font-semibold text-yellow-600 mb-4 border-l-4 border-maroon pl-2">Pay-to-Park</h2>
  <div class="max-w-6xl mx-auto px-2 sm:px-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <div class="p-2 bg-white rounded shadow-sm flex flex-col items-center justify-center text-sm text-gray-700">
        <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
          [ P2P Image Placeholder ]
        </div>
        <p class="mt-2 font-bold text-maroon">Affordable Parking Rates</p>
      </div>
      <div class="p-2 bg-white rounded shadow-sm flex flex-col items-center justify-center text-sm text-gray-700">
        <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
          [ Hostel Image Placeholder ]
        </div>
        <p class="mt-2 font-bold text-maroon">Easy Parking Rates</p>
      </div>
      <div class="p-2 bg-white rounded shadow-sm flex flex-col items-center justify-center text-sm text-gray-700">
        <div class="bg-gray-200 w-full aspect-square flex items-center justify-center border border-gray-400 rounded">
          [ Hostel Image Placeholder ]
        </div>
        <p class="mt-2 font-bold text-maroon">Fast & Secure POS Transaction</p>
      </div>
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
  name: 'client_Dashboard'
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
  background: white;
  color: black;
  padding: 6px 12px;
  border-radius: 4px;
  font-weight: 500;
}
</style>
