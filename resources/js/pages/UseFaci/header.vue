<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const page = usePage()

const showDropdown = ref(false)
const showNotifications = ref(false)
const isMobileMenuOpen = ref(false)
let pollInterval = null
let lastNotificationCount = 0

const userName = computed(() => {
  const user = page.props.auth?.user
  if (!user) return 'User'
  return user.firstName || user.name || 'User'
})

const notifications = ref([])

const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)

onMounted(() => {
  fetchNotifications();
  // Poll every 3 seconds for faster updates
  pollInterval = setInterval(() => {
    fetchNotifications();
  }, 3000);
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})

function playNotificationSound() {
  // Create a simple beep sound using Web Audio API
  const audioContext = new (window.AudioContext || window.webkitAudioContext)()
  const oscillator = audioContext.createOscillator()
  const gainNode = audioContext.createGain()
  
  oscillator.connect(gainNode)
  gainNode.connect(audioContext.destination)
  
  oscillator.frequency.value = 800
  oscillator.type = 'sine'
  
  gainNode.gain.setValueAtTime(0.3, audioContext.currentTime)
  gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5)
  
  oscillator.start(audioContext.currentTime)
  oscillator.stop(audioContext.currentTime + 0.5)
}

function fetchNotifications() {
  const userId = page.props.auth?.user?.id || localStorage.getItem('user_id');
  
  if (!userId) {
    console.warn('No user ID available for notifications');
    return;
  }
  
  const url = `/notifications?user_id=${userId}`;
  
  fetch(url, {
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    }
  })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      return res.json();
    })
    .then(data => {
      const formattedData = (Array.isArray(data) ? data : []).map(n => ({
        ...n,
        time: formatTime(n.created_at),
        read: n.read === 1 || n.read === true || n.read === '1'
      }));
      
      const newUnreadCount = formattedData.filter(n => !n.read).length;
      
      // Only play sound if new unread notifications arrived
      if (newUnreadCount > lastNotificationCount) {
        console.log('🔔 New notifications detected');
        playNotificationSound();
      }
      lastNotificationCount = newUnreadCount;
      
      notifications.value = formattedData;
    })
    .catch(error => {
      console.error('✗ Error fetching notifications:', error);
    });
}

function formatTime(timestamp) {
  if (!timestamp) return 'just now'
  const date = new Date(timestamp)
  const now = new Date()
  const diffMs = now - date
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMs / 3600000)
  const diffDays = Math.floor(diffMs / 86400000)

  if (diffMins < 1) return 'just now'
  if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`
  if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`
  if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`
  return date.toLocaleDateString()
}

function goToSignIn() {
  router.visit('/signmain')
}
function goToHome() {
  router.visit('/use-of-facilities')
}
function goToCommercial() {
  router.visit('/use-of-facilities/commercial')
}
function goToHostel() {
  router.visit('/use-of-facilities/hostel')
}
function goToRental() {
  router.visit('/use-of-facilities/rental')
}

function markAsRead(id) {
  fetch(`/notifications/${id}/mark-read`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(res => {
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    return res.json();
  })
  .then(() => {
    const notification = notifications.value.find(n => n.id === id);
    if (notification) {
      notification.read = true;
    }
  })
  .catch(error => console.error('✗ Error marking as read:', error));
}

function markAllAsRead() {
  const userId = page.props.auth?.user?.id;
  
  if (!userId) {
    console.warn('No user ID for marking all as read');
    return;
  }
  
  fetch(`/notifications/mark-all-read`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ user_id: userId })
  })
  .then(res => {
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    return res.json();
  })
  .then(() => {
    notifications.value.forEach(n => { n.read = true; });
  })
  .catch(error => console.error('✗ Error marking all as read:', error));
}

function logout() {
  router.visit('/om-landing')
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
        <button @click="goToHome" class="hover:text-yellow-200 transition">Home</button>
        <button @click="goToCommercial" class="hover:text-yellow-200 transition">Commercial</button>
        <button @click="goToHostel" class="hover:text-yellow-200 transition">Hostel</button>
        <button @click="goToRental" class="hover:text-yellow-200 transition">Rental</button>
      </nav>

      <!-- Notification + Profile Icons (Desktop Only) -->
      <div class="relative hidden md:flex items-center gap-4 ml-4">
        
        <!-- Notification Icon -->
        <div class="relative">
          <button @click="showNotifications = !showNotifications"
            class="relative flex items-center justify-center w-10 h-10 bg-white/20 hover:bg-yellow-400 text-white rounded-full border border-white/40 hover:scale-105 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <!-- Unread Badge -->
            <span v-if="unreadCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
              {{ unreadCount }}
            </span>
          </button>

          <!-- Notification Dropdown -->
          <div v-if="showNotifications"
            class="absolute right-0 mt-2 w-80 bg-white text-gray-800 rounded-lg shadow-2xl z-50 animate-fade-in max-h-96 overflow-y-auto">
            
            <!-- Header -->
            <div class="bg-[#650000] text-white p-4 flex items-center justify-between border-b">
              <h3 class="text-lg font-bold">Notifications</h3>
              <button v-if="unreadCount > 0" @click="markAllAsRead" class="text-sm hover:underline text-yellow-400">
                Mark all as read
              </button>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-200">
              <div v-for="notification in notifications" :key="notification.id"
                @click="markAsRead(notification.id)"
                :class="['p-4 hover:bg-gray-100 cursor-pointer transition', notification.read ? 'bg-white' : 'bg-blue-50']">
                <div class="flex items-start gap-3">
                  <!-- Unread Indicator -->
                  <div v-if="!notification.read" class="w-2 h-2 bg-red-600 rounded-full mt-2"></div>
                  <div v-else class="w-2 h-2 mt-2"></div>

                  <!-- Content -->
                  <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">{{ notification.title }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ notification.message }}</p>
                    <span class="text-xs text-gray-400 mt-2">{{ notification.time }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div v-if="notifications.length === 0" class="p-8 text-center text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <p>No notifications yet</p>
            </div>
          </div>
        </div>

        <!-- Logout Button (Desktop Only) -->
        <button @click="logout"
          class="flex items-center gap-2 bg-white/20 hover:bg-red-600 text-white px-4 py-2 rounded-full border border-white/40 hover:scale-105 transition font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
          </svg>
          <span class="text-sm">Main Menu</span>
        </button>
      </div>
    </div>
    

    <!-- Mobile Nav -->
    <div v-if="isMobileMenuOpen" class="md:hidden px-6 pb-4 text-white space-y-2">
      <!-- Nav Buttons -->
      <button @click="goToHome" class="block w-full text-left px-4 py-2">Home</button>
      <button @click="goToCommercial" class="block w-full text-left px-4 py-2">Commercial</button>
      <button @click="goToHostel" class="block w-full text-left px-4 py-2">Hostel</button>
      <button @click="goToRental" class="block w-full text-left px-4 py-2">Rental</button>
    </div>
  </header>
</template>

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