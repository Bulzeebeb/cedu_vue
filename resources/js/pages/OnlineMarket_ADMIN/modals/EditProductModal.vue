<template>
  <div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-2xl border border-gray-200 max-h-[90vh] overflow-y-auto">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-[#5F1213]">Edit Product</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl leading-none">&times;</button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit">
        <!-- Image Upload -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Update Image</label>
          <input
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          />
        </div>

        <!-- Product Name -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Product Name</label>
          <input
            v-model="form.name"
            type="text"
            placeholder="e.g. Tomatoes"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          />
        </div>

        <!-- Branch -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Branch</label>
          <select
            v-model="form.branch"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          >
            <option disabled value="">Select Branch</option>
            <option value="Mabini">Mabini</option>
            <option value="Tagum">Tagum</option>
          </select>
        </div>

        <!-- Category -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Category</label>
          <select
            v-model="form.category"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          >
            <option disabled value="">Select Category</option>
            <option>Vegetables</option>
            <option>Fruits</option>
            <option>Poultry</option>
          </select>
        </div>

        <!-- Price -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Price</label>
          <input
            v-model.number="form.price"
            type="number"
            placeholder="e.g. 120"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          />
        </div>

        <!-- Stock -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Stock</label>
          <input
            v-model.number="form.stock"
            type="number"
            placeholder="e.g. 34"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          />
        </div>

        <!-- Status -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Status</label>
          <select
            v-model="form.status"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          >
            <option value="Available">Available</option>
            <option value="Out of Stock">Out of Stock</option>
          </select>
        </div>

        <!-- Unit -->
        <div class="mb-6">
          <label class="block text-sm text-gray-700 font-medium mb-1">Unit</label>
          <input
            type="text"
            v-model="form.unit"
            disabled
            class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-700 cursor-not-allowed"
          />
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-2">
          <button
            type="button"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded"
            @click="$emit('close')"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const form = reactive({
  image: null,
  name: '',
  branch: '',
  category: '',
  price: null,
  stock: null,
  status: 'Available',
  unit: ''
})

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    form.image = file
  }
}

watch([() => form.category, () => form.name], ([category, name]) => {
  const lowerName = name.toLowerCase()
  if (category === 'Vegetables' || category === 'Fruits') {
    form.unit = '/kilo'
  } else if (category === 'Poultry') {
    if (lowerName.includes('egg')) {
      form.unit = '/tray'
    } else if (lowerName.includes('dung')) {
      form.unit = '/sack'
    } else if (lowerName.includes('culled') || lowerName.includes('chicken')) {
      form.unit = '/pc'
    } else {
      form.unit = ''
    }
  } else {
    form.unit = ''
  }
})

function handleSubmit() {
  console.log('Updated Product:', form)
  // Perform API call or emit here
}
</script>
