<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center">
      <div class="bg-white rounded-xl shadow-lg w-[500px] relative p-6">
        <!-- Header -->
        <div class="flex items-center gap-2 mb-4">
          <div class="w-1 h-6 bg-maroon rounded-sm"></div>
          <h2 class="text-xl font-semibold text-yellow-600">PAYMENT PROCEDURE</h2>
          <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">×</button>
        </div>

        <div class="space-y-3">
          <div class="row" v-for="(value, label) in billingInfo" :key="label">
            <label class="font-medium text-gray-700 w-1/2">{{ label }}</label>
            <input type="text" :value="value" disabled class="bg-gray-100 text-center px-2 py-1 rounded w-full text-sm text-gray-700" />
          </div>

          <!-- Amount Received -->
          <div class="row">
            <label class="font-medium text-gray-700 w-1/2">Amount Received:</label>
            <input type="number" v-model.number="amountReceived" class="bg-white border px-2 py-1 rounded w-full text-center text-sm text-gray-700" />
          </div>

          <!-- Change -->
          <div class="row">
            <label class="font-medium text-gray-700 w-1/2">Change:</label>
            <input type="text" :value="computedChange + ' pesos'" disabled class="bg-gray-100 text-center px-2 py-1 rounded w-full text-sm text-gray-700" />
          </div>
        </div>

        <!-- Confirm Payment -->
        <button
          @click="confirmPayment"
          :disabled="amountReceived < totalAmount"
          class="mt-6 w-full py-2 rounded-full font-medium transition-colors"
          :class="amountReceived >= totalAmount ? 'bg-maroon text-white hover:bg-red-800' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
          >
          Confirm Payment
        </button>

        
      </div>
    </div>
  </transition>
  <GeneratePOSModal
  v-if="showPOS && transaction && transaction.id"

  :transaction-id="transaction?.id"
  :client-id="props.client?.id"
  @close="showPOS = false"
/>



</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import GeneratePOSModal from './GeneratePOSModal.vue'

const props = defineProps({
  client: Object,
  billing: Object
})

const emit = defineEmits(['close'])

const isVisible = ref(true)
const showPOS = ref(false)
const transaction = ref(null)

const billingInfo = props.billing
const totalAmount = parseInt((billingInfo?.['Total Amount:'] || '').replace(/\D/g, '')) || 0
const amountReceived = ref(0)

const computedChange = computed(() => {
  const change = amountReceived.value - totalAmount
  return change >= 0 ? change : 0
})

const close = () => {
  isVisible.value = false
  emit('close')
}

const confirmPayment = async () => {
  try {
    const now = new Date()
    const formattedDate = now.toISOString().slice(0, 19).replace('T', ' ')

   const response = await axios.post('/paytopark/transactions', {
  client_id: props.client.id,
  total_payment: totalAmount,
  transaction_date: formattedDate
})

console.log('Transaction response:', response.data) // Add this

transaction.value = response.data // Adjust based on actual response shape

    showPOS.value = true
    isVisible.value = false
  } catch (error) {
    console.error('Failed to confirm payment:', error)
    alert('An error occurred while processing the payment.')
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
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
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
