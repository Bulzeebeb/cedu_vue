<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const sidebarOpen = ref(false)
const isSaved = ref(true)

// Form data
const rateHour = ref(3)           
const rateMinute = ref('00')      
const rateFee = ref('20')         

const parkingPeriod = ref('8 hrs')
const overtimeFee = ref('₱')
const overnightFee = ref('₱')

const openingHour = ref(6)
const closingHour = ref(22)

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}
function handleSave() {
  isSaved.value = true

  // Replace this with an Inertia POST/PUT to backend when ready
  console.log('Parking Rate:', `${rateHour.value}:${rateMinute.value} ${ratePeriod.value}`, 'Fee:', rateFee.value)
  console.log('Parking Period:', parkingPeriod.value)
  console.log('Overtime Fee:', overtimeFee.value)
  console.log('Overnight Fee:', overnightFee.value)
  console.log('Operating Hours:', `${openingHour.value} - ${closingHour.value}`)
}
function handleEdit() {
  isSaved.value = false
}
function handleBack() {
  router.visit('/admin_Dashboard')
}
function admin_Dashboard() {
  router.visit('/admin_Dashboard')
}
function admin_ManageParking() {
  router.visit('/admin_ManageParking')
}
function admin_Account() {
  router.visit('/admin_Account')
}
function admin_Reports() {
  router.visit('/admin_parking_reports')
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <!-- Sidebar -->
    <aside
      :class="[ 'bg-[#5F1213] text-white p-6 h-screen fixed top-0 z-40 transition-all duration-300',
                sidebarOpen ? 'left-0 w-64' : '-left-64',
                'md:left-0 md:w-64 md:block' ]"
    >
      <div class="flex justify-between items-center mb-10">
        <h1 class="text-lg font-bold">CEDU <span class="text-yellow-300">iCentral</span></h1>
        <button class="md:hidden text-xl" @click="toggleSidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <nav class="space-y-4">
        <a href="#" @click="admin_Dashboard" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="#" @click="admin_ManageParking" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-parking"></i> Manage Parking
        </a>
        <a href="#" @click="admin_Reports" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-file-invoice-dollar"></i> Reports
        </a>
        <a href="#" @click="admin_Account" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-user"></i> Account
        </a>
        <a href="#" class="px-4 py-2 rounded-lg font-medium shadow hover:bg-[#FFA600] hover:text-[#5F1213] flex items-center gap-3">
          <i class="fas fa-sign-out-alt"></i> Log out
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <!-- Header -->
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Manage Parking</h1>
        </div>
      </header>

      <!-- Divider -->
      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <!-- Content Sections -->
      <div class="space-y-10 bg-white shadow-2xl rounded-2xl p-10 border-t-8 border-[#5F1213]">


      <!-- SET PARKING RATE -->
      <section class="styled-section bg-[#FFF9F0] border-[#FFD580]">
        <h3 class="section-title">SET PARKING RATE</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block mb-1 text-sm font-medium text-[#5F1213]">Hour</label>
            <input
              type="number"
              class="form-input"
              v-model="rateHour"
              placeholder="3"
              :readonly="isSaved"
              :class="{ 'bg-gray-200': isSaved }"
            />
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium text-[#5F1213]">Minutes</label>
            <select
              class="form-input"
              v-model="rateMinute"
              :disabled="isSaved"
              :class="{ 'bg-gray-200': isSaved }"
            >
              <option value="00">00</option>
              <option value="30">30</option>
            </select>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium text-[#5F1213]">Fee (₱)</label>
            <input
              type="text"
              class="form-input"
              v-model="rateFee"
              placeholder="20"
              :readonly="isSaved"
              :class="{ 'bg-gray-200': isSaved }"
            />
          </div>
        </div>
      </section>




        <!-- SET PARKING PERIOD -->
        <section class="styled-section bg-[#F3F4F6] border-gray-300">
          <h3 class="section-title">SET PARKING PERIOD</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block mb-1 text-sm font-medium text-[#5F1213]">Allowed Parking Duration</label>
              <input
                type="text"
                class="form-input"
                v-model="parkingPeriod"
                placeholder="e.g., 8 hrs"
                :readonly="isSaved"
                :class="{ 'bg-gray-200': isSaved }"
              />
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium text-[#5F1213]">Overtime Fee (₱)</label>
              <input
                type="text"
                class="form-input"
                v-model="overtimeFee"
                placeholder="₱ Overtime Fee"
                :readonly="isSaved"
                :class="{ 'bg-gray-200': isSaved }"
              />
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium text-[#5F1213]">Overnight Fee (₱)</label>
              <input
                type="text"
                class="form-input"
                v-model="overnightFee"
                placeholder="₱ Overnight Fee"
                :readonly="isSaved"
                :class="{ 'bg-gray-200': isSaved }"
              />
            </div>
          </div>
        </section>


       <!-- OPERATING HOURS -->
        <section class="styled-section bg-[#EFF6FF] border-blue-200">
            <h3 class="section-title"> OPERATING HOURS</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block mb-1 text-sm font-medium text-[#5F1213]">Opening Time</label>
                <input
                  type="time"
                  class="form-input"
                  v-model="openingHour"
                  :readonly="isSaved"
                  :class="{ 'bg-gray-200': isSaved }"
                />
              </div>
              <div>
                <label class="block mb-1 text-sm font-medium text-[#5F1213]">Closing Time</label>
                <input
                  type="time"
                  class="form-input"
                  v-model="closingHour"
                  :readonly="isSaved"
                  :class="{ 'bg-gray-200': isSaved }"
                />
              </div>
            </div>
          </section>



        <!-- Action Buttons -->
        <div class="pt-6 flex justify-end space-x-4">
          <button @click="handleEdit" class="btn-primary">Edit</button>
          <button @click="handleSave" class="btn-success">Save</button>
          <button @click="handleBack" class="btn-neutral">Back</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
  background-color: #5F1213;
  color: white;
}
.nav-link:hover {
  background-color: #FFA600;
  color: #5F1213;
}

.styled-section {
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: inset 0 1px 4px rgba(0,0,0,0.05);
  border: 1px solid;
  background-color: #FDF9F8; 
}
.section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #5F1213;
  margin-bottom: 1rem;
}

.form-input {
  border: 1px solid #d1d5db;
  padding: 0.6rem 0.8rem;
  border-radius: 0.5rem;
  width: 100%;
  transition: all 0.2s ease;
  color: #1f2937;
}
.form-input:focus {
  outline: none;
  border-color: #5F1213;
  box-shadow: 0 0 0 2px rgba(95, 18, 19, 0.25);
}

.btn-primary {
  background: #5F1213;
  color: white;
  padding: 0.6rem 1.5rem;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  background-color: #FFA600;
  color: #5F1213;
}
.btn-success {
  background-color: #16a34a;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
}
.btn-success:hover {
  background-color: #15803d;
}
.btn-neutral {
  background-color: #9ca3af;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
}
.btn-neutral:hover {
  background-color: #6b7280;
}
</style>
