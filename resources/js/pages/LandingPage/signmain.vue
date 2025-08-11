
<template>
  <div class="relative z-0 flex h-screen bg-gradient-to-r from-[#650000] via-[#650000] to-yellow-300 text-white">
    <!-- Left Side: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8">
      <h2 class="text-4xl font-bold mb-4 animate-fade-in">
        Welcome to <span class="text-yellow-400">CEDU iCentral</span>
      </h2>
      <p class="mb-8 text-gray-400">Products and Services</p>

      <form @submit.prevent="form.post('/admin-login')" class="w-full max-w-sm">
        <div class="mb-4">
          <input type="email" v-model="form.email" placeholder="Email Address"
            class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black" />
          <div v-if="form.errors.email" class="text-sm text-red-300 mt-1">{{ form.errors.email }}</div>
        </div>

        <div class="mb-4 relative">
          <input :type="showPassword ? 'text' : 'password'" v-model="form.password" placeholder="Password"
            class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black" />
          <button type="button" @click="showPassword = !showPassword"
            class="absolute right-3 top-2 text-gray-400">{{ showPassword ? 'Hide' : 'Show' }}</button>
          <div v-if="form.errors.password" class="text-sm text-red-300 mt-1">{{ form.errors.password }}</div>
        </div>

        <div v-if="form.errors.message" class="text-red-300 text-sm mb-3 text-center">
          {{ form.errors.message }}
        </div>

        <button type="submit" :disabled="form.processing" class="w-full py-2 bg-yellow-400 rounded-lg hover:bg-yellow-600 disabled:opacity-50">
          {{ form.processing ? 'Logging in...' : 'Continue' }}
        </button>

        <p class="text-gray-400 text-center mt-4">
          New here? <button type="button" @click="openSignupModal" class="text-blue-400 underline">Create Account</button>
        </p>
      </form>

      <!-- Signup Modals -->
      <Signup1Modal v-if="showSignup1" @next="openSignup2" @close="closeAllModals" />
      <Signup2Modal v-if="showSignup2" :formData="signup1Data" @next="openVerifyOtp" @close="closeAllModals" />
      <VerifyOtpModal v-if="showVerifyOtp" @close="closeAllModals" />
    </div>

    <!-- Right Side: Image Preview -->
    <div class="hidden md:flex w-1/2 bg-cover" style="background-image: url('/images/signin-cover.png')"></div>
  </div>
</template>


<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Signup1Modal from '../Client/Signup1.vue'
import Signup2Modal from '../Client/Signup2.vue'
import VerifyOtpModal from '../Client/VerifyOtp.vue'

const showPassword = ref(false)
const form = useForm({
  email: '',
  password: ''
})

// Modal state
const showSignup1 = ref(false)
const showSignup2 = ref(false)
const showVerifyOtp = ref(false)
const signup1Data = ref({})

function openSignupModal() {
  showSignup1.value = true
}
function openSignup2(data) {
  signup1Data.value = data
  showSignup1.value = false
  showSignup2.value = true
}
function openVerifyOtp() {
  showSignup2.value = false
  showVerifyOtp.value = true
}
function closeAllModals() {
  showSignup1.value = false
  showSignup2.value = false
  showVerifyOtp.value = false
}
</script>

<style>
body {
  margin: 0;
  font-family: 'Arial', sans-serif;
}
</style>