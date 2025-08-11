<template>
  <div class="flex flex-col min-h-screen bg-gray-100">
    <SiteHeader />

    <main class="flex-grow flex justify-center items-center">
      <div
        class="w-full max-w-6xl bg-white shadow-xl rounded-2xl px-12 py-16 flex flex-col relative"
        style="min-height: 600px;"
      >
        <!-- 🔺 Sign Out Button - Top Right -->
        <div class="absolute top-6 right-6">
          <button
            @click="handleSignOut"
            class="px-5 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition"
          >
            Sign Out
          </button>
        </div>

        <!-- 🔸 Profile Info -->
        <div class="flex items-center space-x-10 mb-12 mt-6">
          <div class="w-30 h-30 rounded-full overflow-hidden border-4 border-yellow-500 shadow">
            <img
              :src="client.image ? '/storage/' + client.image : '/images/Client/default_profile.png'"
              alt="Profile Picture"
              class="w-full h-full object-cover"
            />
          </div>

          <div class="flex flex-col justify-center h-20">
            <h2 class="text-3xl font-bold text-yellow-600">
              {{ client.firstName }} {{ client.lastName }}
            </h2>
            <p class="text-gray-700 text mt-1">{{ client.email }}</p>
          </div>
        </div>

        <!-- 🔹 Action Buttons -->
        <div class="flex justify-start space-x-6 mb-10">
          <button
            @click="goToEditProfile"
            class="w-48 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition"
          >
            Edit Profile
          </button>
          <button
            @click="goToBuyHistory"
            class="w-48 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
          >
            Buy History
          </button>
        </div>

        <!-- 🔻 Back Button - Bottom Right -->
        <div class="absolute bottom-6 right-6">
          <button
            @click="goBack"
            class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition"
          >
            ← Back to Market
          </button>
        </div>
      </div>
    </main>

    <SiteFooter />
  </div>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

// Props from backend
const props = defineProps({
  client: Object
})

// Protect route: redirect to /signin if not authenticated
onMounted(() => {
  if (!props.client) {
    router.visit('/signin')
  }
})

// Go to Edit Profile
const goToEditProfile = () => {
  router.get('/clientProfile')
}

// Navigate to Buy History page
const goToBuyHistory = () => {
  router.get('/buy-history')
}

// Logout and redirect to /signin
const handleSignOut = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/signin')
  })
}

// Back to market
const goBack = () => {
  router.get('/om-landing')
}
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
</style>