<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <!-- Sidebar -->
    <aside
      :class="[
        'bg-[#5F1213] text-white p-6 h-full fixed top-0 z-40 transition-all duration-300',
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
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213]">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213]">
          <i class="fas fa-parking"></i> Manage Parking
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold bg-[#FFA600] text-[#5F1213] shadow">
          <i class="fas fa-file-invoice-dollar"></i> Reports
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213]">
          <i class="fas fa-car"></i> Parking Logs
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213]">
          <i class="fas fa-user"></i> Account
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213]">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen bg-white">
      <main class="p-4 flex-1">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-2xl font-bold text-yellow-500 border-l-8 border-[#5F1213] pl-4 mb-6">
            REPORTS
          </h2>

          <!-- Search + Download -->
          <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto">
              <input v-model="searchQuery" type="text" placeholder="Search by plate number" class="border text-black p-2 rounded w-full sm:w-auto" />
              <select class="border p-2 text-black rounded text-sm">
                <option>Filter: Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
                <option>Annually</option>
              </select>
            </div>
            <div class="self-start lg:self-center mt-2 lg:mt-0 lg:ml-auto">
              <button class="bg-[#5F1213] text-white px-4 py-2 text-sm rounded-md whitespace-nowrap">
                Download PDF
              </button>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-300 text-black">
              <thead class="bg-gray-200">
                <tr>
                  <th class="p-3">No.</th>
                  <th class="p-3">Name</th>
                  <th class="p-3">Plate No.</th>
                  <th class="p-3">Date & Time IN</th>
                  <th class="p-3">Date & Time OUT</th>
                  <th class="p-3">Total Hours</th>
                  <th class="p-3">Total Amount</th>
                  <th class="p-3">QR Code</th>
                  <th class="p-3">Download POS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(log, index) in paginatedLogs" :key="index" class="border-t">
                  <td class="p-3">{{ index + 1 + (currentPage - 1) * rowsPerPage }}</td>
                  <td class="p-3">{{ log.name }}</td>
                  <td class="p-3">{{ log.plate }}</td>
                  <td class="p-3">{{ log.in }}</td>
                  <td class="p-3">{{ log.out }}</td>
                  <td class="p-3">{{ log.hours }}</td>
                  <td class="p-3">{{ log.amount }}</td>
                  <td class="p-3">
                    <img
                      :src="log.qr"
                      @click="openQR(log.qr, log.plate)"
                      class="w-12 h-12 object-contain cursor-pointer hover:scale-105 transition-transform"
                      alt="QR Code"
                    />
                  </td>
                  <td class="p-3">
                    <button
                      @click="downloadPOS(log)"
                      class="bg-yellow-500 text-white px-3 py-1 text-xs rounded hover:bg-yellow-600"
                    >
                      Download
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="bg-yellow-100 font-semibold">
                  <td colspan="6" class="text-right p-3">Total Sales:</td>
                  <td class="p-3">₱{{ totalSales.toFixed(2) }}</td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-center mt-4 gap-2 text-sm">
              <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="px-3 py-1 rounded-full border"
                :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-yellow-400'">
                &lt;
              </button>
              <button v-for="page in visiblePages" :key="page" @click="changePage(page)"
                :class="['px-3 py-1 rounded-full', currentPage === page ? 'bg-yellow-400 text-white' : 'hover:bg-yellow-200']">
                {{ page }}
              </button>
              <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="px-3 py-1 rounded-full border"
                :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-yellow-400'">
                &gt;
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- QR Modal -->
    <!-- ... QR Modal remains unchanged ... -->
  </div>
</template>

<script>
export default {
  name: 'admin_parking_reports',
  data() {
    return {
      sidebarOpen: false,
      showQRModal: false,
      qrImage: '',
      qrPlate: '',
      currentPage: 1,
      rowsPerPage: 10,
      searchQuery: '',
      logs: Array.from({ length: 25 }, (_, i) => ({
        name: `Sample User ${i + 1}`,
        plate: `XYZ-${1000 + i}`,
        in: '2025-07-17 08:00 AM',
        out: '2025-07-17 10:00 AM',
        hours: '2',
        amount: `₱${(20 + (i % 5) * 10).toFixed(2)}`,
        qr: 'https://via.placeholder.com/100x100.png?text=QR'
      }))
    };
  },
  computed: {
    filteredLogs() {
      const query = this.searchQuery.toLowerCase();
      return this.logs.filter(log => log.plate.toLowerCase().includes(query));
    },
    paginatedLogs() {
      const start = (this.currentPage - 1) * this.rowsPerPage;
      return this.filteredLogs.slice(start, start + this.rowsPerPage);
    },
    totalSales() {
      return this.filteredLogs.reduce((total, log) => {
        const numericAmount = parseFloat(log.amount.replace(/[^\d.]/g, ''));
        return total + numericAmount;
      }, 0);
    },
    totalPages() {
      return Math.ceil(this.filteredLogs.length / this.rowsPerPage);
    },
    visiblePages() {
      const range = [];
      const maxPages = 5;
      let start = Math.max(1, this.currentPage - Math.floor(maxPages / 2));
      let end = Math.min(this.totalPages, start + maxPages - 1);
      if (end - start < maxPages - 1) start = Math.max(1, end - maxPages + 1);
      for (let i = start; i <= end; i++) range.push(i);
      return range;
    }
  },
  methods: {
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    },
    downloadPOS(log) {
      const content = `Name: ${log.name}\nPlate No.: ${log.plate}\nTime In: ${log.in}\nTime Out: ${log.out}\nTotal Hours: ${log.hours}\nAmount: ${log.amount}`;
      const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `${log.plate}_POS.txt`;
      a.click();
      URL.revokeObjectURL(url);
    },
    openQR(image, plate) {
      this.qrImage = image;
      this.qrPlate = plate;
      this.showQRModal = true;
    },
    closeQR() {
      this.showQRModal = false;
      this.qrImage = '';
      this.qrPlate = '';
    }
  }
};
</script>
<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
body {
  font-family: 'Inter', sans-serif;
}
</style>