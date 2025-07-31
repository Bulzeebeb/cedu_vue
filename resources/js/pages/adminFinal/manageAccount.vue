<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen p-10 text-[#5F1213]">
      <!-- Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold flex items-center gap-3">Manage Accounts</h1>
        <p class="text-gray-600 text-sm ml-1">Create, edit, and manage user accounts</p>
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
          <button @click="showAddModal = true"
                  class="bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 px-4 py-2 rounded-lg font-semibold shadow">
            <i class="fas fa-user-plus mr-2"></i> Add Account
          </button>
        </div>
        <table class="w-full text-sm">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="py-3 px-4 text-left">Name</th>
              <th class="py-3 px-4 text-left">Email</th>
              <th class="py-3 px-4 text-left">Role</th>
              <th class="py-3 px-4 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="user.id" class="border-b hover:bg-yellow-50">
              <td class="py-3 px-4">{{ user.first_name }} {{ user.middle_initial }} {{ user.last_name }}</td>
              <td class="py-3 px-4">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span class="px-2 py-1 rounded-full text-xs font-bold text-white"
                      :class="user.role === 'Admin' ? 'bg-green-600' : user.role === 'Staff' ? 'bg-blue-500' : 'bg-gray-500'">
                  {{ user.role }}
                </span>
              </td>
              <td class="py-3 px-4 space-x-3">
                <button @click="editUser(user)" class="text-blue-600 hover:text-blue-800 font-medium">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button @click="viewUser(user)" class="text-green-600 hover:text-green-800 font-medium">
                    <i class="fas fa-eye"></i> View
                </button>
                <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-800 font-medium">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="users.length === 0">
              <td colspan="4" class="py-8 text-center text-gray-500">
                No accounts found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add Account Modal -->
      <div v-if="showAddModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/30 w-full max-w-5xl animate-fade-in relative">
          <button @click="closeAddModal" class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
          <h2 class="text-2xl font-bold mb-6 text-[#5F1213] flex items-center gap-3">
            <i class="fas fa-user-plus text-[#FFA600] text-xl"></i> Add New Account
          </h2>
          <form @submit.prevent="addUser">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="text-sm font-semibold">First Name *</label>
                <input v-model="newUser.first_name"
                       placeholder="Juan"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.first_name}"
                       required />
                <p v-if="validationErrors.first_name" class="text-red-500 text-xs mt-1">{{ validationErrors.first_name[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Middle Initial</label>
                <input v-model="newUser.middle_initial"
                       placeholder="P"
                       maxlength="1"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.middle_initial}" />
                <p v-if="validationErrors.middle_initial" class="text-red-500 text-xs mt-1">{{ validationErrors.middle_initial[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Last Name *</label>
                <input v-model="newUser.last_name"
                       placeholder="Dela Cruz"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.last_name}"
                       required />
                <p v-if="validationErrors.last_name" class="text-red-500 text-xs mt-1">{{ validationErrors.last_name[0] }}</p>
              </div>
              <div class="md:col-span-2">
                <label class="text-sm font-semibold">Address</label>
                <input v-model="newUser.address"
                       placeholder="Tagum City"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.address}" />
                <p v-if="validationErrors.address" class="text-red-500 text-xs mt-1">{{ validationErrors.address[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Contact Number</label>
                <input v-model="newUser.contact"
                       placeholder="09123456789"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.contact}" />
                <p v-if="validationErrors.contact" class="text-red-500 text-xs mt-1">{{ validationErrors.contact[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Gender</label>
                <select v-model="newUser.gender"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md"
                        :class="{'border-red-500': validationErrors.gender}">
                    <option value="">Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
                <p v-if="validationErrors.gender" class="text-red-500 text-xs mt-1">{{ validationErrors.gender[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Age</label>
                <input v-model.number="newUser.age"
                       type="number"
                       min="18"
                       max="99"
                       placeholder="e.g., 25"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.age}" />
                <p v-if="validationErrors.age" class="text-red-500 text-xs mt-1">{{ validationErrors.age[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Email *</label>
                <input v-model="newUser.email"
                       type="email"
                       placeholder="juan@example.com"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       :class="{'border-red-500': validationErrors.email}"
                       required />
                <p v-if="validationErrors.email" class="text-red-500 text-xs mt-1">{{ validationErrors.email[0] }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold">Role *</label>
                <select v-model="newUser.role"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md"
                        :class="{'border-red-500': validationErrors.role}"
                        required>
                    <option value="">Select Role</option>
                    <option>Admin</option>
                    <option>Staff</option>
                </select>
                <p v-if="validationErrors.role" class="text-red-500 text-xs mt-1">{{ validationErrors.role[0] }}</p>
              </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
              <button type="button"
                      @click="closeAddModal"
                      class="px-6 py-2 text-gray-600 hover:text-red-600 font-semibold">
                Cancel
              </button>
              <button type="submit"
                      :disabled="submitting"
                      class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] font-bold px-6 py-2 rounded-lg shadow-md hover:shadow-lg disabled:opacity-50">
                <i class="fas fa-plus mr-2"></i>
                {{ submitting ? 'Adding...' : 'Add Account' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Modal -->
      <div v-if="isEditing" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-[500px] animate-fade-in">
          <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-user-edit text-[#FFA600]"></i> Edit Account
          </h2>
          <form @submit.prevent="updateUser">
            <div class="space-y-3">
              <div>
                <label class="text-sm font-semibold">First Name</label>
                <input v-model="editedUser.first_name"
                       placeholder="First Name"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       required />
              </div>
              <div>
                <label class="text-sm font-semibold">Middle Initial</label>
                <input v-model="editedUser.middle_initial"
                       placeholder="M.I."
                       maxlength="1"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100" />
              </div>
              <div>
                <label class="text-sm font-semibold">Last Name</label>
                <input v-model="editedUser.last_name"
                       placeholder="Last Name"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       required />
              </div>
              <div>
                <label class="text-sm font-semibold">Email</label>
                <input v-model="editedUser.email"
                       type="email"
                       placeholder="Email"
                       class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                       required />
              </div>
              <div>
                <label class="text-sm font-semibold">Role</label>
                <select v-model="editedUser.role"
                        class="w-full px-4 py-2 rounded-lg border bg-gray-100"
                        required>
                  <option>Admin</option>
                  <option>Staff</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
              <button type="button"
                      @click="cancelEdit"
                      class="text-gray-600 hover:text-red-600 px-4 py-2">
                Cancel
              </button>
              <button type="submit"
                      :disabled="submitting"
                      class="bg-[#FFA600] text-[#5F1213] px-4 py-2 rounded hover:bg-yellow-400 font-semibold disabled:opacity-50">
                {{ submitting ? 'Saving...' : 'Save' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- View Modal -->
      <div v-if="showViewModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/40 w-full max-w-xl animate-fade-in relative">
          <button @click="showViewModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>

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
              <p class="font-semibold">{{ viewedUser.first_name }} {{ viewedUser.middle_initial }} {{ viewedUser.last_name }}</p>
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
              <p class="font-semibold">{{ viewedUser.contact || 'N/A' }}</p>
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
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-[400px] animate-fade-in">
          <h2 class="text-xl font-bold mb-4 text-red-600 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <p class="mb-6 text-gray-700">
            Are you sure you want to delete <strong>{{ userToDelete?.first_name }} {{ userToDelete?.last_name }}</strong>?
            This action cannot be undone.
          </p>
          <div class="flex justify-end gap-3">
            <button @click="showDeleteModal = false"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
              Cancel
            </button>
            <button @click="deleteUser"
                    :disabled="submitting"
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
import Sidebar from './sidebar.vue'
import axios from 'axios'

// Reactive data
const users = ref([])
const newUser = ref({
  first_name: '',
  last_name: '',
  middle_initial: '',
  address: '',
  contact: '',
  gender: '',
  age: '',
  email: '',
  role: ''
})

const editedUser = ref({})
const viewedUser = ref({})
const userToDelete = ref(null)

// Modal states
const showViewModal = ref(false)
const isEditing = ref(false)
const showAddModal = ref(false)
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
    const response = await axios.get('/admins')
    users.value = response.data
  } catch (error) {
    console.error('Error fetching users:', error)
    errorMessage.value = 'Failed to load users.'
  } finally {
    loading.value = false
  }
}

const addUser = async () => {
  try {
    submitting.value = true
    validationErrors.value = {}

    const response = await axios.post('/admins', newUser.value)

    // Show success message with generated password
    successMessage.value = response.data.message || 'Account created successfully!'

    // Reset form
    newUser.value = {
      first_name: '',
      last_name: '',
      middle_initial: '',
      address: '',
      contact: '',
      gender: '',
      age: '',
      email: '',
      role: ''
    }

    // Close modal and refresh users
    showAddModal.value = false
    await fetchUsers()

  } catch (error) {
    if (error.response?.status === 422) {
      validationErrors.value = error.response.data.errors
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create account.'
    }
  } finally {
    submitting.value = false
  }
}

const editUser = (user) => {
  editedUser.value = { ...user }
  isEditing.value = true
}

const updateUser = async () => {
  try {
    submitting.value = true

    await axios.put(`/api/admins/${editedUser.value.id}`, editedUser.value)

    successMessage.value = 'Account updated successfully!'
    isEditing.value = false
    await fetchUsers()

  } catch (error) {
    if (error.response?.status === 422) {
      // Handle validation errors if needed
      errorMessage.value = 'Please check your input and try again.'
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update account.'
    }
  } finally {
    submitting.value = false
  }
}

const cancelEdit = () => {
  isEditing.value = false
  editedUser.value = {}
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

    await axios.delete(`/api/admins/${userToDelete.value.id}`)

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

const closeAddModal = () => {
  showAddModal.value = false
  validationErrors.value = {}
  newUser.value = {
    first_name: '',
    last_name: '',
    middle_initial: '',
    address: '',
    contact: '',
    gender: '',
    age: '',
    email: '',
    role: ''
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