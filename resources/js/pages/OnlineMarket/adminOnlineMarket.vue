<template>
  <div>
    <SiteHeader :cart-count="cart.length" />

    <!-- Admin Controls -->
    <section class="py-12 bg-gray-100">
      <div class="max-w-6xl mx-auto px-4 space-y-6">
        <!-- Banner -->
        <div
          class="bg-cover bg-center rounded-xl shadow-md text-center relative overflow-hidden h-28 md:h-38"
          style="background-image: url('images/OnlineMarket/vegetable_banner.png');"
        >
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="relative flex items-center justify-start h-full p-8">
            <h2 class="text-6xl font-bold text-white text-left">VEGETABLES</h2>
          </div>
        </div>

        <!-- Admin Buttons -->
        <div class="flex justify-between items-center">
          <div class="flex gap-4 text-sm text-gray-600">
            <label class="flex items-center gap-2">
              Sort by:
              <select v-model="sortBy" class="border rounded px-2 py-1">
                <option value="asc">Price (low - high)</option>
                <option value="desc">Price (high - low)</option>
              </select>
            </label>
            <label class="flex items-center gap-2">
              Show:
              <select v-model="itemsToShow" class="border rounded px-2 py-1">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
              </select>
              Items
            </label>
          </div>

          <button @click="openForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Add Product
          </button>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <div
            v-for="product in sortedProducts"
            :key="product.id"
            class="bg-white rounded-2xl shadow-sm overflow-hidden transform transition hover:-translate-y-1 hover:shadow-lg"
          >
            <!-- Product Image -->
            <div class="relative">
              <img
                :src="product.image"
                alt="Product Image"
                class="w-full h-52 object-cover transition duration-500 transform hover:scale-105"
              />
            </div>

            <!-- Product Details -->
            <div class="p-4 space-y-1">
              <h5 class="text-sm font-bold text-gray-800">{{ product.title }}</h5>
              <p class="text-gray-500 text-xs">Available: {{ product.quantity }} {{ product.metric }}</p>
              <p class="text-sm font-semibold text-gray-700">₱{{ product.price }} / {{ product.metric }}</p>

              <!-- Admin Buttons -->
              <div class="flex justify-between pt-2">
                <button @click="openForm(product)" class="text-blue-600 text-xs hover:underline">Edit</button>
                <button @click="deleteProduct(product.id)" class="text-red-600 text-xs hover:underline">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PRODUCT MODAL: Still shows VegetableAdminPage.vue in the background -->
<div v-if="showForm" class="fixed inset-0 bg-opacity-50 z-50 flex items-center justify-center">
  <!-- Modal Card -->
  <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-lg relative z-50">
    <h2 class="text-2xl font-semibold mb-4 text-center">
      {{ isEditing ? 'Edit Product' : 'Add New Product' }}
    </h2>

    <!-- Form Fields -->
    <input v-model="form.title" placeholder="Product Name" class="w-full p-2 mb-2 border rounded" />
    <input v-model="form.image" placeholder="Image URL" class="w-full p-2 mb-2 border rounded" />
    <input v-model.number="form.quantity" type="number" placeholder="Quantity" class="w-full p-2 mb-2 border rounded" />
    <input v-model.number="form.price" type="number" placeholder="Price" class="w-full p-2 mb-2 border rounded" />
    <input v-model="form.metric" placeholder="Metric (e.g. kilo)" class="w-full p-2 mb-4 border rounded" />

    <!-- Action Buttons -->
    <div class="flex justify-end gap-2">
      <button @click="closeForm" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
      <button @click="saveProduct" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        {{ isEditing ? 'Update' : 'Save' }}
      </button>
    </div>
  </div>
</div>


      </div>
    </section>

    <SiteFooter />
  </div>
</template>

<script>
import SiteHeader from './header.vue'
import SiteFooter from './footer.vue'

export default {
  name: 'VegetableAdminPage',
  components: {
    SiteHeader,
    SiteFooter,
  },
  data() {
    return {
      cart: [],
      sortBy: 'asc',
      itemsToShow: 10,
      showForm: false,
      isEditing: false,
      form: {
        id: null,
        title: '',
        image: '',
        quantity: null,
        price: '',
        metric: '',
      },
      products: [], // We'll fill it in mounted()
    }
  },
  computed: {
    sortedProducts() {
      return this.products
        .slice()
        .sort((a, b) => {
          const priceA = parseFloat(a.price)
          const priceB = parseFloat(b.price)
          return this.sortBy === 'asc' ? priceA - priceB : priceB - priceA
        })
        .slice(0, this.itemsToShow)
    },
  },
  methods: {
    openForm(product = null) {
      if (product) {
        this.isEditing = true
        this.form = { ...product }
      } else {
        this.isEditing = false
        this.form = {
          id: null,
          title: '',
          image: '',
          quantity: null,
          price: '',
          metric: '',
        }
      }
      this.showForm = true
    },
    closeForm() {
      this.showForm = false
    },
    saveProduct() {
      if (this.isEditing) {
        const index = this.products.findIndex(p => p.id === this.form.id)
        if (index !== -1) this.products.splice(index, 1, { ...this.form })
      } else {
        const newId = this.products.length ? Math.max(...this.products.map(p => p.id)) + 1 : 1
        this.products.push({ ...this.form, id: newId })
      }
      this.closeForm()
    },
    deleteProduct(id) {
      this.products = this.products.filter(p => p.id !== id)
    },
  },
  mounted() {
    this.products = [
      {
        id: 1, title: 'Fresh Broccoli', quantity: 20, price: '99.00', metric: 'kilo',
        image: 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=80'
      },
      {
        id: 2, title: 'Crisp Carrots', quantity: 15, price: '50.00', metric: 'kilo',
        image: 'https://images.unsplash.com/photo-1587049352841-4e60ec6dca1d?auto=format&fit=crop&w=800&q=80'
      },
      // Add more...
    ]
  }
}
</script>

<style scoped>
.text-maroon {
  color: #651818;
}
</style>
