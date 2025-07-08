<template>
  <div class="min-h-screen flex flex-col bg-white">
    <!-- Top Nav -->
    <header class="bg-maroon text-white py-2 flex justify-between items-center px-6">
      <div class="flex items-center gap-2">
        <img src="/logo.png" alt="Logo" class="w-10 h-10" />
        <span class="text-xl font-bold">CEDU iCentral</span>
      </div>
      <div class="flex items-center gap-4">
        <input type="text" placeholder="Search" class="px-3 py-1 rounded border" />
        <button class="bg-yellow-400 text-white px-3 py-1 rounded">Search</button>
        <button class="text-white text-2xl">🔔</button>
        <button class="w-8 h-8 rounded-full bg-red-100"></button>
      </div>
    </header>

    <!-- Form -->
    <main class="flex-grow px-6 py-10">
      <h2 class="text-yellow-500 text-2xl font-bold mb-6">Add New Client</h2>
      <div class="bg-gray-100 p-6 rounded-lg w-full max-w-3xl">
        <form class="space-y-4">
          <div>
            <label class="block font-medium mb-1">Client's First Name</label>
            <input
              v-model="firstName"
              type="text"
              class="w-full border rounded px-3 py-2"
            />
          </div>

          <div>
            <label class="block font-medium mb-1">Client's Last Name</label>
            <input
              v-model="lastName"
              type="text"
              class="w-full border rounded px-3 py-2"
            />
          </div>

          <div>
            <label class="block font-medium mb-1">Client's Plate Number</label>
            <input
              v-model="plateNumber"
              type="text"
              class="w-full border rounded px-3 py-2"
            />
          </div>

          <div class="flex gap-4 items-end">
            <div class="flex-1">
              <label class="block font-medium mb-1">Date</label>
              <input type="date" v-model="date" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block font-medium mb-1">Time</label>
              <div class="flex items-center gap-1">
                <input
                  type="number"
                  min="1"
                  max="12"
                  v-model="timeHour"
                  class="w-16 border rounded px-2 py-1 text-center"
                />
                :
                <input
                  type="number"
                  min="0"
                  max="59"
                  v-model="timeMinute"
                  class="w-16 border rounded px-2 py-1 text-center"
                />
                <select v-model="timePeriod" class="border rounded px-2 py-1">
                  <option>AM</option>
                  <option>PM</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex gap-4 mt-6">
            <button
              type="button"
              class="bg-maroon text-white px-6 py-2 rounded hover:opacity-90"
              @click="addClient"
            >
              ADD CLIENT
            </button>
            <button
              type="reset"
              class="bg-yellow-400 text-white px-6 py-2 rounded hover:opacity-90"
              @click="clearForm"
            >
              CLEAR
            </button>
          </div>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-maroon text-white text-sm py-6 px-6 mt-10">
      <div class="grid grid-cols-3 gap-8">
        <div>
          <h3 class="text-yellow-400 font-semibold">Support</h3>
          <p>University of Southeastern Philippines, Tagum-Mabini Campus, Tagum Unit, CEDU Office, Apokon, Tagum City</p>
          <p>cedu@usep.edu.ph</p>
          <p>+88015-88888-9999</p>
        </div>
        <div>
          <h3 class="text-yellow-400 font-semibold">Account</h3>
          <p>My Account</p>
          <p>Login / Register</p>
        </div>
        <div>
          <h3 class="text-yellow-400 font-semibold">Quick Link</h3>
          <p>Privacy Policy</p>
          <p>Terms Of Use</p>
          <p>FAQ</p>
          <p>Contact</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const firstName = ref('')
const lastName = ref('')
const plateNumber = ref('')
const date = ref('')
const timeHour = ref('1')
const timeMinute = ref('00')
const timePeriod = ref('AM')

function addClient() {
  alert(`Client added: ${firstName.value} ${lastName.value}`)
}

function clearForm() {
  firstName.value = ''
  lastName.value = ''
  plateNumber.value = ''
  date.value = ''
  timeHour.value = '1'
  timeMinute.value = '00'
  timePeriod.value = 'AM'
}
</script>

<style scoped>
.bg-maroon {
  background-color: #5d0d0d;
}
</style>
<script>
export default {
  name: "billing_checkout"
}
</script>