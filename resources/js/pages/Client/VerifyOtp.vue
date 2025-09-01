<template>
  <!-- Only the modal card, no overlay or blur -->
  <transition name="scale-fade">
    <div v-if="!success" class="bg-white rounded-xl shadow-lg w-full max-w-lg text-black font-sans overflow-hidden">
      <div class="p-6">
        <h2 class="text-center text-2xl font-bold mb-4">Verify OTP</h2>
        <p class="text-center text-gray-600 mb-2">
          We've sent a 6-digit OTP to your email. Please enter it below to verify your account.
        </p>
        <p class="text-center text-red-600 text-sm mb-6">
          OTP expires in: {{ formattedOtpTime }}
        </p>

        <!-- OTP Form -->
        <form @submit.prevent="submitOtp">
          <div class="mb-4">
            <input type="text" v-model="form.otp" maxlength="6" placeholder="Enter OTP"
              class="w-full px-4 py-2 border border-gray-300 rounded text-center tracking-widest text-xl"
              required />
            <p v-if="form.errors.otp" class="text-red-500 text-sm mt-1">{{ form.errors.otp }}</p>
          </div>

          <button type="submit"
            class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800">
            Verify OTP
          </button>
        </form>

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
  </transition>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { defineEmits, defineProps } from 'vue'
import { router } from '@inertiajs/vue3'


const emit = defineEmits(['close'])
const props = defineProps({
  formData: {
    type: Object,
    default: () => ({})
  }
})
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
  Swal.fire({
    title: 'Verified! You may now login to your account.',
    icon: 'success',
    showConfirmButton: false,
    allowOutsideClick: false,
    timer: 1200,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  form.post('/signup/verify-otp', {
    preserveScroll: true,
    onSuccess: () => {
      success.value = true;
      clearInterval(otpTimer);
      clearInterval(cooldownTimer);
      setTimeout(() => {
        Swal.close();
          window.location.href = '/signmain';
      }, 1200);
    },
    onError: () => {
      Swal.close();
    }
  });
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

function goToLogin(id) {
  router.visit(`/signmain`)
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

/* Overlay fade */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Card scale & fade */
.scale-fade-enter-active, .scale-fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.scale-fade-enter-from {
  opacity: 0;
  transform: scale(0.95);
}
.scale-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>