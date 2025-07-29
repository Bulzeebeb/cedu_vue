<template>
  <div class="min-h-screen bg-white flex flex-col text-gray-800 font-sans">
    <SiteHeader />

    <div class="flex-grow flex items-center justify-center py-12 px-4">
      <div class="bg-white border border-gray-300 rounded-lg shadow-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Change Password</h2>
        <p class="text-center text-gray-600 mb-6">
          Please enter your new password. An OTP will be sent to your email.
        </p>

        <!-- Step 1: Password form -->
        <form v-if="!otpSent" @submit.prevent="sendOtp">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">New Password</label>
            <input
              type="password"
              v-model="form.password"
              placeholder="Enter new password"
              class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
            />
            <p class="text-sm text-red-500 mt-1" v-if="form.errors.password">{{ form.errors.password }}</p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input
              type="password"
              v-model="form.password_confirmation"
              placeholder="Confirm new password"
              class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
            />
            <p class="text-sm text-red-500 mt-1" v-if="form.errors.password_confirmation">
              {{ form.errors.password_confirmation }}
            </p>
          </div>

          <button
            type="submit"
            class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2 rounded-full transition"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Sending OTP...' : 'Update Password' }}
          </button>
        </form>

        <!-- Step 2: OTP Verification -->
        <form v-else @submit.prevent="submitOtp">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">OTP Code</label>
            <input
              type="text"
              v-model="form.otp"
              placeholder="Enter OTP"
              class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
            />
            <p class="text-sm text-red-500 mt-1" v-if="form.errors.otp">{{ form.errors.otp }}</p>

            <div class="text-right mt-1">
              <button type="button" @click="resendOtp" class="text-xs text-blue-600 hover:underline">
                Resend OTP
              </button>
            </div>
          </div>

          <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-full transition"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Verifying...' : 'Confirm Password Change' }}
          </button>
        </form>

        <div class="text-center mt-4">
          <a href="/clientProfile" class="text-blue-600 hover:underline text-sm">← Back to Client Profile</a>
        </div>
      </div>
    </div>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

const otpSent = ref(false)

const form = useForm({
  otp: '',
  password: '',
  password_confirmation: '',
})

const sendOtp = async () => {
  if (!form.password || form.password !== form.password_confirmation) {
    form.setError('password_confirmation', 'Passwords do not match.')
    return
  }

  try {
    await axios.post('/send-password-otp')
    otpSent.value = true

    Swal.fire({
      icon: 'success',
      title: 'OTP Sent!',
      text: 'Check your email for the OTP code.',
      timer: 2500,
      showConfirmButton: false,
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Failed to send OTP',
      text: error.response?.data?.error || 'Please try again later.',
    })
  }
}

const resendOtp = async () => {
  try {
    await axios.post('/send-password-otp')

    Swal.fire({
      icon: 'success',
      title: 'OTP Resent',
      text: 'A new OTP has been sent to your email.',
      timer: 2000,
      showConfirmButton: false,
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Failed to resend OTP',
      text: error.response?.data?.error || 'Please try again later.',
    })
  }
}

const submitOtp = () => {
  form.post('/change-password', {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Password Changed!',
        text: 'Your password was updated successfully.',
      })

      form.reset()
      otpSent.value = false
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Password Change Failed',
        text: 'Please check your OTP or try again.',
      })
    }
  })
}
</script>
