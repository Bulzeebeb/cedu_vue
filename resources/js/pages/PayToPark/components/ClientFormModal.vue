<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex justify-center items-center"
  >
    <div class="bg-white rounded-xl p-6 w-full max-w-2xl relative">
      <!-- Close Button -->
      <button
        @click="closeModal"
        class="absolute top-2 right-2 text-gray-500 hover:text-red-600"
        aria-label="Close modal"
      >
        <i class="fas fa-times"></i>
      </button>

      <h2 class="text-lg font-semibold text-yellow-600 mb-4">Add New Client</h2>

      <form @submit.prevent="addClient" novalidate>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1" for="firstName">First Name</label>
            <input
              id="firstName"
              v-model="client.firstName"
              type="text"
              class="w-full border px-3 py-2 rounded"
              required
              autocomplete="given-name"
            />
          </div>
          <div>
            <label class="block text-sm mb-1" for="lastName">Last Name</label>
            <input
              id="lastName"
              v-model="client.lastName"
              type="text"
              class="w-full border px-3 py-2 rounded"
              required
              autocomplete="family-name"
            />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm mb-1" for="plateNumber">Plate Number</label>
            <input
              id="plateNumber"
              v-model="client.plateNumber"
              type="text"
              class="w-full border px-3 py-2 rounded"
              required
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-sm mb-1" for="date">Date</label>
            <input
              id="date"
              v-model="client.date"
              type="date"
              class="w-full border px-3 py-2 rounded bg-gray-100 cursor-not-allowed"
              disabled
            />
          </div>
          <div>
            <label class="block text-sm mb-1" for="time">Time</label>
            <input
              id="time"
              v-model="client.time"
              type="time"
              class="w-full border px-3 py-2 rounded bg-gray-100 cursor-not-allowed"
              disabled
            />
          </div>
          <div>
            <button
              type="submit"
              class="w-full bg-maroon text-white py-2 rounded hover:bg-red-800 mt-2"
            >
              ADD CLIENT
            </button>
          </div>
          <div>
            <button
              type="button"
              @click="clearForm"
              class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 mt-2"
            >
              CLEAR
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, reactive, watch } from "vue";
import axios from "axios";

const props = defineProps({ show: Boolean });
const emit = defineEmits(["update:show"]);

const client = reactive({
  firstName: "",
  lastName: "",
  plateNumber: "",
  date: "",
  time: "",
});

// Function to set the current date and time
function setCurrentDateTime() {
  const now = new Date();
  client.date = now.toISOString().split("T")[0];
  client.time = now.toTimeString().slice(0, 5);
}

// Watch modal visibility to update time on open
watch(
  () => props.show,
  (newVal) => {
    if (newVal) setCurrentDateTime();
  }
);

// Clear form fields and reset date/time
function clearForm() {
  client.firstName = "";
  client.lastName = "";
  client.plateNumber = "";
  setCurrentDateTime();
}

// Add new client handler
async function addClient() {
  if (
    !client.firstName ||
    !client.lastName ||
    !client.plateNumber
  ) {
    alert("Please fill in all required fields.");
    return;
  }

  const name = `${client.firstName} ${client.lastName}`.trim();
  const timeIn = new Date(`${client.date}T${client.time}:00`);

  if (isNaN(timeIn)) {
    alert("Invalid date or time.");
    return;
  }

  try {
    await axios.post("/paytopark/clients", {
      name,
      plate: client.plateNumber,
      time_in: timeIn.toISOString(),
      status: "PENDING",
    });

    alert("Client successfully added!");
    clearForm();
    emit("update:show", false);
  } catch (error) {
    console.error("Error adding client:", error);
    alert(error.response?.data?.message || "Failed to add client. Check console.");
  }
}

// Close modal
function closeModal() {
  emit("update:show", false);
}
</script>


<style scoped>
.bg-maroon {
  background-color: #650000;
}
</style>
