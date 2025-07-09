<template>
  <div>
    <SiteHeader />

<!-- Shop Page Content -->
<section class="py-12 bg-gray-100">
  <div class="max-w-6xl mx-auto px-4 space-y-6">
    <!-- Rectangle Card with background image -->
    <div
      class="bg-cover bg-center rounded-xl shadow-md text-center relative overflow-hidden h-28 md:h-38"
        style="background-image: url('images/OnlineMarket/vegetable_banner.png');"
    >
      <!-- Optional overlay if text needs contrast -->
      <div class="absolute inset-0 bg-black/20"></div>

      <div class="relative flex items-center justify-start h-full p-8">
  <h2 class="text-6xl font-bold text-white text-left">VEGETABLES</h2>
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
              <p class="text-gray-500 text-[10px] mb-1">
                Available: {{ product.quantity }}
                <span v-if="product.metric">{{ product.metric }}</span>
              </p>
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-gray-700">
                  ₱{{ product.price }}
                  <span v-if="product.metric">/ {{ product.metric }}</span>
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
  name: 'VegetablePage',
  components: {
    SiteHeader,
    SiteFooter,
  },
  data() {
    return {
      sortBy: 'asc',
      itemsToShow: 10,
      products: [
        {
          id: 1,
          title: 'Fresh Broccoli',
          quantity: 20,
          price: '99.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 2,
          title: 'Crisp Carrots',
          quantity: 15,
          price: '50.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1587049352841-4e60ec6dca1d?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 3,
          title: 'Leafy Spinach',
          quantity: 10,
          price: '70.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1567273199143-4ec90d9271da?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 4,
          title: 'Green Peas',
          quantity: 30,
          price: '40.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1603911586859-6397a2e6a0fc?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 5,
          title: 'Organic Zucchini',
          quantity: 12,
          price: '75.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 6,
          title: 'Sweet Corn',
          quantity: 25,
          price: '65.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1603911586859-6397a2e6a0fc?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 7,
          title: 'Red Tomatoes',
          quantity: 18,
          price: '55.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1587049352841-4e60ec6dca1d?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 8,
          title: 'Eggplant',
          quantity: 22,
          price: '60.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1567273199143-4ec90d9271da?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 9,
          title: 'Cabbage',
          quantity: 30,
          price: '45.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80'
        },
        {
          id: 10,
          title: 'Cauliflower',
          quantity: 16,
          price: '85.00',
          metric: 'kilo',
          image: 'https://images.unsplash.com/photo-1603911586859-6397a2e6a0fc?auto=format&fit=crop&w=800&q=80'
        },
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
