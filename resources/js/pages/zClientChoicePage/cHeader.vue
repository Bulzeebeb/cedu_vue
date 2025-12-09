<template>
  <div>
    <!-- HEADER -->
    <header class="bg-gradient-to-r from-[#650000] to-[#8b1e1e] text-white shadow-lg fixed top-0 z-50 left-0 right-0">
      <div class="px-6 py-4 flex items-center justify-between">
        <!-- Logo + Title -->
        <div class="flex items-center space-x-3">
          <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
          <h1 class="text-2xl font-bold tracking-tight">CEDU <span class="text-yellow-400">iCentral </span></h1>
        </div>

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
            class="absolute right-0 mt-2 w-56 bg-white text-[#650000] font-medium border rounded-lg shadow-lg z-60 animate-fade-in p-4 space-y-3">
            <!-- Greeting -->
            <div class="bg-yellow-400 px-3 py-2 rounded text-[#650000] font-large text-sm">
              Hello, <span class="font-bold">{{($page.props.user?.firstName || 'User') + '!' }}</span>
            </div>
            <!-- Profile Button -->
            <button @click="goToMyProfile"
              class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition w-full text-left">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12h.01M12 15h.01M9 12h.01M12 9h.01M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
              </svg>
              <span class="text-sm">My Profile</span>
            </button>
            
            <!-- Main Menu Button -->
            <button @click="goToMainMenu"
              class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition w-full text-left">
              <Menu class="w-5 h-5" /> Main Menu
            </button>
            <!-- Logout Button -->
            <button @click="logOut"
              class="flex items-center gap-2 px-3 py-2 hover:bg-yellow-400 rounded transition w-full text-left">
              <LogOut class="w-5 h-5" /> Log Out
            </button>
          </div>
        </div>
      </div>
    </header>
  </div>
</template>


<script>
import { router } from '@inertiajs/vue3'
export default {
  components: {
  },
  data() {
    return {
      showDropdown: false
    }
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 1s ease-in-out forwards;
}

.animate-fade-in.delay-200 {
  animation-delay: 0.2s;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<script setup>
import { router } from '@inertiajs/vue3'
import { Menu, LogOut } from 'lucide-vue-next'

function goToMyProfile() {
  router.visit('/clientSetting')
}
function goToMainMenu() {
  router.visit('/om-landing')
}
function logOut() {
  router.visit('/landingpage')
}

</script>