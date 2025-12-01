<template>
  <div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-2xl border border-gray-200 max-h-[90vh] overflow-y-auto">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-[#5F1213]">Add New Poultry Product</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl leading-none">&times;</button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit">
        <!-- Image Upload -->
        <div class="mb-4">
          <label class="block text-sm text-gray-700 font-medium mb-1">Insert Image</label>
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
            placeholder="e.g. Native Chicken"
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

        <!-- Unit (auto-filled) -->
        <div class="mb-6">
          <label class="block text-sm text-gray-700 font-medium mb-1">Unit</label>
          <select
            v-model="form.unit"
            class="w-full border border-gray-300 rounded px-3 py-2 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#5F1213]"
          >
            <option value="/pc">/pc</option>
            <option value="/tray">/tray</option>
            <option value="/sack">/sack</option>
            <option value="/kilo">/kilo</option>
          </select>
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
            Add Product
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

// Form definition
const form = useForm({
  image: null,
  name: '',
  branch: '',
  price: null,
  stock: null,
  unit: '/pc', // default for poultry
  category: 'Poultry'
})

// Image upload handler
function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    form.image = file
  }
}

// Submit handler
function handleSubmit() {
  form.post('/admins/products', {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        title: 'Success!',
        text: 'Poultry product added successfully.',
        icon: 'success',
        confirmButtonColor: '#5F1213'
      })
      form.reset()
    },
    onError: () => {
      Swal.fire({
        title: 'Error',
        text: 'There was an error adding the poultry product.',
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    }
  })
}
</script>