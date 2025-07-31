<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'

const sidebarOpen = ref(false)
const isModalOpen = ref(false)

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}

function openModal() {
  isModalOpen.value = true
}

function closeModal() {
  isModalOpen.value = false
}
function saveModal() {
  isModalOpen.value = false
}

const profile = ref({
  firstName: 'Juan',
  lastName: 'Dela Cruz',
  middleInitial: 'P',
  address: 'Tagum City',
  contact: '09123456789',
  gender: 'Male',
  email: 'admin@material.com',
  age: 25,
  password: '********'
})

function admin_Dashboard() {
  router.visit('/admin_Dashboard')
}

function admin_ManageParking() {
  router.visit('/admin_ManageParking')
}
function admin_Account() {
  router.visit('/admin_Account')
}
function admin_Reports() {
  router.visit('/admin_parking_reports')
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P />
    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <!-- Header -->
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
           <h1 class="text-4xl font-extrabold text-[#5F1213]">User Profile</h1>
        </div>
      </header>
      <!-- Divider -->
      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Profile Content -->
      <main class="px-6 py-10 flex-1 flex justify-center items-start">
        <div class="w-full max-w-5xl bg-white shadow-lg p-8 rounded-2xl">
          <div class="flex flex-col items-center mb-8">
            <img src="https://i.pravatar.cc/100" class="rounded-full w-28 h-28 border-4 border-yellow-400 mb-4" />
            <h2 class="text-3xl font-bold text-[#5F1213]">Admin</h2>
            <p class="text-gray-500">System Administrator</p>
          </div>

          <!-- Display Fields -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label class="block text-sm font-semibold mb-1">First Name</label><input class="w-full border p-2 rounded" :value="profile.firstName" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Last Name</label><input class="w-full border p-2 rounded" :value="profile.lastName" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">M.I.</label><input class="w-full border p-2 rounded" :value="profile.middleInitial" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Address</label><input class="w-full border p-2 rounded" :value="profile.address" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Contact Number</label><input class="w-full border p-2 rounded" :value="profile.contact" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Gender</label><input class="w-full border p-2 rounded" :value="profile.gender" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Email Address</label><input class="w-full border p-2 rounded" :value="profile.email" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Age</label><input class="w-full border p-2 rounded" :value="profile.age" disabled /></div>
            <div><label class="block text-sm font-semibold mb-1">Password</label><input class="w-full border p-2 rounded" type="password" :value="profile.password" disabled /></div>
          </div>

          <!-- Edit Button -->
          <div class="flex justify-end mt-8">
            <button @click="openModal" class="bg-[#5F1213] text-white px-6 py-2 rounded hover:bg-[#771A1B]">Edit Profile</button>
          </div>
        </div>
      </main>

    </div>

    <!-- Edit Modal -->
     <div v-if="isModalOpen" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/30 w-full max-w-5xl animate-fade-in relative">
          <button @click="isModalOpen = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
          <h2 class="text-2xl font-bold mb-6 text-[#5F1213] flex items-center gap-3">
            <i class="fas fa-edit text-[#FFA600] text-xl"></i> Edit Profile
          </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><label class="block text-sm font-semibold mb-1">First Name</label><input v-model="profile.firstName" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Last Name</label><input v-model="profile.lastName" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">M.I.</label><input v-model="profile.middleInitial" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Address</label><input v-model="profile.address" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Contact</label><input v-model="profile.contact" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Gender</label><input v-model="profile.gender" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Email</label><input v-model="profile.email" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Age</label><input v-model="profile.age" class="w-full border p-2 rounded" /></div>
          <div><label class="block text-sm font-semibold mb-1">Password</label><input v-model="profile.password" type="password" class="w-full border p-2 rounded" /></div>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
          <button  @click="saveModal" class="bg-[#5F1213] text-white px-4 py-2 rounded hover:bg-[#771A1B]">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
body {
  font-family: 'Inter', sans-serif;
}
</style>
