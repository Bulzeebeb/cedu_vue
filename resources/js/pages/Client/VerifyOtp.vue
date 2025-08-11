<template>
<div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto p-4">
    <div class="bg-white text-black font-sans flex flex-col">
     
      <!-- Optional banner image inside modal -->
      <!--<div class="h-40 bg-cover bg-center" style="background-image: url('/images/banner.jpg')"></div>-->

      <!-- OTP Content -->
       <div class="flex-grow flex items-center justify-center py-8">
        <div class="border border-gray-300 p-6 rounded-md w-full max-w-2xl shadow-md">
         
      <div class="p-6">
        <h2 class="text-center text-2xl font-bold mb-4">Verify OTP</h2>
        <p class="text-center text-gray-600 mb-2">
          We've sent a 6-digit OTP to your email. Please enter it below to verify your account.
        </p>
        <p class="text-center text-red-600 text-sm mb-6">
          OTP expires in: {{ formattedOtpTime }}
        </p>

        <!-- OTP Form -->
        <form @submit.prevent="submitOtp" v-if="!success">
          <div class="mb-4">
            <input type="text" v-model="form.otp" maxlength="6" placeholder="Enter OTP"
              class="w-full px-4 py-2 border border-gray-300 rounded text-center tracking-widest text-xl" required />
            <p v-if="form.errors.otp" class="text-red-500 text-sm mt-1">{{ form.errors.otp }}</p>
          </div>

          <button type="submit"
            class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800">
            Verify OTP
          </button>
        </form>

        <!-- Success Message -->
        <div v-else class="text-center">
          <p class="text-green-600 text-lg font-semibold mb-4">✅ Account created successfully!</p>
          <button @click="goToLogin" class="text-blue-600 font-semibold hover:underline">
            Go to Login →
          </button>
        </div>

        <!-- Resend -->
        <p class="text-center text-sm mt-4">
          Didn't receive the code?
          <span class="text-blue-600 font-semibold hover:underline cursor-pointer"
            :class="{ 'opacity-50 cursor-not-allowed': resendCooldown > 0 }"
            @click="resendCooldown === 0 ? resendOtp() : null">
            Resend <span v-if="resendCooldown > 0">({{ resendCooldown }}s)</span>
          </span>
        </p>
      </div>
        </div>
       </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { defineEmits } from 'vue'

const emit = defineEmits(['close'])
const form = useForm({ otp: '' })
const success = ref(false)
const otpCountdown = ref(300) // 5 mins
const resendCooldown = ref(0)  // 1 min cooldown

let otpTimer = null
let cooldownTimer = null

const formattedOtpTime = computed(() => {
  const m = String(Math.floor(otpCountdown.value / 60)).padStart(2, '0')
  const s = String(otpCountdown.value % 60).padStart(2, '0')
  return `${m}:${s}`
})

function submitOtp() {
  form.post('/signup/verify-otp', {
    preserveScroll: true,
    onSuccess: () => {
      success.value = true
      clearInterval(otpTimer)
      clearInterval(cooldownTimer)

      Swal.fire({
        icon: 'success',
        title: 'Verified!',
        text: 'Your account has been created successfully.',
        timer: 2000,
        showConfirmButton: false
      })

      setTimeout(() => {
        emit('close')
      }, 2000)
    }
  })
}

function resendOtp() {
  if (resendCooldown.value > 0) return

  form.reset('otp')
  resendCooldown.value = 60
  otpCountdown.value = 300
  startOtpTimer()
  startCooldownTimer()

  Swal.fire({
    icon: 'success',
    title: 'OTP Resent',
    text: 'A new OTP has been sent to your email.',
    timer: 2000,
    showConfirmButton: false
  })
}

function startOtpTimer() {
  clearInterval(otpTimer)
  otpTimer = setInterval(() => {
    if (otpCountdown.value > 0) {
      otpCountdown.value--
    } else {
      clearInterval(otpTimer)
    }
  }, 1000)
}

function startCooldownTimer() {
  clearInterval(cooldownTimer)
  cooldownTimer = setInterval(() => {
    if (resendCooldown.value > 0) {
      resendCooldown.value--
    } else {
      clearInterval(cooldownTimer)
    }
  }, 1000)
}

function goToLogin() {
  emit('close')
}

onMounted(() => {
  startOtpTimer()
})

onBeforeUnmount(() => {
  clearInterval(otpTimer)
  clearInterval(cooldownTimer)
})
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}

.text-maroon {
  color: #650000;
}
</style>
