<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold text-green-700 mb-6">VEGETABLES</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Laboratory Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Laboratory 1 Machine Technology</h2>
          <div class="space-y-2">
            <div v-for="(item, index) in labData" :key="index" class="flex items-center">
              <div class="w-24 text-gray-600">{{ item.label }}</div>
              <div class="flex-1">
                <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                  <div 
                    class="h-full bg-green-500" 
                    :style="{ width: item.value + '%' }"
                  ></div>
                </div>
              </div>
              <div class="w-16 text-right text-gray-700">{{ item.value }}%</div>
            </div>
          </div>
        </div>
        
        <!-- Conference Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Conference 2 Trends</h2>
          <div class="space-y-2">
            <div v-for="(item, index) in conferenceData" :key="index" class="flex items-center">
              <div class="w-24 text-gray-600">{{ item.label }}</div>
              <div class="flex-1">
                <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                  <div 
                    class="h-full bg-blue-500" 
                    :style="{ width: item.value + '%' }"
                  ></div>
                </div>
              </div>
              <div class="w-16 text-right text-gray-700">{{ item.value }}%</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Full Data Table -->
      <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Complete Data</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Index</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(value, index) in allValues" :key="index">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ value }}%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      labData: [
        { label: 'Tomatoes', value: 85.0 },
        { label: 'Carrots', value: 72.0 },
        { label: 'Broccoli', value: 63.0 },
        { label: 'Spinach', value: 91.0 },
      ],
      conferenceData: [
        { label: 'Potatoes', value: 78.0 },
        { label: 'Onions', value: 65.0 },
        { label: 'Peppers', value: 82.0 },
        { label: 'Cucumbers', value: 59.0 },
      ],
      allValues: Array.from({ length: 756 }, (_, i) => (5.0 + i * 0.1).toFixed(1))
    }
  }
}
</script>

<style>
/* Additional styles if needed */
</style>