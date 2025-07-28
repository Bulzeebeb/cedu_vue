<template>
  <!-- Footer -->
  <footer class="bg-maroon text-white">
    <div class="w-full px-4 sm:px-8 md:px-12 lg:px-20 xl:px-32 py-10">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Support Column -->
        <div>
          <h5 class="uppercase font-bold mb-4">Support</h5>
          <p class="text-sm">
            University of Southeastern Philippines,<br />
            Tagum-Mabini Campus, Tagum Unit, CEDU Office, Apokon, Tagum City
          </p>
          <p class="text-sm mt-2">cedu@usep.edu.ph</p>
          <p class="text-sm mt-2">+88071-88888-9999</p>
        </div>

        <!-- Account Column -->
        <div>
          <h5 class="uppercase font-bold mb-4">Account</h5>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:underline">My Account</a></li>
            <li><a href="#" class="hover:underline">Login / Register</a></li>
          </ul>
        </div>

        <!-- Quick Links Column -->
        <div>
          <h5 class="uppercase font-bold mb-4">Quick Links</h5>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:underline">Privacy Policy</a></li>
            <li><a href="#" class="hover:underline">Terms Of Use</a></li>
            <li><a href="#" class="hover:underline">FAQ</a></li>
            <li><a href="#" class="hover:underline">Contact</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="text-center py-4 bg-black/20 text-xs">
      © 2024 CEDU Online Market. All rights reserved.
    </div>
  </footer>
</template>

<script>
export default {
  name: 'footer',
};
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
</style>