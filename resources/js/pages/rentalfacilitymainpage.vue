<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';

defineOptions({
  name: 'uf_home'
})

function goToHostel() {
  router.visit('/use-of-facilities/hostel')
}

const goToCommercial = () => {
  router.visit('/use-of-facilities/commercial') 
}

const goToRental = () => {
  router.visit('/use-of-facilities/rental') 
}

import Header from './header.vue'
import Footer from './footer.vue'
</script>

<template>
  <div class="landing-page bg-white text-black min-h-screen font-sans">
    
    <!-- Header -->
     <Header />

    <!-- Hero Banner -->
     <section class="hero-banner relative h-[500px] bg-cover bg-center" style="background-image: url('/images/Faci_Images/intro.png');">
      <div class="hero-overlay bg-black bg-opacity-50 h-full w-full flex flex-col justify-center items-center text-white text-center px-4">
        <h2 class="hero-title text-4xl font-bold mb-4">Book Your Space Now</h2>
        <p class="hero-subtitle text-lg">Flexible rooms, commercial stalls, and event facilities at USeP Tagum.</p>
      </div>
    </section>



    <!-- Section Heading -->
    <section class="mt-12 px-6 text-center">
      <h2 class="section-title">Use-of-Facilities</h2>
      <div class="section-divider"></div>
      <p class="text-gray-600 mt-2">Choose from available services offered by the University of Southeastern Philippines.</p>
    </section>

    <!-- Facilities -->
    <section class="mt-10 px-6">
      <div class="card-container">

        <!-- Hostel Rooms -->
        <Link href="/use-of-facilities/hostel" class="facility-card block">
          <img src="/images/Faci_Images/hostel.png" alt="Hostel Rooms" class="card-img" />
          <div class="card-overlay">
            <h3 class="card-title">Hostel Rooms</h3>
            <button class="card-btn">Book Now →</button>
          </div>
        </Link>

        <!-- Commercial Space -->
        <Link href="/use-of-facilities/commercial" class="facility-card block">
          <img src="/images/Faci_Images/commercial.png" alt="Commercial Space" class="card-img" />
          <div class="card-overlay">
            <h3 class="card-title">Commercial Space</h3>
            <button class="card-btn">Rent Now →</button>
          </div>
        </Link>

        <!-- Rental Facilities -->
        <Link href="/use-of-facilities/rental" class="facility-card block">
          <img src="/images/Faci_Images/rental.png" alt="Rental Facilities" class="card-img" />
          <div class="card-overlay">
            <h3 class="card-title">Rental Facilities</h3>
            <button class="card-btn">Rent Now →</button>
          </div>
        </Link>


      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<style scoped>
/* Maroon Theme */
.bg-maroon {
  background-color: #650000;
}

/* Hero Banner */
.hero-banner {
  background-image: url('/images/facilities-banner.jpg');
  background-size: cover;
  background-position: center;
  height: 300px;
  position: relative;
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  text-align: center;
  padding: 1rem;
}

.hero-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.hero-subtitle {
  font-size: 1.125rem;
  font-weight: 400;
}

/* Section Title */
.section-title {
  font-size: 3rem;
  font-weight: 700;
  color: #facc15;
  margin-bottom: 0.25rem;
}

.section-divider {
  width: 80px;
  height: 4px;
  margin: 0 auto;
  background-color: #650000;
  border-radius: 2px;
}

/* Cards */
.card-container {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

@media (min-width: 640px) {
  .card-container {
    grid-template-columns: repeat(3, 1fr);
  }
}

.facility-card {
  position: relative;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease-in-out;
  cursor: pointer;
}

.facility-card:hover {
  transform: scale(1.05);
}

.card-img {
  width: 100%;
  height: 18rem;
  object-fit: cover;
}

.card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 1.5rem;
}

.card-title {
  color: white;
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.card-btn {
  background-color: white;
  color: #047857;
  font-size: 1rem;
  font-weight: 600;
  padding: 0.5rem 1.25rem;
  border-radius: 9999px;
  transition: background-color 0.3s, color 0.3s;
  border: none;
}

.card-btn:hover {
  background-color: #047857;
  color: white;
}

/* Footer */
.footer-title {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #facc15;
}

/* Maroon Theme */
.bg-maroon {
  background-color: #650000;
}

/* Hero Banner */
.hero-banner {
  background-image: url('/images/facilities-banner.jpg');
  background-size: cover;
  background-position: center;
  height: 300px;
  position: relative;
  animation: zoomIn 3s ease-in-out forwards;
}

@keyframes zoomIn {
  from {
    transform: scale(1.05);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  text-align: center;
  padding: 1rem;
}

.hero-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  animation: fadeDown 1s ease-out;
}

.hero-subtitle {
  font-size: 1.125rem;
  font-weight: 400;
  animation: fadeUp 1.2s ease-out;
}

@keyframes fadeDown {
  from {
    transform: translateY(-10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes fadeUp {
  from {
    transform: translateY(10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}


.section-divider:hover {
  box-shadow: 0 0 16px #facc15, 0 0 6px #facc15;
}

/* Cards */
.card-container {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

@media (min-width: 640px) {
  .card-container {
    grid-template-columns: repeat(3, 1fr);
  }
}

.facility-card {
  position: relative;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  transition: transform 0.4s ease, box-shadow 0.3s;
  cursor: pointer;
  animation: slideUpFade 1s ease forwards;
  opacity: 0;
}

.facility-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 10px 25px rgba(255, 199, 44, 0.3);
}

@keyframes slideUpFade {
  from {
    transform: translateY(30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.card-img {
  width: 100%;
  height: 18rem;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.facility-card:hover .card-img {
  transform: scale(1.05);
}

.card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 1.5rem;
  transition: background 0.4s;
}

.card-title {
  color: white;
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.card-btn {
  background-color: white;
  color: #047857;
  font-size: 1rem;
  font-weight: 600;
  padding: 0.5rem 1.25rem;
  border-radius: 9999px;
  transition: all 0.3s ease;
  border: none;
  position: relative;
  overflow: hidden;
}

.card-btn::after {
  content: "";
  position: absolute;
  background: rgba(4, 120, 87, 0.2);
  border-radius: 50%;
  width: 0;
  height: 0;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
  z-index: 0;
}

.card-btn:hover::after {
  width: 200%;
  height: 200%;
}

.card-btn:hover {
  background-color: #047857;
  color: white;
  z-index: 1;
}

/* Header button animation */
header button {
  position: relative;
  transition: all 0.2s ease;
}

header button::after {
  content: "";
  display: block;
  height: 2px;
  background: white;
  width: 0%;
  transition: width 0.3s;
  margin-top: 2px;
}

header button:hover::after {
  width: 100%;
}

/* Footer */
.footer-title {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #facc15;
}

</style>
