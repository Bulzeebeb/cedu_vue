<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Swal from 'sweetalert2'

import Header from './staffHeader.vue'
import Footer from '../footer.vue'
import ClientFormModal from '@/pages/PayToPark/components/ClientFormModal.vue'
import EditClientModal from '@/pages/PayToPark/components/EditClientModal.vue'
import DailyPOSModal from '@/pages/PayToPark/components/DailyPOSModal.vue'
import BillingCheckoutModal from '@/pages/PayToPark/components/BillingCheckoutModal.vue'
import ClientInfoModal from '@/pages/PayToPark/components/ClientInfoModal.vue'

const { entries, clients, fetchError } = defineProps({
  entries: {
    type: Array,
    default: () => []
  },
  clients: {
    type: Array,
    default: () => []
  },
  fetchError: {
    type: String,
    default: ''
  }
})

// Dashboard Statistics
const totalVehiclesParkedToday = ref(0)
const vehiclesStillIn = ref(0)
const vehiclesOutToday = ref(0)
const totalSalesToday = ref(0)
const averageStayTime = ref(0)

// UI State
const showEditModal = ref(false)
const selectedClientId = ref(null)
const showDailyPOSModal = ref(false)
const showClientModal = ref(false)
const searchQuery = ref('')
const scannedClient = ref({})
const showBilling = ref(false)
const isQrScannerVisible = ref(false)
const sortBy = ref('time_in')
const sortOrder = ref('desc')
const isLoading = ref(false)

let qrScannerInstance = null

// Client Info Modal
const selectedClient = ref(null)
const showClientInfoModal = ref(false)

// Load dashboard statistics
onMounted(async () => {
  try {
    isLoading.value = true
    const { data } = await axios.get('/dashboard-stats')
    totalVehiclesParkedToday.value = data.totalVehiclesParkedToday ?? 0
    vehiclesStillIn.value = data.vehiclesStillIn ?? 0
    vehiclesOutToday.value = data.vehiclesOutToday ?? 0
    totalSalesToday.value = data.totalSalesToday ?? 0
    averageStayTime.value = data.averageStayTime ?? 0
  } catch (err) {
    console.error('Failed to load dashboard stats:', err)
  } finally {
    isLoading.value = false
  }
})

// Filter and sort clients
const filteredClients = computed(() => {
  if (!clients || !Array.isArray(clients)) return []
  
  let filtered = clients.filter(client => {
    const isStillParked = client.time_out === null || client.time_out === undefined
    const matchesSearch =
      !searchQuery.value ||
      (client.name && client.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (client.plate && client.plate.toLowerCase().includes(searchQuery.value.toLowerCase()))
    return isStillParked && matchesSearch
  })

  // Apply sorting
  filtered.sort((a, b) => {
    let aValue, bValue
    
    if (sortBy.value === 'time_in') {
      aValue = new Date(a.time_in) || 0
      bValue = new Date(b.time_in) || 0
    } else if (sortBy.value === 'name') {
      aValue = a.name || ''
      bValue = b.name || ''
    } else if (sortBy.value === 'duration') {
      aValue = calculateDuration(a.time_in)
      bValue = calculateDuration(b.time_in)
    }

    if (sortOrder.value === 'asc') {
      return aValue > bValue ? 1 : -1
    } else {
      return aValue < bValue ? 1 : -1
    }
  })

  return filtered
})

// Calculate parking duration
function calculateDuration(timeIn) {
  if (!timeIn) return 0
  const now = new Date()
  const inTime = new Date(timeIn)
  return Math.floor((now - inTime) / 60000) // in minutes
}

function formatDuration(minutes) {
  if (!minutes || minutes < 0) return '0m'
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  if (hours > 0) return `${hours}h ${mins}m`
  return `${mins}m`
}

// QR Scanner Functions
function loadHtml5QrCodeScript() {
  return new Promise((resolve, reject) => {
    if (window.Html5Qrcode) {
      resolve()
      return
    }
    const script = document.createElement('script')
    script.src = 'https://unpkg.com/html5-qrcode'
    script.onload = () => resolve()
    script.onerror = (e) => reject(e)
    document.head.appendChild(script)
  })
}

async function startQrScan() {
  try {
    await loadHtml5QrCodeScript()
    isQrScannerVisible.value = true

    setTimeout(() => {
      if (qrScannerInstance) {
        qrScannerInstance.clear().catch(() => {})
      }
      qrScannerInstance = new window.Html5Qrcode('qr-reader')
      qrScannerInstance
        .start(
          { facingMode: 'environment' },
          { fps: 10, qrbox: 250 },
          async (decodedText) => {
            await handleScannedQR(decodedText)
            stopQrScan()
          },
          () => {}
        )
        .catch(err => {
          console.error('Failed to start QR scanner:', err)
          isQrScannerVisible.value = false
          Swal.fire({
            title: 'Camera Error',
            text: 'Camera not available. Please check permissions.',
            icon: 'error',
            confirmButtonColor: '#7b1c1c'
          })
        })
    }, 200)
  } catch (error) {
    Swal.fire({
      title: 'Error',
      text: 'Failed to load QR scanner: ' + error.message,
      icon: 'error',
      confirmButtonColor: '#7b1c1c'
    })
  }
}

function stopQrScan() {
  if (qrScannerInstance) {
    qrScannerInstance
      .stop()
      .then(() => {
        try {
          qrScannerInstance.clear()
        } catch (e) {
          // ignore clear errors
        }
        qrScannerInstance = null
      })
      .catch(err => {
        console.error('Failed to stop QR scanner', err)
        qrScannerInstance = null
      })
  }
  isQrScannerVisible.value = false
}

async function handleScannedQR(decodedText) {
  try {
    const clientData = JSON.parse(decodedText)
    const response = await axios.post('/paytopark/scan', {
      id: clientData.id,
      name: clientData.name,
      plate: clientData.plate
    })

    if (response.data && response.data.success && response.data.client) {
      scannedClient.value = response.data.client
      showBilling.value = true
      await Swal.fire({
        title: 'QR Scan Success',
        text: `Client found: ${response.data.client.name}`,
        icon: 'success',
        confirmButtonColor: '#7b1c1c',
        timer: 1500
      })
    } else {
      console.error('Scan failed or client not found.', response.data)
      Swal.fire({
        title: 'Client Not Found',
        text: 'The scanned QR code is invalid or client not found.',
        icon: 'warning',
        confirmButtonColor: '#7b1c1c'
      })
    }
  } catch (error) {
    console.error('Failed to fetch scanned client data:', error)
    Swal.fire({
      title: 'Scan Error',
      text: 'Invalid QR or network error. Please try again.',
      icon: 'error',
      confirmButtonColor: '#7b1c1c'
    })
  }
}

function closeBilling() {
  showBilling.value = false
  scannedClient.value = {}
}

function openDailyPOSModal() {
  Swal.fire({
    title: 'Generate POS Report',
    text: 'Download the daily POS report?',
    icon: 'info',
    confirmButtonColor: '#7b1c1c',
    confirmButtonText: 'Download',
    cancelButtonColor: '#6b7280',
    showCancelButton: true
  }).then((result) => {
    if (result.isConfirmed) {
      showDailyPOSModal.value = true
    }
  })
}

async function checkoutClient(clientId) {
  try {
    const result = await Swal.fire({
      title: 'Checkout Client?',
      text: 'Are you sure you want to checkout this client? This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#7b1c1c',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, Checkout',
      cancelButtonText: 'Cancel',
      reverseButtons: true
    })

    if (result.isConfirmed) {
      const response = await axios.post(`/paytopark/checkout/${clientId}`)
      if (response.data.success) {
        await Swal.fire({
          title: 'Success!',
          text: 'Client checkout completed successfully.',
          icon: 'success',
          confirmButtonColor: '#7b1c1c',
          timer: 2000
        })
        router.reload()
      }
    }
  } catch (error) {
    console.error('Checkout failed:', error)
    await Swal.fire({
      title: 'Error!',
      text: error.response?.data?.message || 'Checkout failed. Please try again.',
      icon: 'error',
      confirmButtonColor: '#7b1c1c'
    })
  }
}

function openEditModal(clientId) {
  Swal.fire({
    title: 'Edit Client',
    text: 'You are about to edit this client information.',
    icon: 'info',
    confirmButtonColor: '#7b1c1c',
    confirmButtonText: 'Proceed'
  }).then((result) => {
    if (result.isConfirmed) {
      selectedClientId.value = clientId
      showEditModal.value = true
    }
  })
}

function more(client) {
  selectedClient.value = client
  showClientInfoModal.value = true
}

function parking_History() {
  Swal.fire({
    title: 'View Parking History',
    text: 'You will be redirected to the parking history page.',
    icon: 'info',
    confirmButtonColor: '#7b1c1c',
    confirmButtonText: 'Continue',
    cancelButtonColor: '#6b7280',
    showCancelButton: true
  }).then((result) => {
    if (result.isConfirmed) {
      router.visit('/parking_History')
    }
  })
}

function toggleSort(field) {
  if (sortBy.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = field
    sortOrder.value = 'desc'
  }
}

function openAddClientModal() {
  Swal.fire({
    title: 'Add New Client',
    text: 'You are about to add a new parking client.',
    icon: 'info',
    confirmButtonColor: '#7b1c1c',
    confirmButtonText: 'Proceed',
    cancelButtonColor: '#6b7280',
    showCancelButton: true
  }).then((result) => {
    if (result.isConfirmed) {
      showClientModal.value = true
    }
  })
}
</script>

<template>
  <div class="bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 min-h-screen font-sans">
    <Header />

    <main class="px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto">
      <!-- Welcome Section -->
      <div class="mb-8">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Welcome Back!</p>
        <h1 class="text-4xl font-bold text-[#7b1c1c] mt-2"><span class="font-bold">{{($page.props.admin?.first_name || 'Staff') + '!' }}</span></h1>
        <p class="text-gray-600 mt-1">Parking Dashboard Overview</p>
      </div>

      <!-- Stats Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <!-- Total Vehicles Today -->
        <div class="bg-gradient-to-br from-[#7b1c1c] to-[#650000] rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-300 text-sm font-medium">Total Vehicles Today</p>
              <p class="text-3xl font-bold mt-2">{{ totalVehiclesParkedToday }}</p>
            </div>
            <i class="fas fa-car-side text-4xl opacity-20"></i>
          </div>
        </div>

        <!-- Vehicles Still In -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-200 text-sm font-medium">Vehicles In Lot</p>
              <p class="text-3xl font-bold mt-2">{{ vehiclesStillIn }}</p>
            </div>
            <i class="fas fa-car text-4xl opacity-20"></i>
          </div>
        </div>

        <!-- Vehicles Out Today -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-green-200 text-sm font-medium">Checked Out Today</p>
              <p class="text-3xl font-bold mt-2">{{ vehiclesOutToday }}</p>
            </div>
            <i class="fas fa-door-open text-4xl opacity-20"></i>
          </div>
        </div>

        <!-- Daily Sales -->
        <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-amber-200 text-sm font-medium">Daily Sales</p>
              <p class="text-3xl font-bold mt-2">₱{{ totalSalesToday.toLocaleString() }}</p>
            </div>
            <i class="fas fa-coins text-4xl opacity-20"></i>
          </div>
        </div>

        <!-- Average Stay Time -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-purple-200 text-sm font-medium">Avg. Stay Time</p>
              <p class="text-3xl font-bold mt-2">{{ averageStayTime }}h</p>
            </div>
            <i class="fas fa-clock text-4xl opacity-20"></i>
          </div>
        </div>
      </div>

      <!-- Controls & Filters Section -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
          <!-- Search Input -->
          <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Clients</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by name or plate number..."
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#7b1c1c] focus:ring-2 focus:ring-[#7b1c1c] focus:ring-opacity-20 outline-none transition"
            />
          </div>

          <!-- Sort Dropdown -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
            <select
              v-model="sortBy"
              @change="sortOrder = 'desc'"
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#7b1c1c] focus:ring-2 focus:ring-[#7b1c1c] focus:ring-opacity-20 outline-none transition"
            >
              <option value="time_in">Check In Time</option>
              <option value="duration">Duration</option>
              <option value="name">Name</option>
            </select>
          </div>

          <!-- Sort Order -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
            <button
              @click="toggleSort(sortBy)"
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition flex items-center justify-center gap-2"
            >
              <i :class="sortOrder === 'asc' ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
              {{ sortOrder === 'asc' ? 'Ascending' : 'Descending' }}
            </button>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
          <button
            @click="startQrScan"
            class="inline-flex items-center gap-2 bg-[#7b1c1c] hover:bg-red-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg"
          >
            <i class="fas fa-qrcode"></i>
            Scan QR Code
          </button>
          <button
            @click="openAddClientModal"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg"
          >
            <i class="fas fa-user-plus"></i>
            Add New Client
          </button>
          <button
            @click="openDailyPOSModal"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg"
          >
            <i class="fas fa-file-pdf"></i>
            Download POS Report
          </button>
          <button
            @click="parking_History"
            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg"
          >
            <i class="fas fa-history"></i>
            Parking History
          </button>
        </div>
      </div>

      <!-- Clients Table Section -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-[#7b1c1c] to-[#650000] text-white">
                <th class="py-4 px-4 text-left font-semibold">#</th>
                <th class="py-4 px-4 text-left font-semibold">
                  <button @click="toggleSort('name')" class="hover:opacity-75 transition flex items-center gap-1">
                    Name
                    <i v-if="sortBy === 'name'" :class="sortOrder === 'asc' ? 'fas fa-arrow-up text-xs' : 'fas fa-arrow-down text-xs'"></i>
                  </button>
                </th>
                <th class="py-4 px-4 text-left font-semibold">Plate</th>
                <th class="py-4 px-4 text-left font-semibold">
                  <button @click="toggleSort('time_in')" class="hover:opacity-75 transition flex items-center gap-1">
                    Check In
                    <i v-if="sortBy === 'time_in'" :class="sortOrder === 'asc' ? 'fas fa-arrow-up text-xs' : 'fas fa-arrow-down text-xs'"></i>
                  </button>
                </th>
                <th class="py-4 px-4 text-left font-semibold">
                  <button @click="toggleSort('duration')" class="hover:opacity-75 transition flex items-center gap-1">
                    Duration
                    <i v-if="sortBy === 'duration'" :class="sortOrder === 'asc' ? 'fas fa-arrow-up text-xs' : 'fas fa-arrow-down text-xs'"></i>
                  </button>
                </th>
                <th class="py-4 px-4 text-left font-semibold">Status</th>
                <th class="py-4 px-4 text-left font-semibold">QR Code</th>
                <th class="py-4 px-4 text-left font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-if="fetchError" class="hover:bg-red-50">
                <td colspan="8" class="text-center text-red-600 py-8 font-medium">
                  <i class="fas fa-exclamation-circle mr-2"></i>Error: {{ fetchError }}
                </td>
              </tr>

              <tr v-else-if="filteredClients.length === 0" class="hover:bg-gray-50">
                <td colspan="8" class="text-center text-gray-500 py-8 font-medium">
                  <i class="fas fa-inbox mr-2"></i>No client records found.
                </td>
              </tr>

              <tr
                v-else
                v-for="(client, index) in filteredClients"
                :key="client.id || index"
                class="hover:bg-blue-50 transition-colors"
              >
                <td class="py-4 px-4 font-medium text-gray-700">{{ index + 1 }}</td>
                <td class="py-4 px-4 font-medium text-gray-900">{{ client.name }}</td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ client.plate }}
                  </span>
                </td>
                <td class="py-4 px-4 text-gray-600 text-xs">{{ new Date(client.time_in).toLocaleString() }}</td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ formatDuration(calculateDuration(client.time_in)) }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>Parked
                  </span>
                </td>
                <td class="py-4 px-4">
                  <img
                    v-if="client.qr_code"
                    :src="`/storage/${client.qr_code}`"
                    alt="QR Code"
                    class="w-14 h-14 object-contain rounded border border-gray-200 p-1"
                  />
                  <span v-else class="text-gray-400 text-xs">—</span>
                </td>
                <td class="py-4 px-4">
                  <div class="flex gap-2">
                    <button
                      @click="openEditModal(client.id)"
                      title="Edit"
                      class="inline-flex items-center justify-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    >
                      <i class="fas fa-edit"></i>
                    </button>

                    <button
                      @click="checkoutClient(client.id)"
                      title="Checkout"
                      class="inline-flex items-center justify-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    >
                      <i class="fas fa-door-open"></i>
                    </button>

                    <button
                      @click="more(client)"
                      title="More Info"
                      class="inline-flex items-center justify-center gap-1 bg-gray-400 hover:bg-gray-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    >
                      <i class="fas fa-info-circle"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Table Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between text-sm text-gray-600 border-t">
          <span>Showing {{ filteredClients.length }} active clients</span>
          <span>Total vehicles in lot: <strong>{{ vehiclesStillIn }}</strong></span>
        </div>
      </div>

      <!-- QR Scanner Overlay -->
      <transition name="fade">
        <div
          v-if="isQrScannerVisible"
          class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center bg-black bg-opacity-50"
        >
          <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-2xl w-[90vw] max-w-sm flex flex-col items-center relative">
            <button
              @click="stopQrScan"
              class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-2xl transition-colors"
              aria-label="Close"
            >
              &times;
            </button>

            <i class="fas fa-qrcode text-4xl text-[#7b1c1c] mb-4"></i>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Scan Client QR Code</h2>
            <p class="text-gray-600 text-sm text-center mb-6">Position the QR code within the frame to scan</p>

            <div id="qr-reader" class="w-full h-96 rounded-xl overflow-hidden shadow-lg mb-6 border-4 border-[#7b1c1c]"></div>

            <button
              @click="stopQrScan"
              class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors"
            >
              <i class="fas fa-times mr-2"></i>Close Scanner
            </button>
          </div>
        </div>
      </transition>
    </main>

    <!-- Modals -->
    <ClientFormModal v-model:show="showClientModal" />
    <EditClientModal v-model:show="showEditModal" :id="selectedClientId" />
    <DailyPOSModal :is-visible="showDailyPOSModal" @close="showDailyPOSModal = false" />
    <BillingCheckoutModal v-if="showBilling" :clientData="scannedClient" @close="closeBilling" />
    <ClientInfoModal v-if="showClientInfoModal" :client="selectedClient" @close="showClientInfoModal = false" />

    <Footer />
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

body {
  font-family: 'Inter', sans-serif;
}
</style>
