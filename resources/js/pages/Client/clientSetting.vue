<template>
  <div class="min-h-screen py-24 flex flex-col font-sans">
    <SiteHeader />

    <main class="flex-grow flex justify-center items-center px-4">
      <div class="w-full max-w-5xl bg-white shadow-xl rounded-2xl px-8 py-12 flex flex-col relative"
        style="min-height: 500px;">

        <!-- Profile Header -->
        <div class="text-center mb-8">
          <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-yellow-500 shadow-lg mb-4">
            <img :src="client.image ? '/storage/' + client.image : '/images/Client/default_profile.png'"
              alt="Profile Picture" class="w-full h-full object-cover" />
          </div>
          <h1 class="text-4xl font-bold text-gray-800 mb-2">
            {{ client.firstName }} {{ client.middleName ? client.middleName + ' ' : '' }}{{ client.lastName }}{{ client.extension ? ' ' + client.extension : '' }}
          </h1>
          <p class="text-xl text-gray-600">{{ client.email }}</p>
        </div>

        <!-- Personal Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <!-- Contact Information -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Contact</h3>
            </div>
            <p class="text-gray-600">{{ client.contactNum || 'Not provided' }}</p>
          </div>

          <!-- Personal Details -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Personal</h3>
            </div>
            <p class="text-gray-600"><span class="font-medium">Gender:</span> {{ client.gender || 'Not specified' }}</p>
            <p class="text-gray-600"><span class="font-medium">Age:</span> {{ client.age || 'Not provided' }}</p>
          </div>

          <!-- Address -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 md:col-span-2 lg:col-span-1">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Address</h3>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">{{ client.address || 'No address provided' }}</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
          <button @click="showProfileModal = true"
            class="flex items-center justify-center space-x-3 px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl hover:from-yellow-600 hover:to-yellow-700 hover:shadow-xl transition-all duration-300 font-semibold transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Edit Profile</span>
          </button>
          <button @click="openPurchaseHistoryModal"
            class="flex items-center justify-center space-x-3 px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 hover:shadow-xl transition-all duration-300 font-semibold transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span>Purchase History</span>
          </button>
          <button @click="openBookingHistoryModal"
            class="flex items-center justify-center space-x-3 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 hover:shadow-xl transition-all duration-300 font-semibold transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m0 0l-2-2m2 2l2-2m2 2V9a2 2 0 00-2-2H8a2 2 0 00-2 2v6.01"></path>
            </svg>
            <span>Booking History</span>
          </button>
        </div>
      </div>
    </main>

    <!-- Profile Modal -->
    <div v-if="showProfileModal" class="fixed inset-0 flex items-center justify-center z-50 p-4"
      style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);">
      <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-100">
        <div class="sticky top-0 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-8 py-6 flex justify-between items-center rounded-t-2xl">
          <h2 class="text-3xl font-bold">Edit Profile</h2>
          <button @click="closeProfileModal" class="text-white hover:text-gray-200 text-3xl hover:bg-white hover:bg-opacity-20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-200">
            ×
          </button>
        </div>

        <div class="p-6">
          <div class="flex flex-col items-center space-y-2 mb-6">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-yellow-500">
              <img :src="previewImage || (client.image ? '/storage/' + client.image : '/images/default-profile.png')"
                alt="Profile" class="w-full h-full object-cover" />
            </div>
            <label class="cursor-pointer bg-yellow-500 text-maroon px-3 py-1 rounded hover:shadow transition text-sm">
              Upload Photo
              <input type="file" class="hidden" @change="handleImageUpload" accept="image/*" />
            </label>
          </div>

          <form @submit.prevent="saveProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm text-gray-600 mb-1">First Name</label>
                <input v-model="form.firstName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Middle Name</label>
                <input v-model="form.middleName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                <input v-model="form.lastName" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Name Extension</label>
                <input v-model="form.extension" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input v-model="form.email" type="email"
                  class="w-full border px-3 py-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" disabled />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Contact Number</label>
                <input v-model="form.contactNum" type="text" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Gender</label>
                <select v-model="form.gender" class="w-full border px-3 py-2 rounded text-black">
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm text-gray-600 mb-1">Age</label>
                <input v-model="form.age" type="number" class="w-full border px-3 py-2 rounded text-black" />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Address</label>
                <textarea v-model="form.address" rows="3" class="w-full border px-3 py-2 rounded text-black"></textarea>
              </div>
            </div>

            <div class="mt-4 text-left">
              <button type="button" @click="handleChangePassword" class="text-yellow-600 text-sm hover:underline">Change
                Password?</button>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeProfileModal"
                class="px-5 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Cancel
              </button>
              <button type="submit"
                class="bg-yellow-500 text-maroon px-5 py-2 rounded hover:shadow hover:scale-105 transition">
                Save Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Change Password Modal -->
    <div v-if="showPasswordModal" class="fixed inset-0 flex items-center justify-center z-50 p-4"
      style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-100">
        <div class="sticky top-0 bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-6 flex justify-between items-center rounded-t-2xl">
          <h2 class="text-3xl font-bold">Change Password</h2>
          <button @click="closePasswordModal" class="text-white hover:text-gray-200 text-3xl hover:bg-white hover:bg-opacity-20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-200">
            ×
          </button>
        </div>

        <div class="p-6">
          <p class="text-center text-gray-600 mb-6">
            Please enter your new password. An OTP will be sent to your email.
          </p>

          <!-- Step 1: Password form -->
          <form v-if="!otpSent" @submit.prevent="sendPasswordOtp">
            <div class="mb-4">
              <label class="block text-sm font-medium mb-1">New Password</label>
              <input
                type="password"
                v-model="passwordForm.password"
                placeholder="Enter new password"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
              />
              <p class="text-sm text-red-500 mt-1" v-if="passwordForm.errors?.password">{{ passwordForm.errors.password }}</p>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium mb-1">Confirm Password</label>
              <input
                type="password"
                v-model="passwordForm.password_confirmation"
                placeholder="Confirm new password"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
              />
              <p class="text-sm text-red-500 mt-1" v-if="passwordForm.errors?.password_confirmation">
                {{ passwordForm.errors.password_confirmation }}
              </p>
            </div>

            <button
              type="submit"
              class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2 rounded-full transition"
              :disabled="passwordForm.processing"
            >
              {{ passwordForm.processing ? 'Sending OTP...' : 'Update Password' }}
            </button>
          </form>

          <!-- Step 2: OTP Verification -->
          <form v-else @submit.prevent="submitPasswordOtp">
            <div class="mb-4">
              <label class="block text-sm font-medium mb-1">OTP Code</label>
              <input
                type="text"
                v-model="passwordForm.otp"
                placeholder="Enter OTP"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600"
              />
              <p class="text-sm text-red-500 mt-1" v-if="passwordForm.errors?.otp">{{ passwordForm.errors.otp }}</p>

              <div class="text-right mt-1">
                <button type="button" @click="resendPasswordOtp" class="text-xs text-blue-600 hover:underline">
                  Resend OTP
                </button>
              </div>
            </div>

            <button
              type="submit"
              class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-full transition"
              :disabled="passwordForm.processing"
            >
              {{ passwordForm.processing ? 'Verifying...' : 'Confirm Password Change' }}
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Purchase History Modal -->
    <div v-if="showHistoryModal" class="fixed inset-0 flex items-center justify-center z-50 p-4"
      style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);">
      <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-100">
        <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-6 flex justify-between items-center rounded-t-2xl">
          <h2 class="text-3xl font-bold flex items-center">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            Purchase History
          </h2>
          <button @click="showHistoryModal = false" class="text-white hover:text-gray-200 text-3xl hover:bg-white hover:bg-opacity-20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-200">
            ×
          </button>
        </div>

        <div class="p-6">
          <!-- Loading State -->
          <div v-if="historyLoading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading your orders...</p>
          </div>

          <!-- No Orders Message -->
          <div v-else-if="purchaseHistory.length === 0" class="text-center py-8">
            <div class="text-gray-400 mb-4">
              <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Orders Yet</h3>
            <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
            <button @click="goToMarket"
              class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
              Start Shopping
            </button>
          </div>

          <!-- Orders List -->
          <div v-else class="space-y-4">
            <div
              v-for="order in purchaseHistory"
              :key="order.id"
              class="bg-white rounded-lg shadow hover:shadow-md transition-shadow"
            >
              <!-- Order Header -->
              <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <div>
                      <h3 class="font-semibold text-lg text-gray-800">
                        Order #{{ order.id }}
                      </h3>
                      <p class="text-sm text-gray-600">
                        Placed on {{ formatDate(order.created_at) }}
                      </p>
                    </div>
                    <div class="mt-2 md:mt-0">
                      <span
                        :class="getStatusClass(order.status)"
                        class="px-3 py-1 rounded-full text-xs font-medium"
                      >
                        {{ getStatusText(order.status) }}
                      </span>
                    </div>
                  </div>
                  <div class="mt-2 md:mt-0 text-right">
                    <p class="text-lg font-bold text-maroon">₱{{ order.total_amount }}</p>
                    <p class="text-sm text-gray-600">{{ order.order_items_count }} item(s)</p>
                  </div>
                </div>
              </div>

              <!-- Order Details -->
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <h4 class="font-medium text-gray-700 mb-2">Customer Information</h4>
                    <p class="text-sm text-gray-600">{{ order.full_name }}</p>
                    <p class="text-sm text-gray-600">{{ order.contact }}</p>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-700 mb-2">Order Date</h4>
                    <p class="text-sm text-gray-600">{{ formatDate(order.order_date) }}</p>
                  </div>
                </div>

                <!-- Order Items -->
                <div>
                  <h4 class="font-medium text-gray-700 mb-3">Items Ordered</h4>
                  <div class="space-y-2">
                    <div
                      v-for="item in order.order_items"
                      :key="item.id"
                      class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                    >
                      <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-medium text-gray-800">{{ item.product_name }}</h5>
                          <p class="text-sm text-gray-600">
                            {{ item.quantity }} {{ item.metric }} × ₱{{ parseFloat(item.unit_price).toFixed(2) }}
                          </p>
                        </div>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold text-maroon">₱{{ parseFloat(item.subtotal).toFixed(2) }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex justify-between items-center">
                  <div class="text-sm text-gray-600">
                    Order Status: <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-medium ml-1">
                      {{ getStatusText(order.status) }}
                    </span>
                  </div>
                  <button
                    @click="downloadOrderPOS(order)"
                    class="px-4 py-2 bg-maroon text-white text-sm rounded-lg hover:bg-red-800 transition-colors"
                  >
                    Download POS
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Load More Button -->
          <div v-if="hasMoreOrders" class="text-center mt-6">
            <button
              @click="loadMoreOrders"
              :disabled="isLoadingMore"
              class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors disabled:opacity-50">
              <span v-if="isLoadingMore">Loading...</span>
              <span v-else>Load More Orders</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking History Modal -->
    <div v-if="showBookingModal" class="fixed inset-0 flex items-center justify-center z-50 p-4"
      style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px);">
      <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-maroon">Booking History</h2>
          <button @click="showBookingModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">
            ×
          </button>
        </div>

        <div class="p-6">
          <!-- Loading State -->
          <div v-if="bookingLoading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading your bookings...</p>
          </div>

          <!-- No Bookings Message -->
          <div v-else-if="bookingHistory.length === 0" class="text-center py-8">
            <div class="text-gray-400 mb-4">
              <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m0 0l-2-2m2 2l2-2m2 2V9a2 2 0 00-2-2H8a2 2 0 00-2 2v6.01"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Bookings Yet</h3>
            <p class="text-gray-500 mb-4">You haven't made any bookings yet.</p>
            <button @click="goToFacilities"
              class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
              Book a Facility
            </button>
          </div>

          <!-- Bookings List -->
          <div v-else class="space-y-4">
            <div v-for="booking in bookingHistory" :key="booking.id"
              class="bg-white border rounded-lg shadow hover:shadow-md transition-shadow">
              <!-- Booking Header -->
              <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <div>
                      <h3 class="font-semibold text-lg text-gray-800">
                        Booking #{{ booking.id }}
                      </h3>
                      <p class="text-sm text-gray-600">
                        Created on {{ formatDate(booking.created_at) }}
                      </p>
                    </div>
                    <div class="mt-2 md:mt-0">
                      <span :class="getBookingStatusClass(booking.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                        {{ getBookingStatusText(booking.status) }}
                      </span>
                    </div>
                  </div>
                  <div class="mt-2 md:mt-0 text-right">
                    <p class="text-lg font-bold text-maroon">₱{{ booking.total_amount || 0 }}</p>
                    <p class="text-sm text-gray-600">{{ booking.facility_name || 'Facility' }}</p>
                  </div>
                </div>
              </div>

              <!-- Booking Details -->
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <h4 class="font-medium text-gray-700 mb-2">Booking Information</h4>
                    <p class="text-sm text-gray-600"><strong>Facility:</strong> {{ booking.facility_name || 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>Date:</strong> {{ formatDate(booking.booking_date) }}</p>
                    <p class="text-sm text-gray-600"><strong>Time:</strong> {{ booking.start_time }} - {{ booking.end_time }}</p>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-700 mb-2">Contact Details</h4>
                    <p class="text-sm text-gray-600"><strong>Name:</strong> {{ booking.full_name }}</p>
                    <p class="text-sm text-gray-600"><strong>Contact:</strong> {{ booking.contact }}</p>
                    <p class="text-sm text-gray-600"><strong>Purpose:</strong> {{ booking.purpose || 'N/A' }}</p>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex justify-between items-center">
                  <div class="text-sm text-gray-600">
                    Status: <span :class="getBookingStatusClass(booking.status)"
                      class="px-2 py-1 rounded-full text-xs font-medium ml-1">
                      {{ getBookingStatusText(booking.status) }}
                    </span>
                  </div>
                  <div class="flex space-x-2">
                    <button v-if="booking.status === 'confirmed'" @click="downloadBookingReceipt(booking)"
                      class="px-4 py-2 bg-maroon text-white text-sm rounded-lg hover:bg-red-800 transition-colors">
                      Download Receipt
                    </button>
                    <button v-if="canCancelBooking(booking)" @click="cancelBooking(booking)"
                      class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition-colors">
                      Cancel Booking
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <SiteFooter />
  </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import SiteHeader from '../zClientChoicePage/cHeader.vue'
import SiteFooter from '../footer.vue'

// Props from backend
const props = defineProps({
  client: Object
})

// Modal states
const showProfileModal = ref(false)
const showHistoryModal = ref(false)
const showBookingModal = ref(false)
const showPasswordModal = ref(false)
const historyLoading = ref(false)
const bookingLoading = ref(false)

// History data
const purchaseHistory = ref([])
const bookingHistory = ref([])
const hasMoreOrders = ref(false)
const currentPage = ref(1)
const isLoadingMore = ref(false)

// Profile form data
const previewImage = ref(null)
const imageFile = ref(null)
const form = ref({
  firstName: props.client?.firstName || '',
  middleName: props.client?.middleName || '',
  lastName: props.client?.lastName || '',
  extension: props.client?.extension || '',
  email: props.client?.email || '',
  contactNum: props.client?.contactNum || '',
  gender: props.client?.gender || '',
  age: props.client?.age || '',
  address: props.client?.address || '',
})

// Password form data
const passwordForm = ref({
  password: '',
  password_confirmation: '',
  otp: ''
})
const otpSent = ref(false)

// Protect route: redirect to /signin if not authenticated
onMounted(() => {
  if (!props.client) {
    router.visit('/signin')
  }
})

// Fetch purchase history function
const fetchPurchaseHistory = async (page = 1) => {
  historyLoading.value = true

  try {
    const response = await fetch(`/buy-history?page=${page}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })

    if (response.ok) {
      const data = await response.json()
      if (page === 1) {
        purchaseHistory.value = data.orders
      } else {
        purchaseHistory.value = [...purchaseHistory.value, ...data.orders]
      }
      hasMoreOrders.value = data.hasMoreOrders || false
      currentPage.value = page
    } else {
      console.error('Error fetching purchase history:', response.statusText)
    }
  } catch (error) {
    console.error('Error fetching purchase history:', error)
  } finally {
    historyLoading.value = false
  }
}

// Load more orders function
const loadMoreOrders = async () => {
  if (isLoadingMore.value) return

  isLoadingMore.value = true

  try {
    const response = await fetch(`/buy-history?page=${currentPage.value + 1}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })

    if (response.ok) {
      const data = await response.json()
      purchaseHistory.value = [...purchaseHistory.value, ...data.orders]
      hasMoreOrders.value = data.hasMoreOrders || false
      currentPage.value = currentPage.value + 1
    } else {
      console.error('Error loading more orders:', response.statusText)
    }
  } catch (error) {
    console.error('Error loading more orders:', error)
  } finally {
    isLoadingMore.value = false
  }
}

// Open purchase history modal and fetch data
const openPurchaseHistoryModal = () => {
  showHistoryModal.value = true
  hasMoreOrders.value = false
  currentPage.value = 1
  isLoadingMore.value = false
  fetchPurchaseHistory(1)
}

// Fetch booking history function
const fetchBookingHistory = async () => {
  bookingLoading.value = true

  try {
    const response = await fetch('/booking-history', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })

    if (response.ok) {
      const data = await response.json()
      bookingHistory.value = data
    } else {
      console.error('Error fetching booking history:', response.statusText)
    }
  } catch (error) {
    console.error('Error fetching booking history:', error)
  } finally {
    bookingLoading.value = false
  }
}

// Open booking history modal and fetch data
const openBookingHistoryModal = () => {
  showBookingModal.value = true
  fetchBookingHistory()
}

// Profile Modal Functions
const closeProfileModal = () => {
  showProfileModal.value = false
  // Reset form and image preview
  form.value = {
    firstName: props.client?.firstName || '',
    middleName: props.client?.middleName || '',
    lastName: props.client?.lastName || '',
    extension: props.client?.extension || '',
    email: props.client?.email || '',
    contactNum: props.client?.contactNum || '',
    gender: props.client?.gender || '',
    age: props.client?.age || '',
    address: props.client?.address || '',
  }
  previewImage.value = null
  imageFile.value = null
}

// Form validation
const validateForm = () => {
  const errors = {}

  // Required fields
  if (!form.value.firstName?.trim()) errors.firstName = 'First name is required'
  if (!form.value.lastName?.trim()) errors.lastName = 'Last name is required'
  if (!form.value.email?.trim()) errors.email = 'Email is required'
  if (!form.value.contactNum?.trim()) errors.contactNum = 'Contact number is required'

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (form.value.email && !emailRegex.test(form.value.email)) {
    errors.email = 'Please enter a valid email address'
  }

  // Contact number validation (Philippine format)
  const contactRegex = /^(\+63|0)[9][0-9]{9}$/
  if (form.value.contactNum && !contactRegex.test(form.value.contactNum.replace(/\s/g, ''))) {
    errors.contactNum = 'Please enter a valid Philippine mobile number'
  }

  // Age validation
  if (form.value.age && (form.value.age < 18 || form.value.age > 100)) {
    errors.age = 'Age must be between 18 and 100'
  }

  return errors
}

const saveProfile = () => {
  // Validate form
  const validationErrors = validateForm()
  if (Object.keys(validationErrors).length > 0) {
    const errorMessages = Object.values(validationErrors).join('\n')
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: errorMessages,
      confirmButtonColor: '#EF4444'
    })
    return
  }

  const formData = new FormData()
  for (const key in form.value) {
    if (form.value[key] !== null && form.value[key] !== undefined) {
      formData.append(key, form.value[key])
    }
  }

  if (imageFile.value) {
    formData.append('image', imageFile.value)
  }

  // Show loading state
  const saveButton = document.querySelector('button[type="submit"]')
  if (saveButton) {
    saveButton.disabled = true
    saveButton.textContent = 'Saving...'
  }

  router.post('/client/setting', formData, {
    forceFormData: true,
    onSuccess: () => {
      showProfileModal.value = false
      // Show SweetAlert success message
      Swal.fire({
        icon: 'success',
        title: 'Profile Updated!',
        text: 'Your profile has been updated successfully.',
        confirmButtonColor: '#EAB308',
        confirmButtonText: 'OK'
      }).then(() => {
        // Refresh the page to show updated data
        window.location.reload()
      })
    },
    onError: (errors) => {
      console.error(errors)
      // Reset button state
      if (saveButton) {
        saveButton.disabled = false
        saveButton.textContent = 'Save Profile'
      }

      // Show SweetAlert error message
      Swal.fire({
        icon: 'error',
        title: 'Update Failed',
        text: 'There were validation errors. Please check your input and try again.',
        confirmButtonColor: '#EF4444',
        confirmButtonText: 'OK'
      })
    }
  })
}

const handleImageUpload = (e) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    previewImage.value = URL.createObjectURL(file)
  }
}

const handleChangePassword = () => {
  // Close profile modal and open password modal
  showProfileModal.value = false
  showPasswordModal.value = true
}

const closePasswordModal = () => {
  showPasswordModal.value = false
  // Reset password form
  passwordForm.value = {
    password: '',
    password_confirmation: '',
    otp: ''
  }
  otpSent.value = false
}

const sendPasswordOtp = async () => {
  if (!passwordForm.password || passwordForm.password !== passwordForm.password_confirmation) {
    passwordForm.setError('password_confirmation', 'Passwords do not match.')
    return
  }

  try {
    await axios.post('/send-password-otp')
    otpSent.value = true

    Swal.fire({
      icon: 'success',
      title: 'OTP Sent!',
      text: 'Check your email for the OTP code.',
      timer: 2500,
      showConfirmButton: false,
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Failed to send OTP',
      text: error.response?.data?.error || 'Please try again later.',
    })
  }
}

const resendPasswordOtp = async () => {
  try {
    await axios.post('/send-password-otp')

    Swal.fire({
      icon: 'success',
      title: 'OTP Resent',
      text: 'A new OTP has been sent to your email.',
      timer: 2000,
      showConfirmButton: false,
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Failed to resend OTP',
      text: error.response?.data?.error || 'Please try again later.',
    })
  }
}

const submitPasswordOtp = () => {
  passwordForm.post('/change-password', {
    preserveScroll: true,
    onSuccess: () => {
      showPasswordModal.value = false
      Swal.fire({
        icon: 'success',
        title: 'Password Changed!',
        text: 'Your password was updated successfully.',
      })

      // Reset password form
      passwordForm.reset()
      otpSent.value = false
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Password Change Failed',
        text: 'Please check your OTP or try again.',
      })
    }
  })
}

// History Modal Functions
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'ready': 'bg-green-100 text-green-800',
    'completed': 'bg-gray-100 text-gray-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    'pending': 'Pending',
    'processing': 'Processing',
    'ready': 'Ready for Pickup',
    'completed': 'Completed',
    'cancelled': 'Cancelled'
  }
  return texts[status] || 'Unknown'
}

const downloadOrderPOS = (order) => {
  // Helper function to determine product section
  function getProductSection(productName) {
    const tagumProducts = ['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra']
    const mabiniProducts = ['Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango', 'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo']

    const normalizedName = productName.toLowerCase()
    const matchesTagum = tagumProducts.some(product => normalizedName.includes(product.toLowerCase()))
    const matchesMabini = mabiniProducts.some(product => normalizedName.includes(product.toLowerCase()))

    if (matchesTagum) return 'tagum'
    if (matchesMabini) return 'mabini'
    return 'tagum'
  }

  // Initialize sections with all products
  const sections = [
    {
      title: 'CEDU TAGUM – CROP PRODUCTION',
      products: [
        'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
        'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Okra', 'Others:'
      ].map(name => ({
        name,
        qty: 0,
        unitCost: 0,
        selected: false,
        bananaType: name === 'Banana' ? '' : undefined,
        otherDetails: name === 'Others:' ? '' : undefined
      }))
    },
    {
      title: 'CEDU MABINI – CROP PRODUCTION',
      products: [
        'Banana', 'Coconut', 'Corn', 'Bamboo', 'Mango',
        'Durian', 'Mangosteen', 'Lanzones', 'Rambutan', 'Siling Labuyo', 'Others:'
      ].map(name => ({
        name,
        qty: 0,
        unitCost: 0,
        selected: false,
        bananaType: name === 'Banana' ? '' : undefined,
        otherDetails: name === 'Others:' ? '' : undefined
      }))
    }
  ]

  // Only proceed if order has items
  if (!order.order_items || order.order_items.length === 0) {
    alert('No items found in this order to generate POS.')
    return
  }

  // Populate sections with order items
  order.order_items.forEach(orderItem => {
    const sectionType = getProductSection(orderItem.product_name)
    const sectionIndex = sectionType === 'tagum' ? 0 : 1

    let productFound = false

    sections[sectionIndex].products.forEach(product => {
      const productNameLower = product.name.toLowerCase()
      const orderItemLower = orderItem.product_name.toLowerCase()

      if (productNameLower === orderItemLower ||
        (productNameLower !== 'others:' && orderItemLower.includes(productNameLower))) {
        product.selected = true
        product.qty = parseInt(orderItem.quantity) || 0
        product.unitCost = parseFloat(orderItem.unit_price) || 0
        productFound = true

        if (product.name === 'Banana' && orderItem.product_name.toLowerCase() !== 'banana') {
          product.bananaType = orderItem.product_name.replace(/banana/i, '').trim()
        }
      }
    })

    if (!productFound) {
      const othersProduct = sections[sectionIndex].products.find(p => p.name === 'Others:')
      if (othersProduct) {
        othersProduct.selected = true
        othersProduct.qty = parseInt(orderItem.quantity) || 0
        othersProduct.unitCost = parseFloat(orderItem.unit_price) || 0
        othersProduct.otherDetails = orderItem.product_name
      }
    }
  })

  const grandTotal = sections.reduce((total, section) => {
    return (
      total +
      section.products
        .filter(item => item.selected)
        .reduce((subtotal, item) => subtotal + item.qty * item.unitCost, 0)
    )
  }, 0).toFixed(2)

  // Create Word document content in HTML format that Word can read
  const wordContent = `
    <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word'>
    <head>
      <meta charset="utf-8">
      <title>ORDER PAYMENT SLIP</title>
      <!--[if gte mso 9]>
      <xml>
        <w:WordDocument>
          <w:View>Print</w:View>
          <w:Zoom>90</w:Zoom>
          <w:DoNotPromptForConvert/>
          <w:DoNotShowInsertionsAndDeletions/>
        </w:WordDocument>
      </xml>
      <![endif]-->
      <style>
        @page {
          size: 8.5in 11in;
          margin: 0.3in 0.4in;
        }
        body {
          font-family: Arial, sans-serif;
          font-size: 9pt;
          line-height: 1.1;
          color: black;
          margin: 0;
          padding: 0;
        }
        .header {
          text-align: center;
          margin-bottom: 8pt;
        }
        h1 {
          font-size: 10pt;
          margin: 1pt 0;
          font-weight: normal;
        }
        h2 {
          font-size: 11pt;
          margin: 1pt 0;
          font-weight: bold;
        }
        h3 {
          font-size: 9pt;
          margin: 1pt 0;
          font-style: italic;
          font-weight: normal;
        }
        .control-no {
          text-align: right;
          font-weight: bold;
          margin-top: 2pt;
          font-size: 9pt;
        }
        .basic-info {
          border: 1pt solid black;
          padding: 4pt;
          margin-bottom: 8pt;
          font-size: 9pt;
        }
        .basic-info p {
          margin: 1pt 0;
        }
        table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 8pt;
          font-size: 8pt;
        }
        th, td {
          border: 1pt solid #666;
          padding: 1pt 3pt;
          text-align: center;
          vertical-align: middle;
          line-height: 1.0;
        }
        th {
          background-color: #f0f0f0;
          font-weight: bold;
          font-size: 8pt;
        }
        .product-cell {
          text-align: left;
        }
        .section-header {
          background-color: #f5f5f5;
          font-weight: bold;
          text-align: left;
          font-size: 8pt;
        }
        .grand-total {
          text-align: right;
          font-weight: bold;
          font-size: 11pt;
          margin: 8pt 0;
        }
        .signatures {
          margin-top: 10pt;
          font-size: 9pt;
          line-height: 1.3;
        }
        .signatures p {
          margin: 4pt 0;
        }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Republic of the Philippines</h1>
        <h2>University of Southeastern Philippines</h2>
        <h3>Resource Management Division (RMD)</h3>
        <h2>ORDER PAYMENT SLIP (OPS/POS)</h2>
        <p class="control-no">Control No.: ${order.id}</p>
      </div>

      <div class="basic-info">
        <p><strong>Payor/Name:</strong> ${order.full_name}</p>
        <p><strong>Organization:</strong> CEDU CROP PRODUCTION PROJECT</p>
        <p><strong>Date:</strong> ${formatDate(order.order_date)}</p>
        <p>OTHER BUSINESS INCOME</p>
      </div>

      <table>
        <thead>
          <tr>
            <th style="width: 8%;">Select</th>
            <th style="width: 35%;">Product</th>
            <th style="width: 12%;">QTY</th>
            <th style="width: 20%;">UNIT COST</th>
            <th style="width: 25%;">TOTAL COST</th>
          </tr>
        </thead>
        <tbody>
          ${sections.map(section => `
            <tr class="section-header">
              <td colspan="5">${section.title}</td>
            </tr>
            ${section.products.map(item => `
              <tr>
                <td>${item.selected ? '☑' : '☐'}</td>
                <td class="product-cell">
                  ${item.name}${item.bananaType ? ' ' + item.bananaType : ''}${item.otherDetails ? ' ' + item.otherDetails : ''}
                </td>
                <td>${item.qty || 0}</td>
                <td>₱${(item.unitCost || 0).toFixed(2)}</td>
                <td>₱${(item.qty * item.unitCost).toFixed(2)}</td>
              </tr>
            `).join('')}
          `).join('')}
        </tbody>
      </table>

      <div class="grand-total">
        GRAND TOTAL COST: Php ${grandTotal}
      </div>

      <div class="signatures">
        <p>Prepared By: _________________________</p>
        <p>Staff: _________________________</p>
        <p>Cashier: _________________________</p>
        <p>OR No.: _________________________</p>
      </div>
    </body>
    </html>
  `

  // Create and download as Word document (.doc format)
  const blob = new Blob(['\ufeff', wordContent], {
    type: 'application/msword'
  })

  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `POS_Order_${order.id}_${new Date().toISOString().split('T')[0]}.doc`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
}

// Navigation Functions
const goToMarket = () => {
  showHistoryModal.value = false
  router.visit('/om-landing')
}

// Booking History Functions
const getBookingStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'confirmed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800',
    'completed': 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getBookingStatusText = (status) => {
  const texts = {
    'pending': 'Pending',
    'confirmed': 'Confirmed',
    'cancelled': 'Cancelled',
    'completed': 'Completed'
  }
  return texts[status] || 'Unknown'
}

const canCancelBooking = (booking) => {
  if (!booking) return false
  const bookingDate = new Date(booking.booking_date)
  const now = new Date()
  const timeDiff = bookingDate.getTime() - now.getTime()
  const daysDiff = timeDiff / (1000 * 3600 * 24)

  // Can cancel if booking is pending/confirmed and more than 24 hours away
  return (booking.status === 'pending' || booking.status === 'confirmed') && daysDiff > 1
}

const cancelBooking = (booking) => {
  Swal.fire({
    title: 'Cancel Booking?',
    text: 'Are you sure you want to cancel this booking?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Yes, cancel it'
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(`/booking/${booking.id}/cancel`, {}, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Booking Cancelled',
            text: 'Your booking has been cancelled successfully.',
            confirmButtonColor: '#10B981'
          }).then(() => {
            fetchBookingHistory() // Refresh the booking history
          })
        },
        onError: (errors) => {
          console.error('Error cancelling booking:', errors)
          Swal.fire({
            icon: 'error',
            title: 'Cancellation Failed',
            text: 'There was an error cancelling your booking. Please try again.',
            confirmButtonColor: '#EF4444'
          })
        }
      })
    }
  })
}

const downloadBookingReceipt = (booking) => {
  // Create a simple receipt for the booking
  const receiptContent = `
    <html>
    <head>
      <title>Booking Receipt</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .details { margin: 10px 0; }
        .total { font-weight: bold; font-size: 18px; margin-top: 20px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Booking Receipt</h1>
        <p>Booking #${booking.id}</p>
      </div>
      <div class="details">
        <p><strong>Facility:</strong> ${booking.facility_name || 'N/A'}</p>
        <p><strong>Date:</strong> ${formatDate(booking.booking_date)}</p>
        <p><strong>Time:</strong> ${booking.start_time} - ${booking.end_time}</p>
        <p><strong>Name:</strong> ${booking.full_name}</p>
        <p><strong>Contact:</strong> ${booking.contact}</p>
        <p><strong>Purpose:</strong> ${booking.purpose || 'N/A'}</p>
        <p><strong>Status:</strong> ${getBookingStatusText(booking.status)}</p>
      </div>
      <div class="total">
        <p>Total Amount: ₱${booking.total_amount || 0}</p>
      </div>
    </body>
    </html>
  `

  const blob = new Blob([receiptContent], { type: 'text/html' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `Booking_Receipt_${booking.id}.html`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
}

const goToFacilities = () => {
  showBookingModal.value = false
  router.visit('/facilities')
}

const handleSignOut = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/signin')
  })
}

const goBack = () => {
  router.get('/om-landing')
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}

.bg-maroon {
  background-color: #651818;
}

/* Ensure proper background */
.flex.flex-col.min-h-screen {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
}

/* Modal animations and backdrop */
.fixed[style*="backdrop-filter"] {
  animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Scrollbar styling for modal content */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #cbd5e1, #94a3b8);
  border-radius: 4px;
  border: 2px solid #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #94a3b8, #64748b);
}

/* Loading animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Button hover effects */
button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}

/* Form input focus states */
input:focus, select:focus, textarea:focus {
  box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
  border-color: #f59e0b;
}

/* Card hover effects */
.bg-white:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
}

/* Gradient text effects */
.gradient-text {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .grid.grid-cols-1.sm\\:grid-cols-3 {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .text-4xl {
    font-size: 2rem;
  }

  .text-3xl {
    font-size: 1.5rem;
  }
}
</style>
