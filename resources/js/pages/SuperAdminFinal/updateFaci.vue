
<template>
  <div class="min-h-screen flex font-sans">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main content -->
    <main class="ml-64 flex-1 bg-gray-100 min-h-screen p-6 text-[#5F1213]">

              <h1 class="text-4xl font-bold text-[#5F1213] mb-10 mt-5">Update Use-of-Facilities</h1>

  <!-- Manage Hostel Booking Section -->
  <div class="bg-white shadow-2xl rounded-2xl p-10 space-y-10 border-t-8 border-[#5F1213] mb-16">
    <div class="flex justify-between items-center">
  <h2 class="text-4xl font-extrabold text-[#5F1213] flex items-center gap-3">
    MANAGE HOSTEL BOOKING
  </h2>
  <div class="flex gap-4">
    <button
      class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
    >
      <i class="fas fa-eye mr-1"></i> View Rooms
    </button>
    <button
      class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
    >
      <i class="fas fa-plus mr-1"></i> Add Room
    </button>
  </div>
</div>

    <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full"></div>

    <div class="mt-4">
      <h3 class="text-xl font-bold text-[#5F1213] mb-4">Pending Bookings</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-300 rounded-xl">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="px-4 py-2">Guest Name</th>
              <th class="px-4 py-2">Room</th>
              <th class="px-4 py-2">Guests</th>
              <th class="px-4 py-2">Check-in</th>
              <th class="px-4 py-2">Check-out</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white text-center">
            <tr class="border-t">
              <td class="px-4 py-2">Juan Dela Cruz</td>
              <td class="px-4 py-2">Room 101</td>
              <td class="px-4 py-2 font-semibold text-blue-700">3 Guests</td>
              <td class="px-4 py-2">2025-07-25</td>
              <td class="px-4 py-2">2025-07-27</td>
              <td class="px-4 py-2 text-yellow-600 font-semibold">Pending</td>
              <td class="px-4 py-2 space-x-2">
                <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                  <i class="fas fa-eye"></i> View
                </button>
                <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-xs">
                  <i class="fas fa-check"></i> Confirm
                </button>
                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                  <i class="fas fa-times"></i> Decline
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Manage Rental Facilities Section -->
  <div class="bg-white shadow-2xl rounded-2xl p-10 space-y-10 border-t-8 border-[#FFA600]">
   <div class="flex justify-between items-center">
  <h2 class="text-4xl font-extrabold text-[#5F1213] flex items-center gap-3">
    MANAGE RENTAL FACILITIES
  </h2>
  <div class="flex gap-4">
    <button
      class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
    >
      <i class="fas fa-eye mr-1"></i> View Facilities
    </button>
    <button
      class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
    >
      <i class="fas fa-plus mr-1"></i> Add Facility
    </button>
  </div>
</div>

    <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full"></div>

    <div class="mt-4">
      <h3 class="text-xl font-bold text-[#5F1213] mb-4">Confirmed Rentals</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-300 rounded-xl">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="px-4 py-2">Facility</th>
              <th class="px-4 py-2">Guest Name</th>
              <th class="px-4 py-2">Guests</th>
              <th class="px-4 py-2">Start</th>
              <th class="px-4 py-2">End</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white text-center">
            <tr class="border-t">
              <td class="px-4 py-2">Covered Court</td>
              <td class="px-4 py-2">Maria Santos</td>
              <td class="px-4 py-2 font-semibold text-blue-700">10 Guests</td>
              <td class="px-4 py-2">2025-07-28 08:00</td>
              <td class="px-4 py-2">2025-07-28 18:00</td>
              <td class="px-4 py-2 text-green-600 font-semibold">Confirmed</td>
              <td class="px-4 py-2 space-x-2">
                <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                  <i class="fas fa-eye"></i> View
                </button>
                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                  <i class="fas fa-trash"></i> Cancel
                </button>
              </td>
            </tr>
            <!-- More dynamic rows here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
<!-- Manage Commercial Spaces Section -->
<div class="bg-white shadow-2xl rounded-2xl p-10 space-y-10 border-t-8 border-[#FFA600] mt-10">
  <div class="flex justify-between items-center">
    <h2 class="text-4xl font-extrabold text-[#5F1213] flex items-center gap-3">
      MANAGE COMMERCIAL SPACES
    </h2>
    <div class="flex gap-4">
      <button
        class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
      >
        <i class="fas fa-eye mr-1"></i> View Spaces
      </button>
      <button
        class="bg-[#5F1213] text-white px-6 py-2 rounded-xl hover:bg-[#FFA600] transition-all duration-300 text-sm font-semibold"
      >
        <i class="fas fa-plus mr-1"></i> Add Space
      </button>
    </div>
  </div>

  <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full"></div>

  <div class="mt-4">
    <h3 class="text-xl font-bold text-[#5F1213] mb-4">Confirmed Leases</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm border border-gray-300 rounded-xl">
        <thead class="bg-[#5F1213] text-white">
          <tr>
            <th class="px-4 py-2">Space</th>
            <th class="px-4 py-2">Lessee Name</th>
            <th class="px-4 py-2">Business Type</th>
            <th class="px-4 py-2">Start</th>
            <th class="px-4 py-2">End</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white text-center">
          <tr class="border-t">
            <td class="px-4 py-2">Kiosk A1</td>
            <td class="px-4 py-2">Juan Dela Cruz</td>
            <td class="px-4 py-2">Food Stall</td>
            <td class="px-4 py-2">2025-08-01</td>
            <td class="px-4 py-2">2025-12-31</td>
            <td class="px-4 py-2 text-green-600 font-semibold">Confirmed</td>
            <td class="px-4 py-2 space-x-2">
              <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                <i class="fas fa-eye"></i> View
              </button>
              <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                <i class="fas fa-trash"></i> Cancel
              </button>
            </td>
          </tr>
          <!-- Add more dynamic commercial space entries here -->
        </tbody>
      </table>
    </div>
  </div>
</div>

</main>

  </div>
</template>


<script setup>
import { Link } from '@inertiajs/vue3'
import Sidebar from './sidebar.vue';
</script>


<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/chart.js');
body {
  font-family: 'Inter', sans-serif;
}

</style>
