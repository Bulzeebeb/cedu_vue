<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebarP2P from './adminSidebarP2P.vue'

const sidebarOpen = ref(false)
const isSaved = ref(true)
const isLoading = ref(false)
const notification = ref({ show: false, message: '', type: 'success' })

// Form data
const rateHour = ref(3)           
const rateMinute = ref('00')      
const rateFee = ref('20')         

const parkingPeriod = ref('8 hrs')
const overtimeFee = ref('50')
const overnightFee = ref('100')

const openingHour = ref('06:00')
const closingHour = ref('22:00')

// Original values for reset
const originalValues = {
  rateHour: 3,
  rateMinute: '00',
  rateFee: '20',
  parkingPeriod: '8 hrs',
  overtimeFee: '50',
  overnightFee: '100',
  openingHour: '06:00',
  closingHour: '22:00'
}

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}

function showNotification(message, type = 'success') {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 4000)
}

function validateForm() {
  if (!rateHour.value || rateHour.value <= 0) {
    showNotification('Please enter a valid hour rate', 'error')
    return false
  }
  if (!rateFee.value || rateFee.value <= 0) {
    showNotification('Please enter a valid fee', 'error')
    return false
  }
  if (!parkingPeriod.value) {
    showNotification('Please enter parking period', 'error')
    return false
  }
  if (openingHour.value >= closingHour.value) {
    showNotification('Opening time must be before closing time', 'error')
    return false
  }
  if (overtimeFee.value && overtimeFee.value <= 0) {
    showNotification('Overtime fee must be greater than 0', 'error')
    return false
  }
  if (overnightFee.value && overnightFee.value <= 0) {
    showNotification('Overnight fee must be greater than 0', 'error')
    return false
  }
  return true
}

function handleSave() {
  if (!validateForm()) return

  if (!confirm('Are you sure you want to save these parking settings?')) {
    return
  }

  isLoading.value = true

  router.post('/parking-settings', {
    rate_hour: rateHour.value,
    rate_minute: rateMinute.value,
    rate_fee: rateFee.value,
    parking_period: parkingPeriod.value,
    overtime_fee: overtimeFee.value ? overtimeFee.value.toString().replace('₱','').trim() : null,
    overnight_fee: overnightFee.value ? overnightFee.value.toString().replace('₱','').trim() : null,
    opening_hour: openingHour.value,
    closing_hour: closingHour.value,
  }, {
    onSuccess: () => {
      isSaved.value = true
      isLoading.value = false
      showNotification('Parking settings saved successfully!', 'success')
    },
    onError: (errors) => {
      isLoading.value = false
      showNotification('Failed to save settings. Please try again.', 'error')
      console.error(errors)
    }
  })
}

function handleEdit() {
  isSaved.value = false
}

function handleReset() {
  if (!confirm('Are you sure you want to reset all changes?')) {
    return
  }
  rateHour.value = originalValues.rateHour
  rateMinute.value = originalValues.rateMinute
  rateFee.value = originalValues.rateFee
  parkingPeriod.value = originalValues.parkingPeriod
  overtimeFee.value = originalValues.overtimeFee
  overnightFee.value = originalValues.overnightFee
  openingHour.value = originalValues.openingHour
  closingHour.value = originalValues.closingHour
  isSaved.value = true
  showNotification('All changes have been reset', 'info')
}

function handleBack() {
  if (!isSaved.value) {
    if (confirm('You have unsaved changes. Do you want to leave without saving?')) {
      router.visit('/admin_Dashboard')
    }
  } else {
    router.visit('/admin_Dashboard')
  }
}

function formatCurrency(event) {
  let value = event.target.value.replace(/[^\d]/g, '')
  if (value) {
    event.target.value = '₱' + value
  }
}
</script>

<template>
  <div class="min-h-screen flex font-sans text-[#5F1213]">
    <AdminSidebarP2P />

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
      <!-- Header Section -->
      <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
        <div class="p-6 md:p-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <button class="md:hidden text-2xl hover:text-[#FFA600] transition-colors" @click="toggleSidebar">
                <i class="fas fa-bars"></i>
              </button>
              <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#5F1213]">Manage Parking</h1>
                <p class="text-sm text-gray-500 mt-1">Configure parking rates and operational hours</p>
              </div>
            </div>
            <div class="hidden md:block">
              <span :class="['px-3 py-1 rounded-full text-xs font-semibold', isSaved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">
                {{ isSaved ? '✓ Saved' : '● Unsaved Changes' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Notification -->
      <transition name="slide-down">
        <div v-if="notification.show" :class="['mx-6 mt-6 p-4 rounded-lg flex items-center gap-3 shadow-md', 
          notification.type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' :
          notification.type === 'error' ? 'bg-red-100 text-red-800 border border-red-300' :
          'bg-blue-100 text-blue-800 border border-blue-300']">
          <i :class="['fas', notification.type === 'success' ? 'fa-check-circle' : notification.type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle']"></i>
          <span class="font-medium">{{ notification.message }}</span>
        </div>
      </transition>

      <!-- Main Content -->
      <div class="p-6 md:p-8 space-y-8 max-w-6xl mx-auto">

        <!-- SET PARKING RATE -->
        <section class="bg-white rounded-xl shadow-md border-l-4 border-[#FFA600] overflow-hidden hover:shadow-lg transition-shadow">
          <div class="bg-gradient-to-r from-[#FFA600] to-orange-400 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
              <i class="fas fa-dollar-sign"></i> SET PARKING RATE
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Configure the base parking rate charged to users</p>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-clock text-[#FFA600] mr-2"></i>Hour
                </label>
                <input
                  type="number"
                  min="0"
                  step="1"
                  class="form-input"
                  v-model.number="rateHour"
                  placeholder="3"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-hourglass-half text-[#FFA600] mr-2"></i>Minutes
                </label>
                <select
                  class="form-input"
                  v-model="rateMinute"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                >
                  <option value="00">00</option>
                  <option value="15">15</option>
                  <option value="30">30</option>
                  <option value="45">45</option>
                </select>
              </div>
              <div class="form-group md:col-span-2">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-peso-sign text-[#FFA600] mr-2"></i>Fee
                </label>
                <input
                  type="number"
                  min="0"
                  step="0.01"
                  class="form-input"
                  v-model.number="rateFee"
                  placeholder="20.00"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
            </div>
          </div>
        </section>

        <!-- SET PARKING PERIOD -->
        <section class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 overflow-hidden hover:shadow-lg transition-shadow">
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
              <i class="fas fa-calendar-days"></i> SET PARKING PERIOD
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Configure parking durations and additional fees</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-parking text-blue-500 mr-2"></i>Allowed Duration
                </label>
                <input
                  type="text"
                  class="form-input"
                  v-model="parkingPeriod"
                  placeholder="e.g., 8 hrs"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-hourglass-end text-orange-500 mr-2"></i>Overtime Fee
                </label>
                <input
                  type="number"
                  min="0"
                  step="0.01"
                  class="form-input"
                  v-model.number="overtimeFee"
                  placeholder="50.00"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-moon text-indigo-500 mr-2"></i>Overnight Fee
                </label>
                <input
                  type="number"
                  min="0"
                  step="0.01"
                  class="form-input"
                  v-model.number="overnightFee"
                  placeholder="100.00"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
            </div>
          </div>
        </section>

        <!-- OPERATING HOURS -->
        <section class="bg-white rounded-xl shadow-md border-l-4 border-green-500 overflow-hidden hover:shadow-lg transition-shadow">
          <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
              <i class="fas fa-clock"></i> OPERATING HOURS
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Set the parking facility's daily operating schedule</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-door-open text-green-500 mr-2"></i>Opening Time
                </label>
                <input
                  type="time"
                  class="form-input"
                  v-model="openingHour"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
              <div class="form-group">
                <label class="block mb-2 text-sm font-semibold text-[#5F1213]">
                  <i class="fas fa-door-closed text-red-500 mr-2"></i>Closing Time
                </label>
                <input
                  type="time"
                  class="form-input"
                  v-model="closingHour"
                  :disabled="isSaved"
                  :class="{ 'opacity-60 cursor-not-allowed': isSaved }"
                />
              </div>
            </div>
          </div>
        </section>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pb-4">
          <button 
            @click="handleBack" 
            class="btn btn-neutral"
            :disabled="isLoading"
          >
            <i class="fas fa-arrow-left mr-2"></i>Back
          </button>
          <button 
            @click="handleReset" 
            class="btn btn-secondary"
            :disabled="isSaved || isLoading"
          >
            <i class="fas fa-redo mr-2"></i>Reset
          </button>
          <button 
            @click="handleEdit" 
            class="btn btn-primary"
            :disabled="!isSaved || isLoading"
          >
            <i class="fas fa-edit mr-2"></i>Edit
          </button>
          <button 
            @click="handleSave" 
            class="btn btn-success"
            :disabled="isSaved || isLoading"
          >
            <i :class="['fas', isLoading ? 'fa-spinner fa-spin' : 'fa-check']"></i>
            <span class="ml-2">{{ isLoading ? 'Saving...' : 'Save' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.form-group {
  display: flex;
  flex-direction: column;
}

.form-input {
  border: 2px solid #e5e7eb;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  width: 100%;
  transition: all 0.2s ease;
  color: #1f2937;
  font-size: 0.95rem;
  background-color: #f9fafb;
}

.form-input:hover:not(:disabled) {
  border-color: #d1d5db;
}

.form-input:focus {
  outline: none;
  border-color: #5F1213;
  box-shadow: 0 0 0 3px rgba(95, 18, 19, 0.1);
  background-color: white;
}

.form-input:disabled {
  background-color: #f3f4f6;
  color: #9ca3af;
  cursor: not-allowed;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  border-radius: 0.75rem;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  font-size: 0.95rem;
  gap: 0.5rem;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: linear-gradient(135deg, #5F1213 0%, #7d1a1c 100%);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #FFA600 0%, #ffb84d 100%);
  color: #5F1213;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(255, 166, 0, 0.3);
}

.btn-success {
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: white;
}

.btn-success:hover:not(:disabled) {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.btn-secondary {
  background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
}

.btn-neutral {
  background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
  color: white;
}

.btn-neutral:hover:not(:disabled) {
  background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}

.slide-down-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}

.slide-down-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>


