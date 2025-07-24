<script setup>
import { router } from '@inertiajs/vue3'

function staff_Dashboard() {
  router.visit('/staff_Dashboard')
}
function edit_Form() {
  router.visit('/edit_Form')
}
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-maroon text-white py-2 px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
      <div class="flex items-center gap-2">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-lg font-bold">CEDU <span class="text-blue-300">iCentral</span></h1>
      </div>
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" placeholder="Search" class="flex-1 rounded px-2 py-1 text-white border border-white bg-transparent" />
        <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 border border-white">Search</button>
        <button class="text-white hover:text-yellow-300"><i class="fas fa-bell"></i></button>
        <div class="w-8 h-8 bg-red-700 rounded-full"></div>
      </div>
    </header>

    <!-- Parking History Section -->
    <section class="px-4 py-6 sm:px-8">
      <h2 class="text-lg font-semibold text-yellow-600 mb-4 border-l-4 border-maroon pl-2">Parking History</h2>

      <div class="flex justify-end mb-4">
        <select class="px-2 py-1 border rounded text-sm">
          <option>Daily</option>
          <option>Weekly</option>
        </select>
      </div>

      <!-- Table -->
      <div class="bg-white rounded shadow-md overflow-x-auto">
        <div class="overflow-y-auto" style="max-height: 400px;">
          <table class="w-full text-left text-sm min-w-[900px]">
            <thead class="bg-gray-200 text-gray-700 sticky top-0 z-10">
              <tr>
                <th class="py-2 px-3">NO.</th>
                <th class="py-2 px-3">NAME</th>
                <th class="py-2 px-3">PLATE NO.</th>
                <th class="py-2 px-3">DATE & TIME IN</th>
                <th class="py-2 px-3">DATE & TIME OUT</th>
                <th class="py-2 px-3">HOURS</th>
                <th class="py-2 px-3">AMOUNT</th>
                <th class="py-2 px-3">STATUS</th>
                <th class="py-2 px-3">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <tr v-for="(entry, index) in paginatedEntries" :key="'entry-' + index" class="border-t">
                <td class="py-2 px-3">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="py-2 px-3">{{ entry.name }}</td>
                <td class="py-2 px-3">{{ entry.plate }}</td>
                <td class="py-2 px-3">{{ entry.timeIn }}</td>
                <td class="py-2 px-3">{{ entry.timeOut }}</td>
                <td class="py-2 px-3">{{ entry.totalHours }}</td>
                <td class="py-2 px-3">{{ entry.totalAmount }}</td>
                <td class="py-2 px-3">{{ entry.status }}</td>
                <td class="py-2 px-3 whitespace-nowrap">
                  <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 mb-1" @click="editClient(index)">Edit</button>
                  <button class="bg-red-500 text-white px-2 py-1 ml-1 rounded hover:bg-red-600" @click="deleteClient(index)">Delete</button>
                </td>
              </tr>

              <!-- Placeholder Rows -->
              <tr v-for="n in emptyRowCount" :key="'blank-' + n" class="border-t">
                <td colspan="9" class="py-2 px-3 text-center text-gray-200">—</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex flex-wrap justify-center sm:justify-end mt-6 gap-2 items-center">
        <button 
          class="px-2 text-maroon disabled:text-gray-400" 
          @click="prevPage"
          :disabled="currentPage === 1"
        >
          ◀
        </button>
        <span 
          v-for="page in totalPages" 
          :key="page"
          @click="currentPage = page"
          class="px-3 py-1 rounded-full cursor-pointer"
          :class="currentPage === page ? 'bg-yellow-500 text-white' : 'text-maroon hover:bg-gray-200'"
        >
          {{ page }}
        </span>
        <button 
          class="px-2 text-maroon disabled:text-gray-400" 
          @click="nextPage"
          :disabled="currentPage === totalPages"
        >
          ▶
        </button>
      </div>

      <!-- Back Button -->
      <div class="flex justify-center sm:justify-end mt-4">
        <button 
          @click="staff_Dashboard"
          class="bg-maroon text-white px-4 py-2 rounded hover:bg-red-800 transition-colors"
        >
          ← Back
        </button>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 sm:px-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
      <div>
        <h3 class="font-bold mb-1">Support</h3>
        <p>University of Southeastern Philippines,<br>Tagum-Mabini Campus, Tagum Unit, CEDU Office, Apokon, Tagum City</p>
        <p>cedu@usep.edu.ph<br>+88015-88888-9998</p>
      </div>
      <div>
        <h3 class="font-bold mb-1">Account</h3>
        <p>My Account</p>
        <p>Login / Register</p>
      </div>
      <div>
        <h3 class="font-bold mb-1">Quick Link</h3>
        <p>Privacy Policy</p>
        <p>Terms of Use</p>
        <p>FAQ</p>
        <p>Contact</p>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'ParkingHistory',
  data() {
    return {
      currentPage: 1,
      itemsPerPage: 5,
      entries: [
        { name: 'Joan Malintad', plate: 'ABC123', timeIn: '7:30 AM', timeOut: '8:30 AM', totalHours: '1', totalAmount: '₱20', status: 'Active' },
        { name: 'Axl Rose', plate: 'XYZ456', timeIn: '7:30 AM', timeOut: '9:00 AM', totalHours: '1.5', totalAmount: '₱30', status: 'Complete' },
        { name: 'Christine Tabacon', plate: 'DEF789', timeIn: '7:30 AM', timeOut: '8:30 AM', totalHours: '1', totalAmount: '₱20', status: 'Active' },
        { name: 'Precious Suico', plate: 'GHI012', timeIn: '7:30 AM', timeOut: '10:00 AM', totalHours: '2.5', totalAmount: '₱50', status: 'Active' },
        { name: 'Mark Reyes', plate: 'JKL345', timeIn: '8:00 AM', timeOut: '9:00 AM', totalHours: '1', totalAmount: '₱25', status: 'Complete' },
        { name: 'Liza Cruz', plate: 'MNO678', timeIn: '9:00 AM', timeOut: '10:00 AM', totalHours: '1', totalAmount: '₱25', status: 'Active' },
        { name: 'Rico Santos', plate: 'PQR901', timeIn: '6:00 AM', timeOut: '8:00 AM', totalHours: '2', totalAmount: '₱45', status: 'Complete' },
        { name: 'Jane Doe', plate: 'STU234', timeIn: '7:15 AM', timeOut: '8:15 AM', totalHours: '1', totalAmount: '₱25', status: 'Active' },
      ]
    }
  },
  computed: {
    paginatedEntries() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.entries.slice(start, start + this.itemsPerPage);
    },
    emptyRowCount() {
      return this.itemsPerPage - this.paginatedEntries.length;
    },
    totalPages() {
      return Math.ceil(this.entries.length / this.itemsPerPage);
    }
  },
  methods: {
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    editClient(index) {
      alert(`Edit Client #${(this.currentPage - 1) * this.itemsPerPage + index + 1}`);
    },
    deleteClient(index) {
      if (confirm('Are you sure you want to delete this client?')) {
        const globalIndex = (this.currentPage - 1) * this.itemsPerPage + index;
        this.entries.splice(globalIndex, 1);
      }
    }
  }
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
</style>
