<template>
  <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ value }}</p>
        <p class="text-xs text-gray-600 mt-2">{{ subtitle }}</p>
      </div>
      <div :class="`p-4 bg-${colorMap}-100 rounded-xl`">
        <i :class="[icon, `text-3xl text-${colorMap}-600`]"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  icon: String,
  color: {
    type: String,
    default: 'blue'
  }
})

const colorMap = computed(() => {
  const colors = {
    green: 'green',
    blue: 'blue',
    purple: 'purple',
    orange: 'orange',
    red: 'red'
  }
  return colors[props.color] || 'blue'
})
</script>
