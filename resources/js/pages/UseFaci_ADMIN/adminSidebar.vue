<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const admin = computed(() => page.props.admin)
const showNotif = ref(false)
let pollInterval = null
let lastNotificationCount = 0

const notifications = ref([])

const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)

function getProfilePictureUrl(path) {
  if (!path) return 'https://i.pravatar.cc/300';
  if (path.startsWith('http')) return path;
  if (path.startsWith('storage/admin_profiles')) path = path.substring(1);
  return `/${path}`;
}

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}

// Fixed routes according to your PHP Laravel/Inertia routes
function goToDashboard() {
  router.visit('/use/facidashboard')
}
function goToCategory() {
  router.visit('/use/category')
}
function goToFacilities() {
  router.visit('/use/facilities')
}
function goToBooking() {
  router.visit('/use/booking')
}
function goToReports() {
  router.visit('/use/reports')
}
function goToLogs() {
  router.visit('/use/logs')
}
function goToMainMenu() {
  router.visit('/adminchoice') // landing/login page
}
function goToAdminProf() {
  router.visit('/use/profile') // admin profile page
}

onMounted(() => {
  fetchNotifications();
  // Poll every 3 seconds for faster updates
  pollInterval = setInterval(() => {
    fetchNotifications();
  }, 3000);

  window.addEventListener('click', () => {
    showNotif.value = false
  })
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
  const adminId = admin.value?.id || page.props.auth?.admin?.id || localStorage.getItem('admin_id');

  if (!adminId) {
    console.warn('No admin ID available for notifications');
    return;
  }

  const url = `/notifications?admin_id=${adminId}`;

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
  const adminId = page.props.auth?.admin?.id;

  if (!adminId) {
    console.warn('No admin ID for marking all as read');
    return;
  }

  fetch(`/notifications/mark-all-read`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ admin_id: adminId })
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
</script>


<template>
  <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0 flex flex-col justify-between">
    <!-- Top Logo and Navigation -->
    <div>
      <div class="mb-10">
        <h1 class="text-lg font-bold">Rental Facilities <span class="text-yellow-500">Admin</span></h1>
      </div>
      <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="#" @click.prevent="goToDashboard"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-home"></i> Dashboard
        </a>

        <!-- Category -->
        <a href="#" @click.prevent="goToCategory"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-tags"></i> Category
        </a>

        <!-- Facilities -->
        <a href="#" @click.prevent="goToFacilities"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-building"></i> Facilities
        </a>

        <!-- Booking -->
        <a href="#" @click.prevent="goToBooking"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-calendar-check"></i> Booking
        </a>

        <!-- Reports -->
        <a href="#" @click.prevent="goToReports"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-chart-bar"></i> Reports
        </a>
      </nav>
    </div>

    <!-- Bottom Notification + Profile Section -->
    <div class="mt-6 relative">
      <!-- Profile Row -->
      <div class="flex items-center justify-between px-2 mt-4">
        <!-- Profile Left -->
        <div class="flex items-center gap-3 cursor-pointer">
          <img :src="getProfilePictureUrl(admin?.profile_picture)"
            class="w-12 h-12 rounded-full border-2 border-white object-cover" />
          <p class="text-sm">{{ admin ? (admin.first_name + ' ' + admin.last_name) : 'Admin' }}</p>
        </div>
      </div>

      <!-- Divider Line -->
      <div class="border-t border-[#FFA600]/40 my-3"></div>

      <!-- Logout -->
      <button @click.prevent="goToMainMenu" type="button"
        class="flex items-center gap-3 text-sm text-white-400 hover:text-white transition px-2 w-full">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.841 15.681l7.07-7.07m0 0l-7.07-7.07m7.07 7.07H3m10 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2" />
          </svg>
         Main Menu
      </button>
    </div>
  </aside>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

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
