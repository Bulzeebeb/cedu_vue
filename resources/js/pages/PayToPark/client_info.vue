<template>
  <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-[500px] relative p-6">
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">VIEW CLIENT DETAILS</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">
          &times;
        </button>
      </div>

      <div class="space-y-3">
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
           <a
        href="/generate_pos"
        class="block text-center mt-6 w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800"
      >
        View POS
      </a>
      
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isVisible = ref(true)
const close = () => (isVisible.value = false)

const billingInfo = {
  'No. :': '001',
  'Full Name:': 'Shannen Ann C. Boliros',
  'Plate No.:': '00NGH',
  'Date:': 'June 24, 2025 - June 24, 2025',
  'Time in:': '8:23 am',
  'Time out:': '10:20 am',
  'Total Number of hours:': '3hrs',
  'Total Amount:': '20 pesos',
  'Status:': 'Complete'
}
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


<script>
export default {
  name: "client_info"
}
</script>