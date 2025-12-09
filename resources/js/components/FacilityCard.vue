<template>
  <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-4">
      <div :class="`p-3 bg-${colorMap}-100 rounded-lg`">
        <i :class="[icon, `text-xl text-${colorMap}-600`]"></i>
      </div>
      <span class="text-xs font-bold text-green-600 bg-green-100 px-3 py-1 rounded-full">
        {{ active }} in use
      </span>
    </div>
    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">{{ title }}</h3>
    <p class="text-2xl font-bold text-gray-900 mt-2">{{ count }}</p>
    <div v-if="occupancy" class="mt-3 flex items-center gap-2">
      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
        <div :class="`h-full bg-${colorMap}-500 transition-all`" :style="{ width: `${occupancy}%` }"></div>
      </div>
      <span class="text-xs font-bold text-gray-900">{{ occupancy }}%</span>
    </div>
    <p class="text-xs text-gray-600 mt-3">{{ subtitle }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  title: String,
  count: [String, Number],
  active: [String, Number],
  occupancy: Number,
  icon: String,
  subtitle: String,
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
  return colors.color || 'blue'
})
</script>
