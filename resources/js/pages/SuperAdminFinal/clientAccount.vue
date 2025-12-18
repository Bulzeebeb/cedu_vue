<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen p-10 text-[#5F1213]">
      <!-- Title -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold flex items-center gap-3">Manage Client Accounts</h1>
        <p class="text-gray-600 text-sm ml-1">View and manage client user accounts</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5F1213]"></div>
      </div>

      <!-- Error Alert -->
      <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
        <p class="font-semibold">Error</p>
        <p>{{ error }}</p>
      </div>

      <!-- Accounts Table -->
      <div v-else class="bg-white shadow-xl rounded-2xl border border-gray-300 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
          <h2 class="text-xl font-semibold flex items-center gap-2">
            <i class="fas fa-list text-[#FFA600]"></i> Client Account List
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
              <th class="py-3 px-4 text-left">Status</th>
              <th class="py-3 px-4 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(account, index) in accounts" :key="account.id" class="border-b hover:bg-yellow-50">
              <td class="py-3 px-4">
                <img :src="account.profile" class="w-10 h-10 rounded-full object-cover" alt="profile" />
              </td>
              <td class="py-3 px-4">{{ account.name }}</td>
              <td class="py-3 px-4">{{ account.email }}</td>
              <td class="py-3 px-4">{{ account.address }}</td>
              <td class="py-3 px-4">{{ account.contact }}</td>
              <td class="py-3 px-4">
                <span
                  class="px-2 py-1 rounded-full text-xs font-bold"
                  :class="account.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'"
                >
                  {{ account.status === 'active' ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-3 px-4 space-x-3">
                <button
                  @click="confirmToggleStatus(account, index)"
                  :class="account.status === 'active' ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800'"
                  class="font-medium"
                >
                  <i :class="account.status === 'active' ? 'fas fa-user-slash' : 'fas fa-user-check'"></i>
                  {{ account.status === 'active' ? 'Disable' : 'Enable' }}
                </button>
              </td>
            </tr>
            <tr v-if="accounts.length === 0">
              <td colspan="7" class="py-8 text-center text-gray-500">No accounts found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from './sidebar.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const accounts = ref([])
const loading = ref(false)
const error = ref(null)
const toggleLoading = ref(false)

// Fetch accounts from API
const fetchAccounts = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get('/userclients', { baseURL: window.location.origin })
    accounts.value = response.data.map(client => ({
      id: client.id,
      profile: client.image_url || '/images/Client/default_profile.png',
      name: `${client.firstName} ${client.middleName ? client.middleName + ' ' : ''}${client.lastName}${client.extension ? ' ' + client.extension : ''}`.trim(),
      email: client.email,
      address: client.address,
      contact: client.contactNum,
      status: client.status || 'active'
    }))
  } catch (err) {
    error.value = 'Failed to load accounts.'
    console.error('Error fetching accounts:', err)
  } finally {
    loading.value = false
  }
}

// Confirm before toggling status
const confirmToggleStatus = async (account, index) => {
  const isActive = account.status === 'active'
  const newStatus = isActive ? 'inactive' : 'active'
  
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `Do you want to ${newStatus} this account?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#FFA600',
    cancelButtonColor: '#d33',
    confirmButtonText: `Yes, ${newStatus} it!`
  })

  if (result.isConfirmed) {
    await toggleStatus(account, index, newStatus)
  }
}

// Toggle account status (enable/disable)
const toggleStatus = async (account, index, newStatus) => {
  toggleLoading.value = true
  try {
    await axios.put(`/userclients/${account.id}/status`, { status: newStatus }, { baseURL: window.location.origin })
    accounts.value[index].status = newStatus
    Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: `Account ${newStatus} successfully!`,
      confirmButtonColor: '#FFA600'
    })
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Failed to update account status.',
      confirmButtonColor: '#FFA600'
    })
    console.error('Error toggling status:', err)
  } finally {
    toggleLoading.value = false
  }
}

onMounted(() => {
  fetchAccounts()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
