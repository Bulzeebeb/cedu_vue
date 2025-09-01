<template>
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto flex-col overflow-hidden p-0 m-0">
  <div class="bg-white rounded-xl shadow-lg text-black font-sans flex flex-col overflow-hidden p-0 m-0">
    <div class="flex-grow flex items-center justify-center py-0 m-0">
      <div class="border border-gray-300 p-6 rounded-md w-full max-w-2xl shadow-md m-0">
          <h2 class="text-center text-2xl font-bold mb-6">Create Account - Step 1 of 2</h2>
          <p class="text-center text-gray-600 mb-6">Personal Information</p>

          <form @submit.prevent="handleNext">
            <!-- Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <input type="text" placeholder="First Name" v-model="form.firstName" required
                  class="w-full px-4 py-2 border border-gray-300 rounded" />
                <p v-if="form.errors.firstName" class="text-red-500 text-sm mt-1">{{ form.errors.firstName }}</p>
              </div>
              <div>
                <input type="text" placeholder="Last Name" v-model="form.lastName" required
                  class="w-full px-4 py-2 border border-gray-300 rounded" />
                <p v-if="form.errors.lastName" class="text-red-500 text-sm mt-1">{{ form.errors.lastName }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <input type="text" placeholder="Middle Name (Optional)" v-model="form.middleName"
                  class="w-full px-4 py-2 border border-gray-300 rounded" />
                <p v-if="form.errors.middleName" class="text-red-500 text-sm mt-1">{{ form.errors.middleName }}</p>
              </div>
              <div>
                <input type="text" placeholder="Extension (Optional)" v-model="form.extension"
                  class="w-full px-4 py-2 border border-gray-300 rounded" />
                <p v-if="form.errors.extension" class="text-red-500 text-sm mt-1">{{ form.errors.extension }}</p>
              </div>
            </div>

            <!-- Address -->
            <div class="mb-4">
              <input type="text" placeholder="Address" v-model="form.address" required
                class="w-full px-4 py-2 border border-gray-300 rounded" />
              <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
            </div>

            <!-- Contact Number, Gender, Age -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <div class="flex items-center">
                  <span class="inline-block px-2 py-2 bg-gray-100 border border-gray-300 rounded-l text-gray-700 select-none">+63</span>
                  <input
                    type="tel"
                    placeholder="Contact Number"
                    v-model="form.contactNum"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-r focus:outline-none"
                    maxlength="11"
                    @input="onContactInput"
                  />
                </div>
                <p v-if="form.errors.contactNum" class="text-red-500 text-sm mt-1">{{ form.errors.contactNum }}</p>
              </div>
              <div>
                <select v-model="form.gender" required class="w-full px-4 py-2 border border-gray-300 rounded">
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
                <p v-if="form.errors.gender" class="text-red-500 text-sm mt-1">{{ form.errors.gender }}</p>
              </div>
              <div>
                <input type="number" placeholder="Age" v-model="form.age" required min="1" max="150"
                  class="w-full px-4 py-2 border border-gray-300 rounded" />
                <p v-if="form.errors.age" class="text-red-500 text-sm mt-1">{{ form.errors.age }}</p>
              </div>
            </div>

            <!-- Progress indicator -->
            <div class="mb-6">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-maroon">Step 1 of 2</span>
                <span class="text-sm text-gray-500">50%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-maroon h-2 rounded-full" style="width: 50%"></div>
              </div>
            </div>

            <button type="submit" class="w-full bg-maroon text-white py-2 rounded-full font-semibold hover:bg-red-800"
              :disabled="form.processing">
              Next Step
            </button>
          </form>

          <p class="text-center text-sm mt-4">
            Already have an account?
            <Link href="/signmain" class="text-blue-600 font-semibold hover:underline">Login</Link>
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { defineEmits } from 'vue'
import Swal from 'sweetalert2'

const emit = defineEmits(['next', 'close'])
const form = useForm({
  firstName: '',
  lastName: '',
  middleName: '',
  extension: '',
  address: '',
  contactNum: '',
  gender: '',
  age: '',
})

function onContactInput(e) {
  // Only allow numbers, max 11 digits
  let val = e.target.value.replace(/\D/g, '').slice(0, 10)
  form.contactNum = val
}

function handleNext() {
  // Validate required fields
  if (!form.firstName || !form.lastName || !form.address || !form.contactNum || !form.gender || !form.age) {
    form.setError('message', 'Please fill in all required fields.')
    return
  }
  // Show SweetAlert2 loading
  Swal.fire({
    title: 'Saving...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  // Emit form data to parent to open Signup2 modal
  setTimeout(() => {
    Swal.close();
    emit('next', { ...form })
  }, 800); // simulate a short delay for effect
}
</script>

<style scoped>
.bg-maroon {
  background-color: #650000;
}

.text-maroon {
  color: #650000;
}
</style>
