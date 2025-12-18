<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen p-10 text-[#5F1213]">
      <!-- Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold flex items-center gap-3">Manage Admin and Staff Accounts</h1>
        <p class="text-gray-600 text-sm ml-1">Create, edit, and manage user accounts</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5F1213]"></div>
      </div>

      <!-- Accounts Table -->
      <div v-else class="bg-white shadow-xl rounded-2xl border border-gray-300 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
          <h2 class="text-xl font-semibold flex items-center gap-2">
            <i class="fas fa-list text-[#FFA600]"></i> Account List
          </h2>

          <div class="flex gap-3">
            <input
              v-model="search"
              type="text"
              placeholder="Search name or email..."
              class="px-3 py-2 rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FFA600] w-64"
            />
            <button
              @click="showAddModal = true"
              class="bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 px-4 py-2 rounded-lg font-semibold shadow"
            >
              <i class="fas fa-user-plus mr-2"></i> Add Account
            </button>
          </div>
        </div>

        <table class="w-full text-sm">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="py-3 px-4 text-left">Name</th>
              <th class="py-3 px-4 text-left">Email</th>
              <th class="py-3 px-4 text-left">Role</th>
              <th class="py-3 px-4 text-left">Status</th>
              <th class="py-3 px-4 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in filteredUsers" :key="user.id" class="border-b hover:bg-yellow-50">
              <td class="py-3 px-4">
                {{ user.first_name }} <span v-if="user.middle_initial">{{ user.middle_initial }}.</span> {{ user.last_name }}
              </td>
              <td class="py-3 px-4">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span
                  class="px-2 py-1 rounded-full text-xs font-bold text-white"
                  :class="user.role === 'Admin' ? 'bg-green-600' : user.role === 'Staff' ? 'bg-blue-500' : 'bg-gray-500'"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="py-3 px-4">
                <span
                  class="px-2 py-1 rounded-full text-xs font-bold"
                  :class="user.status === 'enabled' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'"
                >
                  {{ user.status || 'enabled' }}
                </span>
              </td>
              <td class="py-3 px-4 space-x-3">
                <button @click="openEditModal(user)" class="text-blue-600 hover:text-blue-800 font-medium">
                  <i class="fas fa-edit"></i> Edit
                </button>

                <button @click="viewUser(user)" class="text-green-600 hover:text-green-800 font-medium">
                  <i class="fas fa-eye"></i> View
                </button>
                
                <button @click="resetPassword(user)" class="text-purple-600 hover:text-purple-800 font-medium">
                  <i class="fas fa-key"></i> Reset Pass
                </button>
                
                <button
                  @click="confirmToggleUserStatus(user, index)"
                  :class="{
                    'text-green-600 hover:text-green-800': (user.status || 'enabled') === 'disabled',
                    'text-red-600 hover:text-red-800': (user.status || 'enabled') === 'enabled'
                  }"
                  class="font-medium"
                >
                  <i :class="(user.status || 'enabled') === 'enabled' ? 'fas fa-user-slash' : 'fas fa-user-check'"></i>
                  {{ (user.status || 'enabled') === 'enabled' ? 'Disable' : 'Enable' }}
                </button>
                
                <button @click="confirmDeleteUser(user)" class="text-red-600 hover:text-red-800 font-medium">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="5" class="py-8 text-center text-gray-500">No accounts found.</td>
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
                <input
                  v-model="newUser.first_name"
                  placeholder="Juan"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.first_name}"
                  required
                />
                <p v-if="validationErrors.first_name" class="text-red-500 text-xs mt-1">{{ validationErrors.first_name[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Middle Initial</label>
                <input
                  v-model="newUser.middle_initial"
                  placeholder="P"
                  maxlength="1"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.middle_initial}"
                />
                <p v-if="validationErrors.middle_initial" class="text-red-500 text-xs mt-1">{{ validationErrors.middle_initial[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Last Name *</label>
                <input
                  v-model="newUser.last_name"
                  placeholder="Dela Cruz"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.last_name}"
                  required
                />
                <p v-if="validationErrors.last_name" class="text-red-500 text-xs mt-1">{{ validationErrors.last_name[0] }}</p>
              </div>

              <div class="md:col-span-2">
                <label class="text-sm font-semibold">Address</label>
                <input
                  v-model="newUser.address"
                  placeholder="Tagum City"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.address}"
                />
                <p v-if="validationErrors.address" class="text-red-500 text-xs mt-1">{{ validationErrors.address[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Contact Number</label>
                <input
                  v-model="newUser.contact"
                  placeholder="09123456789"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.contact}"
                />
                <p v-if="validationErrors.contact" class="text-red-500 text-xs mt-1">{{ validationErrors.contact[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Gender</label>
                <select
                  v-model="newUser.gender"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md"
                  :class="{'border-red-500': validationErrors.gender}"
                >
                  <option value="">Select Gender</option>
                  <option>Male</option>
                  <option>Female</option>
                </select>
                <p v-if="validationErrors.gender" class="text-red-500 text-xs mt-1">{{ validationErrors.gender[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Age</label>
                <input
                  v-model.number="newUser.age"
                  type="number"
                  min="18"
                  max="99"
                  placeholder="e.g., 25"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.age}"
                />
                <p v-if="validationErrors.age" class="text-red-500 text-xs mt-1">{{ validationErrors.age[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Email *</label>
                <input
                  v-model="newUser.email"
                  type="email"
                  placeholder="juan@example.com"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.email}"
                  required
                />
                <p v-if="validationErrors.email" class="text-red-500 text-xs mt-1">{{ validationErrors.email[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Role *</label>
                <select
                  v-model="newUser.role"
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md"
                  :class="{'border-red-500': validationErrors.role}"
                  required
                >
                  <option value="">Select Role</option>
                  <option>Admin</option>
                  <option>Staff</option>
                </select>
                <p v-if="validationErrors.role" class="text-red-500 text-xs mt-1">{{ validationErrors.role[0] }}</p>
              </div>

              <div>
                <label class="text-sm font-semibold">Custom Password (Optional)</label>
                <input
                  v-model="newUser.password"
                  type="password"
                  placeholder="Leave blank for auto-generated password"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.password}"
                />
                <p v-if="validationErrors.password" class="text-red-500 text-xs mt-1">{{ validationErrors.password[0] }}</p>
                <p class="text-xs text-gray-500 mt-1">If empty, password will be: first_initial + last_name + 123</p>
              </div>

              <div class="md:col-span-3">
                <label class="text-sm font-semibold">Profile Picture (Optional)</label>
                <input
                  ref="profilePictureInput"
                  type="file"
                  accept="image/jpeg,image/png,image/jpg,image/gif"
                  @change="handleProfilePictureChange"
                  class="w-full px-4 py-2 rounded-lg border bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.profile_picture}"
                />
                <p v-if="validationErrors.profile_picture" class="text-red-500 text-xs mt-1">{{ validationErrors.profile_picture[0] }}</p>
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPEG, PNG, JPG, GIF</p>
              </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
              <button type="button" @click="closeAddModal" class="px-6 py-2 text-gray-600 hover:text-red-600 font-semibold">
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] font-bold px-6 py-2 rounded-lg shadow-md hover:shadow-lg disabled:opacity-50"
              >
                <i class="fas fa-plus mr-2"></i>
                {{ submitting ? 'Adding...' : 'Add Account' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Account Modal -->
      <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6">

          <!-- Close Button -->
          <button @click="closeEditModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-lg"></i>
          </button>

          <!-- Modal Header -->
          <h3 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center gap-3">
            <i class="fas fa-user-edit text-[#FFA600] text-xl"></i> Edit Account
          </h3>

          <!-- Edit Form -->
          <form @submit.prevent="updateUser">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

              <!-- First Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">First Name *</label>
                <input v-model="editedUser.first_name" type="text" required
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.first_name}">
                <p v-if="validationErrors.first_name" class="text-red-500 text-xs mt-1">{{ validationErrors.first_name[0] }}</p>
              </div>

              <!-- Middle Initial -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Middle Initial</label>
                <input v-model="editedUser.middle_initial" type="text" maxlength="1"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.middle_initial}">
                <p v-if="validationErrors.middle_initial" class="text-red-500 text-xs mt-1">{{ validationErrors.middle_initial[0] }}</p>
              </div>

              <!-- Last Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                <input v-model="editedUser.last_name" type="text" required
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.last_name}">
                <p v-if="validationErrors.last_name" class="text-red-500 text-xs mt-1">{{ validationErrors.last_name[0] }}</p>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Email *</label>
                <input v-model="editedUser.email" type="email" required
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.email}">
                <p v-if="validationErrors.email" class="text-red-500 text-xs mt-1">{{ validationErrors.email[0] }}</p>
              </div>

              <!-- Address -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input v-model="editedUser.address" type="text"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.address}">
                <p v-if="validationErrors.address" class="text-red-500 text-xs mt-1">{{ validationErrors.address[0] }}</p>
              </div>

              <!-- Contact Number -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input v-model="editedUser.contact" type="text"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.contact}">
                <p v-if="validationErrors.contact" class="text-red-500 text-xs mt-1">{{ validationErrors.contact[0] }}</p>
              </div>

              <!-- Gender -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select v-model="editedUser.gender"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.gender}">
                  <option value="">Select Gender</option>
                  <option>Male</option>
                  <option>Female</option>
                </select>
                <p v-if="validationErrors.gender" class="text-red-500 text-xs mt-1">{{ validationErrors.gender[0] }}</p>
              </div>

              <!-- Age -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Age</label>
                <input v-model="editedUser.age" type="number" min="18" max="99"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.age}">
                <p v-if="validationErrors.age" class="text-red-500 text-xs mt-1">{{ validationErrors.age[0] }}</p>
              </div>

              <!-- Role -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Role *</label>
                <select v-model="editedUser.role" required
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.role}">
                  <option value="">Select Role</option>
                  <option>Admin</option>
                  <option>Staff</option>
                </select>
                <p v-if="validationErrors.role" class="text-red-500 text-xs mt-1">{{ validationErrors.role[0] }}</p>
              </div>

              <!-- Password -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                <input v-model="editedUser.password" type="password" placeholder="Leave blank to keep current password"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.password}">
                <p v-if="validationErrors.password" class="text-red-500 text-xs mt-1">{{ validationErrors.password[0] }}</p>
              </div>

              <!-- Profile Picture -->
              <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Profile Picture (Optional)</label>
                <input
                  ref="editProfilePictureInput"
                  type="file"
                  accept="image/jpeg,image/png,image/jpg,image/gif"
                  @change="handleEditProfilePictureChange"
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  :class="{'border-red-500': validationErrors.profile_picture}"
                />
                <p v-if="validationErrors.profile_picture" class="text-red-500 text-xs mt-1">{{ validationErrors.profile_picture[0] }}</p>
              </div>

            </div>

            <!-- Modal Footer Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeEditModal"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-5 py-2 rounded-lg">
                Cancel
              </button>
              <button type="submit" :disabled="submitting"
                class="bg-[#FFA600] hover:bg-[#FFB733] text-white font-semibold px-6 py-2 rounded-lg shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-save mr-2"></i>
                {{ submitting ? 'Updating...' : 'Update' }}
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
             <!-- Full Name -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Full Name</p>
              <p class="font-semibold">
                {{ viewedUser.first_name }} <span v-if="viewedUser.middle_initial">{{ viewedUser.middle_initial }}.</span> {{ viewedUser.last_name }}
              </p>
            </div>

            <!-- Email -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Email</p>
              <p class="font-semibold">{{ viewedUser.email || 'N/A' }}</p>
            </div>

            <!-- Role -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Role</p>
              <p class="font-semibold">{{ viewedUser.role || 'N/A' }}</p>
            </div>

            <!-- Status -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Status</p>
              <p class="font-semibold">{{ viewedUser.status || 'enabled' }}</p>
            </div>

            <!-- Plain Password -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Plain Password</p>
              <p class="font-semibold">{{ viewedUser.raw_password || 'N/A' }}</p>
            </div>

            <!-- Address -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Address</p>
              <p class="font-semibold">{{ viewedUser.address || 'N/A' }}</p>
            </div>

            <!-- Contact -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Contact</p>
              <p class="font-semibold">{{ viewedUser.contact || 'N/A' }}</p>
            </div>

            <!-- Gender -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Gender</p>
              <p class="font-semibold">{{ viewedUser.gender || 'N/A' }}</p>
            </div>

            <!-- Age -->
            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
              <p class="text-gray-500 text-xs">Age</p>
              <p class="font-semibold">{{ viewedUser.age || 'N/A' }}</p>
            </div>
          </div>

          <div class="mt-8 flex justify-end">
            <button
              @click="showViewModal = false"
              class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] px-6 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>    
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import Sidebar from './sidebar.vue'
import Swal from 'sweetalert2'

/** State */
const users = ref([])
const search = ref('')
const showEditModal = ref(false)

const newUser = ref({
  first_name: '',
  last_name: '',
  middle_initial: '',
  address: '',
  contact: '',
  gender: '',
  age: '',
  email: '',
  role: '',
  password: '',
  status: 'enabled'
})

const editedUser = ref({})
const viewedUser = ref({})

/** UI State */
const showViewModal = ref(false)
const showAddModal = ref(false)

/** Form/UX State */
const loading = ref(false)
const submitting = ref(false)
const validationErrors = ref({})
const profilePictureFile = ref(null)
const editProfilePictureFile = ref(null)

/** Computed */
const filteredUsers = computed(() => {
  if (!search.value.trim()) return users.value
  const q = search.value.trim().toLowerCase()
  return users.value.filter(u =>
    `${u.first_name} ${u.middle_initial || ''} ${u.last_name}`.toLowerCase().includes(q) ||
    (u.email || '').toLowerCase().includes(q)
  )
})

/** File handlers */
const handleProfilePictureChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    profilePictureFile.value = file
  }
}

const handleEditProfilePictureChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    editProfilePictureFile.value = file
  }
}

/** API Calls */
const fetchUsers = async () => {
  try {
    loading.value = true
    const { data } = await axios.get('/admins')
    users.value = Array.isArray(data) ? data : []
  } catch (err) {
    console.error(err)
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Failed to load users.',
      confirmButtonColor: '#FFA600'
    })
  } finally {
    loading.value = false
  }
}

const addUser = async () => {
  try {
    submitting.value = true
    validationErrors.value = {}

    const formData = new FormData()
    
    // Add form fields
    Object.keys(newUser.value).forEach(key => {
      if (newUser.value[key] !== '' && newUser.value[key] !== null) {
        formData.append(key, newUser.value[key])
      }
    })

    // Add profile picture
    if (profilePictureFile.value) {
      formData.append('profile_picture', profilePictureFile.value)
    }

    // Debug logging
    console.log('Sending request to:', '/admins')
    console.log('FormData contents:', Object.fromEntries(formData.entries()))

    const response = await axios.post('/admins', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json'
      }
    })

    console.log('Response received:', response)

    // Check for success
    if (response.status === 200 || response.status === 201) {
      const generatedPassword = response.data.generated_password
      
      Swal.fire({
        icon: 'success',
        title: 'Account Created!',
        html: generatedPassword 
          ? `<p>Account created successfully!</p><p><strong>Password:</strong> <code>${generatedPassword}</code></p>`
          : 'Account created successfully!',
        confirmButtonColor: '#FFA600'
      })

      closeAddModal()
      await fetchUsers()
    }

  } catch (error) {
    console.error('Error details:', {
      message: error.message,
      response: error.response,
      status: error.response?.status,
      data: error.response?.data
    })

    if (error.response?.status === 422) {
      validationErrors.value = error.response.data.errors || {}
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please check the form fields and try again.',
        confirmButtonColor: '#FFA600'
      })
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: error.response?.data?.message || 'Failed to create account.',
        confirmButtonColor: '#FFA600'
      })
    }
  } finally {
    submitting.value = false
  }
}

const updateUser = async () => {
  try {
    submitting.value = true
    validationErrors.value = {}

    // Create FormData for file upload
    const formData = new FormData()
    
    // Add all form fields
    Object.keys(editedUser.value).forEach(key => {
      if (key !== 'id' && editedUser.value[key] !== '' && editedUser.value[key] !== null) {
        formData.append(key, editedUser.value[key])
      }
    })

    // Add profile picture if selected
    if (editProfilePictureFile.value) {
      formData.append('profile_picture', editProfilePictureFile.value)
    }

    // Add _method for Laravel to handle PUT request with FormData
    formData.append('_method', 'PUT')

    await axios.post(`/admins/${editedUser.value.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: 'User updated successfully!',
      confirmButtonColor: '#FFA600'
    })

    await fetchUsers()
    closeEditModal()
  } catch (error) {
    console.error('Update failed:', error)

    if (error.response?.status === 422 && error.response.data?.errors) {
      validationErrors.value = error.response.data.errors
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please check the form fields and try again.',
        confirmButtonColor: '#FFA600'
      })
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: error.response?.data?.message || 'Failed to update user.',
        confirmButtonColor: '#FFA600'
      })
    }
  } finally {
    submitting.value = false
  }
}

const confirmToggleUserStatus = async (user, index) => {
  const current = user.status || 'enabled'
  const newStatus = current === 'enabled' ? 'disabled' : 'enabled'
  
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `Do you want to ${newStatus} this user?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#FFA600',
    cancelButtonColor: '#d33',
    confirmButtonText: `Yes, ${newStatus} it!`
  })

  if (result.isConfirmed) {
    await toggleUserStatus(user, index, newStatus)
  }
}

const toggleUserStatus = async (user, index, newStatus) => {
  try {
    await axios.put(`/admins/${user.id}/status`, { status: newStatus })
    users.value[index].status = newStatus
    
    Swal.fire({
      icon: 'success',
      title: 'Status Updated!',
      text: `User ${newStatus} successfully!`,
      confirmButtonColor: '#FFA600'
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Failed to update status. Please try again.',
      confirmButtonColor: '#FFA600'
    })
  }
}

const confirmDeleteUser = async (user) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!'
  })

  if (result.isConfirmed) {
    try {
      await axios.delete(`/admins/${user.id}`)
      await fetchUsers()
      
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'User has been deleted.',
        confirmButtonColor: '#FFA600'
      })
    } catch (error) {
      console.error(error)
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: error.response?.data?.message || 'Failed to delete user.',
        confirmButtonColor: '#FFA600'
      })
    }
  }
}

const resetPassword = async (user) => {
  const result = await Swal.fire({
    title: 'Reset Password?',
    text: `This will reset ${user.first_name}'s password to the default format.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#FFA600',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, reset it!'
  })

  if (result.isConfirmed) {
    try {
      const response = await axios.put(`/admins/${user.id}/reset-password`)
      
      Swal.fire({
        icon: 'success',
        title: 'Password Reset!',
        html: `<p>Password has been reset successfully.</p><p><strong>New Password:</strong> <code>${response.data.new_password}</code></p>`,
        confirmButtonColor: '#FFA600'
      })
    } catch (error) {
      console.error(error)
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Failed to reset password. Please try again.',
        confirmButtonColor: '#FFA600'
      })
    }
  }
}

const closeEditModal = () => {
  showEditModal.value = false
  editedUser.value = {}
  validationErrors.value = {}
  editProfilePictureFile.value = null
  if (editProfilePictureInput.value) {
    editProfilePictureInput.value.value = ''
  }
}

const viewUser = (user) => {
  viewedUser.value = { ...user }
  showViewModal.value = true
}

const openEditModal = (user) => {
  editedUser.value = {
    id: user.id || '',
    first_name: user.first_name || '',
    middle_initial: user.middle_initial || '',
    last_name: user.last_name || '',
    address: user.address || '',
    contact: user.contact || '',
    gender: user.gender || '',
    age: user.age || '',
    email: user.email || '',
    role: user.role || '',
    password: ''
  }
  showEditModal.value = true
}

const closeAddModal = () => {
  showAddModal.value = false
  validationErrors.value = {}
  profilePictureFile.value = null
  newUser.value = {
    first_name: '',
    last_name: '',
    middle_initial: '',
    address: '',
    contact: '',
    gender: '',
    age: '',
    email: '',
    role: '',
    password: '',
    status: 'enabled'
  }
  if (profilePictureInput.value) {
    profilePictureInput.value.value = ''
  }
}

/** Lifecycle */
onMounted(fetchUsers)
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

@keyframes fade-in {
  0% { opacity: 0; transform: translateY(-10px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>