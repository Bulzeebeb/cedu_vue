<template>
  <div class="min-h-screen flex font-sans bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Dashboard -->
    <main class="ml-64 flex-1 p-6 text-[#5F1213]">

      <!-- Page Title -->
      <div class="bg-white rounded-xl p-6 mb-6 shadow border flex items-center space-x-4">
        <i class="fas fa-tags text-3xl text-[#FFA600]"></i>
        <h1 class="text-2xl font-semibold">Category</h1>
      </div>

      <!-- Add Button -->
      <div class="mb-4 flex justify-end">
        <button
          @click="openAddModal"
          class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition"
        >
          + Add Category
        </button>
      </div>

      <!-- Category Table -->
      <div class="bg-white rounded-xl shadow border p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Available Categories</h2>

        <!-- No data message -->
        <div v-if="!loading && categories.length === 0" class="text-center py-8 text-gray-500">
          <i class="fas fa-inbox text-4xl mb-2"></i>
          <p>No categories found. Click "Add Category" to create one.</p>
        </div>

        <!-- Table -->
        <table v-else class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-3">ID</th>
              <th class="py-2 px-3">Name</th>
              <th class="py-2 px-3">Description</th>
              <th class="py-2 px-3">Status</th>
              <th class="py-2 px-3">Date Added</th>
              <th class="py-2 px-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="cat in categories"
              :key="cat.id"
              class="border-b border-gray-100 hover:bg-gray-50"
              :class="{ 'opacity-60': !cat.is_active }"
            >
              <td class="py-2 px-3">{{ cat.id }}</td>
              <td class="py-2 px-3">{{ cat.name }}</td>
              <td class="py-2 px-3">{{ cat.description }}</td>
              <td class="py-2 px-3">
                <span
                  :class="cat.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                  class="inline-block text-xs px-2 py-1 rounded"
                >
                  {{ cat.is_active ? 'Active' : 'Disabled' }}
                </span>
              </td>
              <td class="py-2 px-3">{{ formatDate(cat.created_at) }}</td>
              <td class="py-2 px-3 space-x-2">
                <button
                  class="px-3 py-1 bg-[#FFA600] text-white rounded hover:bg-[#e69500] transition"
                  @click="editCategory(cat)"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  :class="cat.is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                  class="px-3 py-1 text-white rounded transition"
                  @click="toggleCategoryStatus(cat)"
                  :title="cat.is_active ? 'Disable category' : 'Enable category'"
                >
                  <i :class="cat.is_active ? 'fas fa-ban' : 'fas fa-check'"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Category Modal -->
      <div
        v-if="showAddModal"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
          <!-- Close Button -->
          <button
            @click="resetForm"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500"
          >
            <i class="fas fa-times text-lg"></i>
          </button>

          <h2 class="text-xl font-semibold mb-4">
            {{ isEditing ? 'Edit Category' : 'Add New Category' }}
          </h2>
          <form @submit.prevent="isEditing ? updateCategory() : addCategory()" class="space-y-4">
            <!-- Category Name -->
            <div>
              <label class="block text-sm font-medium mb-1">Category Name</label>
              <input
                v-model="newCategory.name"
                type="text"
                placeholder="Enter category name"
                required
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
              />
            </div>

            <!-- Category Description -->
            <div>
              <label class="block text-sm font-medium mb-1">Description</label>
              <textarea
                v-model="newCategory.description"
                placeholder="Brief description"
                rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
              ></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="resetForm"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-[#5F1213] text-white rounded-lg hover:bg-[#FFA600] hover:text-[#5F1213] transition disabled:opacity-50"
              >
                {{ loading ? 'Saving...' : (isEditing ? 'Update' : 'Save') }}
              </button>
            </div>
          </form>
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
import { ref, onMounted } from 'vue'
import AdminSidebar from './adminSidebar.vue'
import axios from 'axios'
import Swal from 'sweetalert2'

// Configure axios base URL
axios.defaults.baseURL = 'http://127.0.0.1:8000'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

const API_URL = '/categories'

const showAddModal = ref(false)
const isEditing = ref(false)
const editId = ref(null)
const loading = ref(false)

const categories = ref([])
const newCategory = ref({ name: '', description: '' })

const mainCategories = ['Rental', 'Hostel', 'Commercial']

// Fetch all categories
async function fetchCategories() {
  try {
    loading.value = true
    const response = await axios.get(API_URL)
    console.log('API Response:', response.data)

    // Handle if response is wrapped in a success object
    if (response.data.data) {
      categories.value = response.data.data
    } else if (Array.isArray(response.data)) {
      categories.value = response.data
    } else {
      categories.value = []
    }

    console.log('Categories loaded:', categories.value)
  } catch (error) {
    console.error('Error fetching categories:', error)
    console.error('Error details:', error.response?.data)

    Swal.fire({
      title: 'Error!',
      text: 'Failed to load categories: ' + (error.response?.data?.message || error.message),
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Add new category
async function addCategory() {
  if (!newCategory.value.name.trim()) {
    alert('Please enter a category name')
    return
  }

  try {
    loading.value = true
    const response = await axios.post(API_URL, {
      name: newCategory.value.name,
      description: newCategory.value.description
    })

    if (response.data.success) {
      await fetchCategories()
      resetForm()

      // Success alert
      Swal.fire({
        title: 'Success!',
        text: 'Category added successfully!',
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Error adding category:', error)
    if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      Swal.fire({
        title: 'Error!',
        text: errors.join('\n'),
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    } else {
      Swal.fire({
        title: 'Error!',
        text: 'Failed to add category',
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    }
  } finally {
    loading.value = false
  }
}

// Edit category
function editCategory(cat) {
  newCategory.value = {
    name: cat.name,
    description: cat.description
  }
  editId.value = cat.id
  isEditing.value = true
  showAddModal.value = true
}

// Update category
async function updateCategory() {
  if (!newCategory.value.name.trim()) {
    alert('Please enter a category name')
    return
  }

  try {
    loading.value = true
    const response = await axios.put(`${API_URL}/${editId.value}`, {
      name: newCategory.value.name,
      description: newCategory.value.description
    })

    if (response.data.success) {
      await fetchCategories()
      resetForm()

      // Success alert
      Swal.fire({
        title: 'Success!',
        text: 'Category updated successfully!',
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Error updating category:', error)
    if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      Swal.fire({
        title: 'Error!',
        text: errors.join('\n'),
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    } else {
      Swal.fire({
        title: 'Error!',
        text: 'Failed to update category',
        icon: 'error',
        confirmButtonColor: '#5F1213'
      })
    }
  } finally {
    loading.value = false
  }
}

// Toggle category status (disable/enable)
async function toggleCategoryStatus(cat) {
  console.log('Toggle clicked for:', cat.name)

  const action = cat.is_active ? 'disable' : 'enable'

  // SweetAlert confirmation
  const result = await Swal.fire({
    title: `${action.charAt(0).toUpperCase() + action.slice(1)} Category?`,
    text: `Are you sure you want to ${action} "${cat.name}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: cat.is_active ? '#dc2626' : '#16a34a',
    cancelButtonColor: '#6b7280',
    confirmButtonText: `Yes, ${action} it!`,
    cancelButtonText: 'Cancel'
  })

  if (!result.isConfirmed) {
    return
  }

  try {
    loading.value = true
    console.log('Making request to:', `${API_URL}/${cat.id}/toggle-status`)
    const response = await axios.patch(`${API_URL}/${cat.id}/toggle-status`)
    console.log('Response:', response.data)

    if (response.data.success) {
      await fetchCategories()

      // Success alert
      Swal.fire({
        title: 'Success!',
        text: response.data.message,
        icon: 'success',
        confirmButtonColor: '#5F1213',
        timer: 2000
      })
    }
  } catch (error) {
    console.error('Error toggling category status:', error)
    console.error('Error response:', error.response)

    // Error alert
    Swal.fire({
      title: 'Error!',
      text: error.response?.data?.message || 'Failed to update category status',
      icon: 'error',
      confirmButtonColor: '#5F1213'
    })
  } finally {
    loading.value = false
  }
}

// Check if category is main category
function isMainCategory(name) {
  return mainCategories.includes(name)
}

// Format date
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Open add modal
function openAddModal() {
  resetForm()
  showAddModal.value = true
}

// Reset form
function resetForm() {
  newCategory.value = { name: '', description: '' }
  editId.value = null
  isEditing.value = false
  showAddModal.value = false
}

// Load categories on mount
onMounted(() => {
  fetchCategories()
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
