<template>
  <header class="bg-gradient-to-r from-[#650000] to-[#8b1e1e] text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo + Title -->
      <div class="flex items-center space-x-3">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-2xl font-bold tracking-tight">CEDU <span class="text-yellow-400">iCentral</span></h1>
      </div>

      <!-- Hamburger Button (Mobile Only) -->
      <button class="md:hidden focus:outline-none" @click="isMobileMenuOpen = !isMobileMenuOpen">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex space-x-6 font-medium items-center">
        <button @click="active = 'home'" :class="linkClass('home')">Home</button>
        <button @click="active = 'market'" :class="linkClass('market')">Online Market</button>
        <button @click="active = 'park'" :class="linkClass('park')">Pay to Park</button>
        <button @click="active = 'facilities'" :class="linkClass('facilities')">Use of Facilities</button>
        <button @click="active = 'about'" :class="linkClass('about')">About Us</button>

        <!-- Login/Register Buttons -->
        <div class="ml-4 flex space-x-2">
          <button class="px-4 py-1 bg-white text-[#650000] font-semibold rounded hover:bg-yellow-300 transition">Login</button>
          <button class="px-4 py-1 border border-yellow-300 text-yellow-300 rounded hover:bg-yellow-300 hover:text-[#650000] transition">Register</button>
        </div>
      </nav>
    </div>

    <!-- Mobile Nav -->
    <div v-if="isMobileMenuOpen" class="md:hidden px-6 pb-4 text-white space-y-2">
      <button @click="activateTab('home')" :class="mobileLinkClass('home')">Home</button>
      <button @click="activateTab('market')" :class="mobileLinkClass('market')">Online Market</button>
      <button @click="activateTab('park')" :class="mobileLinkClass('park')">Pay to Park</button>
      <button @click="activateTab('facilities')" :class="mobileLinkClass('facilities')">Use of Facilities</button>
      <button @click="activateTab('about')" :class="mobileLinkClass('about')">About Us</button>

      <!-- Login/Register (Mobile) -->
      <div class="pt-4 border-t border-white/20 space-y-2">
        <button class="w-full px-4 py-2 bg-white text-[#650000] font-semibold rounded hover:bg-yellow-300 transition">Login</button>
        <button class="w-full px-4 py-2 border border-yellow-300 text-yellow-300 rounded hover:bg-yellow-300 hover:text-[#650000] transition">Register</button>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: "HeaderGuest",
  data() {
    return {
      active: "home",
      isMobileMenuOpen: false,
    };
  },
  methods: {
    linkClass(tab) {
      return this.active === tab
        ? "text-yellow-300 underline underline-offset-8"
        : "hover:text-yellow-200 transition";
    },
    mobileLinkClass(tab) {
      return this.active === tab
        ? "block w-full text-left px-4 py-2 text-yellow-300 font-semibold bg-[#7a1e1e] rounded"
        : "block w-full text-left px-4 py-2 hover:bg-[#7a1e1e] rounded transition";
    },
    activateTab(tab) {
      this.active = tab;
      this.isMobileMenuOpen = false;
    },
  },
};
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.98);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
</style>
