<template>
  <div>
    <SiteHeader />

<!-- Shop Page Content -->
<section class="py-12 bg-gray-100">
  <div class="max-w-6xl mx-auto px-4 space-y-6">
    <!-- Rectangle Card with background image -->
    <div
      class="bg-cover bg-center rounded-xl shadow-md text-center relative overflow-hidden h-28 md:h-38"
        style="background-image: url('images/OnlineMarket/fruits_banner.png');"
    >
      <div class="relative flex items-center justify-start h-full p-8">
  <h2 class="text-6xl font-bold text-white text-left">FRUITS</h2>
</div>
</div>
        <!-- Sorting & Show Controls -->
        <div class="flex justify-end items-center gap-4 text-gray-600 text-sm">
          <label class="flex items-center gap-2">
            Sort by:
            <select v-model="sortBy" class="border border-gray-300 rounded px-2 py-1 text-sm">
              <option value="asc">Price (low - high)</option>
              <option value="desc">Price (high - low)</option>
            </select>
          </label>
          <label class="flex items-center gap-2">
            Show:
            <select v-model="itemsToShow" class="border border-gray-300 rounded px-2 py-1 text-sm">
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
            </select>
            Items
          </label>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <div
            v-for="product in sortedProducts"
            :key="product.id"
            class="bg-white rounded-2xl shadow-sm overflow-hidden transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer"
          >
            <!-- Image -->
            <div class="relative">
              <img
                :src="product.image"
                alt="Product Image"
                class="w-full h-52 object-cover transition duration-500 transform hover:scale-105"
              />
            </div>

            <div class="p-4">
              <h5 class="text-sm font-bold mb-1 text-gray-800">{{ product.title }}</h5>
              <p class="text-gray-500 text-[10px] mb-1">Available: {{ product.quantity }}</p>
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-gray-700">
                  ₱{{ product.price }} / kilo
                </span>
                <button
                  class="bg-yellow-500 text-maroon text-[10px] font-semibold px-2 py-1 rounded-full transition duration-300 hover:shadow-md hover:-translate-y-0.5"
                >
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
        </div>
<!-- Pagination -->
<nav aria-label="Page navigation" class="mt-8 flex justify-center">
  <ul class="flex space-x-1">
    <li>
      <a href="#"
         class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">
        &laquo;
      </a>
    </li>
    <li>
      <a href="#"
         class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">
        1
      </a>
    </li>
    <li>
      <a href="#"
         class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">
        2
      </a>
    </li>
    <li>
      <a href="#"
         class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">
        3
      </a>
    </li>
    <li>
      <a href="#"
         class="w-6 h-6 flex items-center justify-center bg-[#651818] text-white text-[10px] rounded-full hover:bg-[#4d1212] transition">
        &raquo;
      </a>
    </li>
  </ul>
</nav>

      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script>
import SiteHeader from './header.vue';
import SiteFooter from './footer.vue';

export default {
  name: 'FruitPage',
  components: {
    SiteHeader,
    SiteFooter,
  },
  data() {
    return {
      sortBy: 'asc',
      itemsToShow: 10,
      products: [
        { id: 1, title: 'Fresh Apples', quantity: 25, price: '120.00', image: 'https://source.unsplash.com/800x600/?apples' },
        { id: 2, title: 'Sweet Mangoes', quantity: 20, price: '150.00', image: 'https://source.unsplash.com/800x600/?mangoes' },
        { id: 3, title: 'Juicy Oranges', quantity: 30, price: '90.00', image: 'https://source.unsplash.com/800x600/?oranges' },
        { id: 4, title: 'Ripe Bananas', quantity: 40, price: '60.00', image: 'https://source.unsplash.com/800x600/?bananas' },
        { id: 5, title: 'Red Strawberries', quantity: 18, price: '200.00', image: 'https://source.unsplash.com/800x600/?strawberries' },
        { id: 6, title: 'Watermelons', quantity: 12, price: '180.00', image: 'https://source.unsplash.com/800x600/?watermelon' },
        { id: 7, title: 'Fresh Grapes', quantity: 22, price: '160.00', image: 'https://source.unsplash.com/800x600/?grapes' },
        { id: 8, title: 'Pineapples', quantity: 10, price: '140.00', image: 'https://source.unsplash.com/800x600/?pineapple' },
        { id: 9, title: 'Blueberries', quantity: 15, price: '220.00', image: 'https://source.unsplash.com/800x600/?blueberries' },
        { id: 10, title: 'Cherries', quantity: 12, price: '250.00', image: 'https://source.unsplash.com/800x600/?cherries' },
        { id: 11, title: 'Dragon Fruit', quantity: 8, price: '190.00', image: 'https://source.unsplash.com/800x600/?dragonfruit' },
        { id: 12, title: 'Papayas', quantity: 20, price: '100.00', image: 'https://source.unsplash.com/800x600/?papaya' },
        { id: 13, title: 'Avocados', quantity: 14, price: '170.00', image: 'https://source.unsplash.com/800x600/?avocado' },
      ],
    };
  },
  computed: {
    sortedProducts() {
      return this.products
        .slice()
        .sort((a, b) => {
          const priceA = parseFloat(a.price);
          const priceB = parseFloat(b.price);
          return this.sortBy === 'asc' ? priceA - priceB : priceB - priceA;
        })
        .slice(0, this.itemsToShow);
    },
  },
};
</script>

<style scoped>
.text-maroon {
  color: #651818;
}
</style>
