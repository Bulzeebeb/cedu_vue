<script setup>
import { router } from '@inertiajs/vue3'

function goHome() {
  router.visit('/')
}
function goToFruit() {
  router.visit('/fruit')
}
function goToVegetable() {
  router.visit('/vegetable')
}
function goToPoultry() {
  router.visit('/poultry')
}
</script>

<template>
  <div class="w-full">
    <!-- Top maroon bar -->
    <div
      class="w-full bg-maroon text-[10px] text-gray-200 flex justify-end items-center pr-4 py-2 border-b border-yellow-500"
    >
      <a href="/" class="flex items-center gap-1 hover:underline">
        <img
          src="/images/OnlineMarket/back.png"
          alt="Back"
          class="h-3 w-3 invert"
        />
        Back to main
      </a>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white text-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-b border-gray-200">
        <div class="flex items-center justify-between h-16">
          <!-- Logo + Brand -->
          <div class="flex items-center flex-shrink-0 gap-2">
            <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
            <h1 class="text-lg font-bold text-maroon">
              CEDU <span class="text-yellow-500">iCentral</span>
            </h1>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button @click="isOpen = !isOpen" class="text-black focus:outline-none">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  v-if="!isOpen"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <!-- Desktop Right Section -->
          <div class="hidden md:flex items-center gap-6 flex-grow justify-end">
            <!-- Nav Links -->
            <div class="flex gap-6">
              <a
                v-for="item in navItems"
                :key="item.name"
                :href="item.href"
                class="nav-link text-maroon font-medium hover:text-yellow-500 hover:underline"
              >
                {{ item.name }}
              </a>
            </div>

            <!-- Search -->
            <div class="flex bg-white border border-gray-200 rounded overflow-hidden">
              <div class="flex items-center px-2">
                <img
                  src="/images/OnlineMarket/searchicon.png"
                  alt="Search Icon"
                  class="h-4 w-4"
                />
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search"
                class="flex-grow px-2 py-1 text-black outline-none bg-white"
              />
              <button @click="handleSearch" class="bg-yellow-500 text-white px-4">Search</button>
            </div>

            <!-- Cart -->
            <button class="relative">
              <img
                src="/images/OnlineMarket/shopping-cart.png"
                alt="Cart"
                class="h-6 w-6"
              />
            </button>

            <!-- Profile + Dropdown -->
            <div class="relative">
              <button @click="toggleDropdown" class="focus:outline-none">
                <img
                  src="/images/OnlineMarket/icons8-profile-50.png"
                  alt="Profile"
                  class="h-6 w-6"
                />
              </button>
              <div
                v-if="isDropdownOpen"
                class="absolute right-0 mt-2 w-32 bg-white text-black rounded shadow-lg z-50"
              >
                <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                <a href="/signout" class="block px-4 py-2 hover:bg-gray-100">Sign Out</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

   <!-- Mobile Sidebar -->
<div v-if="isOpen" class="md:hidden px-4 pb-4 pt-4 bg-white">
  <div class="border border-gray-200 rounded p-4 space-y-4 shadow-lg">
    <!-- Search + Button same box -->
    <div class="flex bg-white border border-gray-200 rounded overflow-hidden max-w-full">
      <div class="flex items-center px-2">
        <img
          src="/images/OnlineMarket/searchicon.png"
          alt="Search Icon"
          class="h-4 w-4"
        />
      </div>
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search"
        class="flex-grow px-2 py-1 text-black outline-none bg-white"
      />
      <button @click="handleSearch" class="bg-yellow-500 text-white px-4">Search</button>
    </div>

    <!-- Links with icons -->
    <a href="/profilePage.vue" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
      <img src="/images/OnlineMarket/icons8-profile-50.png" alt="Profile Icon" class="h-4 w-4" />
      Profile
    </a>
    <a href="/cartPage.vue" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
      <img src="/images/OnlineMarket/shopping-cart.png" alt="Cart Icon" class="h-4 w-4" />
      Cart
    </a>

    <button @click="goHome" href="#" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
  <img src="/images/OnlineMarket/home-icon.png" alt="Home Icon" class="h-4 w-4" />
  Home
</button>
<button @click="goToFruit" href="#" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
  <img src="/images/OnlineMarket/fruits-icon.png" alt="Fruits Icon" class="h-4 w-4" />
  Fruits
</button>
<button @click="goToVegetable" href="#" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
  <img src="/images/OnlineMarket/vegetables-icon.png" alt="Vegetables Icon" class="h-4 w-4" />
  Vegetables
</button>
<button @click="goToPoultry" href="#" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
  <img src="/images/OnlineMarket/poultry-icon.png" alt="Poultry Icon" class="h-4 w-4" />
  Poultry
</button>


    <a href="/signout" class="mobile-link flex items-center gap-2 text-maroon font-medium hover:text-yellow-500 hover:underline">
      <img src="/images/OnlineMarket/logout-icon.png" alt="Sign Out Icon" class="h-4 w-4" />
      Sign Out
    </a>
  </div>
</div>
  </div>
</template>

<script>
export default {
  name: "SiteHeader",
  data() {
    return {
      isOpen: false,
      isDropdownOpen: false,
      searchQuery: "",
      navItems: [
        { name: "Home", href: "/" },
        { name: "Fruits", href: "/fruit" },
        { name: "Vegetables", href: "/vegetable" },
        { name: "Poultry", href: "/poultry" },
      ],
    };
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    handleSearch() {
      alert(`Searching for: ${this.searchQuery}`);
    },
  },
};
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
.text-maroon {
  color: #651818;
}

.nav-link:hover,
.mobile-link:hover {
  color: #facc15; /* Tailwind yellow-400 */
  text-decoration: none;
}
</style>
