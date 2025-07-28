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
      <div class="pos-form border border-gray-400 rounded-lg p-4 sm:p-6 md:p-8 shadow-sm">

        <!-- Header -->
        <div class="text-center mb-4">
          <h1 class="text-sm">Republic of the Philippines</h1>
          <h2 class="text-lg font-bold">University of Southeastern Philippines</h2>
          <h3 class="text-sm italic">Resource Management Division (RMD)</h3>
          <h2 class="text-lg font-bold mt-2">ORDER PAYMENT SLIP (OPS/POS)</h2>
          <p class="text-sm text-right font-semibold mt-1">Control No.: ______</p>
        </div>

        <!-- Basic Info -->
        <div class="text-sm mb-6 space-y-2 border p-4">
          <p><span class="font-semibold">Payor/Name:</span> __________________________</p>
          <p><span class="font-semibold">Organization:</span> CEDU CROP PRODUCTION PROJECT</p>
          <p><span class="font-semibold">Date:</span> __________________________</p>
          <p>OTHER BUSINESS INCOME</p>
        </div>

        <!-- Product Table Generator -->
        <template v-for="(section, sectionIndex) in sections" :key="section.title">
          <h3 class="text-md font-semibold mb-2 mt-6">{{ section.title }}</h3>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-sm border border-gray-400 mb-6">
              <thead class="bg-gray-100 border-b border-gray-400">
                <tr>
                  <th class="px-2 py-1 text-center">Select</th>
                  <th class="px-2 py-1 text-left">Product</th>
                  <th class="px-2 py-1 text-center">QTY</th>
                  <th class="px-2 py-1 text-center">UNIT COST</th>
                  <th class="px-2 py-1 text-center">TOTAL COST</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in section.products"
                  :key="item.name + section.title"
                  class="border-b border-gray-300"
                >
                  <td class="px-2 py-1 text-center">
                    <input type="checkbox" v-model="item.selected" />
                  </td>
                  <td class="px-2 py-1">
                    {{ item.name }}
                    <template v-if="item.selected && item.name === 'Banana'">
                      <input
                        type="text"
                        v-model="item.bananaType"
                        placeholder="Type (e.g. Saba)"
                        class="ml-2 border-b border-gray-500 outline-none bg-transparent text-xs w-32 focus:border-black"
                      />
                    </template>
                    <template v-if="item.selected && item.name === 'Others:'">
                      <input
                        type="text"
                        v-model="item.otherDetails"
                        placeholder="Specify"
                        class="ml-2 border-b border-gray-500 outline-none bg-transparent text-xs w-32 focus:border-black"
                      />
                    </template>
                  </td>
                  <td class="px-2 py-1 text-center">
                    <input type="number" v-model="item.qty" class="w-full max-w-[80px] border px-1 py-0.5 text-center" />
                  </td>
                  <td class="px-2 py-1 text-center">
                    <input type="number" v-model="item.unitCost" class="w-full max-w-[100px] border px-1 py-0.5 text-center" />
                  </td>
                  <td class="px-2 py-1 text-center">
                    {{ (item.qty * item.unitCost).toFixed(2) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>

        <!-- Grand Total -->
        <div class="text-right font-semibold text-lg mt-4 mb-8">
          GRAND TOTAL COST: Php {{ grandTotal }}
        </div>

        <!-- Footer Signature -->
        <div class="text-sm mt-10 space-y-4">
          <p>Prepared By: _________________________</p>
          <p>Staff: _________________________</p>
          <p>Cashier: _________________________</p>
          <p>OR No.: _________________________</p>
        </div>
      </div>

      <!-- Buttons -->
      <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
        <button
          @click="downloadPOS"
          class="w-full sm:w-auto bg-maroon text-white py-2 px-6 rounded-full hover:bg-red-800 font-medium text-sm sm:text-base"
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

function generateProducts(names) {
  return names.map(name => ({
    name,
    qty: 0,
    unitCost: 0,
    selected: false,
    bananaType: name === 'Banana' ? '' : undefined,
    otherDetails: name === 'Others:' ? '' : undefined
  }))
}

const sections = reactive([
  {
    title: 'CEDU TAGUM – CROP PRODUCTION',
    products: generateProducts([
      'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
      'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra', 'Others:'
    ])
  },
  {
    title: 'CEDU MABINI – CROP PRODUCTION',
    products: generateProducts([
      'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
      'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo', 'Others:'
    ])
  }
])

const grandTotal = computed(() => {
  return sections.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + item.qty * item.unitCost, 0)
    )
  }, 0).toFixed(2)
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
input::placeholder {
  color: #999;
}
@media (max-width: 640px) {
  h2.text-xl {
    font-size: 1.125rem;
  }
  .text-sm {
    font-size: 0.875rem;
  }
  .text-lg {
    font-size: 1rem;
  }
}
</style>

<script>
export default {
  name: "onlineMart_pos"
}
</script>
