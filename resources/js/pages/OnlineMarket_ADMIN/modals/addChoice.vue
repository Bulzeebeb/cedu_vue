<template>
  <div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-2xl border border-gray-200">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-[#5F1213]">Choose Category to Add New Product</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl leading-none">&times;</button>
      </div>

      <!-- Category Buttons -->
      <div class="flex flex-col space-y-3">
        <button @click="selectCategory('Fruits')" class="px-4 py-3 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded-lg transition">
          Fruits
        </button>
        <button @click="selectCategory('Vegetables')" class="px-4 py-3 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded-lg transition">
          Vegetables
        </button>
        <button @click="selectCategory('Poultry')" class="px-4 py-3 bg-[#5F1213] hover:bg-[#7d1a1e] text-white rounded-lg transition">
          Poultry
        </button>
      </div>

      <!-- Cancel Button -->
      <div class="flex justify-end mt-4">
        <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded" @click="$emit('close')">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['close', 'select-category'])

const selectCategory = (category) => {
  emit('select-category', category)
  emit('close')
}
</script>
