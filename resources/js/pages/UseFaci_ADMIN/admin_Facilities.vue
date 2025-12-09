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

      <!-- Search, Filter and Actions Row -->
      <div class="mb-4 space-y-4">
        <!-- Search and Filters -->
        <div class="flex flex-wrap gap-4 items-center">
          <!-- Search -->
          <div class="flex-1 min-w-[200px]">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search facilities by name..."
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFA600] bg-white"
            />
          </div>

          <!-- Filters -->
          <select
            v-model="filterCategory"
            @change="resetPagination"
            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFA600] bg-white"
          >
            <option value="">All Categories</option>
            <option v-for="cat in allCategories" :key="cat.id" :value="cat.id">
              {{ cat.name }}
            </option>
          </select>

          <select
            v-model="filterStatus"
            @change="resetPagination"
            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFA600] bg-white"
          >
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>

          <!-- Add Button -->
          <button
            @click="openAddModal"
            class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition"
          >
            + Add Facility
          </button>
        </div>

        <!-- Bulk Actions (only show when items are selected) -->
        <div v-if="selectedFacilities.length > 0" class="flex items-center gap-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
          <span class="text-sm font-medium text-blue-800">
            {{ selectedFacilities.length }} facility(ies) selected
          </span>
          <div class="flex gap-2">
            <button
              @click="bulkToggleStatus(1)"
              class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition"
            >
              <i class="fas fa-play mr-1"></i>Activate
            </button>
            <button
              @click="bulkToggleStatus(0)"
              class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 transition"
            >
              <i class="fas fa-pause mr-1"></i>Deactivate
            </button>
            <button
              @click="bulkDeleteFacilities"
              class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition"
            >
              <i class="fas fa-trash mr-1"></i>Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Facilities Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Available Facilities</h2>

        <!-- No data message -->
        <div v-if="!loading && totalItems === 0" class="text-center py-8 text-gray-500">
          <i class="fas fa-inbox text-4xl mb-2"></i>
          <p>No facilities found. Click "Add Facility" to create one.</p>
        </div>

        <!-- Table -->
        <table v-else class="w-full text-sm text-left border-collapse">
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
              v-for="facility in paginatedFacilities"
              :key="facility.id"
              class="border-b border-gray-100 hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ facility.id }}</td>
              <td class="py-2 px-3">{{ facility.name }}</td>
              <td class="py-2 px-3">
                <span :class="facility.category?.is_active ? '' : 'text-red-600'">
                  {{ facility.category?.name }}
                  <span v-if="!facility.category?.is_active" class="text-xs">(Disabled)</span>
                </span>
              </td>
              <td class="py-2 px-3">
                <div v-for="(val, key) in facility.details" :key="key" class="text-xs">
                  <strong>{{ key }}:</strong> {{ val }}
                </div>
              </td>
              <td class="py-2 px-3">
                <div class="flex space-x-2">
                  <img
                    v-for="(img, i) in facility.images"
                    :key="i"
                    :src="getImageUrl(img)"
                    class="w-12 h-12 object-cover rounded border"
                  />
                  <span v-if="!facility.images || facility.images.length === 0" class="text-gray-400 text-xs">
                    No images
                  </span>
                </div>
              </td>
              <td class="py-2 px-3 space-x-2">
                <button
                  class="px-3 py-1 bg-[#FFA600] text-white rounded hover:bg-[#e69500] transition disabled:opacity-50 disabled:cursor-not-allowed"
                  @click="editFacility(facility)"
                  :disabled="!facility.category?.is_active"
                  :title="!facility.category?.is_active ? 'Category is disabled' : 'Edit facility'"
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

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex items-center justify-between mt-4">
          <div class="text-sm text-gray-700">
            Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, totalItems) }} of {{ totalItems }} facilities
          </div>
          <div class="flex items-center space-x-2">
            <button
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Previous
            </button>

            <button
              v-for="page in visiblePages"
              :key="page"
              @click="typeof page === 'number' ? goToPage(page) : null"
              :class="[
                'px-3 py-1 text-sm border rounded hover:bg-gray-50',
                page === currentPage ? 'bg-[#5F1213] text-white border-[#5F1213]' : '',
                typeof page !== 'number' ? 'cursor-default' : ''
              ]"
              :disabled="typeof page !== 'number'"
            >
              {{ page }}
            </button>

            <button
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Add/Edit Facility Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative max-h-[90vh] overflow-y-auto">
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
          <div class="space-y-4">

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
                v-model="newFacility.category_id"
                @change="loadCategoryFields"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                required
              >
                <option disabled value="">Select Category</option>
                <option v-for="cat in activeCategories" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
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
                    step="0.01"
                    placeholder="Enter price"
                    class="w-1/2 border rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                    required
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
              <label class="block text-sm font-medium mb-1">Upload Images (max 3, up to 5MB each)</label>
              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleImageUpload"
                ref="fileInput"
                class="w-full border rounded-lg px-3 py-2"
              />
              <p class="text-xs text-gray-500 mt-1">Images will be automatically compressed</p>
              <div class="flex mt-2 space-x-2">
                <div v-for="(img, i) in previewImages" :key="i" class="relative">
                  <img
                    :src="img"
                    class="w-20 h-20 object-cover rounded border"
                  />
                  <button
                    type="button"
                    @click="removeImage(i)"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs"
                  >
                    ×
                  </button>
                </div>
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
                type="button"
                @click="isEditing ? updateFacility() : addFacility()"
                :disabled="loading"
                class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition disabled:opacity-50"
              >
                {{ loading ? 'Saving...' : (isEditing ? 'Update' : 'Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading Overlay -->
      <div v-if="loading" class="fixed inset-0 bg-black/20 flex items-center justify-center z-40">
        <div class="bg-white rounded-lg p-4 shadow-lg">
          <i class="fas fa-spinner fa-spin text-2xl text-[#5F1213]"></i>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminSidebar from './adminSidebar.vue'
import axios from 'axios'
import Swal from 'sweetalert2'

// Configure axios
axios.defaults.baseURL = 'http://127.0.0.1:8000'
axios.defaults.headers.common['Accept'] = 'application/json'

const API_URL = 'facilities'
const CATEGORY_URL = 'categories'

const showModal = ref(false)
const isEditing = ref(false)
const editId = ref(null)
const loading = ref(false)
const fileInput = ref(null)

const facilities = ref([])
const allCategories = ref([])
const activeCategories = ref([])
const filterCategory = ref('')
const filterStatus = ref('')

// New reactive variables for enhanced functionality
const searchQuery = ref('')
const sortBy = ref('id')
const sortOrder = ref('asc')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const selectedFacilities = ref([])
const selectAll = ref(false)

const newFacility = ref({
  name: '',
  category_id: '',
  details: {},
  images: []
})

const previewImages = ref([])
const selectedCategory = ref(null)

// Computed properties for enhanced functionality
const filteredFacilities = computed(() => {
  let filtered = facilities.value

  // Apply search filter
  if (searchQuery.value) {
    filtered = filtered.filter(facility =>
      facility.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  // Apply category filter
  if (filterCategory.value) {
    filtered = filtered.filter(facility => facility.category_id == filterCategory.value)
  }

  // Apply status filter
  if (filterStatus.value !== '') {
    filtered = filtered.filter(facility => facility.is_active == filterStatus.value)
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value]
    let bVal = b[sortBy.value]

    if (sortBy.value === 'category') {
      aVal = a.category?.name || ''
      bVal = b.category?.name || ''
    }

    if (typeof aVal === 'string') {
      aVal = aVal.toLowerCase()
      bVal = bVal.toLowerCase()
    }

    if (sortOrder.value === 'asc') {
      return aVal > bVal ? 1 : -1
    } else {
      return aVal < bVal ? 1 : -1
    }
  })

  return filtered
})

const paginatedFacilities = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredFacilities.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredFacilities.value.length / itemsPerPage.value)
})

const totalItems = computed(() => filteredFacilities.value.length)

// Category field definitions
const categoryFields = {
  'Rental': ['Unit Name', 'Price/Rate', 'Capacity', 'Utilities'],
  'Hostel': ['Room Name', 'Price/Rate', 'Capacity', 'Bed Type', 'Ventilation', 'Restroom'],
  'Commercial': ['Hall Name', 'Price/Rate', 'Capacity']
}

// Image compression function
async function compressImage(file, maxSizeMB = 2) {
  return new Promise((resolve) => {
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = (event) => {
      const img = new Image()
      img.src = event.target.result
      img.onload = () => {
        const canvas = document.createElement('canvas')
        let width = img.width
        let height = img.height

        // Max width 1920px
        const maxWidth = 1920
        if (width > maxWidth) {
          height = (height * maxWidth) / width
          width = maxWidth
        }

        canvas.width = width
        canvas.height = height
        const ctx = canvas.getContext('2d')
        ctx.drawImage(img, 0, 0, width, height)

        canvas.toBlob(
          (blob) => {
            resolve(new File([blob], file.name, { type: 'image/jpeg' }))
          },
          'image/jpeg',
          0.8 // 80% quality
        )
      }
    }
  })
}

// Fetch all categories
async function fetchCategories() {
  try {
    const response = await axios.get(CATEGORY_URL)
    const data = response.data.data || response.data
    allCategories.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Error fetching categories:', error)
  }
}

// Fetch active categories only (excluding Commercial)
async function fetchActiveCategories() {
  try {
    const response = await axios.get(`${CATEGORY_URL}/active`)
    const data = response.data.data || response.data
    // Filter out Commercial category
    activeCategories.value = Array.isArray(data)
      ? data.filter(cat => cat.name !== '')
      : []
  } catch (error) {
    console.error('Error fetching active categories:', error)
  }
}

// Fetch all facilities (filtering done on frontend)
async function fetchFacilities() {
  try {
    loading.value = true
    const response = await axios.get(API_URL)
    const data = response.data.data || response.data
    facilities.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Error fetching facilities:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to load facilities',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Get image URL
function getImageUrl(path) {
  if (!path) return ''
  return `http://127.0.0.1:8000/storage/${path}`
}

// Load category fields
function loadCategoryFields() {
  const category = activeCategories.value.find(c => c.id == newFacility.value.category_id)
  if (category) {
    selectedCategory.value = {
      ...category,
      requiredFields: categoryFields[category.name] || []
    }
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

// Handle image upload with compression
async function handleImageUpload(e) {
  const files = Array.from(e.target.files)

  if (files.length > 3) {
    Swal.fire({
      title: 'Error!',
      text: 'You can only upload up to 3 images',
      icon: 'warning',
      confirmButtonColor: '#5F1213'
    })
    e.target.value = ''
    return
  }

  try {
    loading.value = true

    // Show processing message
    Swal.fire({
      title: 'Processing...',
      text: 'Compressing images, please wait',
      icon: 'info',
      showConfirmButton: false,
      allowOutsideClick: false
    })

    // Compress each image
    const compressedFiles = await Promise.all(
      files.map(file => compressImage(file, 2))
    )

    newFacility.value.images = compressedFiles
    previewImages.value = compressedFiles.map(file => URL.createObjectURL(file))

    Swal.fire({
      title: 'Success!',
      text: 'Images compressed and ready to upload',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (error) {
    console.error('Error compressing images:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to process images',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Remove image from preview
function removeImage(index) {
  const dt = new DataTransfer()
  const files = Array.from(newFacility.value.images)

  files.splice(index, 1)
  files.forEach(file => dt.items.add(file))

  newFacility.value.images = Array.from(dt.files)
  previewImages.value.splice(index, 1)

  if (fileInput.value) {
    fileInput.value.files = dt.files
  }
}

// Validate all fields before submission
function validateForm() {
  // Check facility name
  if (!newFacility.value.name.trim()) {
    Swal.fire({
      title: 'Error!',
      text: 'Please enter a facility name',
      icon: 'warning',
      confirmButtonColor: '#5F1213'
    })
    return false
  }

  // Check category selection
  if (!newFacility.value.category_id) {
    Swal.fire({
      title: 'Error!',
      text: 'Please select a category',
      icon: 'warning',
      confirmButtonColor: '#5F1213'
    })
    return false
  }

  // Check if all detail fields are filled
  if (selectedCategory.value) {
    for (const field of selectedCategory.value.requiredFields) {
      if (field === 'Price/Rate') {
        if (!newFacility.value.details['Price/Rate'] || newFacility.value.details['Price/Rate'] === '') {
          Swal.fire({
            title: 'Error!',
            text: 'Please enter the Price/Rate',
            icon: 'warning',
            confirmButtonColor: '#5F1213'
          })
          return false
        }
        if (!newFacility.value.details['Unit'] || newFacility.value.details['Unit'] === '') {
          Swal.fire({
            title: 'Error!',
            text: 'Please select a unit (per night/day/month)',
            icon: 'warning',
            confirmButtonColor: '#5F1213'
          })
          return false
        }
      } else {
        if (!newFacility.value.details[field] || newFacility.value.details[field].trim() === '') {
          Swal.fire({
            title: 'Error!',
            text: `Please fill in the ${field} field`,
            icon: 'warning',
            confirmButtonColor: '#5F1213'
          })
          return false
        }
      }
    }
  }

  return true
}

// Add facility
async function addFacility() {
  // Validate all fields first
  if (!validateForm()) {
    return
  }

  try {
    loading.value = true
    const formData = new FormData()

    formData.append('name', newFacility.value.name)
    formData.append('category_id', newFacility.value.category_id)
    formData.append('details', JSON.stringify(newFacility.value.details))
    formData.append('is_active', 1)  // Add this line to make facilities active by default

    console.log('Sending facility data:', {
      name: newFacility.value.name,
      category_id: newFacility.value.category_id,
      details: newFacility.value.details
    })

    if (newFacility.value.images && newFacility.value.images.length > 0) {
      Array.from(newFacility.value.images).forEach((file) => {
        formData.append('images[]', file)
      })
    }

    const response = await axios.post(API_URL, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.data.success) {
      await fetchFacilities()
      closeModal()

      Swal.fire({
        title: 'Success!',
        text: 'Facility added successfully!',
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Full error:', error)
    console.error('Error response:', error.response?.data)

    let errorMessage = 'Failed to add facility'

    // Check for validation errors
    if (error.response?.data?.errors) {
      const errors = error.response.data.errors
      errorMessage = Object.values(errors).flat().join('\n')
    } else if (error.response?.data?.message) {
      errorMessage = error.response.data.message
    }

    Swal.fire({
      title: 'Error!',
      text: errorMessage,
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Edit facility
function editFacility(facility) {
  newFacility.value = {
    name: facility.name,
    category_id: facility.category_id,
    details: { ...facility.details },
    images: []
  }

  // Load existing images as preview
  if (facility.images && facility.images.length > 0) {
    previewImages.value = facility.images.map(img => getImageUrl(img))
  } else {
    previewImages.value = []
  }

  const category = activeCategories.value.find(c => c.id == facility.category_id)
  if (category) {
    selectedCategory.value = {
      ...category,
      requiredFields: categoryFields[category.name] || []
    }
  }

  editId.value = facility.id
  isEditing.value = true
  showModal.value = true
}

// Update facility
async function updateFacility() {
  // Validate all fields first
  if (!validateForm()) {
    return
  }

  try {
    loading.value = true
    const formData = new FormData()

    formData.append('name', newFacility.value.name)
    formData.append('category_id', newFacility.value.category_id)
    formData.append('details', JSON.stringify(newFacility.value.details))
    formData.append('_method', 'PUT')

    if (newFacility.value.images && newFacility.value.images.length > 0) {
      Array.from(newFacility.value.images).forEach((file) => {
        formData.append('images[]', file)
      })
    }

    const response = await axios.post(`${API_URL}/${editId.value}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.data.success) {
      await fetchFacilities()
      closeModal()

      Swal.fire({
        title: 'Success!',
        text: 'Facility updated successfully!',
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Error updating facility:', error)

    let errorMessage = 'Failed to update facility'

    if (error.response?.data?.errors) {
      const errors = error.response.data.errors
      errorMessage = Object.values(errors).flat().join('\n')
    } else if (error.response?.data?.message) {
      errorMessage = error.response.data.message
    }

    Swal.fire({
      title: 'Error!',
      text: errorMessage,
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Delete facility
async function deleteFacility(id) {
  const result = await Swal.fire({
    title: 'Delete Facility?',
    text: 'This action cannot be undone!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  })

  if (!result.isConfirmed) return

  try {
    loading.value = true
    const response = await axios.delete(`${API_URL}/${id}`)

    if (response.data.success) {
      await fetchFacilities()

      Swal.fire({
        title: 'Deleted!',
        text: 'Facility has been deleted.',
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Error deleting facility:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to delete facility',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Open add modal
function openAddModal() {
  resetForm()
  showModal.value = true
}

// Close modal
function closeModal() {
  resetForm()
  showModal.value = false
}

// Reset form
function resetForm() {
  newFacility.value = {
    name: '',
    category_id: '',
    details: {},
    images: []
  }
  previewImages.value = []
  selectedCategory.value = null
  editId.value = null
  isEditing.value = false
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

// Sorting functions
function sortFacilities(field) {
  if (sortBy.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = field
    sortOrder.value = 'asc'
  }
  currentPage.value = 1 // Reset to first page when sorting
}

function getSortIcon(field) {
  if (sortBy.value !== field) return 'fas fa-sort text-gray-300'
  return sortOrder.value === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'
}

// Pagination functions
function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

function resetPagination() {
  currentPage.value = 1
  selectedFacilities.value = []
  selectAll.value = false
}

const visiblePages = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const delta = 2
  const range = []
  const rangeWithDots = []

  for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    rangeWithDots.push(1, '...')
  } else {
    rangeWithDots.push(1)
  }

  rangeWithDots.push(...range)

  if (current + delta < total - 1) {
    rangeWithDots.push('...', total)
  } else if (total > 1) {
    rangeWithDots.push(total)
  }

  return rangeWithDots.filter(item => item !== '...' || rangeWithDots.indexOf(item) === rangeWithDots.lastIndexOf(item))
})

// Selection functions
function toggleSelectAll() {
  selectAll.value = !selectAll.value
  if (selectAll.value) {
    selectedFacilities.value = paginatedFacilities.value.map(f => f.id)
  } else {
    selectedFacilities.value = []
  }
}

function toggleFacilitySelection(facilityId) {
  const index = selectedFacilities.value.indexOf(facilityId)
  if (index > -1) {
    selectedFacilities.value.splice(index, 1)
  } else {
    selectedFacilities.value.push(facilityId)
  }
  selectAll.value = selectedFacilities.value.length === paginatedFacilities.value.length && paginatedFacilities.value.length > 0
}

// Status toggle functions
async function toggleFacilityStatus(id, status) {
  try {
    loading.value = true
    const response = await axios.patch(`${API_URL}/${id}/status`, {
      is_active: status
    })

    if (response.data.success) {
      await fetchFacilities()
      Swal.fire({
        title: 'Success!',
        text: `Facility ${status ? 'activated' : 'deactivated'} successfully!`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      })
    }
  } catch (error) {
    console.error('Error toggling facility status:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to update facility status',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Bulk actions
async function bulkToggleStatus(status) {
  if (selectedFacilities.value.length === 0) return

  try {
    loading.value = true
    const response = await axios.patch(`${API_URL}/bulk-status`, {
      facility_ids: selectedFacilities.value,
      is_active: status
    })

    if (response.data.success) {
      await fetchFacilities()
      selectedFacilities.value = []
      selectAll.value = false
    }
  } catch (error) {
    console.error('Error bulk toggling status:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to update facility status',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

async function bulkDeleteFacilities() {
  if (selectedFacilities.value.length === 0) return

  try {
    loading.value = true
    const response = await axios.delete(`${API_URL}/bulk-delete`, {
      data: { facility_ids: selectedFacilities.value }
    })

    if (response.data.success) {
      await fetchFacilities()
      selectedFacilities.value = []
      selectAll.value = false
    }
  } catch (error) {
    console.error('Error bulk deleting facilities:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to delete facilities',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Load on mount
onMounted(() => {
  fetchCategories()
  fetchActiveCategories()
  fetchFacilities()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');

button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>
