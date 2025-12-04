<template>
    <div class="min-h-screen flex font-sans">
        <!-- Sidebar -->
        <Sidebar />
        <!-- Main content -->
        <main class="ml-64 w-full p-12 text-[#5F1213] bg-gray-100 min-h-screen">
            <div
                class="max-w-6xl mx-auto bg-white p-10 rounded-2xl shadow-xl border border-gray-200 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-[#FFA600] text-white px-4 py-1 rounded-bl-xl text-xs font-bold shadow">
                    Admin Profile
                </div>
                <div class="flex items-center gap-8 mb-10">
                    <img :src="profileImagePreview || getProfilePictureUrl(admin?.profile_picture)"
                        class="w-32 h-32 rounded-full object-cover border-4 border-[#FFA600] shadow" />
                    <div>
                        <h2 class="text-3xl font-extrabold">{{ admin?.first_name }} {{ admin?.last_name }}</h2>
                        <p class="text-gray-500">{{ admin?.role || 'System Administrator' }}</p>
                    </div>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                        <input type="text" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.first_name || ''" />
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                                :value="admin?.last_name || ''" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">M.I.</label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-left text-gray-700 cursor-default"
                                :value="admin?.middle_initial || ''" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                        <input type="text" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.address || ''" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                        <input type="text" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.contact || ''" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                        <input type="text" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.gender || ''" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Age</label>
                        <input type="number" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.age || ''" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" readonly
                            class="w-full px-4 py-3 border bg-gray-100 border-gray-300 rounded-lg shadow-sm text-gray-700 cursor-default"
                            :value="admin?.email || ''" />
                    </div>

                </div>

                <div class="mt-10 text-right">
                    <button @click="showModal = true"
                        class="bg-[#5F1213] text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-[#FFA600] hover:text-[#5F1213] transition duration-200">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div v-if="showModal"
                class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50 ">
                <div
                    class="bg-white w-full max-w-5xl p-10 rounded-2xl shadow-2xl relative border border-[#FFA600]/30 overflow-y-auto max-h-[90vh]">
                    <!-- Close Button -->
                    <button @click="showModal = false"
                        class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>

                    <!-- Header -->
                    <h2 class="text-3xl font-bold text-[#5F1213] border-b pb-4 mb-8 flex items-center gap-3">
                        <i class="fas fa-user-edit text-[#FFA600]"></i> Update Profile Details
                    </h2>

                    <!-- Profile Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 flex flex-col items-center mb-4">
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Profile Image</label>
                            <div class="relative w-32 h-32 mb-2">
                                <img :src="profileImagePreview || getProfilePictureUrl(editAdmin.profile_picture)"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-[#FFA600] shadow" />
                                <input type="file" accept="image/*" @change="onProfileImageChange"
                                    class="absolute inset-0 opacity-0 cursor-pointer" />
                            </div>
                            <span class="text-xs text-gray-500">Click image to change</span>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">First Name</label>
                            <input type="text" v-model="editAdmin.first_name" placeholder="First Name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Last Name</label>
                            <input type="text" v-model="editAdmin.last_name" placeholder="Last Name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Middle Initial</label>
                            <input type="text" maxlength="1" v-model="editAdmin.middle_initial" placeholder="M.I."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Email</label>
                            <input type="email" v-model="editAdmin.email" placeholder="Email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Address</label>
                            <input type="text" v-model="editAdmin.address" placeholder="Address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Contact Number</label>
                            <input type="text" v-model="editAdmin.contact" placeholder="Contact Number"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Gender</label>
                            <select v-model="editAdmin.gender"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 text-gray-700 hover:shadow-md transition">
                                <option disabled value="">Select Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Age</label>
                            <input type="number" v-model="editAdmin.age" placeholder="Age"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 hover:shadow-md transition" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Role</label>
                            <select v-model="editAdmin.role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FFA600] bg-gray-50 text-gray-700 hover:shadow-md transition">
                                <option disabled value="">Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                    </div>


                    <!-- Change Password Toggle -->
                    <div class="mt-10">
                        <button @click="showPasswordSection = !showPasswordSection"
                            class="text-sm font-semibold text-[#5F1213] hover:text-[#FFA600] transition duration-200">
                            <i class="fas fa-key mr-2"></i>
                            {{ showPasswordSection ? 'Cancel Password Change' : 'Change Password' }}
                        </button>
                    </div>

                    <!-- Password Fields -->
                    <div v-if="showPasswordSection"
                        class="mt-6 bg-[#FFF6E5] border border-[#FFA600] rounded-xl p-6 shadow-md">
                        <h3 class="text-lg font-bold text-[#5F1213] mb-4">
                            <i class="fas fa-lock mr-2"></i>Change Your Password
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" v-model="newPassword"
                                    class="w-full px-4 py-3 pr-12 border rounded-lg bg-white border-[#FFA600] focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm"
                                    placeholder="Enter new password" />
                                <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                                    @click="showPassword = !showPassword">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-[#5F1213] text-sm"></i>
                                </span>
                            </div>
                            <div>
                                <input :type="showPassword ? 'text' : 'password'" v-model="confirmPassword"
                                    class="w-full px-4 py-3 border rounded-lg bg-white border-[#FFA600] focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm"
                                    placeholder="Confirm new password" />
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 mt-4">
                            <i class="fas fa-info-circle mr-1"></i>Make sure your new password is at least 6 characters.
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-10 flex justify-end gap-4">
                        <button @click="showModal = false"
                            class="bg-gray-300 text-[#5F1213] font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-gray-400 hover:text-black transition">
                            Cancel
                        </button>
                        <button @click="saveChanges"
                            class="bg-[#5F1213] text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-[#FFA600] hover:text-[#5F1213] transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>


<script setup>
import { ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import Sidebar from './adminSidebar.vue'
import Swal from 'sweetalert2'

import { computed } from 'vue'
const page = usePage()
const admin = computed(() => page.props.admin)

const updateMenuOpen = ref(false)
const reportsMenuOpen = ref(false)
const accountMenuOpen = ref(false)

const showModal = ref(false)
const showPassword = ref(false)
const showPasswordSection = ref(false)
const newPassword = ref('')
const confirmPassword = ref('')

// Prefill editAdmin with admin info for modal
import { reactive, watch } from 'vue'
const editAdmin = reactive({
    first_name: '',
    last_name: '',
    middle_initial: '',
    email: '',
    address: '',
    contact: '',
    gender: '',
    age: '',
    role: '',
    profile_picture: ''
})


const profileImagePreview = ref('')

function onProfileImageChange(e) {
    const file = e.target.files[0]
    if (file) {
        profileImagePreview.value = URL.createObjectURL(file)
        editAdmin.profile_picture = file // keep file only for FormData; don't use it in <img>
    }
}


watch(showModal, (val) => {
    if (val && admin.value) {
        editAdmin.first_name = admin.value.first_name || ''
        editAdmin.last_name = admin.value.last_name || ''
        editAdmin.middle_initial = admin.value.middle_initial || ''
        editAdmin.email = admin.value.email || ''
        editAdmin.address = admin.value.address || ''
        editAdmin.contact = admin.value.contact || ''
        editAdmin.gender = admin.value.gender || ''
        editAdmin.age = admin.value.age || ''
        editAdmin.role = admin.value.role || 'Admin'
        editAdmin.profile_picture = admin.value.profile_picture || ''

        // Show the existing picture if there is one
        profileImagePreview.value = ''
    }
})


// Helper to get the correct profile picture URL
function getProfilePictureUrl(path) {
    if (!path) return 'https://i.pravatar.cc/100';

    // If it's already a full URL, return as is
    if (path.startsWith('http')) return path;

    // If it's in storage, prepend Laravel's storage path
    return `/storage/${path.replace(/^storage\//, '')}`;
}



const saveChanges = () => {
    // Client-side required field validation
    const requiredFields = [
        { key: 'first_name', label: 'First Name' },
        { key: 'last_name', label: 'Last Name' },
        { key: 'email', label: 'Email' },
        { key: 'role', label: 'Role' },
        { key: 'address', label: 'Address' },
        { key: 'contact', label: 'Contact Number' }
    ]
    for (const field of requiredFields) {
        if (!editAdmin[field.key] || editAdmin[field.key].toString().trim() === '') {
            Swal.fire({ icon: 'warning', title: 'Missing Field', text: `${field.label} is required.` })
            return
        }
    }
    if (showPasswordSection.value && newPassword.value !== confirmPassword.value) {
        Swal.fire({ icon: 'error', title: 'Password Mismatch', text: 'Passwords do not match!' })
        return
    }

    Swal.fire({
        icon: 'question',
        title: 'Are you sure to save changes?',
        confirmButtonText: 'OK',
        showCancelButton: false
    }).then((result) => {

        if (!result.isConfirmed) return

        const formData = new FormData()
        for (const key in editAdmin) {
            if (key === 'profile_picture' && editAdmin[key] instanceof File) {
                formData.append(key, editAdmin[key])
            } else if (key !== 'profile_picture') {
                formData.append(key, editAdmin[key] ?? '')
            }
        }
        if (showPasswordSection.value && newPassword.value) {
            formData.append('password', newPassword.value)
        }
        formData.append('_method', 'PUT') // use POST + method spoofing

        router.post(`/admins/${admin.value.id}`, formData, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                // Re-fetch the latest admin from server (now with full image URL)
                router.reload({ only: ['admin'] })

                showModal.value = false
                profileImagePreview.value = ''
                Swal.fire({ icon: 'success', title: 'Profile Updated', text: 'Your profile information has been updated successfully.' })
            }
        })
    })
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');

body {
    font-family: 'Inter', sans-serif;
}
</style>
