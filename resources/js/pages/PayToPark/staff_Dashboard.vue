<script setup>
import { router } from '@inertiajs/vue3'

function client_Form() {
  router.visit('/client_Form')
}

function parking_History() {
  router.visit('/parking_History')
}

function edit_Form() {
  router.visit('/edit_Form')
}

function deleteClient(index) {
  if (confirm('Are you sure you want to delete this client?')) {
    alert(`Client #${index + 1} will be deleted.`)
  }
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

    <!-- Welcome & Stats -->
    <section class="px-4 py-4 sm:px-6">
      <h2 class="text-gray-500 text-sm">WELCOME BACK!</h2>
      <h1 class="text-3xl font-bold text-maroon">SHANNEN ANN</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 my-6">
        <div class="bg-maroon text-white text-center py-6 rounded-2xl shadow">
          <p class="text-2xl font-bold">00</p>
          <p class="text-sm mt-1">TOTAL NO. OF<br />VEHICLE PARKED</p>
        </div>
        <div class="bg-maroon text-white text-center py-6 rounded-2xl shadow">
          <p class="text-2xl font-bold">00</p>
          <p class="text-sm mt-1">VEHICLES IN</p>
        </div>
        <div class="bg-maroon text-white text-center py-6 rounded-2xl shadow">
          <p class="text-2xl font-bold">00</p>
          <p class="text-sm mt-1">VEHICLES OUT</p>
        </div>
        <div class="bg-maroon text-white text-center py-6 rounded-2xl shadow">
          <p class="text-2xl font-bold">₱0.00</p>
          <p class="text-sm mt-1">DAILY TOTAL SALES</p>
        </div>
      </div>

      <!-- Search & Table -->
      <div class="bg-gray-100 p-4 rounded shadow-md overflow-x-auto">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-4">
          <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <input type="text" placeholder="Search" class="px-2 py-1 rounded border border-white w-full sm:w-auto" />
            <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 border border-white">Search</button>
            <select class="px-2 py-1 rounded border text-sm w-full sm:w-auto">
              <option>Filter: Daily</option>
            </select>
          </div>
          <div class="flex flex-wrap gap-2 w-full lg:w-auto justify-end">
            <button @click="parking_History" class="bg-white border text-maroon px-3 py-1 rounded shadow hover:bg-gray-200 w-full sm:w-auto">Parking History</button>
            <button @click="client_Form" class="bg-maroon text-white px-3 py-1 rounded hover:bg-red-900 w-full sm:w-auto">+ ADD NEW CLIENT</button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-sm bg-white rounded">
            <thead class="bg-gray-200 text-gray-700">
              <tr>
                <th class="py-2 px-3">NO.</th>
                <th class="py-2 px-3">NAME</th>
                <th class="py-2 px-3">PLATE NO.</th>
                <th class="py-2 px-3">DATE & TIME IN</th>
                <th class="py-2 px-3">DATE & TIME OUT</th>
                <th class="py-2 px-3">STATUS</th>
                <th class="py-2 px-3">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <tr v-for="(client, index) in clients" :key="index" class="border-t">
                <td class="py-2 px-3">{{ index + 1 }}</td>
                <td class="py-2 px-3">{{ client.name }}</td>
                <td class="py-2 px-3">{{ client.plate }}</td>
                <td class="py-2 px-3">{{ client.timeIn }}</td>
                <td class="py-2 px-3">{{ client.timeOut }}</td>
                <td class="py-2 px-3">{{ client.status }}</td>
                <td class="py-2 px-3 flex flex-wrap gap-2">
                  <button @click="edit_Form" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button @click="deleteClient(index)" class="bg-red-700 text-white px-2 py-1 rounded text-xs hover:bg-red-800">Delete</button>
                  <button class="bg-blue-200 text-blue-700 px-2 py-1 rounded text-xs hover:bg-blue-300">More</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
      <div>
        <h3 class="font-bold mb-1">Support</h3>
        <p>University of Southeastern Philippines,<br />Tagum-Mabini Campus, Tagum Unit, CEDU Office, Apokon, Tagum City</p>
        <p>cedu@usep.edu.ph<br />+88015-88888-9998</p>
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
  name: 'StaffDashboard',
  data() {
    return {
      clients: [
        {
          name: 'Joan Malintad',
          plate: 'ABC 1234',
          timeIn: '7:30 AM - 07/03/2025',
          timeOut: '9:00 AM - 07/03/2025',
          status: 'Active'
        },
        {
          name: 'Axl Rose Supranes',
          plate: 'XYZ 5678',
          timeIn: '8:00 AM - 07/03/2025',
          timeOut: '10:00 AM - 07/03/2025',
          status: 'Complete'
        },
        {
          name: 'Christine Jane Tabacon',
          plate: 'JLN 1111',
          timeIn: '7:30 AM - 07/03/2025',
          timeOut: '',
          status: 'Active'
        },
        {
          name: 'Precious Lyn Suico',
          plate: 'SBC 2222',
          timeIn: '9:00 AM - 07/03/2025',
          timeOut: '',
          status: 'Active'
        }
      ]
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
