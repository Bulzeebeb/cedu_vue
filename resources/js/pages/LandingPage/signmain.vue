<template>
  <div class="flex h-screen bg-gradient-to-r from-[#650000] via-[#650000] to-yellow-300 text-white">
    <!-- Left Side: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8">
      <h2 class="text-4xl font-bold mb-4 animate-fade-in">Welcome to <span class="text-yellow-400">CEDU iCentral</span></h2>
      <p class="mb-8 text-gray-400">Products and Services</p>

      <form @submit.prevent="login" class="w-full max-w-sm">
        <div class="mb-4">
          <input type="email" v-model="email" placeholder="Email Address"
                 class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black"/>
        </div>
        <div class="mb-4 relative">
          <input :type="showPassword ? 'text' : 'password'" v-model="password" placeholder="Password"
                 class="w-full px-4 py-2 rounded-lg bg-white focus:outline-none text-black"/>
          <button type="button" @click="showPassword = !showPassword"
                  class="absolute right-3 top-2 text-gray-400">
            {{ showPassword ? 'Hide' : 'Show' }}
          </button>
        </div>

        <button type="submit"
                class="w-full py-2 bg-yellow-400 rounded-lg hover:bg-yellow-600">Continue</button>

        <p class="text-gray-400 text-center mt-4">
          New here? <a href="#" class="text-blue-400">Create Account</a>
        </p>
      </form>
    </div>

    <!-- Right Side: Image Preview -->
    <div class="hidden md:flex w-1/2 bg-cover" style="background-image: url('/images/signin-cover.png')">
      <!-- Use a grid of movie posters here -->
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const password = ref('');
const showPassword = ref(false);

const login = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value
    });

    alert(response.data.message);
    localStorage.setItem('token', response.data.token);
    localStorage.setItem('role', response.data.user.role);

    // Example role-based navigation
    if (response.data.user.role === 'superadmin') {
      window.location.href = '/superadmin-dashboard';
    } else {
      window.location.href = '/admin-dashboard';
    }

  } catch (error) {
    alert('Invalid credentials');
  }
};
</script>

<style>
body {
  margin: 0;
  font-family: 'Arial', sans-serif;
}
</style>
