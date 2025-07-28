<template>
  <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white text-black rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4">

      <!-- Header Title and Close -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-yellow-600">GENERATED POS</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">
          &times;
        </button>
      </div>

      <!-- POS FORM CONTENT START -->
      <div class="pos-form border border-gray-400 rounded-lg p-4 sm:p-6 shadow-sm">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center sm:items-stretch mb-6 gap-4">
          <div class="w-full sm:w-1/5 flex items-center justify-center">
            <img src="/logo.png" alt="Logo" class="h-full max-h-[120px] object-contain" />
          </div>
          <div class="w-full sm:w-4/5 flex flex-col justify-center text-center px-4">
            <h1 class="text-base">Republic of the Philippines</h1>
            <h2 class="text-xl font-bold">University of Southeastern Philippines</h2>
            <h3 class="text-sm italic">Resource Management Division (RMD)</h3><br>
            <h2 class="text-2xl font-bold tracking-wide mt-1">ORDER OF PAYMENT</h2>
          </div>
        </div>

        <!-- Basic Info (with border) -->
        <div class="border border-gray-300 rounded-md p-4 text-sm mb-6 space-y-2 shadow-sm">
          <div class="flex justify-between">
            <div></div>
            <div><span class="font-semibold">Control No.:</span> 001</div>
          </div>
          <div class="flex justify-between">
            <div><span class="font-semibold">Name:</span> Shannen Ann C. Boliros</div>
          </div>
          <div><span class="font-semibold">Organization:</span> BSIT</div>
          <div class="flex justify-between">
            <div><span class="font-semibold">Date Applied:</span> July 8, 2025</div>
          </div>
          <div><span class="font-semibold">Validity Period:</span> July 8–10, 2025</div>
        </div>

        <!-- Facility to Use -->
        <h3 class="text-lg font-semibold mb-2">Facility to Use</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm border border-gray-300 mb-6">
            <thead>
              <tr class="bg-gray-200">
                <th class="text-left p-2 border-b border-gray-300">Select</th>
                <th class="text-left p-2 border-b border-gray-300">Facility</th>
                <th class="text-right p-2 border-b border-gray-300">Cost (Php)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(cost, name) in facilities" :key="name" class="even:bg-gray-50">
                <td class="p-2 border-b border-gray-200">
                  <input type="checkbox" v-model="selectedFacilities" :value="name" />
                </td>
                <td class="p-2 border-b border-gray-200">
                  <template v-if="name === 'Other (Specify)'">
                    <input
                      v-if="selectedFacilities.includes(name)"
                      type="text"
                      v-model="otherFacilityName"
                      placeholder="Specify other facility"
                      class="border px-2 py-1 rounded w-full"
                    />
                    <span v-else>{{ name }}</span>
                  </template>
                  <template v-else>{{ name }}</template>
                </td>
                <td class="p-2 text-right border-b border-gray-200">
                  <input
                    type="number"
                    v-model.number="facilities[name]"
                    class="border px-2 py-1 rounded text-right w-24"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Additional Payments -->
        <h3 class="text-lg font-semibold mb-2">Additional Payment For:</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm border border-gray-300 mb-6">
            <thead>
              <tr class="bg-gray-200">
                <th class="text-left p-2 border-b border-gray-300">Select</th>
                <th class="text-left p-2 border-b border-gray-300">Payment</th>
                <th class="text-right p-2 border-b border-gray-300">Cost (Php)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(cost, name) in additionalPayments" :key="name" class="even:bg-gray-50">
                <td class="p-2 border-b border-gray-200">
                  <input type="checkbox" v-model="selectedAdditionalPayments" :value="name" />
                </td>
                <td class="p-2 border-b border-gray-200">
                  <template v-if="name === 'Other (Specify)'">
                    <input
                      v-if="selectedAdditionalPayments.includes(name)"
                      type="text"
                      v-model="otherPaymentName"
                      placeholder="Specify other payment"
                      class="border px-2 py-1 rounded w-full"
                    />
                    <span v-else>{{ name }}</span>
                  </template>
                  <template v-else>{{ name }}</template>
                </td>
                <td class="p-2 text-right border-b border-gray-200">
                  <input
                    type="number"
                    v-model.number="additionalPayments[name]"
                    class="border px-2 py-1 rounded text-right w-24"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Total Cost -->
        <div class="text-right font-semibold text-xl mt-4 mb-8 border-t pt-3">
          Total Cost: Php {{ totalCost.toFixed(2) }}
        </div>

        <!-- Signatures -->
        <div class="flex flex-col sm:flex-row justify-between mt-8 text-sm gap-8">
          <div class="text-center">
            <span class="block mb-6">By: Staff</span>
            <div class="border-t border-gray-400 w-40 mx-auto"></div>
          </div>
          <div class="text-center">
            <span class="block mb-6">Noted by: CEDU Head / RMD Director</span>
            <div class="border-t border-gray-400 w-60 mx-auto"></div>
          </div>
        </div>
      </div>
      <!-- POS FORM CONTENT END -->

      <!-- Buttons -->
      <div class="mt-10 flex justify-center gap-4">
        <button
          @click="downloadPOS"
          class="w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800 font-medium"
        >
          Download POS
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'

const isVisible = ref(true)
const close = () => (isVisible.value = false)
const downloadPOS = () => console.log('Downloading POS...')

const facilities = reactive({
  'Billboard Posting': 0,
  'Classrooms': 0,
  'Commercial Stall': 0,
  'Consultancy': 0,
  'Covered Court': 0,
  'Farmer\'s Training Center': 0,
  'Fitness Center': 0,
  'Gymnasium': 0,
  'Hostel Dining Hall': 0,
  'Hostel Rooms': 0,
  'Hostel Training Hall': 0,
  'Open Space/Ground': 0,
  'Printing Press': 0,
  'Social Hall': 0,
  'Other (Specify)': 0
})

const additionalPayments = reactive({
  'Excess Hour/s': 0,
  'Overtime Pay of Staff': 0,
  'Use of Generator': 0,
  'Other (Specify)': 0
})

const otherFacilityName = ref('')
const otherPaymentName = ref('')
const selectedFacilities = ref([])
const selectedAdditionalPayments = ref([])

const totalCost = computed(() => {
  const facilityTotal = selectedFacilities.value.reduce((sum, key) => sum + facilities[key], 0)
  const additionalTotal = selectedAdditionalPayments.value.reduce((sum, key) => sum + additionalPayments[key], 0)
  return facilityTotal + additionalTotal
})
</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
}
.bg-maroon:hover {
  background-color: #a30000;
}
div {
  color: black;
}
</style>

<script>
export default {
  name: "generate_pos"
}
</script>
