<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen p-10 text-[#5F1213]">
      <!-- Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold flex items-center gap-3">Manage Accounts</h1>
        <p class="text-gray-600 text-sm ml-1">Manage user accounts</p>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ errorMessage }}
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5F1213]"></div>
      </div>

      <!-- Accounts Table -->
      <div v-else class="bg-white shadow-xl rounded-2xl border border-gray-300 p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold flex items-center gap-2">
            <i class="fas fa-list text-[#FFA600]"></i> Account List
          </h2>

        </div>
        <table class="w-full text-sm">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="py-3 px-4 text-left">Profile</th>
              <th class="py-3 px-4 text-left">Name</th>
              <th class="py-3 px-4 text-left">Email</th>
              <th class="py-3 px-4 text-left">Address</th>
              <th class="py-3 px-4 text-left">Contact Number</th>
              <th class="py-3 px-4 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="user.id" class="border-b hover:bg-yellow-50">
              <td class="py-2 px-2">
                <img :src="getUserImage(user.image)" class="w-10 h-10 object-cover rounded mb-1" />
              </td>
              <td class="py-3 px-4">{{ user.firstName }} {{ user.middleName }} {{ user.lastName }}</td>
              <td class="py-3 px-4">{{ user.email }}</td>
              <td class="py-3 px-4">{{ user.address || 'N/A' }}</td>
              <td class="py-3 px-4">{{ user.contactNum || 'N/A' }}</td>
              <td class="py-3 px-4 space-x-3">
                <button @click="viewUser(user)" class="text-green-600 hover:text-green-800 font-medium">
                  <i class="fas fa-eye"></i> View
                </button>
                <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-800 font-medium">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="users.length === 0">
              <td colspan="6" class="py-8 text-center text-gray-500">
                No accounts found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- View Modal -->
      <div v-if="showViewModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div
          class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/40 w-full max-w-xl animate-fade-in relative">
          <button @click="showViewModal = false"
            class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>

          <div class="mb-6 flex items-center gap-3 border-b pb-4">
            <div class="bg-[#FFA600]/20 text-[#FFA600] rounded-full p-3">
              <i class="fas fa-user text-2xl"></i>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-[#5F1213] leading-tight">Account Overview</h2>
              <p class="text-sm text-gray-500">{{ viewedUser.role }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm text-gray-800">
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Full Name</p>
              <p class="font-semibold">{{ viewedUser.firstName }} {{ viewedUser.middleName }} {{
                viewedUser.lastName }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Email</p>
              <p class="font-semibold">{{ viewedUser.email }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Address</p>
              <p class="font-semibold">{{ viewedUser.address || 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Contact</p>
              <p class="font-semibold">{{ viewedUser.contactNum || 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Gender</p>
              <p class="font-semibold">{{ viewedUser.gender || 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Age</p>
              <p class="font-semibold">{{ viewedUser.age || 'N/A' }}</p>
            </div>
          </div>

          <div class="mt-8 flex justify-end">
            <button @click="showViewModal = false"
              class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] px-6 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-[400px] animate-fade-in">
          <h2 class="text-xl font-bold mb-4 text-red-600 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <p class="mb-6 text-gray-700">
            Are you sure you want to delete <strong>{{ userToDelete?.firstName }} {{ userToDelete?.lastName
            }}</strong>?
            This action cannot be undone.
          </p>
          <div class="flex justify-end gap-3">
            <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 hover:text-gray-800">
              Cancel
            </button>
            <button @click="deleteUser" :disabled="submitting"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold disabled:opacity-50">
              {{ submitting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Sidebar from './adminSidebar.vue'
import axios from 'axios'

// Reactive data
const users = ref([])
const newUser = ref({
  firstName: '',
  lastName: '',
  middleName: '',
  address: '',
  contactNum: '',
  gender: '',
  age: '',
  email: ''
})

const viewedUser = ref({})
const userToDelete = ref(null)

// Modal states
const showViewModal = ref(false)
const showDeleteModal = ref(false)

// Form states
const loading = ref(false)
const submitting = ref(false)
const validationErrors = ref({})
const successMessage = ref('')
const errorMessage = ref('')

// Functions
const fetchUsers = async () => {
  try {
    loading.value = true
    const response = await axios.get('/auth:sanctum')
    users.value = response.data
  } catch (error) {
    console.error('Error fetching users:', error)
    errorMessage.value = 'Failed to load users.'
  } finally {
    loading.value = false
  }
}

const viewUser = (user) => {
  viewedUser.value = { ...user }
  showViewModal.value = true
}

const confirmDelete = (user) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const deleteUser = async () => {
  try {
    submitting.value = true

    await axios.delete(`/api/userclients/${userToDelete.value.id}`)

    successMessage.value = 'Account deleted successfully!'
    showDeleteModal.value = false
    userToDelete.value = null
    await fetchUsers()

  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Failed to delete account.'
  } finally {
    submitting.value = false
  }
}

// Clear messages after 5 seconds
const clearMessages = () => {
  setTimeout(() => {
    successMessage.value = ''
    errorMessage.value = ''
  }, 5000)
}

// Watch for messages and auto-clear them
const watchMessages = () => {
  if (successMessage.value || errorMessage.value) {
    clearMessages()
  }
}

// Lifecycle
onMounted(() => {
  fetchUsers()
})

// Watch for message changes
watchMessages()
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

@keyframes fade-in {
  0% {
    opacity: 0;
    transform: translateY(-10px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>