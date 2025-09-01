<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#5F1213] text-white h-screen p-6 fixed top-0 left-0">
      <div class="mb-10">
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-500">iCentral</span></h1>
      </div>
      <nav class="space-y-4">
        <Link :href="route('admin.dashboard')" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          <i class="fas fa-home"></i> Dashboard
        </Link>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-arrows-rotate"></i> Update</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/update/paytopark" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Pay-to-Park</Link>
            <Link href="/admin/update/onlinemarket" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</Link>
            <Link href="/admin/update/useoffacilities" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</Link>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-chart-bar"></i> Reports</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link href="/admin/report/paytopark" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Pay-to-Park</Link>
            <Link href="/admin/report/onlinemarket" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Online Market</Link>
            <Link href="/admin/report/useoffacilities" class="block px-4 py-2 text-sm shadow rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Use-of-Facilities</Link>
          </div>
        </div>
        <div class="group">
          <a href="#" class="flex items-center justify-between gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200 w-full">
            <span class="flex items-center gap-3"><i class="fas fa-user-circle"></i> Account</span>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="hidden group-hover:block ml-6 mt-2 space-y-2">
            <Link :href="route('admin.profile')" class="block px-4 py-2 text-sm rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Profile</Link>
            <Link :href="route('admin.account')" class="block px-4 py-2 text-sm rounded-md bg-[#5F1213] text-white hover:bg-[#FFA600] hover:text-[#5F1213] transition">Manage Account</Link>
          </div>
        </div>
        <Link href="/admin/logs" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-white font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] transition">
          <i class="fas fa-file-alt"></i> Logs</Link>
       <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-[#5F1213] text-red-400 font-medium shadow hover:bg-red-600 hover:text-white transition"> <i class="fas fa-sign-out-alt"></i> Logout </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen p-10 text-[#5F1213]">
      <!-- Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold flex items-center gap-3">Manage Accounts</h1>
        <p class="text-gray-600 text-sm ml-1">Create, edit, and manage user accounts</p>
      </div>

      <!-- Accounts Table -->
      <div class="bg-white shadow-xl rounded-2xl border border-gray-300 p-6">
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
            <tr v-for="(user, index) in users" :key="index" class="border-b hover:bg-yellow-50">
              <td class="py-3 px-4">{{ user.firstName }} {{ user.middleInitial }} {{ user.lastName }}</td>
              <td class="py-3 px-4">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span class="px-2 py-1 rounded-full text-xs font-bold text-white"
                      :class="user.role === 'Admin' ? 'bg-green-600' : user.role === 'Staff' ? 'bg-blue-500' : 'bg-gray-500'">
                  {{ user.role }}
                </span>
              </td>
              <td class="py-3 px-4 space-x-3">
                <button @click="editUser(index)" class="text-blue-600 hover:text-blue-800 font-medium">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button @click="viewUser(index)" class="text-green-600 hover:text-green-800 font-medium">
                    <i class="fas fa-eye"></i> View
                </button>
                <button @click="deleteUser(index)" class="text-red-600 hover:text-red-800 font-medium">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add Account Modal -->
      <div v-if="showAddModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/30 w-full max-w-5xl animate-fade-in relative">
          <button @click="showAddModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
          <h2 class="text-2xl font-bold mb-6 text-[#5F1213] flex items-center gap-3">
            <i class="fas fa-user-plus text-[#FFA600] text-xl"></i> Add New Account
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label class="text-sm font-semibold">First Name</label><input v-model="newUser.firstName" placeholder="Juan" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div><label class="text-sm font-semibold">Middle Initial</label><input v-model="newUser.middleInitial" placeholder="P" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div><label class="text-sm font-semibold">Last Name</label><input v-model="newUser.lastName" placeholder="Dela Cruz" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div class="md:col-span-2"><label class="text-sm font-semibold">Address</label><input v-model="newUser.address" placeholder="Tagum City" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div><label class="text-sm font-semibold">Contact Number</label><input v-model="newUser.contact" placeholder="09123456789" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div>
            <label class="text-sm text-gray-700 font-semibold mb-1 block">Gender</label>
            <select v-model="newUser.gender"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md">
                <option value="" disabled>Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
            </div>
            <div><label class="text-sm font-semibold">Age</label><input v-model="newUser.age" placeholder="e.g., 25" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div><label class="text-sm font-semibold">Email</label><input v-model="newUser.email" placeholder="juan@example.com" class="w-full px-4 py-2 rounded-lg border bg-gray-100" /></div>
            <div>
            <label class="text-sm text-gray-700 font-semibold mb-1 block">Role</label>
            <select v-model="newUser.role"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FFA600] transition duration-200 hover:shadow-md">
                <option value="" disabled>Select Role</option>
                <option>Admin</option>
                <option>Staff</option>
            </select>
            </div>
          </div>
          <div class="mt-8 flex justify-end">
            <button @click="addUserAndClose"
                    class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] font-bold px-6 py-2 rounded-lg shadow-md hover:shadow-lg">
              <i class="fas fa-plus mr-2"></i> Add Account
            </button>
          </div>
          <p v-if="addError" class="text-red-500 text-sm mt-3 font-semibold">{{ addError }}</p>
        </div>
      </div>

      <!-- Edit Modal -->
      <div v-if="isEditing" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-[400px] animate-fade-in">
          <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-user-edit text-[#FFA600]"></i> Edit Account
          </h2>
          <div class="space-y-3">
            <input v-model="editedUser.firstName" placeholder="First Name" class="w-full px-4 py-2 rounded-lg border bg-gray-100" />
            <input v-model="editedUser.lastName" placeholder="Last Name" class="w-full px-4 py-2 rounded-lg border bg-gray-100" />
            <input v-model="editedUser.email" placeholder="Email" class="w-full px-4 py-2 rounded-lg border bg-gray-100" />
            <input v-model="editedUser.role" placeholder="Role" class="w-full px-4 py-2 rounded-lg border bg-gray-100" />
          </div>
          <div class="flex justify-end gap-3 mt-4">
            <button @click="updateUser" class="bg-[#FFA600] text-[#5F1213] px-4 py-2 rounded hover:bg-yellow-400 font-semibold">Save</button>
            <button @click="cancelEdit" class="text-gray-600 hover:text-red-600">Cancel</button>
          </div>
        </div>
      </div>

      <!-- View Modal -->
      <!-- View Modal -->
<div v-if="showViewModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-white p-8 rounded-2xl shadow-2xl border border-[#FFA600]/40 w-full max-w-xl animate-fade-in relative">

    <!-- Close Button -->
    <button @click="showViewModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>

    <!-- Header -->
    <div class="mb-6 flex items-center gap-3 border-b pb-4">
      <div class="bg-[#FFA600]/20 text-[#FFA600] rounded-full p-3">
        <i class="fas fa-user text-2xl"></i>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-[#5F1213] leading-tight">Account Overview</h2>
        <p class="text-sm text-gray-500">{{ viewedUser.role }}</p>
      </div>
    </div>

    <!-- Detail Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm text-gray-800">
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Full Name</p>
        <p class="font-semibold">{{ viewedUser.firstName }} {{ viewedUser.middleInitial }} {{ viewedUser.lastName }}</p>
      </div>
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Email</p>
        <p class="font-semibold">{{ viewedUser.email }}</p>
      </div>
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Address</p>
        <p class="font-semibold">{{ viewedUser.address }}</p>
      </div>
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Contact</p>
        <p class="font-semibold">{{ viewedUser.contact }}</p>
      </div>
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Gender</p>
        <p class="font-semibold">{{ viewedUser.gender }}</p>
      </div>
      <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
        <p class="text-gray-500 text-xs">Age</p>
        <p class="font-semibold">{{ viewedUser.age }}</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 flex justify-end">
      <button @click="showViewModal = false"
              class="bg-gradient-to-r from-[#FFA600] to-[#FFB733] text-[#5F1213] px-6 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition">
        Close
      </button>
    </div>
  </div>
</div>

    </main>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const users = ref([
  {
    firstName: 'Juan', middleInitial: 'P', lastName: 'Dela Cruz',
    email: 'juan@example.com', role: 'Admin', address: 'Tagum City',
    contact: '09123456789', gender: 'Male', age: 25
  }
])

const newUser = ref({
  firstName: '', lastName: '', middleInitial: '', address: '',
  contact: '', gender: '', age: '', email: '', role: ''
})

const editedUser = ref({})
const viewedUser = ref({})
const showViewModal = ref(false)
const isEditing = ref(false)
const showAddModal = ref(false)
const addError = ref('')
let editingIndex = null

const addUser = () => {
  if (!newUser.value.firstName || !newUser.value.lastName || !newUser.value.email || !newUser.value.role) {
    addError.value = 'First Name, Last Name, Email, and Role are required.'
    return
  }
  users.value.push({ ...newUser.value })
  newUser.value = {
    firstName: '', lastName: '', middleInitial: '', address: '',
    contact: '', gender: '', age: '', email: '', role: ''
  }
  addError.value = ''
}

const addUserAndClose = () => {
  addUser()
  if (!addError.value) showAddModal.value = false
}

const deleteUser = (index) => {
  if (confirm('Are you sure you want to delete this account?')) {
    users.value.splice(index, 1)
  }
}

const editUser = (index) => {
  editedUser.value = { ...users.value[index] }
  editingIndex = index
  isEditing.value = true
}

const updateUser = () => {
  if (editingIndex !== null) {
    users.value[editingIndex] = { ...editedUser.value }
    isEditing.value = false
  }
}

const cancelEdit = () => {
  isEditing.value = false
}

const viewUser = (index) => {
  viewedUser.value = { ...users.value[index] }
  showViewModal.value = true
}
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