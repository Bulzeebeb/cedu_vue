<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import BillingCheckout from '@/pages/PayToPark/components/BillingCheckoutModal.vue'

const props = defineProps({
  show: Boolean,
  id: Number
})
const emit = defineEmits(['update:show'])

const client = ref({
  name: '',
  plate: '',
  time_in: '',
  time_out: '',
  status: '',
  qr_code: '',
  firstName: '',
  lastName: '',
  time: '',
  date: ''
})

const showBilling = ref(false)

// Fetch client info when ID changes
watch(() => props.id, async (newId) => {
  if (newId) {
    try {
      const response = await axios.get(`/clients/${newId}`)
      const data = response.data
      const [firstName, ...rest] = (data.name || '').split(' ')
      client.value = {
        ...data,
        firstName: firstName || '',
        lastName: rest.join(' ') || '',
        date: data.time_in?.split('T')[0] || '',
        time: data.time_in?.split('T')[1]?.substring(0, 5) || ''
      }
    } catch (error) {
      console.error('Failed to fetch client data:', error)
    }
  }
})

function closeModal() {
  emit('update:show', false)
}

function openBillingModal() {
  showBilling.value = true
}

async function saveClient() {
  try {
    await axios.put(`/paytopark/clients/${props.id}`, {
      name: `${client.value.firstName} ${client.value.lastName}`.trim(),
      plate: client.value.plate,
    })
  

    closeModal()
  } catch (error) {
    console.error('Failed to save client:', error)
  }
}
</script>

<template>
 

  <div v-if="show" class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-2xl relative">
      <button class="absolute top-3 right-4 text-gray-500 hover:text-black" @click="closeModal">✕</button>
      <h2 class="text-xl font-bold text-maroon mb-4">Edit Client</h2>

      <form @submit.prevent="openBillingModal" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">First Name</label>
            <input v-model="client.firstName" type="text" class="w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium">Last Name</label>
            <input v-model="client.lastName" type="text" class="w-full border rounded px-3 py-2" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium">Plate Number</label>
          <input v-model="client.plate" type="text" class="w-full border rounded px-3 py-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Date</label>
            <input v-model="client.date" type="date" class="w-full border rounded px-3 py-2 bg-gray-100" readonly />
          </div>
          <div>
            <label class="block text-sm font-medium">Time</label>
            <input v-model="client.time" type="time" class="w-full border rounded px-3 py-2 bg-gray-100" readonly />
          </div>
        </div>

        <div class="flex justify-between pt-4">
          <button type="submit" class="bg-maroon text-white px-4 py-2 rounded hover:bg-red-800">Timeout</button>
          <button type="button" @click="saveClient" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Fixed Billing Checkout Modal with proper prop -->
  <BillingCheckout v-if="showBilling" :clientId="id" @close="showBilling = false" />
   
</template>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
</style>
