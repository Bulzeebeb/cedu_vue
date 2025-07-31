<script setup>
import { router } from '@inertiajs/vue3'

function goToSignIn() {
  router.visit('/signmain')
}
function goToHome() {
  router.visit('/landingpage')
}
function goToOnlineMarket() {
  router.visit('/onlinemarket')
}
function goToPayToPark() {
  router.visit('/paytopark')
}
function goToRental() {
  router.visit('/rentalfacility')
}
function goToAboutUs() {
  router.visit('/aboutus')
}
</script>

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-6 font-medium">
                <button @click="goToHome" :class="linkClass('landingpage')">Home</button>
                <button @click="goToOnlineMarket" :class="linkClass('market')">Online Market</button>
                <button @click="goToPayToPark" :class="linkClass('park')">Pay to Park</button>
                <button @click="goToRental" :class="linkClass('facilities')">Use of Facilities</button>
                <button @click="goToAboutUs" :class="linkClass('about')">About Us</button>
            </nav>

            <!-- Profile Button (Desktop Only) -->
            <!-- Profile Button (Desktop Only) -->
            <div class="relative ml-4 hidden md:block">
                <button @click="showDropdown = !showDropdown"
                    class="flex items-center gap-2 bg-white/20  hover:bg-yellow-400 text-white px-3 py-1.5 rounded-full border border-white/40 hover:scale-105 transition">
                    <!-- Blue circular user icon -->
                    <div class="bg-[#650000] p-1.5 rounded-full border-2 border-white">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>

                    <!-- Down arrow icon -->
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.084l3.71-3.853a.75.75 0 011.1 1.02l-4.25 4.417a.75.75 0 01-1.1 0L5.21 8.27a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div v-if="showDropdown"
                    class="absolute right-0 mt-2 w-56 bg-white text-[#650000] font-medium border rounded-lg shadow-lg z-50 animate-fade-in p-4 space-y-3">
                    <!-- Greeting -->
                    <div class="bg-yellow-400 px-3 py-2 rounded text-[#650000] font-medium text-sm">
                        Hello, <span class="font-bold">User</span>
                    </div>

                    <!-- Options -->
                    <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8m-8 4h5m-5 4h8" />
                        </svg>
                        <span class="text-sm">Help & FAQs</span>
                    </a>
                    <button @click="goToSignIn"
                        class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12h.01M12 15h.01M9 12h.01M12 9h.01M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                        <span class="text-sm">Sign In</span>
                    </button>


                </div>

            </div>
        </div>

        <!-- Mobile Nav -->
        <div v-if="isMobileMenuOpen" class="md:hidden px-6 pb-4 text-white space-y-2">
            <!-- Nav Buttons -->
            <button @click="goToHome" :class="mobileLinkClass('home')">Home</button>
            <button @click="goToOnlineMarket" :class="mobileLinkClass('market')">Online Market</button>
            <button @click="goToPayToPark" :class="mobileLinkClass('park')">Pay to Park</button>
            <button @click="goToRental" :class="mobileLinkClass('facilities')">Use of Facilities</button>
            <button @click="goToAboutUs" :class="mobileLinkClass('about')">About Us</button>

            <!-- Profile Section -->
            <div class="pt-6 border-t border-white/20">
                <div class="flex items-center space-x-3 mb-2">
                    <img src="/images/profile.jpg" alt="Profile"
                        class="h-10 w-10 rounded-full border-2 border-yellow-400" />
                    <p class="text-sm font-semibold">John Doe</p>
                </div>
                <div class="space-y-1 pl-12 text-sm">
                    <a href="#" class="block hover:underline">Hello, User</a>
                    <button @click="goToSignIn" class="block hover:underline w-full text-left">Sign In</button>
                    <a href="#" class="block hover:underline">Log Out</a>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import { router } from '@inertiajs/vue3'

export default {
  name: "Header",
  data() {
    return {
      active: "home",
      isMobileMenuOpen: false,
      showDropdown: false,
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

      // Navigation on mobile
      if (tab === 'home' || tab === 'landingpage') router.visit('/landingpage');
      if (tab === 'market') router.visit('/onlinemarketmainpage');
      if (tab === 'park') router.visit('/paytoparkmainpage');
      if (tab === 'facilities') router.visit('/rentalfacilitymainpage');
      if (tab === 'about') router.visit('/about');
      if (tab === 'home' || tab === 'landingpage') router.visit('/');
    },
    goToHome() {
      this.active = 'home';
      router.visit('/landingpage');
    },
    goToOnlineMarket() {
      this.active = 'market';
      router.visit('/onlinemarketmainpage');
    },
    goToPayToPark() {
      this.active = 'park';
      router.visit('/paytoparkmainpage');
    },
    goToRental() {
      this.active = 'facilities';
      router.visit('/rentalfacilitymainpage');
    },
    goToSignIn() {
      router.visit('/signmain');
    },
    handleOutsideClick(e) {
      const dropdown = this.$el.querySelector(".relative");
      if (dropdown && !dropdown.contains(e.target)) {
        this.showDropdown = false;
      }
    },
  },
  mounted() {
    document.addEventListener("click", this.handleOutsideClick);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleOutsideClick);
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