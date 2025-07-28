<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const isOpen = ref(false)

function goHome() {
  router.visit('/')
}
</script>

<template>
  <div class="w-full">
    <!-- Top Maroon Bar -->
    <div class="w-full bg-maroon text-[10px] text-gray-200 flex justify-end items-center pr-4 py-2 border-b border-yellow-500">
      <a href="/" class="flex items-center gap-1 hover:underline">
        <img src="/images/OnlineMarket/back.png" alt="Back" class="h-3 w-3 invert" />
        Back to main
      </a>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white text-black border-b border-gray-200">
      <div class="w-full px-4">
        <div class="flex items-center justify-between h-16">
          <!-- Logo + Brand -->
          <div class="flex items-center gap-2">
            <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
            <h1 class="text-lg font-bold text-maroon">
              CEDU <span class="text-yellow-500">iCentral</span>
            </h1>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden">
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

          <!-- Desktop Nav -->
          <div class="hidden md:flex gap-6">
            <a
              v-for="item in navItems"
              :key="item.name"
              :href="item.href"
              class="nav-link text-maroon font-medium hover:text-yellow-500 hover:underline"
            >
              {{ item.name }}
            </a>
          </div>
        </div>
      </div>

      <!-- Mobile Dropdown Nav -->
      <div v-if="isOpen" class="md:hidden px-4 pb-4">
        <a
          v-for="item in navItems"
          :key="item.name"
          :href="item.href"
          class="block py-2 text-maroon hover:text-yellow-500"
        >
          {{ item.name }}
        </a>
      </div>
    </nav>
  </div>
</template>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
.text-maroon {
  color: #651818;
}
</style>
