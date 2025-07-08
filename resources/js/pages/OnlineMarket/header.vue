<template>
  <header class="bg-maroon text-white py-2 px-4 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
      <h1 class="text-lg font-bold text-white">
        <h1 class="text-lg font-bold text-white">CEDU  <span class="text-yellow-300">iCentral</span></h1>
      </h1>
    </div>
    <nav class="flex gap-2">
      <div class="flex items-center gap-4">
        <input type="text" placeholder="Search" class="bg-white text-black border rounded px-3 py-1" />
        <button class="bg-yellow-500 text-white px-4 py-1 rounded">Search</button>
        <img src="/images/OnlineMarket/shoppingcart.png" alt="Cart" class="h-10 w-10" />
        <img src="/images/OnlineMarket/profile.png" alt="Profile" class="h-10 w-10" />
      </div>
    </header>
  </div>
</template>

<script>
export default {
  name: 'Header'
};
</script>

<style scoped>
.text-maroon {
  color: #651818;
}
</style>
