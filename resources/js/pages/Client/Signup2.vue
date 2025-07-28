<template>
  <div class="bg-white text-black min-h-screen font-sans flex flex-col">
    <SiteHeader />

    <section class="w-full bg-cover bg-center" style="background-image: url('/images/banner.jpg')"></section>

    <div class="flex-grow flex items-center justify-center py-8">
      <div class="border border-gray-300 p-6 rounded-md w-full max-w-2xl shadow-md">
        <h2 class="text-center text-2xl font-bold mb-6">Create Account - Step 2 of 2</h2>
        <p class="text-center text-gray-600 mb-6">Account Information</p>

        <form @submit.prevent="handleCreateAccount">
          <!-- Email -->
          <div class="mb-4">
            <input
              type="email"
              placeholder="Email Address"
              v-model="form.email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded"
            />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>

          <!-- Password and Confirm Password -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <input
                type="password"
                placeholder="Password"
                v-model="form.password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded"
              />
              <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
            </div>
            <div>
              <input
                type="password"
                placeholder="Confirm Password"
                v-model="form.password_confirmation"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded"
              />
              <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
            </div>
          </div>

          <!-- Terms -->
          <div class="mb-6">
            <label class="flex items-center">
              <input type="checkbox" v-model="acceptTerms" required class="mr-2" />
              <span class="text-sm">
                I agree to the
                <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>
              </span>
            </label>
          </div>

          <!-- Step Progress -->
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-maroon">Step 2 of 2</span>
              <span class="text-sm text-gray-500">100%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-maroon h-2 rounded-full" style="width: 100%"></div>
            </div>
          </div>

          <button
            type="submit"
            class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800"
          >
            Create Account
          </button>
        </form>

        <div class="flex justify-center mt-4">
          <button @click="goBack" class="text-blue-600 font-semibold hover:underline">
            ← Back to Step 1
          </button>
        </div>

        <p class="text-center text-sm mt-4">
          Already have an account?
          <Link href="/signin" class="text-blue-600 font-semibold hover:underline">Login</Link>
        </p>
      </div>
    </div>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm, Link } from '@inertiajs/vue3'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

// Props from Step 1
const props = defineProps({
  formData: {
    type: Object,
    default: () => ({})
  }
})

// Combine step 1 and step 2 data
const form = useForm({
  ...props.formData,
  email: '',
  password: '',
  password_confirmation: ''
})

const acceptTerms = ref(false)

async function handleCreateAccount() {
  if (!acceptTerms.value) {
    alert('Please accept the Terms and Conditions.')
    return
  }

  if (form.password !== form.password_confirmation) {
    alert('Passwords do not match.')
    return
  }

  try {
    await form.post('/signup/store', {
      preserveScroll: true,
      onError: (errors) => {
        console.error('Validation failed:', errors)
      }
    })
  } catch (error) {
    console.error('Unexpected error:', error)
    alert('Something went wrong. Please try again.')
  }
}

function goBack() {
  router.visit('/signup/step1')
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
</style>
