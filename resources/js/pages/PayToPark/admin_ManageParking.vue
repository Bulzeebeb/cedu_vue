<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'

const props = defineProps({
  settings: Object // passed from Laravel controller
})

// Sidebar toggle state
const sidebarOpen = ref(false)
<<<<<<< Updated upstream
=======
const isSaved = ref(true)

// Form data
const rateHour = ref(3)           
const rateMinute = ref('00')      
const rateFee = ref('20')         

const parkingPeriod = ref('8 hrs')
const overtimeFee = ref('')
const overnightFee = ref('')

const openingHour = ref('06:00')
const closingHour = ref('22:00')
>>>>>>> Stashed changes

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}
<<<<<<< Updated upstream

function closeSidebar() {
  console.log('Sidebar closed') // ✅ this should appear
  sidebarOpen.value = false
}

// Local form state (empty until populated from DB)
const rateHour = ref('')
const rateMinute = ref('')
const rateFee = ref('')
const parkingPeriod = ref('')
const overtimeFee = ref('')
const overnightFee = ref('')
const openingHour = ref('')
const closingHour = ref('')

// Sync DB values from props
watch(
  () => props.settings,
  (newSettings) => {
    if (newSettings) {
      rateHour.value = newSettings.rate_hour ?? ''
      rateMinute.value = newSettings.rate_minute ?? ''
      rateFee.value = newSettings.rate_fee !== null ? newSettings.rate_fee.toString() : ''
      parkingPeriod.value = newSettings.parking_period ?? ''
      overtimeFee.value = newSettings.overtime_fee !== null ? newSettings.overtime_fee.toString() : ''
      overnightFee.value = newSettings.overnight_fee !== null ? newSettings.overnight_fee.toString() : ''
      openingHour.value = newSettings.opening_hour ?? ''
      closingHour.value = newSettings.closing_hour ?? ''
    }
  },
  { immediate: true }
)


function handleSave() {
  router.post(route('parking.settings.store'), {
    rate_hour: rateHour.value ? parseInt(rateHour.value) : null,
    rate_minute: rateMinute.value ? parseInt (rateMinute.value) : null,
    rate_fee: rateFee.value ? parseFloat(rateFee.value) : null,
    
    overtime_fee: overtimeFee.value ? parseFloat(overtimeFee.value) : null,
    overnight_fee: overnightFee.value ? parseFloat(overnightFee.value) : null,
    parking_period: parkingPeriod.value || null,
    opening_hour: openingHour.value || null,
    closing_hour: closingHour.value || null,
=======
function handleSave() {
  isSaved.value = true
  router.post('/parking-settings', {
    rate_hour: rateHour.value,
    rate_minute: rateMinute.value,
    rate_fee: rateFee.value,
    parking_period: parkingPeriod.value,
    overtime_fee: overtimeFee.value.replace('₱','') || null,
    overnight_fee: overnightFee.value.replace('₱','') || null,
    opening_hour: openingHour.value,
    closing_hour: closingHour.value,
>>>>>>> Stashed changes
  }, {
    preserveScroll: true,
    onSuccess: () => {
<<<<<<< Updated upstream
      isSaved.value = true
      router.reload({ only: ['settings'] }) // refresh from DB
=======
>>>>>>> Stashed changes
      alert('Parking settings saved successfully!')
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}
function handleEdit() {
  isSaved.value = false
}
<<<<<<< Updated upstream
=======
function handleBack() {
  router.visit('/admin_Dashboard')
}
>>>>>>> Stashed changes
</script>


<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P :sidebarOpen="sidebarOpen" @close="closeSidebar"/>

<<<<<<< Updated upstream
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10 transition-all duration-300">
=======
    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
      <!-- Header -->
>>>>>>> Stashed changes
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Manage Parking</h1>
<<<<<<< Updated upstream
        </div>
      </header>

      <div class="h-1 w-full bg-gradient-to-r from-[#5F1213] via-[#FFA600] to-[#5F1213] rounded-full mb-6"></div>

      <div class="space-y-10 bg-white shadow-2xl rounded-2xl p-10 ">
        <!-- SET PARKING RATE -->
        <section class="styled-section bg-[#EFF6FF] ">
          <h3 class="section-title">SET PARKING RATE</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block mb-1">Hour</label>
              <input type="number" class="form-input" v-model="rateHour" :readonly="isSaved" />
            </div>
            <div>
              <label class="block mb-1">Minutes</label>
                <input type="number" class="form-input"  v-model="rateMinute" :readonly="isSaved" />
            </div>
            <div>
              <label class="block mb-1">Fee (₱)</label>
              <input type="number" step="0.01" class="form-input" v-model="rateFee" :readonly="isSaved" />
            </div>
          </div>
        </section>

        <!-- SET PARKING PERIOD -->
        <section class="styled-section bg-[#EFF6FF]">
          <h3 class="section-title">ADD-ON FEES</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
             <!--
          
            -->
            <div>
              <label class="block mb-1">Overtime Fee (₱)</label>
              <input type="number" step="0.01" class="form-input" v-model="overtimeFee" :readonly="isSaved" />
            </div>
            <div>
              <label class="block mb-1">Overnight Fee (₱)</label>
              <input type="number" step="0.01" class="form-input" v-model="overnightFee" :readonly="isSaved" />
=======
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
>>>>>>> Stashed changes
            </div>
          </div>
        </section>

<<<<<<< Updated upstream
        <!-- OPERATING HOURS -->
        <section class="styled-section bg-[#EFF6FF] ">
          <h3 class="section-title">OPERATING HOURS</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1">Opening Time</label>
              <input type="time" class="form-input" v-model="openingHour" :readonly="isSaved" />
            </div>
            <div>
              <label class="block mb-1">Closing Time</label>
              <input type="time" class="form-input" v-model="closingHour" :readonly="isSaved" />
            </div>
              <div>
              <label class="block mb-1">Daily POS Cutt-off Period</label>
              <input type="time" class="form-input" v-model="parkingPeriod" :readonly="isSaved" />
=======

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
>>>>>>> Stashed changes
            </div>
          </section>



<<<<<<< Updated upstream
        <!-- Buttons -->
        <div class="pt-6 flex justify-end space-x-4">
          <button @click="handleEdit" class="btn-primary">Edit</button>
          <button @click="handleSave" class="btn-success">Save</button>
=======
        <!-- Action Buttons -->
        <div class="pt-6 flex justify-end space-x-4">
          <button @click="handleEdit" class="btn-primary">Edit</button>
          <button @click="handleSave" class="btn-success">Save</button>
          <button @click="handleBack" class="btn-neutral">Back</button>
>>>>>>> Stashed changes
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
<<<<<<< Updated upstream
.styled-section {
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.section-title {
  font-size: 1.25rem;
  font-weight: bold;
=======
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
>>>>>>> Stashed changes
  margin-bottom: 1rem;
}
.form-input {
  border: 1px solid #d1d5db;
<<<<<<< Updated upstream
  background: #ffff;
  padding: 0.6rem;
  border-radius: 0.5rem;
  width: 100%;
}
=======
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

>>>>>>> Stashed changes
.btn-primary {
  background: #5F1213;
  color: white;
  padding: 0.6rem 1.5rem;
<<<<<<< Updated upstream
  border-radius: 0.5rem;
}
.btn-success {
  background-color: #FFA600;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
=======
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
>>>>>>> Stashed changes
}
</style>
