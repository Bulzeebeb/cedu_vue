<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

// Sidebar toggle
const sidebarOpen = ref(false)
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

// Navigation
const admin_Dashboard = () => router.visit('/admin_Dashboard')
const admin_ManageParking = () => router.visit('/admin_ManageParking')
const admin_Account = () => router.visit('/admin_Account')
const admin_Reports = () => router.visit('/admin_parking_reports')

// Pagination, search, data
const currentPage = ref(1)
const rowsPerPage = 10
const searchQuery = ref('')
const logs = ref(Array.from({ length: 25 }, (_, i) => ({
  name: `Sample User ${i + 1}`,
  plate: `XYZ-${1000 + i}`,
  in: '2025-07-17 08:00 AM',
  out: '2025-07-17 10:00 AM',
  hours: '2',
  amount: `₱${(20 + (i % 5) * 10).toFixed(2)}`,
  qr: 'https://via.placeholder.com/100x100.png?text=QR'
})))

const filteredLogs = computed(() => {
  return logs.value.filter(log =>
    log.plate.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage
  return filteredLogs.value.slice(start, start + rowsPerPage)
})

const totalSales = computed(() => {
  return filteredLogs.value.reduce((total, log) => {
    const num = parseFloat(log.amount.replace(/[^\d.]/g, ''))
    return total + num
  }, 0)
})

const totalPages = computed(() =>
  Math.ceil(filteredLogs.value.length / rowsPerPage)
)

const visiblePages = computed(() => {
  const range = []
  const maxPages = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxPages / 2))
  let end = Math.min(totalPages.value, start + maxPages - 1)
  if (end - start < maxPages - 1) start = Math.max(1, end - maxPages + 1)
  for (let i = start; i <= end; i++) range.push(i)
  return range
})

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page
}

function downloadPOS(log) {
  const content = `Name: ${log.name}\nPlate No.: ${log.plate}\nTime In: ${log.in}\nTime Out: ${log.out}\nTotal Hours: ${log.hours}\nAmount: ${log.amount}`
  const blob = new Blob([content], { type: 'text/plain;charset=utf-8' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `${log.plate}_POS.txt`
  a.click()
  URL.revokeObjectURL(url)
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213] relative">
    <!-- Sidebar -->
    <aside
      :class="[
        'bg-[#5F1213] text-white p-6 h-screen fixed top-0 z-40 transition-all duration-300',
        sidebarOpen ? 'left-0 w-64' : '-left-64',
        'md:left-0 md:w-64 md:block'
      ]"
    >
      <div class="flex justify-between items-center mb-10">
        <h1 class="text-lg font-bold">
          CEDU <span class="text-yellow-300">iCentral</span>
        </h1>
        <button class="md:hidden text-xl" @click="toggleSidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <nav class="space-y-4">
        <a href="#" @click.prevent="admin_Dashboard" class="nav-link">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="#" @click.prevent="admin_ManageParking" class="nav-link">
          <i class="fas fa-parking"></i> Manage Parking
        </a>
        <a href="#" @click.prevent="admin_Reports" class="nav-link">
          <i class="fas fa-file-invoice-dollar"></i> Reports
        </a>
        <a href="#" @click.prevent="admin_Account" class="nav-link">
          <i class="fas fa-user"></i> Account
        </a>
        <a href="#" class="nav-link">
          <i class="fas fa-sign-out-alt"></i> Log out
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-6 overflow-x-hidden">
      <header class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Reports</h1>
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Filters -->
      <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
          <div class="relative w-full sm:w-64">
            <input v-model="searchQuery" type="text" placeholder="Search plate number..." class="input-style" />
            <i class="fas fa-search search-icon"></i>
          </div>
          <select class="select-style">
            <option disabled selected>Filter: Daily</option>
            <option>Weekly</option>
            <option>Monthly</option>
            <option>Annually</option>
          </select>
        </div>
        <button class="download-btn">
          <i class="fas fa-file-download"></i> Download PDF
        </button>
      </div>

      <!-- Responsive Table Wrapper -->
      <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-[900px] w-full text-sm text-left text-gray-800 border border-gray-200">
          <thead class="bg-gradient-to-r from-[#5F1213] via-[#7A2E2F] to-[#5F1213] text-white text-sm uppercase tracking-wide">
            <tr>
              <th class="p-4 border-b">#</th>
              <th class="p-4 border-b">Name</th>
              <th class="p-4 border-b">Plate No.</th>
              <th class="p-4 border-b">Time IN</th>
              <th class="p-4 border-b">Time OUT</th>
              <th class="p-4 border-b">Hours</th>
              <th class="p-4 border-b">Amount</th>
              <th class="p-4 border-b">QR</th>
              <th class="p-4 border-b">POS</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(log, index) in paginatedLogs" :key="index" class="hover:bg-yellow-50 border-t border-gray-200">
              <td class="p-3">{{ index + 1 + (currentPage - 1) * rowsPerPage }}</td>
              <td class="p-3">{{ log.name }}</td>
              <td class="p-3">{{ log.plate }}</td>
              <td class="p-3">{{ log.in }}</td>
              <td class="p-3">{{ log.out }}</td>
              <td class="p-3">{{ log.hours }}</td>
              <td class="p-3 font-semibold text-[#5F1213]">{{ log.amount }}</td>
              <td class="p-3">
                <img :src="log.qr" class="w-10 h-10 object-cover cursor-pointer hover:scale-110 transition-transform rounded" alt="QR" />
              </td>
              <td class="p-3 text-center">
                <button @click="downloadPOS(log)" class="relative group text-[#5F1213] hover:text-[#3C0C0C] transition text-lg">
                  <i class="fas fa-download"></i>
                </button>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="bg-yellow-100 font-semibold text-[#5F1213]">
              <td colspan="6" class="text-right p-3">Total Sales:</td>
              <td class="p-3">₱{{ totalSales.toFixed(2) }}</td>
              <td colspan="2"></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-center mt-6 gap-2 text-sm">
        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="page-btn" :class="currentPage === 1 ? 'disabled-btn' : 'hover:bg-yellow-400'">&lt;</button>
        <button v-for="page in visiblePages" :key="page" @click="changePage(page)" :class="['page-btn', currentPage === page ? 'bg-yellow-400 text-white' : 'hover:bg-yellow-200']">{{ page }}</button>
        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="page-btn" :class="currentPage === totalPages ? 'disabled-btn' : 'hover:bg-yellow-400'">&gt;</button>
      </div>
    </div>
  </div>
</template>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

body {
  font-family: 'Inter', sans-serif;
}
.nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 500;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  transition: background 0.3s, color 0.3s;
}
.nav-link:hover {
  background-color: #FFA600;
  color: #5F1213;
}
.input-style {
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  width: 100%;
}
.search-icon {
  position: absolute;
  top: 50%;
  left: 0.75rem;
  transform: translateY(-50%);
  color: #888;
}
.select-style {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.375rem;
}
.download-btn {
  background-color: #5F1213;
  color: white;
  padding: 0.625rem 1.25rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: background 0.3s;
}
.download-btn:hover {
  background-color: #731a1c;
}
.page-btn {
  padding: 0.25rem 0.75rem;
  border: 1px solid #ccc;
  border-radius: 9999px;
}
.disabled-btn {
  color: #aaa;
  cursor: not-allowed;
}
</style>
