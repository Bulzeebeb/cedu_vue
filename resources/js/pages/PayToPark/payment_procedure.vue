

<template>
  <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-[500px] relative p-6">
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">PAYMENT PROCEDURE</h2>
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

        <!-- Total Amount -->
        <div class="row">
          <label class="font-medium text-gray-700 w-1/2">Total Amount:</label>
          <input
            type="text"
            :value="totalAmount + ' pesos'"
            disabled
            class="bg-gray-100 text-center px-2 py-1 rounded w-full text-sm text-gray-700"
          />
        </div>

        <!-- Amount Received -->
        <div class="row">
          <label class="font-medium text-gray-700 w-1/2">Amount Received:</label>
          <input
            type="number"
            v-model.number="amountReceived"
            class="bg-white border px-2 py-1 rounded w-full text-center text-sm text-gray-700"
          />
        </div>

        <!-- Change -->
        <div class="row">
          <label class="font-medium text-gray-700 w-1/2">Change:</label>
          <input
            type="text"
            :value="computedChange + ' pesos'"
            disabled
            class="bg-gray-100 text-center px-2 py-1 rounded w-full text-sm text-gray-700"
          />
        </div>
      </div>

       <!-- Confirm Payment -->
      <a
        :href="amountReceived >= totalAmount ? '/generate_pos' : '#'"
        class="mt-6 w-full py-2 text-center rounded-full font-medium transition-colors block"
        :class="amountReceived >= totalAmount ? 'bg-maroon text-white hover:bg-red-800' : 'bg-gray-300 text-gray-500 pointer-events-none cursor-not-allowed'"
      >
        Confirm Payment
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const isVisible = ref(true)

const close = () => {
  isVisible.value = false
  console.log('Modal closed')
}


const totalAmount = 20
const amountReceived = ref(0)

const computedChange = computed(() => {
  return amountReceived.value >= totalAmount ? amountReceived.value - totalAmount : 0
})
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
  name: "payment_procedure"
}
</script>