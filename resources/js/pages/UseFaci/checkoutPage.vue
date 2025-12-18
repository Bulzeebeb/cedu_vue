<template>
  <div class="bg-gray-50 min-h-screen">
    <SiteHeader :booking-count="bookingCount" />

    <section class="py-8 md:py-12">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-3xl md:text-4xl font-bold text-maroon mb-2">
            Confirm Your Booking
          </h1>
          <p class="text-gray-600 text-sm md:text-base">
            Complete your facility booking by reviewing details and providing your information
          </p>
        </div>

        <!-- Progress Indicator -->
        <div class="flex items-center justify-between mb-8 text-xs md:text-sm">
          <div class="flex items-center">
            <div class="bg-maroon text-white rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center font-bold">1</div>
            <span class="ml-2 font-semibold text-maroon">Review</span>
          </div>
          <div class="flex-1 h-1 bg-maroon mx-2 md:mx-4"></div>
          <div class="flex items-center">
            <div class="bg-yellow-500 text-maroon rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center font-bold">2</div>
            <span class="ml-2 font-semibold text-gray-400">Confirmation</span>
          </div>
        </div>

        <form @submit.prevent="confirmBooking">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Forms -->
            <div class="lg:col-span-2 space-y-6">
              <!-- User Account Info -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                  </svg>
                  Account Information
                </h2>
                <div class="bg-gray-50 rounded p-4 border border-gray-100">
                  <p class="text-sm text-gray-600 mb-1">Email Address</p>
                  <p class="text-base font-medium text-gray-800">{{ user.email }}</p>
                </div>
              </div>

              <!-- Booking Details Information -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                  Booking Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">First Name *</label>
                    <input
                      v-model="booking.firstName"
                      type="text"
                      placeholder="Enter first name"
                      required
                      @blur="validateFirstName"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.firstName, 'border-gray-300': !errors.firstName}"
                    />
                    <p v-if="errors.firstName" class="text-xs text-red-600 mt-1">{{ errors.firstName }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Last Name *</label>
                    <input
                      v-model="booking.lastName"
                      type="text"
                      placeholder="Enter last name"
                      required
                      @blur="validateLastName"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.lastName, 'border-gray-300': !errors.lastName}"
                    />
                    <p v-if="errors.lastName" class="text-xs text-red-600 mt-1">{{ errors.lastName }}</p>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Contact Number *</label>
                  <input
                    v-model="booking.contact"
                    type="tel"
                    placeholder="09xxxxxxxxx"
                    maxlength="11"
                    required
                    @blur="validateContact"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    :class="{'border-red-500 bg-red-50': errors.contact, 'border-gray-300': !errors.contact}"
                  />
                  <p class="text-xs text-gray-500 mt-1">Format: 09123456789 (11 digits starting with 09)</p>
                  <p v-if="errors.contact" class="text-xs text-red-600 mt-1">{{ errors.contact }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Booking Date *</label>
                    <input
                      v-model="booking.date"
                      type="date"
                      :min="todayDate"
                      required
                      @blur="validateDate"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.date, 'border-gray-300': !errors.date}"
                    />
                    <p v-if="errors.date" class="text-xs text-red-600 mt-1">{{ errors.date }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Time *</label>
                    <input
                      v-model="booking.time"
                      type="time"
                      required
                      @blur="validateTime"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                      :class="{'border-red-500 bg-red-50': errors.time, 'border-gray-300': !errors.time}"
                    />
                    <p v-if="errors.time" class="text-xs text-red-600 mt-1">{{ errors.time }}</p>
                  </div>
                </div>
              </div>

              <!-- Purpose/Details -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"></path>
                    <path d="M12.075 12.075h4.85A2.075 2.075 0 0019 14.15v4.85a2.075 2.075 0 01-2.075 2.075h-4.85a2.075 2.075 0 01-2.075-2.075v-4.85a2.075 2.075 0 012.075-2.075z"></path>
                  </svg>
                  Booking Purpose & Notes
                </h2>
                <div class="mb-4">
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Purpose *</label>
                  <input
                    v-model="booking.purpose"
                    type="text"
                    placeholder="e.g., Meeting, Training, Event"
                    required
                    @blur="validatePurpose"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                    :class="{'border-red-500 bg-red-50': errors.purpose, 'border-gray-300': !errors.purpose}"
                  />
                  <p v-if="errors.purpose" class="text-xs text-red-600 mt-1">{{ errors.purpose }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Additional Notes (Optional)</label>
                  <textarea
                    v-model="booking.notes"
                    placeholder="Add any special requests or additional information..."
                    rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                  ></textarea>
                  <p class="text-xs text-gray-500 mt-1">Include any special requirements or requests</p>
                </div>
              </div>

              <!-- Payment Method -->
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-maroon mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                  Payment Method
                </h2>
                <div class="space-y-3">
                  <label class="flex items-center p-3 border rounded-lg cursor-pointer transition" :class="booking.paymentMethod === 'advance' ? 'bg-yellow-50 border-yellow-500' : 'border-gray-300 hover:border-gray-400'">
                    <input
                      v-model="booking.paymentMethod"
                      type="radio"
                      value="advance"
                      class="w-4 h-4 text-maroon"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                      Advance Payment
                    </span>
                    <span class="ml-auto text-xs text-gray-500">Pay now to confirm booking</span>
                  </label>
                  <label class="flex items-center p-3 border rounded-lg cursor-pointer transition" :class="booking.paymentMethod === 'onsite' ? 'bg-yellow-50 border-yellow-500' : 'border-gray-300 hover:border-gray-400'">
                    <input
                      v-model="booking.paymentMethod"
                      type="radio"
                      value="onsite"
                      class="w-4 h-4 text-maroon"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                      On-site Payment
                    </span>
                    <span class="ml-auto text-xs text-gray-500">Pay at CEDU office</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Right Column: Booking Summary -->
            <div class="lg:col-span-1">
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                <h2 class="text-lg font-bold text-maroon mb-4">Booking Summary</h2>

                <!-- Booking Details -->
                <div v-if="bookingItem" class="space-y-4 mb-6 pb-6 border-b">
                  <div v-if="bookingItem.image" class="rounded-lg overflow-hidden">
                    <img
                      :src="bookingItem.image"
                      :alt="bookingItem.name"
                      class="w-full h-40 object-cover"
                    />
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Facility</p>
                    <p class="text-base font-bold text-maroon">{{ bookingItem.name }}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Capacity</p>
                    <p class="text-sm text-gray-800">{{ bookingItem.capacity }} persons</p>
                  </div>
                </div>

                <!-- Pricing Summary -->
                <div v-if="bookingItem" class="space-y-3">
                  <div class="flex justify-between text-sm text-gray-600">
                    <span>Rate (per hour):</span>
                    <span>₱{{ bookingItem.rate }}</span>
                  </div>
                  <div class="flex justify-between text-sm text-gray-600">
                    <span>Duration:</span>
                    <span>{{ bookingItem.duration || '1' }} hour(s)</span>
                  </div>
                  <div v-if="discount > 0" class="flex justify-between text-sm text-green-600 font-medium">
                    <span>Discount:</span>
                    <span>-₱{{ discount }}</span>
                  </div>
                  <div class="flex justify-between text-base font-bold text-maroon pt-3 border-t">
                    <span>Total:</span>
                    <span>₱{{ totalAmount }}</span>
                  </div>
                </div>

                <div v-else class="py-8 text-center text-gray-500">
                  <p class="text-sm">No booking selected.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
            <button
              type="button"
              @click="goBack"
              class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors text-sm"
            >
              ← Back
            </button>

            <button
              type="submit"
              :disabled="isConfirming || !bookingItem || !isFormValid"
              class="px-6 py-3 rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-500 text-maroon font-bold hover:shadow-lg transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isConfirming" class="flex items-center justify-center">
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Processing...
              </span>
              <span v-else>Confirm Booking</span>
            </button>
          </div>
        </form>
      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'
import { router } from '@inertiajs/vue3'

// Props from Laravel
const props = defineProps({
  bookingItem: {
    type: Object,
    default: null
  },
  user: {
    type: Object,
    required: true
  },
  bookingCount: {
    type: Number,
    default: 0
  }
})

// State
const isConfirming = ref(false)

const booking = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: new Date().toISOString().substr(0, 10),
  time: '',
  purpose: '',
  notes: '',
  paymentMethod: 'advance'
})

const errors = ref({
  firstName: '',
  lastName: '',
  contact: '',
  date: '',
  time: '',
  purpose: ''
})

// Computed properties
const todayDate = computed(() => {
  return new Date().toISOString().substr(0, 10)
})

const discount = computed(() => {
  return '0.00'
})

const totalAmount = computed(() => {
  if (!props.bookingItem) return '0.00'
  const rate = parseFloat(props.bookingItem.rate || 0)
  const duration = parseInt(props.bookingItem.duration || 1)
  const disc = parseFloat(discount.value)
  return (rate * duration - disc).toFixed(2)
})

const isFormValid = computed(() => {
  return !Object.values(errors.value).some(err => err !== '')
})

// ...existing validation functions similar to OnlineMarket checkout...
function validateFirstName() {
  if (!booking.value.firstName.trim()) {
    errors.value.firstName = 'First name is required'
  } else if (booking.value.firstName.trim().length < 2) {
    errors.value.firstName = 'First name must be at least 2 characters'
  } else {
    errors.value.firstName = ''
  }
}

function validateLastName() {
  if (!booking.value.lastName.trim()) {
    errors.value.lastName = 'Last name is required'
  } else if (booking.value.lastName.trim().length < 2) {
    errors.value.lastName = 'Last name must be at least 2 characters'
  } else {
    errors.value.lastName = ''
  }
}

function validateContact() {
  const contactPattern = /^09\d{9}$/
  if (!booking.value.contact) {
    errors.value.contact = 'Contact number is required'
  } else if (!contactPattern.test(booking.value.contact)) {
    errors.value.contact = 'Invalid format. Use 09xxxxxxxxx (11 digits)'
  } else {
    errors.value.contact = ''
  }
}

function validateDate() {
  const selectedDate = new Date(booking.value.date)
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  if (!booking.value.date) {
    errors.value.date = 'Booking date is required'
  } else if (selectedDate < today) {
    errors.value.date = 'Cannot select past dates'
  } else {
    errors.value.date = ''
  }
}

function validateTime() {
  if (!booking.value.time) {
    errors.value.time = 'Time is required'
  } else {
    errors.value.time = ''
  }
}

function validatePurpose() {
  if (!booking.value.purpose.trim()) {
    errors.value.purpose = 'Purpose is required'
  } else if (booking.value.purpose.trim().length < 3) {
    errors.value.purpose = 'Purpose must be at least 3 characters'
  } else {
    errors.value.purpose = ''
  }
}

function validateAllFields() {
  validateFirstName()
  validateLastName()
  validateContact()
  validateDate()
  validateTime()
  validatePurpose()

  return !Object.values(errors.value).some(err => err !== '')
}

// Navigation
function goBack() {
  router.visit('/facilities')
}

function confirmBooking() {
  if (!validateAllFields()) {
    alert('Please correct the errors in the form.')
    return
  }

  if (!props.bookingItem) {
    alert('No facility selected.')
    return
  }

  isConfirming.value = true

  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

  // Submit booking to backend
  fetch('/booking/confirm', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': csrfToken || '',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
      firstName: booking.value.firstName,
      lastName: booking.value.lastName,
      contact: booking.value.contact,
      date: booking.value.date,
      time: booking.value.time,
      purpose: booking.value.purpose,
      notes: booking.value.notes,
      paymentMethod: booking.value.paymentMethod,
      facilityId: props.bookingItem?.id
    })
  })
    .then(response => {
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.error || `HTTP error! status: ${response.status}`)
        })
      }
      return response.json()
    })
    .then(data => {
      console.log('Booking confirmed successfully:', data)
      router.visit('/booking/success')
    })
    .catch(error => {
      console.error('Booking confirmation failed:', error)
      alert('Booking confirmation failed: ' + error.message + '. Please try again.')
    })
    .finally(() => {
      isConfirming.value = false
    })
}

// Initialize form
onMounted(() => {
  booking.value.date = todayDate.value

  if (props.user) {
    booking.value.firstName = props.user.firstName || props.user.first_name || ''
    booking.value.lastName = props.user.lastName || props.user.last_name || ''
    booking.value.contact = props.user.contactNum ||
                           props.user.contact_num ||
                           props.user.contact ||
                           props.user.phone ||
                           props.user.contact_number ||
                           ''
  }
})
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}

.from-yellow-400 {
  --tw-gradient-from: #FBBF24;
}

.to-yellow-500 {
  --tw-gradient-to: #EAB308;
}

::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
