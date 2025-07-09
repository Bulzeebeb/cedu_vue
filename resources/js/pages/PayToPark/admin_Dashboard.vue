<template>
    <div class="dashboard bg-white text-black min-h-screen font-sans">
      <!-- Main Dashboard Content -->
      <section class="py-6 px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6 text-center">
          <div class="bg-blue-100 p-4 rounded shadow">
            <h3 class="text-xl font-bold text-blue-600">20</h3>
            <p>Total Vehicles Parked</p>
          </div>
          <div class="bg-yellow-100 p-4 rounded shadow">
            <h3 class="text-xl font-bold text-yellow-600">7</h3>
            <p>Vehicles In</p>
          </div>
          <div class="bg-green-100 p-4 rounded shadow">
            <h3 class="text-xl font-bold text-green-600">13</h3>
            <p>Vehicles Out</p>
          </div>
          <div class="bg-red-100 p-4 rounded shadow">
            <h3 class="text-xl font-bold text-red-600">6</h3>
            <p>Parking Done Within 24 HRS</p>
          </div>
        </div>
  
        <!-- Chart Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white shadow-md rounded-xl p-6">
            <h4 class="text-lg font-semibold mb-4 text-maroon">Highlights - IN | OUT</h4>
            <canvas id="inOutChart"></canvas>
          </div>
          <div class="bg-white shadow-md rounded-xl p-6">
            <h4 class="text-lg font-semibold mb-4 text-maroon">Highlights - Vehicle Category</h4>
            <canvas id="categoryChart"></canvas>
          </div>
        </div>
  
        <!-- Total Income -->
        <div class="text-center mt-10">
          <h2 class="text-2xl font-bold text-maroon">Total Income</h2>
          <p class="text-3xl font-semibold mt-2">$171</p>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  import Chart from 'chart.js/auto';
  
  export default {
    name: 'Dashboard',
    mounted() {
      this.renderInOutChart();
      this.renderCategoryChart();
    },
    methods: {
      renderInOutChart() {
        const ctx = document.getElementById('inOutChart');
        new Chart(ctx, {
          type: 'pie',
          data: {
            labels: ['Vehicle In-Time', 'Vehicle Out-Time'],
            datasets: [{
              label: 'IN | OUT',
              data: [60, 40],
              backgroundColor: ['#00BFFF', '#20B2AA'],
            }],
          },
          options: {
            responsive: true,
          },
        });
      },
      renderCategoryChart() {
        const ctx = document.getElementById('categoryChart');
        new Chart(ctx, {
          type: 'pie',
          data: {
            labels: ['Two Wheeler', 'Four Wheeler', 'Three Wheeler'],
            datasets: [{
              label: 'Vehicle Category',
              data: [30, 50, 20],
              backgroundColor: ['#FFD700', '#FF6347', '#FFA500'],
            }],
          },
          options: {
            responsive: true,
          },
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .text-maroon {
    color: #650000;
  }
  .bg-maroon {
    background-color: #650000;
  }
  </style>
  