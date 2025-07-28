<template>
  <div class="flex justify-between items-center mb-8">
    <!-- Removed the "Dashboard" text here -->
    <div></div> <!-- Keeps spacing intact -->

    <!-- Notification & Profile Section -->
    <div class="flex items-center gap-4 relative">
      <!-- Bell Notification Icon -->
      <button @click="toggleNotif" class="relative focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- Notification Dropdown -->
      <div
        v-if="showNotif"
        class="absolute top-12 right-0 w-80 bg-white rounded-xl shadow-2xl z-50 overflow-hidden border border-gray-200"
      >
        <div class="bg-gradient-to-r from-[#FFA600] to-[#ffcc66] text-[#5F1213] font-semibold px-4 py-3 text-sm">
          Notifications Center
        </div>
        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100">
          <li class="flex items-start gap-3 px-4 py-4 hover:bg-gray-50 cursor-pointer transition">
            <div class="bg-[#FFA600] rounded-full p-2">
              <i class="fas fa-shopping-cart text-white text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-[#5F1213]">New order placed by Maria Santos</p>
              <p class="text-xs text-gray-500">Just now</p>
            </div>
          </li>
        </ul>
        <div class="text-center text-xs text-gray-600 bg-gray-50 py-2 hover:bg-gray-100 transition cursor-pointer">
          View all notifications
        </div>
      </div>

      <!-- Profile Image -->
      <img
        src="https://i.pravatar.cc/40"
        class="rounded-full w-10 h-10 border-2 border-white cursor-pointer"
        alt="Profile"
        @click="goToProfile"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const showNotif = ref(false)

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}

function goToProfile() {
  router.visit('/admin/profile')
}

onMounted(() => {
  window.addEventListener('click', () => {
    showNotif.value = false
  })
})
</script>
