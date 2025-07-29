<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">

    <!-- Embedded Header -->
    <div class="w-full">
      <div class="w-full bg-maroon text-[10px] text-gray-200 flex justify-end items-center pr-4 py-2 border-b border-yellow-500">

      </div>

      <nav class="bg-white text-black border-b border-gray-200">
        <div class="w-full px-4">
          <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-2">
              <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
              <h1 class="text-lg font-bold text-maroon">
                CEDU <span class="text-yellow-500">iCentral</span>
              </h1>
            </div>

            <div class="md:hidden">
              <button @click="isOpen = !isOpen" class="text-black focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    v-if="!isOpen"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>

            <div class="hidden md:flex gap-6">
              <a v-for="item in navItems" :key="item.name" :href="item.href" class="nav-link text-maroon font-medium hover:text-yellow-500 hover:underline">
                {{ item.name }}
              </a>
            </div>
          </div>
        </div>

        <div v-if="isOpen" class="md:hidden px-4 pb-4">
          <a v-for="item in navItems" :key="item.name" :href="item.href" class="block py-2 text-maroon hover:text-yellow-500">
            {{ item.name }}
          </a>
        </div>
      </nav>
    </div>

    <!-- Profile Content -->
    <main class="flex-grow">
      <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6 mt-6 mb-6 space-y-6">
        <div class="flex flex-col items-center space-y-2">
          <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-yellow-500">
            <img :src="previewImage || '/images/default-profile.png'" alt="Profile" class="w-full h-full object-cover" />
          </div>
          <label class="cursor-pointer bg-yellow-500 text-maroon px-3 py-1 rounded hover:shadow transition text-sm">
            Upload Photo
            <input type="file" class="hidden" @change="handleImageUpload" accept="image/*" />
          </label>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 text-center">My Profile</h2>

        <form @submit.prevent="saveProfile">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-600 mb-1">First Name</label>
              <input v-model="form.firstName" type="text" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Middle Name</label>
              <input v-model="form.middleName" type="text" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Last Name</label>
              <input v-model="form.lastName" type="text" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Name Extension</label>
              <input v-model="form.extension" type="text" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Email</label>
              <input v-model="form.email" type="email" class="w-full border px-3 py-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" disabled />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Contact Number</label>
              <input v-model="form.contactNum" type="text" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Gender</label>
              <select v-model="form.gender" class="w-full border px-3 py-2 rounded text-black">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div>
              <label class="block text-sm text-gray-600 mb-1">Age</label>
              <input v-model="form.age" type="number" class="w-full border px-3 py-2 rounded text-black" />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm text-gray-600 mb-1">Address</label>
              <textarea v-model="form.address" rows="3" class="w-full border px-3 py-2 rounded text-black"></textarea>
            </div>
          </div>

          <div class="mt-4 text-left">
            <Link href="/change-password" class="text-yellow-600 text-sm hover:underline">Change Password?</Link>
          </div>

          <div class="mt-6 text-right">
            <button type="submit" class="bg-yellow-500 text-maroon px-5 py-2 rounded hover:shadow hover:scale-105 transition">
              Save Profile
            </button>
          </div>
        </form>
      </div>
    </main>

    <SiteFooter />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import SiteFooter from './footer.vue'

const isOpen = ref(false)
const imageFile = ref(null)

const navItems = [
  { name: '← Back', href: '/clientSetting' },
]

const props = defineProps({
  user: Object,
})

const previewImage = ref(null)
const form = ref({
  firstName: props.user?.firstName || '',
  middleName: props.user?.middleName || '',
  lastName: props.user?.lastName || '',
  extension: props.user?.extension || '',
  email: props.user?.email || '',
  contactNum: props.user?.contactNum || '',
  gender: props.user?.gender || '',
  age: props.user?.age || '',
  address: props.user?.address || '',
})

function saveProfile() {
  const formData = new FormData()
  for (const key in form.value) {
    formData.append(key, form.value[key])
  }

  if (imageFile.value) {
    formData.append('image', imageFile.value)
  }

  router.post('/client/setting', formData, {
    forceFormData: true,
    onSuccess: () => {
      alert('Profile updated successfully.')
    },
    onError: (errors) => {
      console.error(errors)
      alert('There were validation errors.')
    }
  })
}

function handleImageUpload(e) {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    previewImage.value = URL.createObjectURL(file)
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
.text-maroon {
  color: #651818;
}
</style>
