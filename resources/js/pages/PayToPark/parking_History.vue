<template>
    <div class="landing-page bg-white text-black min-h-screen font-sans">
      <!-- Header -->
      <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <img src="/logo.png" alt="CEDU Logo" class="h-10 w-10" />
          <h1 class="text-lg font-bold text-white">CEDU <span class="text-blue-300">iCentral</span></h1>
        </div>
        <div class="flex items-center gap-2">
          <input type="text" placeholder="Search" class="rounded px-2 py-1 text-black" />
          <button class="bg-yellow-500 text-white px-3 py-1 rounded">Search</button>
          <button class="text-white"><i class="fas fa-bell"></i></button>
          <div class="w-8 h-8 bg-red-700 rounded-full"></div>
        </div>
      </header>
  
      <!-- Parking History Section -->
      <section class="mx-8 py-8">
        <h2 class="text-lg font-semibold text-yellow-600 mb-4 border-l-4 border-maroon pl-2">Parking History</h2>
  
        <div class="flex justify-end mb-4">
          <select class="px-2 py-1 border rounded text-sm">
            <option>Filter: Daily</option>
          </select>
        </div>
  
        <!-- Scrollable Table Container -->
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
                  <th class="py-2 px-3">Total Number of Hours</th>
                  <th class="py-2 px-3">Total Amount</th>
                  <th class="py-2 px-3">STATUS</th>
                  <th class="py-2 px-3">ACTIONS</th>
                </tr>
              </thead>
              <tbody class="text-gray-700">
                <tr v-for="(entry, index) in entries" :key="index" class="border-t">
                  <td class="py-2 px-3">{{ index + 1 }}</td>
                  <td class="py-2 px-3">{{ entry.name }}</td>
                  <td class="py-2 px-3">{{ entry.plate }}</td>
                  <td class="py-2 px-3">{{ entry.timeIn }}</td>
                  <td class="py-2 px-3">{{ entry.timeOut }}</td>
                  <td class="py-2 px-3">{{ entry.totalHours }}</td>
                  <td class="py-2 px-3">{{ entry.totalAmount }}</td>
                  <td class="py-2 px-3">{{ entry.status }}</td>
                  <td class="py-2 px-3">
                    <button class="text-blue-500">✏️</button>
                    <button class="text-red-500 ml-2">🗑️</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <!-- Pagination aligned to right -->
        <div class="flex justify-end mt-6 gap-2 pr-2">
          <button class="text-gray-400 cursor-not-allowed">◀</button>
          <button class="bg-yellow-500 text-white px-3 py-1 rounded-full">1</button>
          <button class="text-maroon">2</button>
          <button class="text-maroon">3</button>
          <button class="text-maroon">4</button>
          <span class="text-maroon">...</span>
          <button class="text-maroon">21</button>
          <button class="text-maroon">▶</button>
        </div>
      </section>
  
      <!-- Footer -->
      <footer class="bg-maroon text-white mt-10 py-6 px-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
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
        entries: [
          { name: 'Joan Malintad', plate: '₱100,000.00', timeIn: '7:30 AM - 07/03/2025', timeOut: '7:30 AM - 07/03/2025', totalHours: '', totalAmount: '', status: 'Active' },
          { name: 'Axl Rose Supranes', plate: '₱130,000.00', timeIn: '7:30 AM - 07/03/2025', timeOut: '7:30 AM - 07/03/2025', totalHours: '', totalAmount: '', status: 'Complete' },
          { name: 'Christine Jane Tabacon', plate: '₱175,000.00', timeIn: '7:30 AM - 07/03/2025', timeOut: '7:30 AM - 07/03/2025', totalHours: '', totalAmount: '', status: 'Active' },
          { name: 'Precious Lyn Suico', plate: '₱250,000.00', timeIn: '7:30 AM - 07/03/2025', timeOut: '7:30 AM - 07/03/2025', totalHours: '', totalAmount: '', status: 'Active' },
          { name: 'Mark Reyes', plate: '₱90,000.00', timeIn: '8:00 AM - 07/03/2025', timeOut: '10:00 AM - 07/03/2025', totalHours: '2', totalAmount: '₱40.00', status: 'Complete' },
          { name: 'Liza Dela Cruz', plate: '₱110,000.00', timeIn: '9:00 AM - 07/03/2025', timeOut: '11:00 AM - 07/03/2025', totalHours: '2', totalAmount: '₱50.00', status: 'Active' },
          { name: 'Rico Santos', plate: '₱120,000.00', timeIn: '6:00 AM - 07/03/2025', timeOut: '8:00 AM - 07/03/2025', totalHours: '2', totalAmount: '₱45.00', status: 'Complete' },
          { name: 'Jane Doe', plate: '₱95,000.00', timeIn: '7:15 AM - 07/03/2025', timeOut: '8:15 AM - 07/03/2025', totalHours: '1', totalAmount: '₱25.00', status: 'Active' },
          { name: 'Juan Dela Cruz', plate: '₱115,000.00', timeIn: '10:00 AM - 07/03/2025', timeOut: '12:00 PM - 07/03/2025', totalHours: '2', totalAmount: '₱50.00', status: 'Complete' },
          { name: 'Maria Clara', plate: '₱105,000.00', timeIn: '11:00 AM - 07/03/2025', timeOut: '12:30 PM - 07/03/2025', totalHours: '1.5', totalAmount: '₱35.00', status: 'Active' },
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
  </style>
  