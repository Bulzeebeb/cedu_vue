<template>
  <div v-if="isVisible" class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-lg w-[500px] relative p-6">
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">TIMEOUT BILLING</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">
          &times;
        </button>
      </div>

      <div class="space-y-3" v-if="Object.keys(billingInfo).length > 0">
        <div class="row" v-for="(value, label) in billingInfo" :key="label">
          <label class="font-medium text-gray-700 w-1/2">{{ label }}</label>
          <input
            type="text"
            :value="value"
            disabled
            class="bg-gray-100 text-center px-2 py-1 rounded w-full text-sm text-gray-700"
          />
        </div>
      </div>
      <div v-else class="text-center text-gray-500 mt-6">Loading billing details...</div>

      <button
        @click="proceedToPayment"
        class="block text-center mt-6 w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800"
      >
        Proceed to Payment
      </button>

      <PaymentProcedure
        v-if="showPayment"
        :client="client"
        :billing="billingInfo"
        @close="showPayment = false"
      />

      <GeneratePOSModal
        v-if="showPOS"
        :client="posData.client"
        :transaction="posData.transaction"
        @close="showPOS = false"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import PaymentProcedure from '@/pages/PayToPark/components/PaymentProcedureModal.vue'
import GeneratePOSModal from '@/pages/PayToPark/components/GeneratePOSModal.vue'

const props = defineProps({
  clientId: Number,   // optional
  clientData: Object, // optional
})

const client = ref({})
const billingInfo = ref({})
const isVisible = ref(true)
const showPayment = ref(false)
const showPOS = ref(false)
const posData = ref({ client: null, transaction: null })  // initialize to avoid undefined error

const close = () => {
  isVisible.value = false
  showPayment.value = false
  showPOS.value = false
}

function proceedToPayment() {
  showPayment.value = true
}

function roundUpHours(ms) {
  const totalMins = Math.ceil(ms / (1000 * 60))
  return Math.ceil(totalMins / 60)
}

function computeBilling(timeIn, timeOut) {
  const msDiff = new Date(timeOut) - new Date(timeIn)
  const totalHours = roundUpHours(msDiff)
  const extraHours = Math.max(totalHours - 3, 0)
  const totalAmount = 20 + (extraHours * 5)
  return { totalHours, totalAmount }
}

function formatTime(timeStr) {
  const date = new Date(timeStr)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })
}

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

async function fetchClientAndBilling() {
  try {
    let data = props.clientData
    if (!data && props.clientId) {
      const res = await axios.get(`/clients/${props.clientId}`)
      data = res.data
    }

    if (data) {
      client.value = data

      const now = new Date()
      const billing = computeBilling(data.time_in, now)

      billingInfo.value = {
        'No. :': data.id,
        'Full Name:': data.name,
        'Plate No.:': data.plate,
        'Date:': formatDate(now),
        'Time in:': formatTime(data.time_in),
        'Time out:': formatTime(now),
        'Total Number of hours:': `${billing.totalHours} hr(s)`,
        'Total Amount:': `${billing.totalAmount} pesos`,
        'Status:': 'Complete'
      }
    }
  } catch (error) {
    console.error('Failed to fetch billing info:', error)
  }
}

// Watch clientId or clientData prop changes and fetch billing accordingly
watch(
  () => [props.clientId, props.clientData],
  () => {
    fetchClientAndBilling()
  },
  { immediate: true }
)
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
  transition: background-color 0.3s ease;
}
.bg-maroon:hover {
  background-color: #a30000;
}
.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.5rem;
}
</style>
