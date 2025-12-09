<template>
  <div class="bg-white rounded-xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Booking Calendar</h3>
      <div class="flex items-center space-x-2">
        <button
          @click="previous"
          class="p-2 text-gray-600 hover:text-[#5F1213] transition-colors"
        >
          <i class="i-icon-park-outline-left text-xl"></i>
        </button>
        <button
          @click="today"
          class="bg-[#5F1213] text-white px-4 py-2 rounded-lg hover:bg-[#441011] transition-colors"
        >
          Today
        </button>
        <button
          @click="next"
          class="p-2 text-gray-600 hover:text-[#5F1213] transition-colors"
        >
          <i class="i-icon-park-outline-right text-xl"></i>
        </button>
      </div>
    </div>
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const props = defineProps({
  bookings: {
    type: Array,
    default: () => []
  },
  onDateSelect: {
    type: Function,
    default: () => {}
  },
  onEventClick: {
    type: Function,
    default: () => {}
  }
})

const calendarRef = ref(null)
const calendarApi = ref(null)

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: false, // We'll use custom header
  events: [],
  dateClick: handleDateClick,
  eventClick: handleEventClick,
  height: 'auto',
  contentHeight: 400,
  aspectRatio: 1.35,
  eventDisplay: 'block',
  dayMaxEvents: 3,
  moreLinkClick: 'popover'
})

function handleDateClick(info) {
  props.onDateSelect(info.date)
}

function handleEventClick(info) {
  props.onEventClick(info.event.extendedProps)
}

function previous() {
  if (calendarApi.value) {
    calendarApi.value.prev()
  }
}

function next() {
  if (calendarApi.value) {
    calendarApi.value.next()
  }
}

function today() {
  if (calendarApi.value) {
    calendarApi.value.today()
  }
}

async function fetchEvents() {
  try {
    const response = await fetch('/use/bookings-calendar', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    const events = await response.json()
    calendarOptions.value.events = events
  } catch (error) {
    console.error('Error fetching calendar events:', error)
  }
}

onMounted(() => {
  if (calendarRef.value) {
    calendarApi.value = calendarRef.value.getApi()
    fetchEvents()
  }
})
</script>
