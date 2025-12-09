<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  cartCount: {
    type: Number,
    default: 0
  }
})

// Define emits for search functionality
const emit = defineEmits(['search'])

const isOpen = ref(false)
const searchQuery = ref('')

const navItems = [
  { name: "Home", href: "/onlinemarketlandingpageclient" },
  { name: "Fruits", href: "/fruit" },
  { name: "Vegetables", href: "/vegetable" },
  { name: "Poultry", href: "/poultry" },
]

function toggleMobileMenu() {
  isOpen.value = !isOpen.value
}

function closeMobileMenu() {
  isOpen.value = false
}

function handleSearch() {
  if (searchQuery.value.trim()) {
    emit('search', searchQuery.value.trim())
  }
}

function clearSearch() {
  searchQuery.value = ''
}

// Handle search on Enter key press
function handleSearchKeypress(event) {
  if (event.key === 'Enter') {
    handleSearch()
  }
}

function navigateTo(href) {
  router.visit(href)
  closeMobileMenu()
}

function goCart() {
  navigateTo('/cart')
}

function goProfile() {
  navigateTo('/clientSetting')
}

function logout() {
  router.visit('/om-landing')
}
</script>

<template>
  <div class="fixed top-0 left-0 w-full z-50">
    <!-- Top Info Bar -->
    <div class="w-full bg-maroon text-[10px] text-gray-200 font-medium flex justify-end items-center pr-4 py-2 border-b border-yellow-500">
      <span class="hidden sm:inline">Corporate Enterprise Department Unit | University of Southeastern Philippines - Tagum-Mabini Campus</span>
      <span class="sm:hidden">CEDU | USEPh Tagum-Mabini</span>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white text-black border-b border-gray-300 w-full shadow-md">
      <div class="px-4 sm:px-6 lg:px-10 w-full">
        <div class="flex items-center justify-between h-16">
          <!-- Logo + Brand -->
          <div class="flex items-center flex-shrink-0 gap-2 min-w-max">
            <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
            <h1 class="text-sm sm:text-lg font-bold text-maroon">
              CEDU <span class="text-yellow-500">iCentral</span>
              <span class="hidden sm:inline">Online Market</span>
            </h1>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button @click="toggleMobileMenu" class="text-black hover:text-yellow-500 focus:outline-none transition">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Desktop Right Section -->
          <div class="hidden md:flex items-center gap-4 lg:gap-6 flex-grow justify-end">
            <!-- Nav Links -->
            <div class="flex gap-4 lg:gap-6">
              <a v-for="item in navItems" :key="item.name" :href="item.href"
                class="nav-link text-maroon font-medium text-sm hover:text-yellow-500 transition">
                {{ item.name }}
              </a>
            </div>

            <!-- Search Bar -->
            <div class="flex bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm hover:border-yellow-500 transition">
              <div class="flex items-center px-3">
                <img src="/images/OnlineMarket/searchicon.png" alt="Search" class="h-4 w-4" />
              </div>
              <input v-model="searchQuery" type="text" placeholder="Search..."
                class="flex-grow px-2 py-2 text-sm text-black outline-none bg-white" 
                @keypress="handleSearchKeypress" />
              <button @click="handleSearch" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 text-sm font-medium transition">
                Search
              </button>
            </div>

            <!-- Cart -->
            <button @click="goCart" class="relative hover:opacity-75 transition">
              <img src="/images/OnlineMarket/shopping-cart.png" alt="Cart" class="h-6 w-6" />
              <span v-if="props.cartCount > 0"
                class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ props.cartCount }}
              </span>
            </button>

            <!-- Logout -->
            <button @click="logout"
              class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full font-medium text-sm transition duration-300 whitespace-nowrap">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
              </svg>
              <span>Menu</span>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Sidebar -->
    <Transition name="slide" 
      enter-active-class="transition ease-out duration-300"
      enter-from-class="transform -translate-x-full" 
      enter-to-class="transform translate-x-0"
      leave-active-class="transition ease-in duration-200" 
      leave-from-class="transform translate-x-0"
      leave-to-class="transform -translate-x-full">
      <div v-if="isOpen" class="md:hidden fixed inset-0 bg-black/50 z-40" @click="closeMobileMenu"></div>
    </Transition>

    <Transition name="slide"
      enter-active-class="transition ease-out duration-300"
      enter-from-class="transform -translate-x-full" 
      enter-to-class="transform translate-x-0"
      leave-active-class="transition ease-in duration-200" 
      leave-from-class="transform translate-x-0"
      leave-to-class="transform -translate-x-full">
      <div v-if="isOpen" class="md:hidden fixed left-0 top-24 bottom-0 w-64 bg-white shadow-lg z-40 overflow-y-auto">
        <div class="p-4 space-y-2">
          <!-- Mobile Search -->
          <div class="flex bg-white border border-gray-300 rounded-lg overflow-hidden mb-4">
            <div class="flex items-center px-2">
              <img src="/images/OnlineMarket/searchicon.png" alt="Search" class="h-4 w-4" />
            </div>
            <input v-model="searchQuery" type="text" placeholder="Search products..."
              class="flex-grow px-2 py-2 text-sm text-black outline-none bg-white" 
              @keypress="handleSearchKeypress" />
            <button @click="handleSearch" class="bg-yellow-500 text-white px-3 hover:bg-yellow-600 transition">
              Go
            </button>
          </div>

          <!-- Mobile Links -->
          <button @click="goProfile"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded transition">
            <img src="/images/OnlineMarket/icons8-profile-50.png" alt="Profile" class="h-5 w-5" />
            <span>Profile</span>
          </button>

          <button @click="goCart"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded relative transition">
            <img src="/images/OnlineMarket/shopping-cart.png" alt="Cart" class="h-5 w-5" />
            <span>Cart</span>
            <span v-if="props.cartCount > 0"
              class="absolute right-4 bg-yellow-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
              {{ props.cartCount }}
            </span>
          </button>

          <hr class="my-2 border-gray-200" />

          <button @click="navigateTo('/onlinemarketlandingpageclient')"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded transition">
            <img src="/images/OnlineMarket/home-icon.png" alt="Home" class="h-5 w-5" />
            <span>Home</span>
          </button>

          <button @click="navigateTo('/fruit')"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded transition">
            <img src="/images/OnlineMarket/fruits-icon.png" alt="Fruits" class="h-5 w-5" />
            <span>Fruits</span>
          </button>

          <button @click="navigateTo('/vegetable')"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded transition">
            <img src="/images/OnlineMarket/vegetables-icon.png" alt="Vegetables" class="h-5 w-5" />
            <span>Vegetables</span>
          </button>

          <button @click="navigateTo('/poultry')"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-maroon font-medium hover:bg-yellow-50 rounded transition">
            <img src="/images/OnlineMarket/poultry-icon.png" alt="Poultry" class="h-5 w-5" />
            <span>Poultry</span>
          </button>

          <hr class="my-2 border-gray-200" />

          <button @click="logout"
            class="mobile-link w-full flex items-center gap-3 px-4 py-3 text-red-600 font-medium hover:bg-red-50 rounded transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
            </svg>
            <span>Sign Out</span>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.bg-maroon {
  background-color: #651818;
}

.text-maroon {
  color: #651818;
}

.nav-link,
.mobile-link {
  transition: all 0.3s ease;
}

.nav-link:hover {
  color: #facc15;
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter-from {
  transform: translateX(-100%);
}

.slide-leave-to {
  transform: translateX(-100%);
}
</style>
