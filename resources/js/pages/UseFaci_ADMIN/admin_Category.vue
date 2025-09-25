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
        <table class="w-full text-sm text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 font-semibold text-gray-700">
              <th class="py-2 px-3">ID</th>
              <th class="py-2 px-3">Name</th>
              <th class="py-2 px-3">Description</th>
              <th class="py-2 px-3">Required Fields</th>
              <th class="py-2 px-3">Date Added</th>
              <th class="py-2 px-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="cat in categories"
              :key="cat.id"
              class="border-b border-gray-100 hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ cat.id }}</td>
              <td class="py-2 px-3">{{ cat.name }}</td>
              <td class="py-2 px-3">{{ cat.description }}</td>
              <td class="py-2 px-3">
                <span
                  v-for="(field, idx) in cat.requiredFields"
                  :key="idx"
                  class="inline-block bg-gray-200 text-xs px-2 py-1 rounded mr-1"
                >
                  {{ field }}
                </span>
              </td>
              <td class="py-2 px-3">{{ new Date(cat.created_at).toLocaleDateString() }}</td>
              <td class="py-2 px-3 space-x-2">
                <button
                  class="px-3 py-1 bg-[#FFA600] text-white rounded hover:bg-[#e69500] transition"
                  @click="editCategory(cat)"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                  @click="deleteCategory(cat.id)"
                >
                  <i class="fas fa-trash"></i>
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
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
              />
            </div>

            <!-- Category Description -->
            <div>
              <label class="block text-sm font-medium mb-1">Description</label>
              <textarea
                v-model="newCategory.description"
                placeholder="Brief description (e.g., Hostel with room details, Rental units, etc.)"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
              ></textarea>
            </div>

            <!-- Required Fields -->
            <div>
              <label class="block text-sm font-medium mb-1">Required Fields</label>
              <div class="flex gap-2 mb-2">
                <input
                  v-model="newField"
                  type="text"
                  placeholder="Enter field name (e.g., Room Name, Rate)"
                  class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFA600]"
                />
                <button
                  type="button"
                  @click="addField"
                  class="px-3 py-2 bg-[#5F1213] text-white rounded hover:bg-[#FFA600] hover:text-[#5F1213]"
                >
                  Add
                </button>
              </div>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="(field, idx) in newCategory.requiredFields"
                  :key="idx"
                  class="bg-gray-200 px-2 py-1 rounded text-sm flex items-center"
                >
                  {{ field }}
                  <i
                    class="fas fa-times ml-2 cursor-pointer text-red-500"
                    @click="removeField(idx)"
                  ></i>
                </span>
              </div>
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

const showAddModal = ref(false)
const isEditing = ref(false)
const editId = ref(null)
const newField = ref('')

const categories = ref([
  {
    id: 1,
    name: 'Rental',
    description: 'Rental spaces with monthly rates',
    requiredFields: ['Unit Name', 'Monthly Rate', 'Utilities'],
    created_at: new Date()
  },
  {
    id: 2,
    name: 'Hostel',
    description: 'Dorm-style rooms for students',
    requiredFields: ['Room Name', 'Rate', 'Capacity', 'Bed Type'],
    created_at: new Date()
  },
  {
    id: 3,
    name: 'Commercial',
    description: 'Commercial facilities like halls and shops',
    requiredFields: ['Hall Name', 'Daily Rate', 'Capacity'],
    created_at: new Date()
  }
])

const newCategory = ref({ name: '', description: '', requiredFields: [] })

function openAddModal() {
  resetForm()
  showAddModal.value = true
}

function addCategory() {
  if (!newCategory.value.name.trim()) return
  categories.value.push({
    id: categories.value.length + 1,
    name: newCategory.value.name,
    description: newCategory.value.description,
    requiredFields: [...newCategory.value.requiredFields],
    created_at: new Date()
  })
  resetForm()
}

function editCategory(cat) {
  newCategory.value = { ...cat, requiredFields: [...cat.requiredFields] }
  editId.value = cat.id
  isEditing.value = true
  showAddModal.value = true
}

function updateCategory() {
  const index = categories.value.findIndex(c => c.id === editId.value)
  if (index !== -1) {
    categories.value[index] = {
      ...categories.value[index],
      ...newCategory.value
    }
  }
  resetForm()
}

function deleteCategory(id) {
  categories.value = categories.value.filter(c => c.id !== id)
}

function addField() {
  if (newField.value.trim()) {
    newCategory.value.requiredFields.push(newField.value.trim())
    newField.value = ''
  }
}

function removeField(idx) {
  newCategory.value.requiredFields.splice(idx, 1)
}

function resetForm() {
  newCategory.value = { name: '', description: '', requiredFields: [] }
  editId.value = null
  isEditing.value = false
  showAddModal.value = false
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
