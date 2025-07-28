<script setup>
import { router } from '@inertiajs/vue3'

function staff_Dashboard() {
  router.visit('/staff_Dashboard')
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

    <!-- Add New Client Section -->
    <section class="px-4 py-6 sm:px-8">
      <h2 class="text-lg font-semibold text-yellow-600 mb-6 border-l-4 border-maroon pl-2">Add New Client</h2>

      <div class="bg-gray-100 rounded-xl p-6 max-w-4xl mx-auto">
        <form @submit.prevent="addClient">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
              <label class="block text-sm font-medium mb-1">Client’s First Name</label>
              <input v-model="client.firstName" type="text" class="w-full border border-red-400 px-3 py-2 rounded" />
            </div>

            <!-- Last Name -->
            <div>
              <label class="block text-sm font-medium mb-1">Client’s Last Name</label>
              <input v-model="client.lastName" type="text" class="w-full border border-red-400 px-3 py-2 rounded" />
            </div>

            <!-- Plate Number -->
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium mb-1">Client’s Plate Number</label>
              <input v-model="client.plateNumber" type="text" class="w-full border border-red-400 px-3 py-2 rounded" />
            </div>

            <!-- Date -->
            <div>
              <label class="block text-sm font-medium mb-1">Date</label>
              <input v-model="client.date" type="date" class="w-full border px-3 py-2 rounded" />
            </div>

            <!-- Time and AM/PM -->
            <div class="flex gap-2 flex-col sm:flex-row">
              <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Time</label>
                <input v-model="client.time" type="time" class="w-full border px-3 py-2 rounded" />
              </div>
              <div class="w-full sm:w-28">
                <label class="block text-sm font-medium mb-1">AM/PM</label>
                <select v-model="client.ampm" class="w-full border px-3 py-2 rounded">
                  <option>AM</option>
                  <option>PM</option>
                </select>
              </div>
            </div>

            <!-- Add Client Button -->
            <div>
              <button type="submit" class="w-full bg-maroon text-white font-semibold py-2 rounded hover:bg-red-800 mt-2 sm:mt-6">
                ADD CLIENT
              </button>
            </div>

            <!-- Clear Button -->
            <div>
              <button type="button" @click="clearForm" class="w-full bg-yellow-500 text-white font-semibold py-2 rounded hover:bg-yellow-600 mt-2 sm:mt-6">
                CLEAR
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Back Button -->
      <div class="flex justify-center sm:justify-end mt-6 max-w-4xl mx-auto">
        <button
          @click="staff_Dashboard"
          class="bg-maroon text-white px-5 py-2 rounded hover:bg-red-800 transition-colors"
        >
          ← Back
        </button>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white mt-10 py-6 px-4 sm:px-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
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
  name: 'AddNewClient',
  data() {
    return {
      client: {
        firstName: '',
        lastName: '',
        plateNumber: '',
        date: '',
        time: '',
        ampm: 'AM',
      },
    };
  },
  methods: {
    addClient() {
      console.log('Client added:', this.client);
      alert('Client added successfully!');
      this.clearForm();
    },
    clearForm() {
      this.client = {
        firstName: '',
        lastName: '',
        plateNumber: '',
        date: '',
        time: '',
        ampm: 'AM',
      };
    },
  },
};
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}
.text-maroon {
  color: #650000;
}
</style>
