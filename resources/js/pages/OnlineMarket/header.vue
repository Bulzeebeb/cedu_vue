<template>
  <div class="w-full bg-maroon">
    <!-- Back to Main Link -->
    <div class="text-[10px] text-gray-200 flex justify-end items-center pr-4 py-2 border-b border-yellow-500">
      <a href="/" class="flex items-center gap-1 hover:underline">
        <img src="/images/OnlineMarket/back.png" alt="Back" class="h-3 w-3 invert" />
        Back to main
      </a>
    </div>

    <!-- Header -->
    <header class="w-full flex items-center justify-between px-4 py-2 bg-white border-b border-gray-200">
      <!-- Logo + Title -->
      <div class="flex items-center gap-2">
        <img src="/images/logo.png" alt="CEDU Logo" class="h-10 w-10" />
        <h1 class="text-lg font-bold text-maroon">
          CEDU <span class="text-yellow-500">iCentral</span>
        </h1>
      </div>

      <!-- Search Bar -->
      <div class="flex items-center w-full max-w-lg mx-4">
        <div class="flex border border-gray-300 rounded w-full overflow-hidden">
          <div class="flex items-center px-2">
            <img src="/images/OnlineMarket/searchicon.png" alt="Search Icon" class="h-4 w-4" />
          </div>
          <input
            type="text"
            placeholder="Search"
            class="flex-grow px-2 py-2 outline-none text-black"
          />
          <button class="bg-yellow-500 text-white px-4">
            Search
          </button>
        </div>
      </div>

      <!-- Cart + Profile -->
      <div class="flex items-center gap-4 text-black">
        <!-- Cart Button with badge -->
        <button class="relative">
          <img src="/images/OnlineMarket/shopping-cart.png" alt="Cart" class="h-5 w-5" />
          <span class="absolute -top-1 -right-1 bg-maroon text-black text-[10px] px-1 rounded-full">1</span>
        </button>

        <!-- Profile Button + Sign In Link -->
        <div class="flex items-center gap-1 text-sm">
          <button>
            <img src="/images/OnlineMarket/icons8-profile-50.png" alt="Profile" class="h-5 w-5" />
          </button>
          <a href="/signin" class="hover:underline">Sign In / Sign Up</a>
        </div>
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
