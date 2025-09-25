<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">

      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border flex items-center space-x-4">
        <i class="fas fa-building text-3xl text-[#FFA600]"></i>
        <h1 class="text-2xl font-semibold">Facilities</h1>
      </div>

      <!-- Add Button -->
      <div class="mb-4 flex justify-end">
        <button
          @click="openAddModal"
          class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition"
        >
          + Add Facility
        </button>
      </div>

      <!-- Facilities Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Available Facilities</h2>
        <table class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-3">ID</th>
              <th class="py-2 px-3">Name</th>
              <th class="py-2 px-3">Category</th>
              <th class="py-2 px-3">Details</th>
              <th class="py-2 px-3">Images</th>
              <th class="py-2 px-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="facility in facilities"
              :key="facility.id"
              class="border-b border-gray-100 hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ facility.id }}</td>
              <td class="py-2 px-3">{{ facility.name }}</td>
              <td class="py-2 px-3">{{ facility.category }}</td>
              <td class="py-2 px-3">
                <div v-for="(val, key) in facility.details" :key="key">
                  <strong>{{ key }}:</strong> {{ val }}
                </div>
                <div v-if="facility.details['Price/Rate']">
                  <strong>Rate:</strong>
                  {{ facility.details['Price/Rate'] }} / {{ facility.details['Unit'] }}
                </div>
              </td>
              <td class="py-2 px-3">
                <div class="flex space-x-2">
                  <img
                    v-for="(img, i) in facility.images"
                    :key="i"
                    :src="img"
                    class="w-12 h-12 object-cover rounded border"
                  />
                </div>
              </td>
              <td class="py-2 px-3 space-x-2">
                <button
                  class="px-3 py-1 bg-[#FFA600] text-white rounded hover:bg-[#e69500] transition"
                  @click="editFacility(facility)"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                  @click="deleteFacility(facility.id)"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Facility Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
          <!-- Close Button -->
          <button
            @click="closeModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
          >
            <i class="fas fa-times text-lg"></i>
          </button>

          <h2 class="text-xl font-semibold mb-4">
            {{ isEditing ? 'Edit Facility' : 'Add New Facility' }}
          </h2>
          <form @submit.prevent="isEditing ? updateFacility() : addFacility()" class="space-y-4">
            
            <!-- Facility Name -->
            <div>
              <label class="block text-sm font-medium mb-1">Facility Name</label>
              <input
                v-model="newFacility.name"
                type="text"
                placeholder="Enter facility name"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                required
              />
            </div>

            <!-- Category -->
            <div>
              <label class="block text-sm font-medium mb-1">Category</label>
              <select
                v-model="newFacility.category"
                @change="loadCategoryFields"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                required
              >
                <option disabled value="">Select Category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
              </select>
            </div>

            <!-- Dynamic Fields -->
            <div v-if="selectedCategory">
              <h3 class="text-md font-semibold mb-2">Details</h3>

              <div v-for="field in selectedCategory.requiredFields" :key="field">
                <label class="block text-sm font-medium mb-1">{{ field }}</label>
                
                <!-- If field is Price/Rate -->
                <div v-if="field === 'Price/Rate'" class="flex space-x-2">
                  <input
                    v-model="newFacility.details['Price/Rate']"
                    type="number"
                    placeholder="Enter price"
                    class="w-1/2 border rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  />
                  <select
                    v-model="newFacility.details['Unit']"
                    class="w-1/2 border rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  >
                    <option value="per night">Per Night</option>
                    <option value="per day">Per Day</option>
                    <option value="per month">Per Month</option>
                  </select>
                </div>

                <!-- Other fields -->
                <input
                  v-else
                  v-model="newFacility.details[field]"
                  type="text"
                  :placeholder="`Enter ${field}`"
                  class="w-full border rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                  required
                />
              </div>
            </div>

            <!-- Upload Images -->
            <div>
              <label class="block text-sm font-medium mb-1">Upload Images (max 3)</label>
              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleImageUpload"
                class="w-full border rounded-lg px-3 py-2"
              />
              <div class="flex mt-2 space-x-2">
                <img
                  v-for="(img, i) in newFacility.images"
                  :key="i"
                  :src="img"
                  class="w-20 h-20 object-cover rounded border"
                />
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition"
              >
                {{ isEditing ? 'Update' : 'Save' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminSidebar from './adminSidebar.vue'

// Categories with Price/Rate + Unit
const categories = ref([
  {
    id: 1,
    name: 'Rental',
    requiredFields: ['Unit Name', 'Price/Rate', 'Capacity', 'Utilities']
  },
  {
    id: 2,
    name: 'Hostel',
    requiredFields: ['Room Name', 'Price/Rate', 'Capacity', 'Bed Type', 'Ventilation', 'Restroom']
  },
  {
    id: 3,
    name: 'Commercial',
    requiredFields: ['Hall Name', 'Price/Rate', 'Capacity']
  }
])

const showModal = ref(false)
const isEditing = ref(false)
const editId = ref(null)

const facilities = ref([
  {
    id: 1,
    name: 'Aircon Room',
    category: 'Hostel',
    details: {
      'Room Name': 'Aircon Room',
      'Price/Rate': 1500,
      'Unit': 'per night',
      'Capacity': '2',
      'Bed Type': 'Single',
      'Ventilation': 'Air-conditioned',
      'Restroom': 'Private'
    },
    images: []
  },
  {
    id: 2,
    name: 'Conference Hall',
    category: 'Commercial',
    details: {
      'Hall Name': 'Main Hall',
      'Price/Rate': 5000,
      'Unit': 'per day',
      'Capacity': '50'
    },
    images: []
  }
])

const newFacility = ref({
  name: '',
  category: '',
  details: {},
  images: []
})

const selectedCategory = ref(null)

function openAddModal() {
  resetForm()
  showModal.value = true
}

function closeModal() {
  resetForm()
  showModal.value = false
}

function loadCategoryFields() {
  selectedCategory.value = categories.value.find(c => c.name === newFacility.value.category)
  if (selectedCategory.value) {
    newFacility.value.details = {}
    selectedCategory.value.requiredFields.forEach(field => {
      if (field === 'Price/Rate') {
        newFacility.value.details['Price/Rate'] = ''
        newFacility.value.details['Unit'] = 'per night'
      } else {
        newFacility.value.details[field] = ''
      }
    })
  }
}

function handleImageUpload(e) {
  const files = Array.from(e.target.files)
  if (files.length > 3) {
    alert('You can only upload up to 3 images')
    return
  }
  newFacility.value.images = files.map(file => URL.createObjectURL(file))
}

function addFacility() {
  facilities.value.push({
    id: facilities.value.length + 1,
    ...newFacility.value
  })
  closeModal()
}

function editFacility(fac) {
  newFacility.value = JSON.parse(JSON.stringify(fac))
  selectedCategory.value = categories.value.find(c => c.name === newFacility.value.category)
  editId.value = fac.id
  isEditing.value = true
  showModal.value = true
}

function updateFacility() {
  const index = facilities.value.findIndex(f => f.id === editId.value)
  if (index !== -1) {
    facilities.value[index] = { ...newFacility.value, id: editId.value }
  }
  closeModal()
}

function deleteFacility(id) {
  facilities.value = facilities.value.filter(f => f.id !== id)
}

function resetForm() {
  newFacility.value = { name: '', category: '', details: {}, images: [] }
  selectedCategory.value = null
  editId.value = null
  isEditing.value = false
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
