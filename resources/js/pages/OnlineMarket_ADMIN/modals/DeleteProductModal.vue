<template>
  <div class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-sm p-6 rounded-lg shadow-2xl border border-gray-200">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-[#5F1213]">
          {{ isBulk ? 'Delete Selected Products' : 'Delete Product' }}
        </h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl leading-none">&times;</button>
      </div>

      <!-- Confirmation Text -->
      <div class="mb-6 text-[#5F1213]">
        <p v-if="isBulk">
          Are you sure you want to delete
          <strong>{{ selectedCount }}</strong>
          product<span v-if="selectedCount > 1">s</span>?
        </p>
        <p v-else>
          Are you sure you want to delete
          <strong>{{ product?.name }}</strong>?
        </p>
        <p class="text-sm text-gray-500 mt-1">
          This action cannot be undone.
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end space-x-2">
        <button
          type="button"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          @click="confirmDelete"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
        >
          {{ isBulk ? 'Delete All' : 'Delete' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  isBulk: Boolean,
  product: Object,
  selectedCount: Number
})

const emit = defineEmits(['close', 'delete', 'bulk-delete'])

function confirmDelete() {
  if (props.isBulk) {
    emit('bulk-delete')
  } else {
    emit('delete', props.product?.id)
  }
  emit('close')
}
</script>
