<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-6 pt-4 text-[#5F1213] flex flex-col items-start">
      <!-- Page Title -->
      <div class="bg-white w-full max-w-6xl rounded-xl p-6 mb-6 shadow border">
        <h1 class="text-2xl font-semibold text-left">Reports</h1>
      </div>

      <!-- Report Controls (inline row, single line) -->
      <div
        class="bg-white w-full max-w-6xl rounded-xl p-4 mb-8 shadow-lg flex flex-wrap items-end gap-4"
      >
        <!-- Category -->
        <div class="flex flex-col">
          <label class="font-medium text-sm">Category:</label>
          <select
            v-model="selectedReport"
            class="border rounded px-2 py-1 text-sm w-40"
          >
            <option value="">-- Choose --</option>
            <option value="hostel">Hostel</option>
            <option value="facilities">Facilities</option>
            <option value="commercial">Commercial</option>
          </select>
        </div>

        <!-- Period -->
        <div v-if="selectedReport" class="flex flex-col">
          <label class="font-medium text-sm">Period:</label>
          <select
            v-model="selectedPeriod"
            class="border rounded px-2 py-1 text-sm w-36"
          >
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>

        <!-- Sort By -->
        <div v-if="selectedReport" class="flex flex-col">
          <label class="font-medium text-sm">Sort By:</label>
          <select
            v-model="sortKey"
            class="border rounded px-2 py-1 text-sm w-40"
          >
            <option value="">-- Default --</option>
            <option value="students">Students</option>
            <option value="outside">Outside School</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex flex-col">
          <label class="font-medium text-sm">Search:</label>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search..."
            class="border rounded px-2 py-1 text-sm w-40"
          />
        </div>

        <!-- Date From -->
        <div v-if="selectedReport" class="flex flex-col">
          <label class="font-medium text-sm">From:</label>
          <input
            type="date"
            v-model="startDate"
            class="border rounded px-2 py-1 text-sm w-32"
          />
        </div>

        <!-- Date To -->
        <div v-if="selectedReport" class="flex flex-col">
          <label class="font-medium text-sm">To:</label>
          <input
            type="date"
            v-model="endDate"
            class="border rounded px-2 py-1 text-sm w-32"
          />
        </div>
      </div>

      <!-- Report Table -->
      <div
        v-if="selectedReport"
        class="bg-white w-full max-w-6xl rounded-xl p-6 shadow-lg"
      >
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">{{ reportTitle }}</h3>

          <!-- Download inline -->
          <div class="relative">
            <button
              @click="toggleDropdown"
              class="bg-[#FFA600] text-white px-4 py-2 text-sm rounded hover:bg-[#e29400] flex items-center"
            >
              Download <span class="ml-2">▼</span>
            </button>
            <div
              v-show="showDropdown"
              class="absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg z-50"
            >
              <button
                v-for="format in formats"
                :key="format"
                @click="exportReport(format)"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm"
              >
                {{ format.toUpperCase() }}
              </button>
            </div>
          </div>
        </div>

        <table id="report-table" class="min-w-full border border-gray-200">
          <thead class="bg-[#5F1213] text-white">
            <tr>
              <th class="px-4 py-2 border">#</th>
              <th class="px-4 py-2 border">Date</th>
              <th class="px-4 py-2 border">Time</th>
              <th class="px-4 py-2 border">Name</th>
              <th class="px-4 py-2 border">
                {{
                  selectedReport === "hostel"
                    ? "Room"
                    : selectedReport === "facilities"
                    ? "Facility"
                    : "Shop"
                }}
              </th>
              <th class="px-4 py-2 border">Occupied</th>
              <th class="px-4 py-2 border">Booked</th>
              <th class="px-4 py-2 border">Available</th>
              <th class="px-4 py-2 border">AM Sales</th>
              <th class="px-4 py-2 border">PM Sales</th>
              <th class="px-4 py-2 border">Total Sales</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in filteredData"
              :key="index"
              class="text-center border"
            >
              <td class="px-4 py-2 border">{{ index + 1 }}</td>
              <td class="px-4 py-2 border">{{ item.date }}</td>
              <td class="px-4 py-2 border">{{ item.time }}</td>
              <td class="px-4 py-2 border">{{ item.client }}</td>
              <td class="px-4 py-2 border">{{ item.name }}</td>
              <td class="px-4 py-2 border">{{ item.occupied }}</td>
              <td class="px-4 py-2 border">{{ item.booked }}</td>
              <td class="px-4 py-2 border">{{ item.available }}</td>
              <td class="px-4 py-2 border">₱{{ item.morning }}</td>
              <td class="px-4 py-2 border">₱{{ item.evening }}</td>
              <td class="px-4 py-2 border">
                ₱{{ item.morning + item.evening }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Placeholder -->
      <div
        v-else
        class="bg-white w-full max-w-6xl rounded-xl p-6 shadow-lg text-gray-500"
      >
        Select a report to view data and export options.
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from "vue"
import AdminSidebar from "./adminSidebar.vue"
//import * as XLSX from "xlsx"
import jsPDF from "jspdf"
import autoTable from "jspdf-autotable"

const selectedReport = ref("")
const selectedPeriod = ref("daily")
const searchQuery = ref("")
const sortKey = ref("")
const showDropdown = ref(false)
const formats = ["pdf", "excel", "word"]

// Date range
const startDate = ref("")
const endDate = ref("")

// Dummy client names
const clients = [
  "Juan Dela Cruz (Student)",
  "Maria Santos (Student)",
  "Pedro Reyes",
  "Ana Lopez",
  "Mark Villanueva",
  "Carla Gomez (Student)",
  "Jose Ramirez",
  "Emily Cruz (Student)"
]

// Dummy report data
const reportData = {
  hostel: Array.from({ length: 31 }, (_, i) => {
    const day = i + 1
    return {
      date: `2025-09-${String(day).padStart(2, "0")}`,
      time: `${Math.floor(Math.random() * 12) + 1}:00 ${
        Math.random() > 0.5 ? "AM" : "PM"
      }`,
      client: clients[Math.floor(Math.random() * clients.length)],
      name: `Room A${100 + day}`,
      occupied: Math.floor(Math.random() * 12) + 5,
      booked: Math.floor(Math.random() * 5) + 1,
      available: Math.floor(Math.random() * 8) + 2,
      morning: Math.floor(Math.random() * 2000) + 500,
      evening: Math.floor(Math.random() * 2000) + 500
    }
  }),
  facilities: Array.from({ length: 31 }, (_, i) => {
    const day = i + 1
    return {
      date: `2025-09-${String(day).padStart(2, "0")}`,
      time: `${Math.floor(Math.random() * 12) + 1}:00 ${
        Math.random() > 0.5 ? "AM" : "PM"
      }`,
      client: clients[Math.floor(Math.random() * clients.length)],
      name: day % 2 === 0 ? "Gym" : "Pool",
      occupied: Math.floor(Math.random() * 25) + 5,
      booked: Math.floor(Math.random() * 10) + 1,
      available: Math.floor(Math.random() * 10) + 1,
      morning: Math.floor(Math.random() * 1500) + 400,
      evening: Math.floor(Math.random() * 1500) + 400
    }
  }),
  commercial: Array.from({ length: 31 }, (_, i) => {
    const day = i + 1
    return {
      date: `2025-09-${String(day).padStart(2, "0")}`,
      time: `${Math.floor(Math.random() * 12) + 1}:00 ${
        Math.random() > 0.5 ? "AM" : "PM"
      }`,
      client: clients[Math.floor(Math.random() * clients.length)],
      name: day % 2 === 0 ? "Shop 1" : "Shop 2",
      occupied: Math.floor(Math.random() * 8) + 1,
      booked: Math.floor(Math.random() * 3) + 1,
      available: Math.floor(Math.random() * 5) + 1,
      morning: Math.floor(Math.random() * 800) + 200,
      evening: Math.floor(Math.random() * 800) + 200
    }
  })
}

const reportTitle = computed(() => {
  if (!selectedReport.value) return ""
  const type =
    selectedReport.value.charAt(0).toUpperCase() +
    selectedReport.value.slice(1)
  const period =
    selectedPeriod.value.charAt(0).toUpperCase() +
    selectedPeriod.value.slice(1)
  return `${type} Report - ${period}`
})

const filteredData = computed(() => {
  if (!selectedReport.value) return []
  let data = reportData[selectedReport.value] || []

  if (searchQuery.value) {
    data = data.filter(
      (item) =>
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.client.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  if (startDate.value && endDate.value) {
    data = data.filter(
      (item) => item.date >= startDate.value && item.date <= endDate.value
    )
  }

  if (sortKey.value === "students") {
    data = data.filter((item) => item.client.includes("Student"))
  } else if (sortKey.value === "outside") {
    data = data.filter((item) => !item.client.includes("Student"))
  }

  return data
})

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function exportReport(format) {
  if (format === "excel") {
    const ws = XLSX.utils.json_to_sheet(filteredData.value)
    const wb = XLSX.utils.book_new()
    XLSX.utils.book_append_sheet(wb, ws, "Report")
    XLSX.writeFile(wb, "report.xlsx")
  } else if (format === "pdf") {
    const doc = new jsPDF()
    doc.text(reportTitle.value, 14, 10)
    autoTable(doc, {
      html: "#report-table"
    })
    doc.save("report.pdf")
  } else if (format === "word") {
    let tableHTML = document.getElementById("report-table").outerHTML
    let blob = new Blob(["\ufeff", tableHTML], {
      type: "application/msword"
    })
    let url = URL.createObjectURL(blob)
    let link = document.createElement("a")
    link.href = url
    link.download = "report.doc"
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }
}
</script>
