<template>
  <div class="min-h-screen py-24 flex flex-col font-sans bg-gray-100">
    <!-- HEADER -->
    <SiteHeader />

    <!-- Main Content -->
    <main class="flex-1 p-6 pt-4 text-[#5F1213] flex flex-col items-center justify-center">
      <!-- Page Title -->
      <div class="bg-gray-100 w-full max-w-7xl rounded-xl p-6 mb-6 shadow border text-left">
        <h1 class="text-3xl font-semibold mb-1">Manage Accounts</h1>
        <p class="text-gray-500">Manage user accounts</p>
      </div>

      <!-- Alert -->
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded w-full max-w-6xl mb-6">
        {{ error }}
      </div>

      <!-- Loading Indicator -->
      <div v-if="loading"
        class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-2 rounded w-full max-w-6xl mb-6">
        Loading accounts...
      </div>

      <!-- Account Table -->
      <div class="bg-white w-full max-w-7xl rounded-xl p-10 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold text-left">Account List</h3>
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
            <tr v-for="(account, index) in accounts" :key="account.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2 border">
                <img :src="account.profile" class="w-10 h-10 rounded-full" alt="profile" />
              </td>
              <td class="px-4 py-2 border">{{ account.name }}</td>
              <td class="px-4 py-2 border">{{ account.email }}</td>
              <td class="px-4 py-2 border">{{ account.address }}</td>
              <td class="px-4 py-2 border">{{ account.contact }}</td>
              <td class="px-4 py-2 border">

                <button
                  :class="account.status === 'active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
                  class="text-white px-3 py-1 rounded" @click="toggleStatus(account.id)" :disabled="toggleLoading">
                  <span v-if="toggleLoading">Loading...</span>
                  <span v-else>{{ account.status === 'active' ? 'Disable' : 'Enable' }}</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>


    </main>

    <!-- FOOTER -->
    <Footer />
  </div>
</template>

<script setup>
import SiteHeader from './AdminChoiceHeader.vue'
import Footer from '../footer.vue'
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const accounts = ref([])
const loading = ref(false)
const error = ref(null)
const toggleLoading = ref(false)

const hasAccounts = computed(() => accounts.value.length > 0)

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

// Toggle account status (enable/disable)
const toggleStatus = async (id) => {
  const account = accounts.value.find(acc => acc.id === id)
  if (!account) return

  const isActive = account.status === 'active'
  const action = isActive ? 'disable' : 'enable'
  const confirmMessage = `Are you sure you want to ${action} this account?`

  const result = await Swal.fire({
    title: 'Confirm Action',
    text: confirmMessage,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: `Yes, ${action} it!`
  })

  if (result.isConfirmed) {
    toggleLoading.value = true
    try {
      // Assuming there's an API endpoint to update status, e.g., PUT /userclients/{id}/status
      await axios.put(`/userclients/${id}/status`, { status: isActive ? 'inactive' : 'active' }, { baseURL: window.location.origin })
      account.status = isActive ? 'inactive' : 'active'
      Swal.fire(
        'Success!',
        `Account has been ${action}d.`,
        'success'
      )
    } catch (err) {
      Swal.fire(
        'Error!',
        `Failed to ${action} account.`,
        'error'
      )
      console.error(`Error ${action}ing account:`, err)
    } finally {
      toggleLoading.value = false
    }
  }
}

onMounted(() => {
  fetchAccounts()
})
</script>
