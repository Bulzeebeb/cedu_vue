<script setup>
import { computed } from 'vue'

const props = defineProps({
  client: Object
})
const emit = defineEmits(['close'])

// Directly load stored QR code from Laravel's public storage
const qrSrc = computed(() =>
  props.client?.qr_code
    ? `/storage/${props.client.qr_code}`
    : ''
)

const downloadQRcode = async () => {
  try {
    const response = await fetch(qrSrc.value)
    const blob = await response.blob()
    const objectUrl = URL.createObjectURL(blob)

    const link = document.createElement('a')
    link.href = objectUrl
    link.download = 'billing_qrcode.png'
    document.body.appendChild(link)
    link.click()

    document.body.removeChild(link)
    URL.revokeObjectURL(objectUrl)
  } catch (err) {
    console.error('Failed to download QR code:', err)
  }
}
</script>

<template>
  <div class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-[500px] max-h-[70vh] overflow-y-auto relative p-6">
      <!-- Close button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 text-2xl font-bold text-gray-600 hover:text-red-500"
      >
        &times;
      </button>

      <!-- Title -->
      <div class="flex items-center gap-2 mb-6">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-2xl font-semibold text-yellow-600">
          VIEW CLIENT DETAILS
        </h2>
      </div>

      <!-- Client Info -->
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">No. :</label>
          <input type="text" :value="client.id" disabled class="w-full px-3 py-2 border rounded-md bg-gray-50" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name:</label>
          <input type="text" :value="client.name" disabled class="w-full px-3 py-2 border rounded-md bg-gray-50" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Plate No.:</label>
          <input type="text" :value="client.plate" disabled class="w-full px-3 py-2 border rounded-md bg-gray-50" />
        </div>
      </div>

      <hr class="my-6 border-gray-300" />

      <!-- QR Code -->
      <div class="p-3 rounded-lg shadow-md bg-white">
        <div class="flex justify-center items-center mb-3">
          <h2 class="text-sm font-semibold text-yellow-600">Generated QR Code</h2>
        </div>
        <div class="flex flex-col items-center space-y-3" v-if="qrSrc">
          <img :src="qrSrc" alt="QR code" class="w-40 h-40 border border-gray-300 rounded-md shadow" />
          <p class="text-xs text-gray-500 text-center">Scan to verify billing info</p>
          <button
            @click="downloadQRcode"
            class="mt-2 px-4 py-2 bg-maroon text-white rounded-full hover:bg-red-800 text-sm shadow-sm"
          >
            Download QR code
          </button>
        </div>
      </div>

      <!-- View POS button -->
      <a href="/generate_pos" class="block text-center mt-6 w-full bg-maroon text-white py-3 rounded-full hover:bg-red-800 text-base">
        View POS
      </a>
    </div>
  </div>
</template>
