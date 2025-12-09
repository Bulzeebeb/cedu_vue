<template>
  <header class="bg-gradient-to-r from-[#650000] to-[#8b1e1e] text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo + Title -->
      <div class="flex items-center space-x-3">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-2xl font-bold tracking-tight">
          CEDU <span class="text-yellow-400">iCentral</span>
        </h1>
      </div>

      <!-- Hamburger Button (Mobile Only) -->
      <button class="md:hidden focus:outline-none" @click="isOpen = !isOpen">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

      <!-- Desktop Nav -->
      <nav class="hidden md:flex space-x-6 font-medium items-center">
        <!-- Logout Button (Desktop Only) -->
        <button @click="logout"
          class="flex items-center gap-2 bg-white/20 hover:bg-red-600 text-white px-4 py-2 rounded-full border border-white/40 hover:scale-105 transition font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
          </svg>
          <span class="text-sm">Main Menu</span>
        </button>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const isOpen = ref(false)
const isLoggedIn = ref(false) // TODO: replace with actual logic
const activeTab = ref('payPark')

function logout() {
  router.visit('/om-landing')
}

function activateTab(tab) {
  activeTab.value = tab
  isOpen.value = false
}

function linkClass(tab) {
  return activeTab.value === tab
    ? 'text-yellow-300 underline underline-offset-8'
    : 'hover:text-yellow-200 transition'
}

function mobileLinkClass(tab) {
  return activeTab.value === tab
    ? 'block w-full text-left px-4 py-2 text-yellow-300 font-semibold bg-[#7a1e1e] rounded'
    : 'block w-full text-left px-4 py-2 hover:bg-[#7a1e1e] rounded transition'
}

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