<template>
    <div class="min-h-screen py-24 flex flex-col font-sans">
        <!-- HEADER -->
        <SiteHeader />

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 pt-4 text-[#5F1213] flex flex-col items-center justify-center min-h-screen">
            <div
                class="max-w-7xl mx-auto bg-white p-6 md:p-10 rounded-2xl shadow-2xl border border-gray-200 relative overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                <div class="absolute top-0 right-0">
                    <div class="bg-[#FFA600] text-white px-4 py-2 rounded-bl-2xl text-sm font-bold shadow-lg flex items-center gap-2">
                        <i class="fas fa-user-shield text-xs"></i>
                        Admin Profile
                    </div>
                    <div class="mt-2 flex justify-end">
                        <button @click="showModal = true" class="bg-[#5F1213] text-white font-semibold px-4 py-2 rounded-xl shadow-lg hover:bg-[#FFA600] hover:text-[#5F1213] hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2 text-sm">
                            <i class="fas fa-edit"></i>
                            Edit Profile
                        </button>
                    </div>
                </div>

                <!-- Profile Header -->
                <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8 mb-12">
                    <div class="relative group">
                        <img :src="profileImagePreview || getProfilePictureUrl(admin?.profile_picture)"
                            class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-[#FFA600] shadow-xl transition-transform duration-300 group-hover:scale-105" />
                        <div
                            class="absolute inset-0 rounded-full bg-[#FFA600]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-[#5F1213] mb-2">{{ admin?.first_name }} {{
                            admin?.last_name }}</h1>
                        <p class="text-lg text-gray-600 font-medium">{{ admin?.role || 'System Administrator' }}</p>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                            <i class="fas fa-envelope text-[#FFA600]"></i>
                            <span class="text-sm text-gray-500">{{ admin?.email }}</span>
                        </div>
                    </div>



                </div>

                <!-- Personal Information Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-[#5F1213] mb-6 flex items-center gap-2">
                        <i class="fas fa-user text-[#FFA600]"></i>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-signature text-[#FFA600] text-xs"></i>
                                First Name
                            </label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.first_name || ''" />
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-signature text-[#FFA600] text-xs"></i>
                                    Last Name
                                </label>
                                <input type="text" readonly
                                    class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                    :value="admin?.last_name || ''" />
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-font text-[#FFA600] text-xs"></i>
                                    M.I.
                                </label>
                                <input type="text" readonly
                                    class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-center text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                    :value="admin?.middle_initial || ''" />
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-venus-mars text-[#FFA600] text-xs"></i>
                                Gender
                            </label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.gender || ''" />
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-birthday-cake text-[#FFA600] text-xs"></i>
                                Age
                            </label>
                            <input type="number" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.age || ''" />
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-[#5F1213] mb-6 flex items-center gap-2">
                        <i class="fas fa-address-book text-[#FFA600]"></i>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-[#FFA600] text-xs"></i>
                                Email Address
                            </label>
                            <input type="email" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.email || ''" />
                        </div>
                        <div class="group">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-[#FFA600] text-xs"></i>
                                Contact Number
                            </label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.contact || ''" />
                        </div>
                        <div class="md:col-span-2 group">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-[#FFA600] text-xs"></i>
                                Address
                            </label>
                            <input type="text" readonly
                                class="w-full px-4 py-3 border bg-gray-50 border-gray-300 rounded-xl shadow-sm text-gray-700 cursor-default transition-all duration-200 group-hover:shadow-md"
                                :value="admin?.address || ''" />
                        </div>
                    </div>
                </div>


            </div>

            <!-- Modal -->
            <div v-if="showModal"
                class="fixed inset-0 bg-black/50 backdrop-blur-md flex justify-center items-center z-50 p-4 animate-fade-in">
                <div
                    class="bg-white w-full max-w-6xl max-h-[95vh] rounded-3xl shadow-2xl relative border border-[#FFA600]/20 overflow-hidden transform transition-all duration-300 scale-100">
                    <!-- Close Button -->
                    <button @click="handleModalClose"
                        class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold z-10 transition-colors duration-200 hover:bg-red-50 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Header -->
                    <div class="bg-gradient-to-r from-[#5F1213] to-[#7a1a1a] text-white px-8 py-6">
                        <h2 class="text-3xl font-bold flex items-center gap-3">
                            <i class="fas fa-user-edit text-[#FFA600]"></i> Update Profile Details
                        </h2>
                        <p class="text-white/80 mt-2">Make changes to your profile information</p>
                    </div>

                    <div class="p-8 overflow-y-auto max-h-[calc(95vh-200px)]">
                        <!-- Profile Image Section -->
                        <div class="flex flex-col items-center mb-8">
                            <div class="relative group mb-4">
                                <img :src="profileImagePreview || getProfilePictureUrl(editAdmin.profile_picture)"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-[#FFA600] shadow-xl transition-transform duration-300 group-hover:scale-105" />
                                <div
                                    class="absolute inset-0 rounded-full bg-[#FFA600]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <i class="fas fa-camera text-white text-xl"></i>
                                </div>
                                <input type="file" accept="image/*" @change="onProfileImageChange"
                                    class="absolute inset-0 opacity-0 cursor-pointer rounded-full" />
                            </div>
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <i class="fas fa-upload text-[#FFA600]"></i>
                                Click image to change profile picture
                            </p>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-[#5F1213] mb-6 flex items-center gap-2">
                                <i class="fas fa-user text-[#FFA600]"></i>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-signature text-[#FFA600] text-xs"></i>
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" v-model="editAdmin.first_name" placeholder="Enter first name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.first_name }" />
                                    <p v-if="errors.first_name" class="text-red-500 text-xs mt-1">{{ errors.first_name
                                        }}</p>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-signature text-[#FFA600] text-xs"></i>
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" v-model="editAdmin.last_name" placeholder="Enter last name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.last_name }" />
                                    <p v-if="errors.last_name" class="text-red-500 text-xs mt-1">{{ errors.last_name }}
                                    </p>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-font text-[#FFA600] text-xs"></i>
                                        Middle Initial
                                    </label>
                                    <input type="text" maxlength="1" v-model="editAdmin.middle_initial" placeholder="M"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200 text-center" />
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-venus-mars text-[#FFA600] text-xs"></i>
                                        Gender
                                    </label>
                                    <select v-model="editAdmin.gender"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 text-gray-700 hover:shadow-md transition-all duration-200">
                                        <option disabled value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-birthday-cake text-[#FFA600] text-xs"></i>
                                        Age
                                    </label>
                                    <input type="number" v-model="editAdmin.age" placeholder="Enter age" min="18"
                                        max="100"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200" />
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-user-tag text-[#FFA600] text-xs"></i>
                                        Role <span class="text-red-500">*</span>
                                    </label>
                                    <select v-model="editAdmin.role"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 text-gray-700 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.role }">
                                        <option disabled value="">Select Role</option>
                                        <option value="Admin">Administrator</option>
                                        <option value="Staff">Staff Member</option>
                                    </select>
                                    <p v-if="errors.role" class="text-red-500 text-xs mt-1">{{ errors.role }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-[#5F1213] mb-6 flex items-center gap-2">
                                <i class="fas fa-address-book text-[#FFA600]"></i>
                                Contact Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-envelope text-[#FFA600] text-xs"></i>
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" v-model="editAdmin.email" placeholder="Enter email address"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.email }" @blur="validateEmail" />
                                    <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-phone text-[#FFA600] text-xs"></i>
                                        Contact Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" v-model="editAdmin.contact" placeholder="Enter contact number"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.contact }" />
                                    <p v-if="errors.contact" class="text-red-500 text-xs mt-1">{{ errors.contact }}</p>
                                </div>
                                <div class="md:col-span-2 group">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt text-[#FFA600] text-xs"></i>
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" v-model="editAdmin.address" placeholder="Enter full address"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] bg-gray-50 hover:shadow-md transition-all duration-200"
                                        :class="{ 'border-red-500': errors.address }" />
                                    <p v-if="errors.address" class="text-red-500 text-xs mt-1">{{ errors.address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <div class="mb-8">
                            <button @click="showPasswordSection = !showPasswordSection"
                                class="flex items-center gap-2 text-lg font-semibold text-[#5F1213] hover:text-[#FFA600] transition-all duration-200 group">
                                <i
                                    class="fas fa-key text-[#FFA600] group-hover:rotate-12 transition-transform duration-200"></i>
                                {{ showPasswordSection ? 'Cancel Password Change' : 'Change Password' }}
                                <i :class="showPasswordSection ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                    class="text-sm transition-transform duration-200"></i>
                            </button>

                            <!-- Password Fields -->
                            <div v-if="showPasswordSection"
                                class="mt-6 bg-gradient-to-r from-[#FFF6E5] to-[#FFF8E1] border-2 border-[#FFA600]/30 rounded-2xl p-6 shadow-lg animate-slide-down">
                                <h3 class="text-xl font-bold text-[#5F1213] mb-6 flex items-center gap-2">
                                    <i class="fas fa-lock text-[#FFA600]"></i>Change Your Password
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">New
                                            Password</label>
                                        <input :type="showPassword ? 'text' : 'password'" v-model="newPassword"
                                            class="w-full px-4 py-3 pr-12 border-2 rounded-xl bg-white border-[#FFA600]/50 focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm transition-all duration-200"
                                            placeholder="Enter new password" @input="checkPasswordStrength" />
                                        <span class="absolute inset-y-0 right-3 top-6 flex items-center cursor-pointer"
                                            @click="showPassword = !showPassword">
                                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                                class="text-[#5F1213] text-sm hover:text-[#FFA600] transition-colors"></i>
                                        </span>
                                        <!-- Password Strength Indicator -->
                                        <div class="mt-2">
                                            <div class="flex gap-1">
                                                <div :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-gray-300'"
                                                    class="h-1 flex-1 rounded-full transition-colors duration-200">
                                                </div>
                                                <div :class="passwordStrength >= 2 ? 'bg-yellow-500' : 'bg-gray-300'"
                                                    class="h-1 flex-1 rounded-full transition-colors duration-200">
                                                </div>
                                                <div :class="passwordStrength >= 3 ? 'bg-green-500' : 'bg-gray-300'"
                                                    class="h-1 flex-1 rounded-full transition-colors duration-200">
                                                </div>
                                            </div>
                                            <p class="text-xs mt-1" :class="passwordStrengthText.color">{{
                                                passwordStrengthText.text }}</p>
                                        </div>
                                    </div>
                                    <div class="relative group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm
                                            Password</label>
                                        <input :type="showPassword ? 'text' : 'password'" v-model="confirmPassword"
                                            class="w-full px-4 py-3 pr-12 border-2 rounded-xl bg-white border-[#FFA600]/50 focus:ring-2 focus:ring-[#FFA600] focus:border-[#FFA600] shadow-sm transition-all duration-200"
                                            placeholder="Confirm new password" />
                                        <span class="absolute inset-y-0 right-3 top-6 flex items-center cursor-pointer"
                                            @click="showPassword = !showPassword">
                                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                                class="text-[#5F1213] text-sm hover:text-[#FFA600] transition-colors"></i>
                                        </span>
                                        <p v-if="confirmPassword && newPassword !== confirmPassword"
                                            class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Passwords do not match
                                        </p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 mt-6 bg-white/50 rounded-lg p-4">
                                    <h4 class="font-semibold mb-2 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-[#FFA600]"></i>
                                        Password Requirements:
                                    </h4>
                                    <ul class="text-xs space-y-1 ml-6">
                                        <li class="flex items-center gap-2">
                                            <i
                                                :class="newPassword.length >= 8 ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
                                            At least 8 characters
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i
                                                :class="/(?=.*[a-z])/.test(newPassword) ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
                                            One lowercase letter
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i
                                                :class="/(?=.*[A-Z])/.test(newPassword) ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
                                            One uppercase letter
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i
                                                :class="/(?=.*\d)/.test(newPassword) ? 'fas fa-check text-green-500' : 'fas fa-times text-red-500'"></i>
                                            One number
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-end gap-4">
                        <button @click="handleModalClose"
                            class="bg-gray-300 text-[#5F1213] font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-gray-400 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button @click="saveChanges"
                            class="bg-[#5F1213] text-white font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-[#FFA600] hover:text-[#5F1213] hover:shadow-lg transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isSaving">
                            <i v-if="isSaving" class="fas fa-spinner fa-spin"></i>
                            <i v-else class="fas fa-save"></i>
                            {{ isSaving ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>

</template>


<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import SiteHeader from './AdminChoiceHeader.vue'
import Footer from '../footer.vue'
import Swal from 'sweetalert2'

const page = usePage()
const admin = computed(() => page.props.admin)

onMounted(() => {
    // Check for flash messages
    if (page.props.flash && page.props.flash.success) {
        Swal.fire({
            title: 'Success',
            text: page.props.flash.success,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    if (page.props.flash && page.props.flash.error) {
        Swal.fire({
            title: 'Error',
            text: page.props.flash.error,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
})

const updateMenuOpen = ref(false)
const reportsMenuOpen = ref(false)
const accountMenuOpen = ref(false)

const showModal = ref(false)
const showPassword = ref(false)
const showPasswordSection = ref(false)
const newPassword = ref('')
const confirmPassword = ref('')
const isSaving = ref(false)

// Validation errors
const errors = reactive({
    first_name: '',
    last_name: '',
    email: '',
    address: '',
    contact: '',
    role: ''
})

// Password strength
const passwordStrength = ref(0)
const passwordStrengthText = computed(() => {
    const levels = [
        { text: 'Very Weak', color: 'text-red-500' },
        { text: 'Weak', color: 'text-red-400' },
        { text: 'Fair', color: 'text-yellow-500' },
        { text: 'Good', color: 'text-blue-500' },
        { text: 'Strong', color: 'text-green-500' }
    ]
    return levels[passwordStrength.value] || levels[0]
})

// Prefill editAdmin with admin info for modal
const editAdmin = reactive({
    id: '',
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

// Store original values for dirty check
const originalAdmin = reactive({
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

// Check if form has unsaved changes
const hasUnsavedChanges = computed(() => {
    return (
        editAdmin.first_name !== originalAdmin.first_name ||
        editAdmin.last_name !== originalAdmin.last_name ||
        editAdmin.middle_initial !== originalAdmin.middle_initial ||
        editAdmin.email !== originalAdmin.email ||
        editAdmin.address !== originalAdmin.address ||
        editAdmin.contact !== originalAdmin.contact ||
        editAdmin.gender !== originalAdmin.gender ||
        editAdmin.age !== originalAdmin.age ||
        editAdmin.role !== originalAdmin.role ||
        editAdmin.profile_picture !== originalAdmin.profile_picture ||
        (showPasswordSection.value && newPassword.value)
    )
})

function onProfileImageChange(e) {
    const file = e.target.files[0]
    if (file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select an image smaller than 5MB.'
            })
            return
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please select a valid image file.'
            })
            return
        }

        profileImagePreview.value = URL.createObjectURL(file)
        editAdmin.profile_picture = file
    }
}

function validateEmail() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!editAdmin.email) {
        errors.email = 'Email is required'
    } else if (!emailRegex.test(editAdmin.email)) {
        errors.email = 'Please enter a valid email address'
    } else {
        errors.email = ''
    }
}

function checkPasswordStrength() {
    let strength = 0
    const password = newPassword.value

    if (password.length >= 8) strength++
    if (/(?=.*[a-z])/.test(password)) strength++
    if (/(?=.*[A-Z])/.test(password)) strength++
    if (/(?=.*\d)/.test(password)) strength++
    if (/(?=.*[@$!%*?&])/.test(password)) strength++

    passwordStrength.value = strength
}

function clearErrors() {
    Object.keys(errors).forEach(key => {
        errors[key] = ''
    })
}

function handleModalClose() {
    if (hasUnsavedChanges.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Unsaved Changes',
            text: 'You have unsaved changes. Are you sure you want to close?',
            showCancelButton: true,
            confirmButtonText: 'Close',
            cancelButtonText: 'Stay'
        }).then((result) => {
            if (result.isConfirmed) {
                closeModal()
            }
        })
    } else {
        closeModal()
    }
}

function closeModal() {
    showModal.value = false
    showPasswordSection.value = false
    newPassword.value = ''
    confirmPassword.value = ''
    profileImagePreview.value = ''
    clearErrors()
}

watch(showModal, (val) => {
    if (val && admin.value) {
        // Copy current admin data to edit form
        Object.assign(editAdmin, {
            id: admin.value.id || '',
            first_name: admin.value.first_name || '',
            last_name: admin.value.last_name || '',
            middle_initial: admin.value.middle_initial || '',
            email: admin.value.email || '',
            address: admin.value.address || '',
            contact: admin.value.contact || '',
            gender: admin.value.gender || '',
            age: admin.value.age || '',
            role: admin.value.role || 'Admin',
            profile_picture: admin.value.profile_picture || ''
        })

        // Store original values for dirty check
        Object.assign(originalAdmin, editAdmin)

        profileImagePreview.value = ''
        clearErrors()
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

const saveChanges = async () => {
    clearErrors()

    // Client-side required field validation
    const requiredFields = [
        { key: 'first_name', label: 'First Name' },
        { key: 'last_name', label: 'Last Name' },
        { key: 'email', label: 'Email' },
        { key: 'role', label: 'Role' },
        { key: 'address', label: 'Address' },
        { key: 'contact', label: 'Contact Number' }
    ]

    let hasErrors = false
    for (const field of requiredFields) {
        if (!editAdmin[field.key] || editAdmin[field.key].toString().trim() === '') {
            errors[field.key] = `${field.label} is required.`
            hasErrors = true
        }
    }

    // Email validation
    validateEmail()
    if (errors.email) hasErrors = true

    // Password validation
    if (showPasswordSection.value) {
        if (!newPassword.value) {
            Swal.fire({ icon: 'warning', title: 'Password Required', text: 'Please enter a new password.' })
            return
        }
        if (newPassword.value !== confirmPassword.value) {
            Swal.fire({ icon: 'error', title: 'Password Mismatch', text: 'Passwords do not match!' })
            return
        }
        if (passwordStrength.value < 3) {
            Swal.fire({ icon: 'warning', title: 'Weak Password', text: 'Please choose a stronger password.' })
            return
        }
    }

    if (hasErrors) {
        Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please fix the errors and try again.' })
        return
    }

    const result = await Swal.fire({
        icon: 'question',
        title: 'Are you sure to save changes?',
        text: 'This will update your profile information.',
        confirmButtonText: 'Save Changes',
        showCancelButton: true,
        cancelButtonText: 'Cancel'
    })

    if (!result.isConfirmed) return

    isSaving.value = true

    try {
        const formData = new FormData()
        console.log('editAdmin data:', editAdmin)
        for (const key in editAdmin) {
            if (key === 'profile_picture' && editAdmin[key] instanceof File) {
                formData.append(key, editAdmin[key])
            } else if (key !== 'profile_picture') {
                // Ensure required fields are not empty strings
                const value = editAdmin[key]
                if (value !== null && value !== undefined && value !== '') {
                    formData.append(key, value)
                } else if (['first_name', 'last_name', 'email', 'address', 'contact', 'gender', 'age'].includes(key)) {
                    // For required fields, ensure they have a value
                    formData.append(key, value || '')
                }
            }
        }
        console.log('FormData contents:')
        for (let [key, value] of formData.entries()) {
            console.log(key, value)
        }
        if (showPasswordSection.value && newPassword.value) {
            formData.append('password', newPassword.value)
        }

        await new Promise((resolve, reject) => {
            router.put(`/admin/profile/update`, formData, {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    router.reload({ only: ['admin'] })
                    closeModal()
                    // Check for flash messages
                    if (page.props.flash?.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated',
                            text: page.props.flash.success,
                            timer: 3000,
                            showConfirmButton: false
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated',
                            text: 'Your profile information has been updated successfully.',
                            timer: 3000,
                            showConfirmButton: false
                        })
                    }
                    resolve()
                },
                onError: (errors) => {
                    // Handle server-side validation errors
                    if (errors.email) {
                        Swal.fire({ icon: 'error', title: 'Email Error', text: errors.email })
                    } else if (errors.contact) {
                        Swal.fire({ icon: 'error', title: 'Contact Error', text: errors.contact })
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while saving. Please try again.' })
                    }
                    reject(errors)
                }
            })
        })
    } catch (error) {
        console.error('Save error:', error)
    } finally {
        isSaving.value = false
    }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
@import url('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');

body {
    font-family: 'Inter', sans-serif;
}

/* Modal Animations */
@keyframes fade-in {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-slide-down {
    animation: slide-down 0.4s ease-out;
}

/* Custom scrollbar for modal content */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #FFA600;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #e69500;
}

/* Focus styles for accessibility */
input:focus,
select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 166, 0, 0.1);
}

/* Loading spinner animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Hover effects for interactive elements */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:rotate-12 {
    transform: rotate(12deg);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .max-w-6xl {
        max-width: 95vw;
    }

    .p-8 {
        padding: 1rem;
    }

    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
