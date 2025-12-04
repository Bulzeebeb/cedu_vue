<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-6 pt-4 text-[#5F1213] flex flex-col items-center">
      <!-- Page Title -->
      <div class="bg-gray-100 w-full max-w-6xl rounded-xl p-6 mb-6 shadow border text-left">
        <h1 class="text-3xl font-semibold mb-1">Manage Accounts</h1>
        <p class="text-gray-500">Manage user accounts</p>
      </div>

      <!-- Alert -->
      <div
        v-if="!hasAccounts"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded w-full max-w-6xl mb-6"
      >
        Failed to load users.
      </div>

      <!-- Account Table -->
      <div class="bg-white w-full max-w-6xl rounded-xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold text-left">Account List</h3>
          <button
            class="bg-[#5F1213] text-white px-4 py-2 rounded hover:bg-red-900"
            @click="openAddModal"
          >
            + Add Staff
          </button>
        </div>

        <table class="min-w-full border border-gray-200 text-left">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="px-4 py-2 border">Profile</th>
              <th class="px-4 py-2 border">Name</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Address</th>
              <th class="px-4 py-2 border">Contact Number</th>
              <th class="px-4 py-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="accounts.length === 0" class="text-center">
              <td colspan="6" class="py-4 text-gray-500">No accounts found.</td>
            </tr>
            <tr
              v-for="(account, index) in accounts"
              :key="index"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-4 py-2 border">
                <img
                  :src="account.profile"
                  class="w-10 h-10 rounded-full"
                  alt="profile"
                />
              </td>
              <td class="px-4 py-2 border">{{ account.name }}</td>
              <td class="px-4 py-2 border">{{ account.email }}</td>
              <td class="px-4 py-2 border">{{ account.address }}</td>
              <td class="px-4 py-2 border">{{ account.contact }}</td>
              <td class="px-4 py-2 border">
                <button
                  class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mr-2"
                  @click="openEditModal(index)"
                >
                  Edit
                </button>
                <button
                  :class="
                    account.status === 'active'
                      ? 'bg-red-500 hover:bg-red-600'
                      : 'bg-green-500 hover:bg-green-600'
                  "
                  class="text-white px-3 py-1 rounded"
                  @click="toggleStatus(index)"
                >
                  {{ account.status === 'active' ? 'Disable' : 'Enable' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add Modal -->
      <div
        v-if="showAddModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
      >
        <div class="bg-white rounded-xl w-1/3 p-6">
          <h2 class="text-2xl font-semibold mb-4">Add Staff Account</h2>
          <form @submit.prevent="addAccount">
            <label class="block mb-2">Name</label>
            <input
              v-model="newAccount.name"
              class="w-full border px-3 py-2 rounded mb-4"
              required
            />

            <label class="block mb-2">Email</label>
            <input
              v-model="newAccount.email"
              class="w-full border px-3 py-2 rounded mb-4"
              required
            />

            <label class="block mb-2">Address</label>
            <input
              v-model="newAccount.address"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <label class="block mb-2">Contact</label>
            <input
              v-model="newAccount.contact"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <label class="block mb-2">Profile Picture</label>
            <input
              type="file"
              accept="image/*"
              @change="onFileChange"
              class="w-full border px-3 py-2 rounded mb-4"
            />
            <div v-if="newAccount.profile" class="mb-4">
              <img :src="newAccount.profile" class="w-16 h-16 rounded-full" />
            </div>

            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closeAddModal"
                class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 rounded bg-[#5F1213] text-white hover:bg-red-900"
              >
                Add
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Modal -->
      <div
        v-if="showEditModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
      >
        <div class="bg-white rounded-xl w-1/3 p-6">
          <h2 class="text-2xl font-semibold mb-4">Edit Account</h2>
          <form @submit.prevent="updateAccount">
            <label class="block mb-2">Name</label>
            <input
              v-model="editAccount.name"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <label class="block mb-2">Email</label>
            <input
              v-model="editAccount.email"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <label class="block mb-2">Address</label>
            <input
              v-model="editAccount.address"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <label class="block mb-2">Contact</label>
            <input
              v-model="editAccount.contact"
              class="w-full border px-3 py-2 rounded mb-4"
            />

            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 rounded bg-yellow-500 text-white hover:bg-yellow-600"
              >
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import AdminSidebar from './adminSidebar.vue'
import { ref, computed } from 'vue'

// Dummy account data
const accounts = ref([
  {
    profile: 'https://i.pravatar.cc/100?img=1',
    name: 'John Doe',
    email: 'john@example.com',
    address: '123 Main St',
    contact: '09123456789',
    status: 'active'
  },
  {
    profile: 'https://i.pravatar.cc/100?img=2',
    name: 'Jane Smith',
    email: 'jane@example.com',
    address: '456 Oak Ave',
    contact: '09987654321',
    status: 'active'
  },
  {
    profile: 'https://i.pravatar.cc/100?img=3',
    name: 'Mark Wilson',
    email: 'mark@example.com',
    address: '789 Pine Rd',
    contact: '09223344556',
    status: 'disabled'
  }
])

const hasAccounts = computed(() => accounts.value.length > 0)

// -------------------- ADD MODAL --------------------
const showAddModal = ref(false)
const newAccount = ref({
  profile: '',
  name: '',
  email: '',
  address: '',
  contact: '',
  status: 'active'
})

const openAddModal = () => {
  newAccount.value = {
    profile: '',
    name: '',
    email: '',
    address: '',
    contact: '',
    status: 'active'
  }
  showAddModal.value = true
}

const closeAddModal = () => {
  showAddModal.value = false
}

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (event) => {
      newAccount.value.profile = event.target.result
    }
    reader.readAsDataURL(file)
  }
}

const addAccount = () => {
  accounts.value.push({ ...newAccount.value })
  closeAddModal()
}

// -------------------- EDIT MODAL --------------------
const showEditModal = ref(false)
const editAccount = ref({})
let editIndex = ref(null)

const openEditModal = (index) => {
  editIndex.value = index
  editAccount.value = { ...accounts.value[index] }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
}

// Update account
const updateAccount = () => {
  if (editIndex.value !== null) {
    accounts.value[editIndex.value] = { ...editAccount.value }
  }
  closeEditModal()
}

// -------------------- TOGGLE STATUS --------------------
const toggleStatus = (index) => {
  accounts.value[index].status =
    accounts.value[index].status === 'active' ? 'disabled' : 'active'
}
</script>
