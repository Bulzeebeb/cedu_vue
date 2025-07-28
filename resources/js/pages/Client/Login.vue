<template>
  <div class="bg-white text-black min-h-screen font-sans flex flex-col">
    <SiteHeader />

    <section
      class="w-full bg-cover bg-center"
      style="background-image: url('/images/banner.jpg')"
    ></section>

    <div class="flex-grow flex items-center justify-center py-10">
      <div class="border border-gray-300 p-6 rounded-md w-full max-w-md shadow-md">
        <h2 class="text-center text-2xl font-bold mb-4">Log In</h2>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin">
          <!-- Email -->
          <div class="mb-4">
            <input
              type="email"
              placeholder="Email"
              v-model="form.email"
              class="w-full px-4 py-2 border border-gray-300 rounded"
              required
            />
            <p v-if="form.errors.email && form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password -->
          <div class="mb-2 relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              placeholder="Password"
              v-model="form.password"
              class="w-full px-4 py-2 border border-gray-300 rounded"
              required
            />
            <span
              @click="togglePassword"
              class="absolute right-3 top-3 cursor-pointer text-gray-600 text-sm"
            >👁</span>

            <!-- Password field-specific error -->
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.password }}
            </p>

            <!-- General login error shown below password field -->
            <p v-if="form.errors.email && !form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800"
          >
            Login
          </button>
        </form>

        <!-- Register Redirect -->
        <p class="text-center text-sm mt-4">
          Don’t have an account?
          <Link href="/signup/step1" class="text-blue-600 font-semibold hover:underline">
            Register
          </Link>
        </p>
      </div>
    </div>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

const showPassword = ref(false)

const form = useForm({
  email: '',
  password: ''
})

function togglePassword() {
  showPassword.value = !showPassword.value
}

function handleLogin() {
  form.post('/signin', {
    preserveScroll: true,
    onError: () => {
      console.log('Login failed')
    }
  })
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
</style>
