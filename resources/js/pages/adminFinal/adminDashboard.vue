<template>
  <div class="min-h-screen flex font-sans">
    <Sidebar />
    <!-- Main content -->
    <main class="ml-64 flex-1 bg-gray-100 min-h-screen p-6 text-[#5F1213]">
      <!-- Top bar -->

      <!-- Stat cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg">
          <h2 class="text-sm font-medium uppercase">Total No. of Vehicles</h2>
          <p class="text-3xl font-bold mt-2">1,294</p>
          <p class="text-sm text-green-600 mt-1">+5.2% since yesterday</p>
        </div>
        <div class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg">
          <h2 class="text-sm font-medium uppercase">Sales for Today</h2>
          <p class="text-3xl font-bold mt-2">₱18,540</p>
          <p class="text-sm text-green-600 mt-1">↑ +8.7% compared to same time yesterday</p>
        </div>
        <div class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg">
          <h2 class="text-sm font-medium uppercase">Facilities Booked</h2>
          <p class="text-3xl font-bold mt-2">47</p>
          <p class="text-sm text-blue-600 mt-1">↑ +12 bookings from last week</p>
        </div>
      </div>

      <!-- Graph and Orders -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white text-[#5F1213] rounded-xl p-6 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Sales Value</h3>
            <div class="flex items-center gap-3">
              <button class="px-4 py-2 bg-[#5F1213] text-white rounded-xl font-semibold shadow-md hover:bg-[#4a0e0f] transition">Month</button>
              <button class="px-4 py-2 bg-[#FFA600] text-[#5F1213] rounded-xl font-semibold shadow-md hover:bg-yellow-400 transition">Week</button>
              <button @click="printSalesChart" class="ml-2 p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-[#5F1213] transition" title="Print Sales Chart">
                <i class="fas fa-print"></i>
              </button>
            </div>
          </div>
          <div class="h-56">
            <canvas id="salesChart" class="w-full h-full"></canvas>
          </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white text-[#5F1213] rounded-xl p-6 shadow-lg">
          <h3 class="text-lg font-semibold mb-4">Total Orders</h3>
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 text-left">
                <th class="pb-2">Product</th>
                <th class="pb-2">Price</th>
                <th class="pb-2">Status</th>
                <th class="pb-2">Buyer</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-gray-100">
                <td class="py-2">Banana Chips</td>
                <td>₱120</td>
                <td><span class="text-green-600 font-medium">Paid</span></td>
                <td>Juan Dela Cruz</td>
              </tr>
              <tr class="border-b border-gray-100">
                <td class="py-2">T-Shirt</td>
                <td>₱250</td>
                <td><span class="text-yellow-600 font-medium">Pending</span></td>
                <td>Maria Santos</td>
              </tr>
              <tr class="border-b border-gray-100">
                <td class="py-2">Eco Bag</td>
                <td>₱80</td>
                <td><span class="text-green-600 font-medium">Paid</span></td>
                <td>Carlos Reyes</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Title
} from 'chart.js'

// Register Chart.js components
Chart.register(
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Title
)

// Orders data
const orders = ref([
  { product: 'Banana Chips', name: 'Juan Dela Cruz', price: 150, status: 'Paid' },
  { product: 'T-Shirt', name: 'Maria Santos', price: 299, status: 'Unpaid' },
  { product: 'Eco Bag', name: 'Pedro Reyes', price: 85, status: 'Paid' },
  { product: 'Keychain', name: 'Liza Dizon', price: 50, status: 'Paid' }
])

// Notification state
const showNotif = ref(false)

function toggleNotif(event) {
  event.stopPropagation()
  showNotif.value = !showNotif.value
}

// Close notification when clicking outside
onMounted(() => {
  window.addEventListener('click', () => {
    showNotif.value = false
  })

  // Initialize Chart
  const salesCtx = document.getElementById('salesChart')?.getContext('2d')
  if (salesCtx) {
    new Chart(salesCtx, {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Sales',
          data: [5000, 7000, 3000, 6000, 10000, 9500, 8000],
          backgroundColor: '#FFA600'
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    })
  }
})

// Print sales chart
function printSalesChart() {
  const chartCanvas = document.getElementById('salesChart')
  const printWindow = window.open('', '', 'width=800,height=600')
  printWindow.document.write('<html><head><title>Print Sales Chart</title></head><body>')
  printWindow.document.write('<h2>Sales Value Chart</h2>')
  printWindow.document.write(chartCanvas.outerHTML)
  printWindow.document.write('</body></html>')
  printWindow.document.close()
  printWindow.focus()
  setTimeout(() => {
    printWindow.print()
    printWindow.close()
  }, 500)
}

import { Link } from '@inertiajs/vue3'
import Sidebar from './sidebar.vue'

</script>


<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/chart.js');
body {
  font-family: 'Inter', sans-serif;
}

</style>
