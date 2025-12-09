<template>
    <div class="bg-white text-[#5F1213] min-h-screen font-sans">
      <Header />

      <!-- Hero Section -->
      <section
        class="relative bg-cover bg-center text-white py-32 md:py-40 flex items-center justify-center overflow-hidden"
        style="background-image: url('/images/Faci_Images/intro.png')"
      >
        <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70"></div>
        <!-- Animated background elements -->
        <div class="absolute top-20 left-10 w-32 h-32 bg-maroon/20 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-2xl animate-bounce"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
          <div class="mb-6">
            <span class="inline-block bg-yellow-400 text-maroon px-4 py-2 rounded-full text-sm font-semibold mb-4 animate-fade-in-up">
              Welcome to CEDU iCentral
            </span>
          </div>
          <h1 class="text-5xl md:text-7xl font-extrabold leading-tight drop-shadow-2xl mb-6 animate-fade-in-up animation-delay-200">
            Book Your <span class="text-yellow-400">Perfect</span> Space
          </h1>
          <p class="mt-6 text-xl md:text-2xl opacity-90 drop-shadow-md leading-relaxed animate-fade-in-up animation-delay-400">
            Discover premium facilities at the University of Southeastern Philippines.
            From hostels to commercial spaces, find everything you need for your stay.
          </p>

          <!-- Quick Search Widget -->
          <div class="mt-12 max-w-2xl mx-auto animate-fade-in-up animation-delay-600">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-white/20">
              <h3 class="text-xl font-semibold mb-4 text-white">Quick Booking Search</h3>
              <div class="grid md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-yellow-200 mb-2">Facility Type</label>
                  <select v-model="searchForm.facilityType" class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="" class="text-gray-800">Select Type</option>
                    <option value="hostel" class="text-gray-800">Hostel</option>
                    <option value="commercial" class="text-gray-800">Commercial</option>
                    <option value="rental" class="text-gray-800">Rental</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-yellow-200 mb-2">Check-in Date</label>
                  <input v-model="searchForm.checkInDate" type="date" class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-yellow-200 mb-2">Check-out Date</label>
                  <input v-model="searchForm.checkOutDate" type="date" class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
              </div>
              <button @click="performSearch" :disabled="isSearching" class="w-full mt-4 bg-yellow-400 hover:bg-yellow-500 disabled:bg-yellow-300 text-maroon font-semibold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 disabled:hover:scale-100 shadow-lg disabled:cursor-not-allowed">
                <span v-if="isSearching">Searching...</span>
                <span v-else>Search Available Spaces</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
          </svg>
        </div>
      </section>
  
      <!-- About Section -->
      <section
        class="max-w-7xl mx-auto py-20 px-6 grid md:grid-cols-2 gap-16 items-center"
      >
        <div>
          <h2 class="text-3xl md:text-4xl font-bold mb-6">
            We Provide the Best Facilities
          </h2>
          <p class="text-gray-700 text-base md:text-lg leading-relaxed">
            From student accommodations to multipurpose commercial spaces and rental
            venues, our facilities are designed to meet the needs of our diverse community.
            We ensure a safe, functional, and inspiring environment for students, faculty,
            and visitors alike.
          </p>
        </div>
  
        <!-- Image with dotted background -->
        <div class="relative">
          <div
            class="absolute w-[80%] h-[80%] bottom-[-30px] right-[-30px] z-0 opacity-20"
            style="background-image: radial-gradient(currentColor 2px, transparent 2px);
            background-size: 18px 18px; color: #5F1213;"
          ></div>
          <img
            src="/images/Faci_Images/commercial.png"
            alt="Main facility"
            class="relative z-10 w-full h-[340px] object-cover rounded-2xl shadow-2xl"
          />
        </div>
      </section>

      <!-- Features Section -->
      <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Why Choose Our Facilities?</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
              Experience excellence in every aspect of our facilities, designed with your comfort and convenience in mind.
            </p>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
              <div class="w-16 h-16 bg-maroon/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-2">Secure & Safe</h3>
              <p class="text-gray-600">24/7 security monitoring and controlled access to ensure your safety and peace of mind.</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
              <div class="w-16 h-16 bg-maroon/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-2">Modern Amenities</h3>
              <p class="text-gray-600">State-of-the-art facilities with modern equipment and comfortable environments.</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
              <div class="w-16 h-16 bg-maroon/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-2">Community Focused</h3>
              <p class="text-gray-600">Designed to foster collaboration and community building among students and visitors.</p>
            </div>

            <!-- Feature 4 -->
            <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
              <div class="w-16 h-16 bg-maroon/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-2">Easy Booking</h3>
              <p class="text-gray-600">Simple and intuitive booking process with instant confirmation and flexible options.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Facilities Cards -->
      <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6">
          <h2 class="text-3xl font-bold mb-12 text-center">
            Our Facilities
          </h2>
  
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
            <!-- Hostel -->
            <div
              class="relative group rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 cursor-pointer"
              @click="goToHostel"
            >
              <img
                src="/images/Faci_Images/hostel.png"
                alt="Hostel"
                class="w-full h-80 object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6"
              >
                <h3 class="text-2xl font-bold text-white">Hostel</h3>
                <p class="text-gray-200 text-sm mt-2 mb-3">
                  Comfortable and secure living spaces for students and guests.
                </p>
                <span
                  class="text-yellow-400 text-sm font-semibold group-hover:underline"
                >
                  See project →
                </span>
              </div>
            </div>
  
            <!-- Commercial -->
            <div
              class="relative group rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 cursor-pointer"
              @click="goToCommercial"
            >
              <img
                src="/images/Faci_Images/commercial.png"
                alt="Commercial"
                class="w-full h-80 object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6"
              >
                <h3 class="text-2xl font-bold text-white">Commercial Spaces</h3>
                <p class="text-gray-200 text-sm mt-2 mb-3">
                  Perfect for retail, business, and service operations.
                </p>
                <span
                  class="text-yellow-400 text-sm font-semibold group-hover:underline"
                >
                  See project →
                </span>
              </div>
            </div>
  
            <!-- Rental -->
            <div
              class="relative group rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 cursor-pointer"
              @click="goToRental"
            >
              <img
                src="/images/Faci_Images/rental.png"
                alt="Rental"
                class="w-full h-80 object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6"
              >
                <h3 class="text-2xl font-bold text-white">Rental Venues</h3>
                <p class="text-gray-200 text-sm mt-2 mb-3">
                  Flexible spaces for meetings, events, and community gatherings.
                </p>
                <span
                  class="text-yellow-400 text-sm font-semibold group-hover:underline"
                >
                  See project →
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <Footer />
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue'
  import Header from "./header.vue";
  import Footer from "./footer.vue";
  import { router } from "@inertiajs/vue3";

  // Search form data
  const searchForm = reactive({
    facilityType: '',
    checkInDate: '',
    checkOutDate: ''
  })

  const isSearching = ref(false)

  // Navigation functions
  const goToHostel = () => router.visit("/use-of-facilities/hostel");
  const goToCommercial = () => router.visit("/use-of-facilities/commercial");
  const goToRental = () => router.visit("/use-of-facilities/rental");

  // Search functionality
  const performSearch = async () => {
    if (!searchForm.facilityType || !searchForm.checkInDate || !searchForm.checkOutDate) {
      alert('Please fill in all search fields')
      return
    }

    // Validate dates
    const checkIn = new Date(searchForm.checkInDate)
    const checkOut = new Date(searchForm.checkOutDate)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    if (checkIn < today) {
      alert('Check-in date cannot be in the past')
      return
    }

    if (checkOut <= checkIn) {
      alert('Check-out date must be after check-in date')
      return
    }

    isSearching.value = true

    try {
      // Navigate to the appropriate facility page with search parameters
      const params = new URLSearchParams({
        check_in: searchForm.checkInDate,
        check_out: searchForm.checkOutDate,
        type: searchForm.facilityType
      })

      const routeMap = {
        'hostel': '/use-of-facilities/hostel',
        'commercial': '/use-of-facilities/commercial',
        'rental': '/use-of-facilities/rental'
      }

      const targetRoute = routeMap[searchForm.facilityType]
      if (targetRoute) {
        router.visit(`${targetRoute}?${params.toString()}`)
      }
    } catch (error) {
      console.error('Search error:', error)
      alert('An error occurred while searching. Please try again.')
    } finally {
      isSearching.value = false
    }
  }

  // Quick navigation for facility cards
  const navigateToFacility = (facilityType) => {
    const routeMap = {
      'hostel': goToHostel,
      'commercial': goToCommercial,
      'rental': goToRental
    }

    const navigateFunction = routeMap[facilityType]
    if (navigateFunction) {
      navigateFunction()
    }
  }
  </script>

  <style scoped>
  .bg-maroon {
    background-color: #650000;
  }
  .text-maroon {
    color: #650000;
  }

  /* Animation keyframes */
  @keyframes fade-in-up {
    0% {
      opacity: 0;
      transform: translateY(30px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
    opacity: 0;
  }

  .animation-delay-200 {
    animation-delay: 0.2s;
  }

  .animation-delay-400 {
    animation-delay: 0.4s;
  }

  .animation-delay-600 {
    animation-delay: 0.6s;
  }

  /* Enhanced card hover effects */
  .facility-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .facility-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  }

  /* Custom scrollbar for better UX */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  ::-webkit-scrollbar-thumb {
    background: #650000;
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #4a0000;
  }

  /* Responsive improvements */
  @media (max-width: 768px) {
    .hero-title {
      font-size: 2.5rem;
    }

    .hero-subtitle {
      font-size: 1.125rem;
    }

    .search-widget {
      margin-top: 2rem;
      padding: 1rem;
    }
  }

  /* Loading states */
  .loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
  }

  @keyframes loading {
    0% {
      background-position: 200% 0;
    }
    100% {
      background-position: -200% 0;
    }
  }
  </style>
  