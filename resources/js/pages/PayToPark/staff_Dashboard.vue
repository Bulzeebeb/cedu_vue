<template>
    <div class="landing-page bg-white text-black min-h-screen font-sans">
      <!-- Header -->
      <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <img src="/logo.png" alt="CEDU Logo" class="h-10 w-10" />
          <h1 class="text-lg font-bold text-white">CEDU <span class="text-blue-300">iCentral</span></h1>
        </div>
        <div class="flex items-center gap-2">
          <input
            type="text"
            placeholder="Search"
            class="rounded px-2 py-1 text-black"
          />
          <button class="bg-yellow-500 text-white px-3 py-1 rounded">Search</button>
          <button class="text-white">
            <i class="fas fa-bell"></i> <!-- Optional: Use FontAwesome or another icon lib -->
          </button>
          <div class="w-8 h-8 bg-red-700 rounded-full"></div> <!-- Avatar -->
        </div>
      </header>
  
      <!-- Welcome & Stats -->
      <section class="px-6 py-4">
        <h2 class="text-gray-500 text-sm">WELCOME BACK!</h2>
        <h1 class="text-3xl font-bold text-maroon">SHANNEN ANN</h1>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6">
          <div class="bg-maroon text-white text-center py-6 rounded shadow">
            <p class="text-2xl font-bold">00</p>
            <p class="text-sm mt-1">TOTAL NO. OF<br />VEHICLE PARKED</p>
          </div>
          <div class="bg-maroon text-white text-center py-6 rounded shadow">
            <p class="text-2xl font-bold">00</p>
            <p class="text-sm mt-1">VEHICLES IN</p>
          </div>
          <div class="bg-maroon text-white text-center py-6 rounded shadow">
            <p class="text-2xl font-bold">00</p>
            <p class="text-sm mt-1">VEHICLES OUT</p>
          </div>
          <div class="bg-maroon text-white text-center py-6 rounded shadow">
            <p class="text-2xl font-bold">00</p>
            <p class="text-sm mt-1">DAILY TOTAL SALES</p>
          </div>
        </div>
  
        <!-- Search & Table -->
        <div class="bg-gray-100 p-4 rounded shadow-md">
          <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
            <div class="flex items-center gap-2">
              <input type="text" placeholder="Search" class="px-2 py-1 rounded border" />
              <button class="bg-yellow-500 text-white px-3 py-1 rounded">Search</button>
              <select class="ml-2 px-2 py-1 rounded border text-sm">
                <option>Filter: Daily</option>
              </select>
            </div>
            <div class="flex gap-2">
              <button class="bg-white border text-maroon px-3 py-1 rounded shadow">Parking History</button>
              <button class="bg-maroon text-white px-3 py-1 rounded">+ ADD NEW CLIENT</button>
            </div>
          </div>
  
          <!-- Table -->
          <table class="w-full text-left text-sm bg-white rounded overflow-hidden">
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
                <td class="py-2 px-3">
                  <button class="text-blue-500">✏️</button>
                  <button class="text-red-500 ml-2">🗑️</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
  
      <!-- Footer -->
      <footer class="bg-maroon text-white mt-10 py-6 px-4 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
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
    name: 'ClientDashboard',
    data() {
      return {
        clients: [
          {
            name: 'Joan Malintad',
            plate: '₱100,000.00',
            timeIn: '7:30 AM - 07/03/2025',
            timeOut: '7:30 AM - 07/03/2025',
            status: 'Active',
          },
          {
            name: 'Axl Rose Supranes',
            plate: '₱130,000.00',
            timeIn: '7:30 AM - 07/03/2025',
            timeOut: '7:30 AM - 07/03/2025',
            status: 'Complete',
          },
          {
            name: 'Christine Jane Tabacon',
            plate: '₱175,000.00',
            timeIn: '7:30 AM - 07/03/2025',
            timeOut: '7:30 AM - 07/03/2025',
            status: 'Active',
          },
          {
            name: 'Precious Lyn Suico',
            plate: '₱250,000.00',
            timeIn: '7:30 AM - 07/03/2025',
            timeOut: '7:30 AM - 07/03/2025',
            status: 'Active',
          },
        ],
      };
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
  .nav-btn {
    background: white;
    color: black;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
  }
  </style>
  