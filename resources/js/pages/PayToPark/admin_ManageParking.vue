<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'

const props = defineProps({
  settings: Object // passed from Laravel controller
})

// Sidebar toggle state
const sidebarOpen = ref(false)

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}

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
  }, {
    preserveScroll: true,
    onSuccess: () => {
      isSaved.value = true
      router.reload({ only: ['settings'] }) // refresh from DB
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
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P :sidebarOpen="sidebarOpen" @close="closeSidebar"/>

    <div class="flex-1 md:ml-64 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10 transition-all duration-300">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl hover:text-[#FFA600]" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="text-4xl font-extrabold text-[#5F1213]">Manage Parking</h1>
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
            </div>
          </div>
        </section>

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
            </div>
          </div>
        </section>

        <!-- Buttons -->
        <div class="pt-6 flex justify-end space-x-4">
          <button @click="handleEdit" class="btn-primary">Edit</button>
          <button @click="handleSave" class="btn-success">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.styled-section {
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.section-title {
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 1rem;
}
.form-input {
  border: 1px solid #d1d5db;
  background: #ffff;
  padding: 0.6rem;
  border-radius: 0.5rem;
  width: 100%;
}
.btn-primary {
  background: #5F1213;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
}
.btn-success {
  background-color: #FFA600;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
}
</style>
