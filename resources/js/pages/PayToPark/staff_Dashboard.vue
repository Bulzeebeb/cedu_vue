<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

import Header from '@/pages/Header.vue'
import Footer from '@/pages/footer.vue'
import ClientFormModal from '@/pages/PayToPark/components/ClientFormModal.vue'
import EditClientModal from '@/pages/PayToPark/components/EditClientModal.vue'
import DailyPOSModal from '@/pages/PayToPark/components/DailyPOSModal.vue'
import BillingCheckoutModal from '@/pages/PayToPark/components/BillingCheckoutModal.vue'
import ClientInfoModal from '@/pages/PayToPark/components/ClientInfoModal.vue' // <-- make sure path is correct

// Destructure props so template can directly reference `clients`, `fetchError`, etc.
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

const totalVehiclesParkedToday = ref(0)
const vehiclesStillIn = ref(0)
const vehiclesOutToday = ref(0)
const totalSalesToday = ref(0)

onMounted(async () => {
  try {
    const { data } = await axios.get('/dashboard-stats')
    totalVehiclesParkedToday.value = data.totalVehiclesParkedToday ?? 0
    vehiclesStillIn.value = data.vehiclesStillIn ?? 0
    vehiclesOutToday.value = data.vehiclesOutToday ?? 0
    totalSalesToday.value = data.totalSalesToday ?? 0
  } catch (err) {
    console.error('Failed to load dashboard stats:', err)
  }
})

const showEditModal = ref(false)
const selectedClientId = ref(null)
const showDailyPOSModal = ref(false)
const showClientModal = ref(false)
const searchQuery = ref('')
const scannedClient = ref({})
const showBilling = ref(false)

const isQrScannerVisible = ref(false)
let qrScannerInstance = null

// For Client Info
const selectedClient = ref(null)
const showClientInfoModal = ref(false)

const filteredClients = computed(() => {
  // clients is destructured from props above
  if (!clients || !Array.isArray(clients)) return []
  return clients.filter(client => {
    const isStillParked = client.time_out === null || client.time_out === undefined
    const matchesSearch =
      !searchQuery.value ||
      (client.name && client.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (client.plate && client.plate.toLowerCase().includes(searchQuery.value.toLowerCase()))
    return isStillParked && matchesSearch
  })
})

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

    // small timeout to ensure DOM has updated
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
          (errorMessage) => {
            // optional: console.debug(errorMessage)
          }
        )
        .catch(err => {
          console.error('Failed to start QR scanner:', err)
          isQrScannerVisible.value = false
        })
    }, 200)
  } catch (error) {
    alert('Failed to load QR scanner: ' + error)
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
    } else {
      console.error('Scan failed or client not found.', response.data)
      alert('Client not found or scan invalid.')
    }
  } catch (error) {
    console.error('Failed to fetch scanned client data:', error)
    alert('Invalid QR or network error.')
  }
}

function closeBilling() {
  showBilling.value = false
  scannedClient.value = {}
}

function openDailyPOSModal() {
  showDailyPOSModal.value = true
}

function openEditModal(clientId) {
  selectedClientId.value = clientId
  showEditModal.value = true
}

// Updated "More" function to open modal instead of routing
function more(client) {
  selectedClient.value = client
  showClientInfoModal.value = true
}

function parking_History() {
  router.visit('/parking_History')
}
</script>

<template>
  <div class="bg-gray-50 text-gray-900 min-h-screen font-sans">
    <Header />

    <main class="px-4 sm:px-6 py-6 max-w-7xl mx-auto">
      <!-- Greeting -->
      <div class="mb-6">
        <p class="text-sm text-gray-500">WELCOME BACK!</p>
        <h1 class="text-3xl font-bold text-[#7b1c1c]">SHANNEN ANN</h1>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6 mb-6">
        <div class="bg-[#650000] rounded-lg shadow-md text-center py-5 px-3">
          <div class="text-yellow-400 text-3xl mb-1">
            <i class="fas fa-car-side"></i>
          </div>
          <div class="text-white text-2xl font-bold">{{ totalVehiclesParkedToday }}</div>
          <div class="text-yellow-400 mt-0.5 text-xs">Total Vehicles Parked Today</div>
        </div>

        <div class="bg-[#650000] rounded-lg shadow-md text-center py-5 px-3">
          <div class="text-yellow-400 text-3xl mb-1">
            <i class="fas fa-car-on"></i>
          </div>
          <div class="text-white text-2xl font-bold">{{ vehiclesStillIn }}</div>
          <div class="text-yellow-400 mt-0.5 text-xs">Vehicles Still In</div>
        </div>

        <div class="bg-[#650000] rounded-lg shadow-md text-center py-5 px-3">
          <div class="text-yellow-400 text-3xl mb-1">
            <i class="fas fa-door-open"></i>
          </div>
          <div class="text-white text-2xl font-bold">{{ vehiclesOutToday }}</div>
          <div class="text-yellow-400 mt-0.5 text-xs">Vehicles Out Today</div>
        </div>

        <div class="bg-[#650000] rounded-lg shadow-md text-center py-5 px-3">
          <div class="text-yellow-400 text-3xl mb-1">
            <i class="fas fa-coins"></i>
          </div>
          <div class="text-white text-2xl font-bold">₱{{ totalSalesToday.toLocaleString() }}</div>
          <div class="text-yellow-400 mt-0.5 text-xs">Daily Sales</div>
        </div>
      </div>

      <!-- Controls Section -->
      <div class="bg-white p-4 rounded-xl shadow-md mt-6">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-4">
          <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by name or plate"
              class="px-3 py-2 rounded border border-gray-300 text-sm w-full sm:w-auto"
            />

            <!-- Dropdown Filter -->
            <button
              @click="openDailyPOSModal"
              class="bg-[#7b1c1c] text-white px-4 py-2 rounded hover:bg-red-900 text-sm flex items-center gap-2"
            >
              <i class="fas fa-file"></i>
              Download POS
            </button>
          </div>

          <!-- Right Controls -->
          <div class="flex flex-wrap gap-2 w-full lg:w-auto justify-end">
            <button
              @click="parking_History"
              class="bg-white border border-gray-300 text-[#7b1c1c] px-4 py-2 rounded shadow hover:bg-gray-100 text-sm"
            >
              Parking History
            </button>
            <button
              @click="showClientModal = true"
              class="bg-[#7b1c1c] text-white px-4 py-2 rounded hover:bg-red-900 text-sm"
            >
              + ADD NEW CLIENT
            </button>
            <!-- Scan Button -->
            <button
              @click="startQrScan"
              class="bg-[#7b1c1c] text-white px-4 py-2 rounded hover:bg-red-900 text-sm flex items-center gap-2"
            >
              <i class="fas fa-qrcode"></i>
              Scan QR Code
            </button>

            <!-- Fullscreen Camera Overlay -->
            <div
              v-if="isQrScannerVisible"
              class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center"
            >
              <!-- Centered Scanner Card -->
              <div class="bg-gray-900 rounded-2xl p-4 sm:p-6 shadow-2xl w-[90vw] max-w-md flex flex-col items-center relative">
                <!-- Title and Close -->
                <div class="w-full flex justify-between items-center mb-2">
                  <h2 class="text-white font-bold text-lg sm:text-xl text-center flex-1">Scan Client QR Code</h2>
                  <button
                    @click="stopQrScan"
                    class="text-gray-400 hover:text-red-500 text-2xl ml-2 absolute right-4 top-4"
                    aria-label="Close"
                  >
                    &times;
                  </button>
                </div>

                <p class="text-gray-400 text-sm mb-4 text-center">Align the QR code inside the frame</p>

                <!-- QR Scanner Container -->
                <div id="qr-reader" class="w-full h-[450px] rounded-xl overflow-hidden shadow-lg"></div>

                <!-- Stop Scan Button -->
                <button
                  @click="stopQrScan"
                  class="mt-6 w-full px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 text-base sm:text-lg"
                >
                  Stop Scanning
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Clients Table -->
        <div class="overflow-x-auto">
          <table class="w-full table-auto text-sm text-left">
            <thead>
              <tr class="bg-[#7b1c1c] text-white">
                <th class="py-2 px-3">#</th>
                <th class="py-2 px-3">Name</th>
                <th class="py-2 px-3">Plate</th>
                <th class="py-2 px-3">Time In</th>
                <th class="py-2 px-3">Time Out</th>
                <th class="py-2 px-3">Status</th>
                <th class="py-2 px-3">QR Code</th>
                <th class="py-2 px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="fetchError">
                <td colspan="8" class="text-center text-red-600 py-4">
                  Error: {{ fetchError }}
                </td>
              </tr>

              <tr v-else-if="filteredClients && filteredClients.length === 0">
                <td colspan="8" class="text-center text-gray-500 py-4">
                  No client records found.
                </td>
              </tr>

              <tr
                v-else
                v-for="(client, index) in filteredClients"
                :key="client.id || index"
                class="border-t hover:bg-gray-50 transition"
              >
                <td class="py-2 px-3">{{ index + 1 }}</td>
                <td class="py-2 px-3">{{ client.name }}</td>
                <td class="py-2 px-3">{{ client.plate }}</td>
                <td class="py-2 px-3">{{ client.time_in }}</td>
                <td class="py-2 px-3">{{ client.time_out || '—' }}</td>
                <td class="py-2 px-3">{{ client.status }}</td>
                <td class="px-6 py-4">
                  <img
                    v-if="client.qr_code"
                    :src="`/storage/${client.qr_code}`"
                    alt="QR Code"
                    class="w-16 h-16 object-contain"
                  />
                </td>
                <td class="py-2 px-3">
                  <div class="flex gap-2">
                    <button
                      @click="openEditModal(client.id)"
                      title="Edit"
                      class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
                    >
                      <i class="fas fa-edit"></i>
                    </button>

                    <button
                      @click="more(client)"
                      title="More"
                      class="bg-gray-300 text-gray-800 px-2 py-1 rounded hover:bg-gray-400"
                    >
                      <i class="fas fa-ellipsis-v"></i>
                    </button>

                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <ClientFormModal v-model:show="showClientModal" />
      <EditClientModal v-model:show="showEditModal" :id="selectedClientId" />
      <DailyPOSModal
        :is-visible="showDailyPOSModal"
        @close="showDailyPOSModal = false"
      />

      <BillingCheckoutModal
        v-if="showBilling"
        :clientData="scannedClient"
        @close="closeBilling"
      />
      <ClientInfoModal
        v-if="showClientInfoModal"
        :client="selectedClient"
        @close="showClientInfoModal = false"
      />
    </main>

    <Footer />
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
body {
  font-family: 'Inter', sans-serif;
}
</style>
