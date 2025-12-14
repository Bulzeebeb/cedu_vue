<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  sidebarOpen: { type: Boolean, default: false }
})
const emit = defineEmits(['close'])

const showNotif = ref(false)

// Navigation
function goToHome() {
  router.visit('/landingpage')
}
function goToDashboard() {
  router.visit('/dashboard')
  emit('close')
}
function goToManageParking() {
  router.visit('/managep2p')
  emit('close')
}
function goToReports() {
  router.visit('/reports')
  emit('close')
}
function goToLogs() {
  router.visit('/logs')
  emit('close')
}

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}

onMounted(() => {
  window.addEventListener('click', () => (showNotif.value = false))
})
</script>

<template>
  <transition name="slide">
    <aside
      :class="[ 
        'fixed top-0 left-0 h-full bg-[#5F1213] text-white z-50 transition-transform duration-300 ease-in-out',
        sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full',
        'md:translate-x-0 md:w-64' // Always show sidebar on medium+ screens
      ]"
    >
      <!-- Overlay for mobile -->
      <div
        v-if="sidebarOpen"
        @click="emit('close')"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
      ></div>

      <!-- Sidebar content -->
      <div class="relative w-64 bg-[#5F1213] text-white flex flex-col justify-between p-6 h-full z-50">
        <div>
          <div class="mb-10 flex justify-between items-center">
            <h1 class="text-lg font-bold">
              CEDU <span class="text-yellow-500">iCentral</span>
            </h1>
            <!-- X button only visible on mobile -->
            <button class="text-yellow-500 text-2xl md:hidden" @click="emit('close')">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <nav class="space-y-4">
            <a href="#" @click="goToDashboard" class="hover:bg-[#FFA600] hover:text-[#5F1213] px-4 py-2 rounded-lg flex items-center gap-3">
              <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="#" @click="goToManageParking" class="hover:bg-[#FFA600] hover:text-[#5F1213] px-4 py-2 rounded-lg flex items-center gap-3">
              <i class="fas fa-parking"></i> Manage Parking
            </a>
            <a href="#" @click="goToReports" class="hover:bg-[#FFA600] hover:text-[#5F1213] px-4 py-2 rounded-lg flex items-center gap-3">
              <i class="fas fa-file-invoice-dollar"></i> Reports
            </a>
            <a href="#" @click="goToLogs" class="hover:bg-[#FFA600] hover:text-[#5F1213] px-4 py-2 rounded-lg flex items-center gap-3">
              <i class="fas fa-file-alt"></i> Logs
            </a>
          </nav>
        </div>

        <!-- Bottom Section -->
        <div class="mt-6 relative">
          <div class="flex items-center justify-between px-2 mt-4">
            <div class="flex items-center gap-3">
              <img src="https://i.pravatar.cc/300" alt="Profile" class="w-12 h-12 rounded-full border-2 border-white" />
              <p class="text-sm">Admin</p>
            </div>

            <button @click.stop="toggleNotif">
              <i class="fas fa-bell text-base"></i>
              <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
          </div>

          <a href="#" @click="goToHome" class="flex items-center gap-3 text-sm text-red-400 hover:text-white px-2 mt-4">
            <i class="fas fa-power-off text-lg"></i> Log Out
          </a>
        </div>
      </div>
    </aside>
  </transition>
</template>


<style scoped>
.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}
.slide-enter-to,
.slide-leave-from {
  transform: translateX(0);
  opacity: 1;
}
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
</style>
