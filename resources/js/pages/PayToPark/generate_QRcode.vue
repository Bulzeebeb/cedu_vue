<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
  >
    <div class="bg-white rounded-xl shadow-lg w-[500px] relative p-6">
      <!-- Header -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">GENERATED QR CODE</h2>
        <button
          @click="close"
          class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500"
        >
          &times;
        </button>
      </div>

      <!-- QR code only -->
      <div class="mt-4 flex flex-col items-center">
        <img
          :src="qrSrc"
          alt="QR code"
          class="w-40 h-40 border rounded"
        />
        <p class="text-xs text-gray-500 mt-2">Scan to verify billing info</p>
      </div>

      <!-- Button -->
      <button
        @click="downloadQRcode"
        class="mt-6 w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800"
      >
        Download QR code
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const isVisible = ref(true)
const close = () => (isVisible.value = false)
const downloadQRcode = () => console.log('Downloading QR...')

// Hardcoded billing info for QR encoding (not displayed)
const billingData = {
  'No.': '001',
  'Full Name': 'Shannen Ann C. Boliros',
  'Plate No.': '00NGH',
  'Date': 'June 24, 2025',
  'Time In': '8:23 am',
  'Time Out': '10:20 am',
  'Total Hours': '3 hrs',
  'Total Amount': '₱ 20',
  'Status': 'Complete'
}

const qrData = computed(() => encodeURIComponent(JSON.stringify(billingData)))
const qrSrc = computed(() => `https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=${qrData.value}`)
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
  transition: background-color 0.3s ease;
}
.bg-maroon:hover {
  background-color: #a30000;
}
</style>



<script>
export default { name: 'generate_QRcode' }
</script>


