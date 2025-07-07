<template>
  <div>
    <!-- Header -->
    <header class="bg-white shadow-md">
      <div class="container mx-auto flex items-center justify-between p-4">
        <h1 class="text-xl font-bold text-maroon">CEDU iCentral</h1>
        <div class="flex items-center gap-4">
          <input type="text" placeholder="Search" class="border rounded px-3 py-1" />
          <button class="bg-yellow-500 text-white px-4 py-1 rounded">Search</button>
          <span class="text-sm">Shopping cart: <strong>$97.00</strong></span>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="text-center py-10 bg-white">
      <img src="/src/assets/hero-image.png" alt="Fruits Basket" class="mx-auto w-full max-w-3xl" />
      <h2 class="text-lg mt-6 text-gray-500">WELCOME TO SHOPPY</h2>
      <h1 class="text-3xl font-bold text-yellow-600">Fresh & Healthy Organic Food</h1>
      <p class="mt-2 text-gray-600">Free shipping on all your order, we deliver, you enjoy</p>
      <button class="mt-4 px-6 py-2 bg-maroon text-white rounded">Shop now</button>
    </section>

    <!-- CEDU Market Products -->
    <section class="bg-gray-100 py-12">
      <h3 class="text-2xl font-bold text-yellow-600 text-center mb-8">CEDU Market Products</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <div class="bg-blue-400 p-6 rounded text-white text-center">
          <h4 class="text-xl font-bold mb-2">Vegetables</h4>
          <img src="/src/assets/vegetables.png" alt="Vegetables" class="w-full h-40 object-cover rounded" />
          <button class="mt-4 px-4 py-2 bg-white text-green-600 font-semibold rounded">Shop Now</button>
        </div>
        <div class="bg-black p-6 rounded text-white text-center">
          <h4 class="text-xl font-bold mb-2">Poultry</h4>
          <img src="/src/assets/poultry.png" alt="Poultry" class="w-full h-40 object-cover rounded" />
          <button class="mt-4 px-4 py-2 bg-white text-green-600 font-semibold rounded">Shop Now</button>
        </div>
        <div class="bg-yellow-400 p-6 rounded text-white text-center">
          <h4 class="text-xl font-bold mb-2">Fruits</h4>
          <img src="/src/assets/fruits.png" alt="Fruits" class="w-full h-40 object-cover rounded" />
          <button class="mt-4 px-4 py-2 bg-white text-green-600 font-semibold rounded">Shop Now</button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-maroon text-white py-8">
      <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
        <div>
          <h5 class="font-bold mb-2">Support</h5>
          <p>University of Southeastern Philippines,<br />
            Tagum-Mabini Campus, Tagum Unit, CEDU Office, Apokon, Tagum City</p>
          <p>cedu@usep.edu.ph</p>
          <p>+88071-88888-9999</p>
        </div>
        <div>
          <h5 class="font-bold mb-2">Account</h5>
          <p>My Account</p>
          <p>Login / Register</p>
        </div>
        <div>
          <h5 class="font-bold mb-2">Quick Link</h5>
          <p>Privacy Policy</p>
          <p>Terms Of Use</p>
          <p>FAQ</p>
          <p>Contact</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'HomePage'
};
</script>

<style scoped>
.bg-maroon {
  background-color: #651818;
}
.text-maroon {
  color: #651818;
}
</style>
