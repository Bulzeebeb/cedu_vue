<template>
  <div class="bg-white text-black min-h-screen font-sans">
    <SiteHeader />
        <!-- Banner -->
        <section class="w-full h-10 bg-cover bg-center" style="background-image: url('/images/banner.jpg')"></section>

        <!-- Breadcrumb -->
        <div class="px-15  text-sm text-gray-600">
            <router-link to="/" class="hover:underline">Home</router-link> / <router-link to="/" class="hover:underline">Login</router-link>
        </div>

        <!-- Login Form -->
        <div class="flex justify-center my-10">
        <div class="border-2 border-blue-500 p-6 rounded-md w-full max-w-md">
            <h2 class="text-center text-2xl font-bold mb-4">Log In</h2>
            <form @submit.prevent="handleLogin">
            <div class="mb-4">
                <input type="email" placeholder="Email" v-model="email" class="w-full px-4 py-2 border border-gray-300 rounded" />
            </div>
            <div class="mb-2 relative">
                <input :type="showPassword ? 'text' : 'password'" placeholder="Password" v-model="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded" />
                <span @click="showPassword = !showPassword"
                    class="absolute right-3 top-3 cursor-pointer text-gray-600 text-sm">
                👁
                </span>
            </div>
            <div class="flex justify-between items-center text-sm mb-4">
                <label class="flex items-center gap-1">
                <input type="checkbox" v-model="remember" />
                Remember me
                </label>
                <a href="#" class="text-blue-500 hover:underline">Forget Password?</a>
            </div>
            <button type="submit" class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800">
                Login
            </button>
            </form>
            <p class="text-center text-sm mt-4">
            Don’t have account?
            <a href="#" class="text-blue-600 font-semibold hover:underline">Register</a>
            </p>
        </div>
        </div>
    <SiteFooter />
  </div>
</template>

<script>
import SiteHeader from './header.vue';
import SiteFooter from './footer.vue';

export default {
  name: 'SignUp',
  components: {
    SiteHeader,
    SiteFooter
  },
  data() {
    return {
      email: '',
      password: '',
      remember: false,
      showPassword: false
    };
  },
  methods: {
    handleLogin() {
      // handle login logic here (validation, API, etc.)
      alert(`Logging in with: ${this.email}`);
    }
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
</style>
