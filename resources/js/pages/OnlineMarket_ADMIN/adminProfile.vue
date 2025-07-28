<template>
  <main class="w-full p-12 text-[#5F1213] bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white p-10 rounded-2xl shadow-xl border border-gray-200 relative overflow-hidden">
      <div class="absolute top-0 right-0 bg-[#FFA600] text-white px-4 py-1 rounded-bl-xl text-xs font-bold shadow">
        Admin Profile
      </div>
      <div class="flex items-center gap-8 mb-10">
        <img src="https://i.pravatar.cc/100" alt="User Photo" class="w-28 h-28 rounded-full border-4 border-[#FFA600] shadow-lg" />
        <div>
          <h2 class="text-3xl font-extrabold">Admin</h2>
          <p class="text-gray-500">System Administrator</p>
        </div>
      </div>

      <!-- Info -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
          <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="Juan" />
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div class="col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
            <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="Dela Cruz" />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">M.I.</label>
            <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-left text-gray-700 cursor-default" value="P" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
          <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="Tagum City" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
          <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="09123456789" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
          <input type="text" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="Male" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Age</label>
          <input type="number" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="25" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
          <input type="email" readonly class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="admin@material.com" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <div class="relative">
            <input :type="showPassword ? 'text' : 'password'" readonly class="w-full px-4 py-3 pr-10 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default" value="secret123" />
            <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" @click="showPassword = !showPassword">
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-500"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="mt-10 text-right">
        <button @click="showModal = true" class="bg-[#5F1213] text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
          Edit Profile
        </button>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50 ">
      <div class="bg-white w-full max-w-5xl p-10 rounded-2xl shadow-2xl relative border border-[#FFA600]/30 overflow-y-auto max-h-[90vh]">
        <button @click="showModal = false" class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
        <h2 class="text-3xl font-bold text-[#5F1213] border-b pb-4 mb-8 flex items-center gap-3">
          <i class="fas fa-user-edit text-[#FFA600]"></i> Update Profile Details
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">First Name</label>
            <input type="text" placeholder="Juan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Last Name</label>
            <input type="text" placeholder="Dela Cruz" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Middle Initial</label>
            <input type="text" maxlength="1" placeholder="P" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Email</label>
            <input type="email" placeholder="admin@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Address</label>
            <input type="text" placeholder="Tagum City" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Contact Number</label>
            <input type="text" placeholder="09123456789" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Gender</label>
            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 text-gray-700 hover:shadow-md transition">
              <option disabled selected>Select Gender</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-700 mb-1 block">Age</label>
            <input type="number" placeholder="25" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
          </div>
        </div>

        <div class="mt-10">
          <button @click="showPasswordSection = !showPasswordSection" class="text-sm font-semibold text-[#5F1213] hover:text-[#FFA600] transition duration-200">
            <i class="fas fa-key mr-2"></i>
            {{ showPasswordSection ? 'Cancel Password Change' : 'Change Password' }}
          </button>
        </div>

        <div v-if="showPasswordSection" class="mt-6 bg-[#FFF6E5] border border-[#FFA600] rounded-xl p-6 shadow-md">
          <h3 class="text-lg font-bold text-[#5F1213] mb-4">
            <i class="fas fa-lock mr-2"></i>Change Your Password
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="relative">
              <input :type="showPassword ? 'text' : 'password'" v-model="newPassword" class="w-full px-4 py-3 pr-12 border rounded-lg bg-white border-[#FFA600] focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm" placeholder="Enter new password" />
              <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" @click="showPassword = !showPassword">
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-[#5F1213] text-sm"></i>
              </span>
            </div>
            <div>
              <input :type="showPassword ? 'text' : 'password'" v-model="confirmPassword" class="w-full px-4 py-3 border rounded-lg bg-white border-[#FFA600] focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm" placeholder="Confirm new password" />
            </div>
          </div>
          <div class="text-xs text-gray-600 mt-4">
            <i class="fas fa-info-circle mr-1"></i>Make sure your new password is at least 6 characters.
          </div>
        </div>

        <div class="mt-10 flex justify-end gap-4">
          <button @click="showModal = false" class="bg-gray-300 text-[#5F1213] font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-gray-400 hover:text-black transition">
            Cancel
          </button>
          <button @click="saveChanges" class="bg-[#5F1213] text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref } from 'vue'

const showModal = ref(false)
const showPassword = ref(false)
const showPasswordSection = ref(false)
const newPassword = ref('')
const confirmPassword = ref('')

const saveChanges = () => {
  if (showPasswordSection.value && newPassword.value !== confirmPassword.value) {
    alert('Passwords do not match!')
    return
  }
  alert('Profile updated successfully!')
  showModal.value = false
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
