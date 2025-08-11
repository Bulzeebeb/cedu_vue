<template>
  <div class="bg-[#fff3e6] text-[#5F1213] min-h-screen">
    <Header />

    <!-- Banner -->
    <section
      class="relative bg-cover bg-center text-white py-24 px-8 text-center"
      style="background-image: url('/images/bg-campus.jpg')"
    >
      <div class="absolute inset-0 bg-[#650000]/80 backdrop-blur-sm"></div>
      <div class="relative z-10 max-w-4xl mx-auto">
        <h1 class="text-5xl font-extrabold mb-4">
          About <span class="text-yellow-400">CEDU Tagum-Mabini</span>
        </h1>
        <p class="text-lg max-w-2xl mx-auto">
          Corporate Enterprise Development Unit of the University of Southeastern Philippines,
          dedicated to empowering income-generating projects and sustainable campus ventures.
        </p>
      </div>
    </section>

    <!-- About CEDU -->
    <section class="py-16 px-8 max-w-6xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-6 text-[#650000]">Who We Are</h2>
      <p class="text-gray-800 text-lg leading-relaxed">
        The <strong>Corporate Enterprise Development Unit (CEDU)</strong> Tagum-Mabini is a strategic arm of the University of Southeastern Philippines that manages, develops, and innovates income-generating projects (IGPs) including agriculture, facility rentals, and other university-based businesses.
        With a dedicated team and clear governance structure, CEDU ensures resourceful, accountable, and community-focused enterprise efforts aligned with the university's mission.
      </p>
    </section>

    <!-- Organizational Chart -->
    <section class="bg-white py-16 px-6">
      <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-[#650000] mb-12">Organizational Chart</h2>

        <!-- Top Hierarchy -->
        <div class="flex flex-col items-center space-y-6 mb-16 relative">
          <div v-for="(person, index) in [
            { name: 'Dr. Bonifacio Gabales Jr', role: 'University President', image: 'bonifacio-gabales-jr.jpg' },
            { name: 'Dr. Luisa B. Faunillan, CPA', role: 'Vice President for Administration', image: 'luisa-faunillan.jpg' },
            { name: 'Dr. John Mark S. Francisco', role: 'Director, Resource Management Division', image: 'john-francisco.jpg' },
            { name: 'Dr. Cesar A. Limbaga Jr, R.Agr.', role: 'CEDU Head', image: 'cesar-limbaga.jpg' },
            { name: 'Liza May M. Lamban, R.Agr., MBA', role: 'CEDU Deputy Head', image: 'liza-lamban.jpg' }
          ]" :key="index" class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4 w-80 text-left">
            <div class="flex items-center gap-4">
              <img src="/images/org/liza-lamban.jpg" alt="Liza May M. Lamban" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
              <div>
                <p class="font-semibold text-base text-[#650000]">{{ person.name }}</p>
                <p class="text-gray-700 text-sm">{{ person.role }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Staff -->
        <div class="grid md:grid-cols-3 gap-6 mb-10 text-center">
          <div v-for="(staff, index) in [
            { name: 'Emanuel D. Gascar', role: 'Marketing Staff', image: 'emanuel-gascar.jpg' },
            { name: 'Juniel N. Jacobe, R.Agr.', role: 'Technical Staff', image: 'juniel-jacobe.jpg' },
            { name: 'Rami-mar A. Calbonero', role: 'Admin/Finance Staff', image: 'rami-mar-calbonero.jpg' }
          ]" :key="index" class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
            <div class="flex items-center gap-4">
              <img src="/images/org/liza-lamban.jpg" alt="Liza May M. Lamban" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
              <div class="text-left">
                <p class="font-semibold text-base text-[#650000]">{{ staff.name }}</p>
                <p class="text-gray-700 text-sm">{{ staff.role }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Production Units -->
        <div class="grid md:grid-cols-3 gap-8 text-center mt-12">
          <!-- Crop Production -->
          <div class="space-y-4">
            <div v-for="(crop, index) in [
              { name: 'Dr. Ulysses P. Besas, R.Agr.', role: 'In-Charge, Crop Production', image: 'ulysses-besas.jpg' },
              { name: 'Jonathan C. Pareja, R.Agr.', role: 'Farm Manager', image: 'jonathan-pareja.jpg' },
              { name: 'Corsabel S. Casilac Sr.', role: 'Assistant Farm Manager', image: 'corsabel-casilac.jpg' }
            ]" :key="index" class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <div class="flex items-center gap-4">
                <img src="/images/org/liza-lamban.jpg" alt="Liza May M. Lamban" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
                <div class="text-left">
                  <p class="font-semibold text-base text-[#650000]">{{ crop.name }}</p>
                  <p class="text-gray-700 text-sm">{{ crop.role }}</p>
                </div>
              </div>
            </div>
            <div class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <p class="font-semibold text-base text-[#650000]">15 Farmers</p>
            </div>
          </div>

          <!-- Animal Production -->
          <div class="space-y-4">
            <div v-for="(animal, index) in [
              { name: 'Rismond Zenny P. Ubod, R.Agr., MSc', role: 'In-Charge, Animal Production', image: 'rismond-ubod.jpg' },
              { name: 'Ian May C. Gripo', role: 'Farm Manager', image: 'ian-gripo.jpg' }
            ]" :key="index" class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <div class="flex items-center gap-4">
                <img src="/images/org/liza-lamban.jpg" alt="Liza May M. Lamban" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
                <div class="text-left">
                  <p class="font-semibold text-base text-[#650000]">{{ animal.name }}</p>
                  <p class="text-gray-700 text-sm">{{ animal.role }}</p>
                </div>
              </div>
            </div>
            <div class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <p class="font-semibold text-base text-[#650000]">2 Farm Caretakers</p>
            </div>
          </div>

          <!-- Rental Services -->
          <div class="space-y-4">
            <div class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <div class="flex items-center gap-4">
                <img src="/images/org/liza-lamban.jpg" alt="Liza May M. Lamban" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
                <div class="text-left">
                  <p class="font-semibold text-base text-[#650000]">Liza May M. Lamban, R.Agr., MBA</p>
                  <p class="text-gray-700 text-sm">In-Charge, Rental Services</p>
                </div>
              </div>
            </div>
            <div class="bg-[#fff3e6] shadow-md border rounded-lg px-6 py-4">
              <div class="flex items-center gap-4">
                <img src="/images/org/mary-jane-akiatan.jpg" alt="Mary Jane L. Akiatan" class="w-20 h-20 object-cover rounded-full border-2 border-[#650000]" />
                <div class="text-left">
                  <p class="font-semibold text-base text-[#650000]">Mary Jane L. Akiatan</p>
                  <p class="text-gray-700 text-sm">Hostel Aide</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script>
import Header from './header.vue'
import Footer from './footer.vue'

export default {
  name: 'AboutUs',
  components: {
    Header,
    Footer
  }
}
</script>

<style scoped>
section {
  scroll-margin-top: 5rem;
}
</style>