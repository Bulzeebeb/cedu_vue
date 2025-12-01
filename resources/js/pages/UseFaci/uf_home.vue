<template>
    <div class="bg-white text-[#5F1213] min-h-screen font-sans">
      <Header />
  
      <!-- Hero Section -->
      <section
        class="relative bg-cover bg-center text-white py-28 md:py-36 flex items-center justify-center"
        style="background-image: url('/images/Faci_Images/intro.png')"
      >
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 text-center px-4 max-w-3xl">
          <h1 class="text-4xl md:text-6xl font-extrabold leading-tight drop-shadow-2xl">
            Explore Our Facilities
          </h1>
          <p class="mt-4 text-lg md:text-xl opacity-90 drop-shadow-md">
            Providing world-class amenities to support learning, growth, and community.
          </p>
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
  import Header from "./header.vue";
  import Footer from "./footer.vue";
  import { router } from "@inertiajs/vue3";
  
  const goToHostel = () => router.visit("/use-of-facilities/hostel");
  const goToCommercial = () => router.visit("/use-of-facilities/commercial");
  const goToRental = () => router.visit("/use-of-facilities/rental");
  </script>
  