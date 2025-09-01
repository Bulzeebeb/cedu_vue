<template>
  <div class="relative z-0 min-h-screen bg-gradient-to-r from-[#650000] via-[#650000] to-yellow-300 text-white flex">
    <!-- Left Side: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8">
      <h2 class="text-4xl font-bold mb-4 animate-fade-in">
        Welcome to <span class="text-yellow-400">CEDU iCentral</span>
      </h2>
      <p class="mb-8 text-gray-400">Products and Services</p>

      <form @submit.prevent="handleLogin" class="w-full max-w-sm">
        <div class="mb-4">
          <input type="email" v-model="form.email" placeholder="Email Address"
            class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black" />
          <div v-if="form.errors.email" class="text-sm text-red-300 mt-1">{{ form.errors.email }}</div>
        </div>

        <div class="mb-4 relative">
          <input :type="showPassword ? 'text' : 'password'" v-model="form.password" placeholder="Password"
            class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black" />
          <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2 text-gray-400">{{
            showPassword ? 'Hide' : 'Show' }}</button>
          <div v-if="form.errors.password" class="text-sm text-red-300 mt-1">{{ form.errors.password }}</div>
        </div>

        <div v-if="form.errors.message" class="text-red-300 text-sm mb-3 text-center">
          {{ form.errors.message }}
        </div>

        <button type="submit" :disabled="form.processing"
          class="w-full py-2 bg-yellow-400 rounded-lg hover:bg-yellow-600 disabled:opacity-50">
          Continue
        </button>

        <p class="text-gray-400 text-center mt-4">
          New here? <button type="button" @click="openSignupModal" class="text-blue-400 underline">Create
            Account</button>
        </p>
      </form>

      <!-- Signup Modals -->
      <Signup1Modal v-if="showSignup1" @next="openSignup2" @close="closeAllModals" />
      <Signup2Modal v-if="showSignup2" :formData="signup1Data" @next="openVerifyOtp" @close="backToSignup1" />
    </div>

    <!-- Right Side: Image Preview -->
    <div class="hidden md:flex w-1/2 bg-cover" style="background-image: url('/images/signin-cover.png')"></div>
  </div>

  <!-- Modal Overlay rendered at root level -->
  <div v-if="showVerifyOtp"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto p-4">
    <VerifyOtpModal :formData="signup1Data" @close="closeAllModals" />
  </div>
</template>


<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Signup1Modal from '../Client/Signup1.vue'
import Signup2Modal from '../Client/Signup2.vue'
import VerifyOtpModal from '../Client/VerifyOtp.vue'
import { onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

const showPassword = ref(false)
const form = useForm({
  email: '',
  password: ''
})

async function handleLogin() {
  form.clearErrors()

  // Step 1: Show "Logging in..." loader
  await Swal.fire({
    title: 'Logging in...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading()
    },
    timer: 1000 // optional short delay before showing success
  })

  // Step 2: Show success message
  await Swal.fire({
    icon: 'success',
    title: 'Logged in successfully!',
    showConfirmButton: false,
    timer: 1000
  })

  // Step 3: Proceed to actual login request
  await form.post('/admin-login', {
    preserveScroll: true,
  })
}

// Modal state
const showSignup1 = ref(false)
const showSignup2 = ref(false)
const showVerifyOtp = ref(false)
const signup1Data = ref({})

// Check for flash prop from backend to open VerifyOtp modal
const page = usePage()
onMounted(() => {
  if (page.props.flash && page.props.flash.showVerifyOtp) {
    showSignup1.value = false
    showSignup2.value = false
    showVerifyOtp.value = true
  }
})


function openSignupModal() {
  showSignup1.value = true
  showSignup2.value = false
  showVerifyOtp.value = false
}
function openSignup2(data) {
  signup1Data.value = data
  showSignup1.value = false
  showSignup2.value = true
  showVerifyOtp.value = false
}
function openVerifyOtp() {
  showSignup1.value = false
  showSignup2.value = false
  showVerifyOtp.value = true
  console.log('VerifyOtp modal opened:', showVerifyOtp.value)
}
function closeAllModals() {
  showSignup1.value = false
  showSignup2.value = false
  showVerifyOtp.value = false
}

function backToSignup1() {
  showSignup2.value = false
  showSignup1.value = true
  showVerifyOtp.value = false
}
</script>

<style>
body {
  margin: 0;
  font-family: 'Arial', sans-serif;
}
</style>