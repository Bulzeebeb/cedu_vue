<template>
  <div class="foodpanda-container">
    <!-- Toast Notification -->
    <transition name="toast">
      <div v-if="showToast" class="toast-notification">
        <span class="toast-icon">✓</span>
        <span class="toast-message">{{ toastMessage }}</span>
      </div>
    </transition>

    <!-- Header -->
    <header class="header">
      <div class="header-left">
        <div class="logo">
          <img src="/images/unnamed.png" alt="foodpanda" class="logo-icon" />
          <span class="logo-text">foodpanda</span>
        </div>
        
        <div class="header-info">
          <div class="location">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            <span>Home Pagsabangan Rd, 8100 Tagum, Philippines</span>
          </div>
          <div class="delivery-time">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Time Standard (15 - 30 mins)</span>
          </div>
        </div>
      </div>

      <div class="header-right">
        <div class="search-container">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text" placeholder="Search in menu" class="search-input"/>
        </div>

        <div class="user-profile">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <div class="user-info">
            <p class="user-name">Alexis Nicole</p>
            <p class="user-status">Premium Member</p>
          </div>
        </div>

        <div class="action-buttons">
          <button class="icon-btn" title="Wishlist">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
          </button>
          <button class="icon-btn cart-btn" title="Cart">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"/>
              <circle cx="20" cy="21" r="1"/>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <span class="cart-badge">1</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Category Navigation -->
    <nav class="category-nav">
      <div class="nav-scroll">
        <div class="nav-search">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text" placeholder="Search categories..." class="nav-search-input"/>
        </div>
        <button
          v-for="category in categories"
          :key="category.id"
          :class="['category-tab', { active: activeCategory === category.id }]"
          @click="activeCategory = category.id"
        >
          <span class="cat-icon">{{ category.icon }}</span>
          <span class="cat-name">{{ category.name }}</span>
          <span class="cat-count">{{ category.count }}</span>
        </button>
      </div>
    </nav>

    <div class="main-container">
      <!-- Menu Items Section -->
      <main class="menu-container">
        <!-- Popular Section -->
        <section class="menu-section">
          <div class="section-header">
            <div class="section-title">
              <span class="fire-icon">🔥</span>
              <h2>Popular</h2>
            </div>
            <p class="section-subtitle">Most ordered items right now</p>
          </div>

          <div class="menu-grid">
            <div v-for="item in popularItems" :key="item.id" class="menu-card">
              <div class="card-image-wrapper">
                <img :src="item.image" :alt="item.name" class="card-image" />
                <div v-if="item.badge" class="card-badge">{{ item.badge }}</div>
              </div>
              <div class="card-content">
                <h3 class="item-name">{{ item.name }}</h3>
                <p v-if="item.description" class="item-description">
                  {{ item.description }}
                </p>
                <div class="item-footer">
                  <span class="item-price">₱{{ item.price }}</span>
                  <button class="add-btn" @click="addToCart(item)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <span>Add</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- New Offers Section -->
        <section class="menu-section">
          <div class="section-header">
            <div class="section-title">
              <span class="gift-icon">🎁</span>
              <h2>New Offers</h2>
            </div>
            <p class="section-subtitle">Latest deals and promotions</p>
          </div>

          <div class="menu-grid">
            <div v-for="item in newOffers" :key="item.id" class="menu-card">
              <div class="card-image-wrapper">
                <img :src="item.image" :alt="item.name" class="card-image" />
                <div v-if="item.badge" class="card-badge discount">{{ item.badge }}</div>
              </div>
              <div class="card-content">
                <h3 class="item-name">{{ item.name }}</h3>
                <p v-if="item.description" class="item-description">
                  {{ item.description }}
                </p>
                <div class="item-footer">
                  <span class="item-price">₱{{ item.price }}</span>
                  <button class="add-btn" @click="addToCart(item)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <span>Add</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <!-- Order Summary Sidebar -->
      <aside class="order-sidebar">
        <!-- Recommendations -->
        <div class="recommendations-card">
          <div class="card-header">
            <h3>Popular with your order</h3>
            <p>Other customers also bought these</p>
          </div>
          
          <div class="recommendations-carousel">
            <button class="carousel-nav-btn prev">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
              </svg>
            </button>
            
            <div class="carousel-items">
              <div v-for="item in sidebarItems" :key="item.id" class="carousel-item">
                <img :src="item.image" :alt="item.name" />
                <p class="item-title">{{ item.name }}</p>
                <p class="item-cost">₱{{ item.price }}</p>
                <button class="mini-add-btn" @click="addToCart(item)">+</button>
              </div>
            </div>

            <button class="carousel-nav-btn next">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="summary-card">
          <div class="summary-header">
            <h3>Order Summary</h3>
          </div>

          <div class="summary-details">
            <div class="summary-item">
              <span class="label">Subtotal</span>
              <span class="value">₱302</span>
            </div>
            <div class="summary-item">
              <span class="label">Delivery Fee</span>
              <span class="value">₱59</span>
            </div>
            <div class="summary-item">
              <span class="label">Service Fee</span>
              <span class="value">₱9</span>
            </div>
          </div>

          <!-- Cutlery Option -->
          <div class="cutlery-option">
            <div class="cutlery-header">
              <span class="utensil-icon">🥄</span>
              <div class="cutlery-text">
                <p class="cutlery-label">Cutlery</p>
                <p class="cutlery-hint">No cutlery provided</p>
              </div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" />
              <span class="slider"></span>
            </label>
          </div>

          <!-- Promo -->
          <div class="promo-banner">
            <span class="promo-icon">✨</span>
            <div class="promo-text">
              <p class="promo-main">₱47 more to free delivery</p>
              <p class="promo-sub">Add items to qualify</p>
            </div>
          </div>

          <!-- Total -->
          <div class="total-section">
            <div class="total-display">
              <span class="total-label">Total</span>
              <div class="total-info">
                <p class="total-amount">₱370</p>
                <p class="total-note">(incl. fees and tax)</p>
              </div>
            </div>
            <button class="summary-link">See summary</button>
          </div>

          <!-- Checkout Button -->
          <button class="checkout-btn">
            <span>Proceed to Checkout</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </button>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeCategory = ref(1)
const showToast = ref(false)
const toastMessage = ref('')
const cartCount = ref(1)
const cartSubtotal = ref(302)

const categories = [
  { id: 1, name: 'Popular', count: 6, icon: '🔥' },
  { id: 2, name: 'New Offers', count: 3, icon: '🎁' },
  { id: 3, name: 'Featured', count: 2, icon: '⭐' },
  { id: 4, name: 'Burgers', count: 4, icon: '🍔' },
  { id: 5, name: 'Chicken', count: 8, icon: '🍗' },
  { id: 6, name: 'Group Meals', count: 3, icon: '👥' },
  { id: 7, name: 'Fries & Extras', count: 16, icon: '🍟' },
  { id: 8, name: 'Drinks', count: 12, icon: '🥤' }
]

const popularItems = [
  {
    id: 1,
    name: 'Medium Fries',
    price: 94,
    badge: null,
    image: '/images/1.png'
  },
  {
    id: 2,
    name: 'BFF Fries',
    price: 180,
    badge: 'Popular',
    description: 'World famous fries for 3-4 persons',
    image: '/images/2.png'
  },
  {
    id: 3,
    name: 'Large Fries',
    price: 114,
    badge: null,
    image: '/images/3.png'
  },
  {
    id: 4,
    name: 'Shake Shake Fries BBQ',
    price: 104,
    badge: 'New',
    image: '/images/4.png'
  },
  {
    id: 5,
    name: 'Crispy Chicken Sandwich',
    price: 177,
    badge: null,
    description: 'With fries & drink',
    image: '/images/5.png'
  },
  {
    id: 6,
    name: 'BFF McShaker BBQ Fries',
    price: 199,
    badge: 'Limited',
    image: '/images/6.png'
  }
]

const newOffers = [
  {
    id: 7,
    name: 'Spicy Combo Meal',
    price: 150,
    badge: '15% OFF',
    description: 'Limited time',
    image: 'https://via.placeholder.com/280x200/FF6B9D/ffffff?text=Spicy+Combo'
  },
  {
    id: 8,
    name: 'Double Burger Meal',
    price: 250,
    badge: '20% OFF',
    description: 'Two burgers with sides',
    image: 'https://via.placeholder.com/280x200/C44569/ffffff?text=Double+Burger'
  },
  {
    id: 9,
    name: 'Family Bundle',
    price: 899,
    badge: '25% OFF',
    description: 'Feeds 4-5 persons',
    image: 'https://via.placeholder.com/280x200/F8B500/ffffff?text=Family+Bundle'
  }
]

const sidebarItems = [
  {
    id: 10,
    name: 'Burger McDo',
    price: 49,
    image: '/images/5.png'
  },
  {
    id: 11,
    name: 'Crispy Chicken',
    price: 70,
    image: '/images/6.png'
  },
  {
    id: 12,
    name: 'Extra Sides',
    price: 120,
    image: '/images/4.png'
  }
]

const addToCart = (item) => {
  toastMessage.value = `✓ Added ${item.name} to cart`
  showToast.value = true
  cartCount.value += 1
  cartSubtotal.value += item.price
  console.log('Added to cart:', item)
  
  setTimeout(() => {
    showToast.value = false
  }, 2000)
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary-color: #e91e63;
  --secondary-color: #f5f5f5;
  --text-primary: #1a1a1a;
  --text-secondary: #666;
  --text-tertiary: #999;
  --border-color: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.foodpanda-container {
  display: flex;
  flex-direction: column;
  background-color: var(--secondary-color);
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Header Styles */
.header {
  background-color: white;
  padding: 12px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: var(--shadow-sm);
  border-bottom: 1px solid var(--border-color);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 24px;
  flex: 1;
}

.logo {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  white-space: nowrap;
}

.logo-icon {
  width: 32px;
  height: 32px;
  flex-shrink: 0;
}

.logo-text {
  font-size: 18px;
  font-weight: 700;
  color: var(--primary-color);
  letter-spacing: -0.5px;
}

.header-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.location,
.delivery-time {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: var(--text-secondary);
  line-height: 1.4;
}

.location svg,
.delivery-time svg {
  width: 16px;
  height: 16px;
  color: var(--primary-color);
  flex-shrink: 0;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-left: 24px;
}

.search-container {
  position: relative;
  width: 200px;
}

.search-input {
  width: 100%;
  padding: 8px 12px 8px 32px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  font-size: 13px;
  background-color: #f9f9f9;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color);
  background-color: white;
  box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
}

.search-container svg {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  width: 16px;
  height: 16px;
  color: var(--text-tertiary);
  pointer-events: none;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 12px;
  background-color: #f9f9f9;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.user-profile:hover {
  background-color: #f0f0f0;
}

.user-profile svg {
  width: 20px;
  height: 20px;
  color: var(--primary-color);
  flex-shrink: 0;
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.user-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-primary);
}

.user-status {
  font-size: 11px;
  color: var(--text-tertiary);
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 8px;
}

.icon-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background-color: #f9f9f9;
  color: var(--text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.icon-btn:hover {
  background-color: var(--primary-color);
  color: white;
}

.icon-btn svg {
  width: 18px;
  height: 18px;
}

.cart-btn {
  position: relative;
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: var(--primary-color);
  color: white;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: 700;
}

/* Category Navigation */
.category-nav {
  background-color: white;
  padding: 0;
  border-bottom: 1px solid var(--border-color);
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  position: sticky;
  top: 0;
  z-index: 50;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.nav-scroll {
  display: flex;
  padding: 12px 0;
  gap: 4px;
  align-items: center;
}

.nav-search {
  position: relative;
  width: 200px;
  flex-shrink: 0;
  margin-right: 12px;
  border-right: 1px solid var(--border-color);
  padding-right: 12px;
}

.nav-search svg {
  position: absolute;
  left: 8px;
  top: 50%;
  transform: translateY(-50%);
  width: 16px;
  height: 16px;
  color: var(--text-tertiary);
  pointer-events: none;
}

.nav-search-input {
  width: 100%;
  padding: 8px 12px 8px 32px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  font-size: 13px;
  background-color: #f9f9f9;
  transition: all 0.3s ease;
  color: var(--text-primary);
}

.nav-search-input:focus {
  outline: none;
  border-color: var(--primary-color);
  background-color: white;
  box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
}

.nav-search-input::placeholder {
  color: var(--text-tertiary);
}

.category-tab {
  padding: 8px 16px;
  border: none;
  background: none;
  color: var(--text-secondary);
  font-size: 13px;
  cursor: pointer;
  border-bottom: 3px solid transparent;
  transition: all 0.3s ease;
  white-space: nowrap;
  display: flex;
  gap: 6px;
  align-items: center;
}

.cat-icon {
  font-size: 18px;
}

.cat-name {
  font-weight: 500;
}

.cat-count {
  font-size: 12px;
  color: var(--text-tertiary);
}

.category-tab.active {
  color: var(--text-primary);
  border-bottom-color: var(--primary-color);
  font-weight: 600;
  background-color: rgba(233, 30, 99, 0.05);
}

.category-tab.active .cat-count {
  color: var(--primary-color);
  font-weight: 600;
}

.category-tab:hover:not(.active) {
  color: var(--text-primary);
  background-color: rgba(0, 0, 0, 0.02);
}

/* Main Container */
.main-container {
  display: flex;
  flex: 1;
  padding: 24px 0;
  gap: 24px;
}

.menu-container {
  flex: 1;
  overflow-y: auto;
}

/* Menu Section */
.menu-section {
  margin-bottom: 40px;
}

.section-header {
  margin-bottom: 20px;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 6px;
}

.fire-icon,
.gift-icon {
  font-size: 24px;
}

.section-title h2 {
  font-size: 22px;
  font-weight: 700;
  color: var(--text-primary);
}

.section-subtitle {
  color: var(--text-tertiary);
  font-size: 13px;
  font-weight: 500;
}

/* Menu Grid */
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}

.menu-card {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
}

.menu-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.card-image-wrapper {
  position: relative;
  width: 100%;
  padding-top: 66.67%;
  overflow: hidden;
  background-color: var(--secondary-color);
}

.card-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.menu-card:hover .card-image {
  transform: scale(1.05);
}

.card-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  background-color: var(--primary-color);
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.card-badge.discount {
  background-color: #ff6b6b;
}

.card-content {
  padding: 14px 12px;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.item-name {
  font-size: 15px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 4px;
  line-height: 1.3;
}

.item-description {
  font-size: 12px;
  color: var(--text-tertiary);
  margin-bottom: 8px;
  line-height: 1.4;
}

.item-type {
  display: none;
}

.item-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  gap: 10px;
}

.item-price {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
}

.add-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 6px;
  border: none;
  background-color: #d41c5f;
  color: white;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
  white-space: nowrap;
  flex-shrink: 0;
}

.add-btn:hover {
  background-color: #d41c5f;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(233, 30, 99, 0.3);
}

.add-btn:active {
  transform: translateY(0);
}

.add-btn svg {
  width: 14px;
  height: 14px;
}

/* Order Sidebar */
.order-sidebar {
  width: 340px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  position: sticky;
  top: 140px;
  max-height: calc(100vh - 160px);
  overflow-y: auto;
}

/* Recommendations Card */
.recommendations-card {
  background-color: white;
  border-radius: 8px;
  padding: 16px;
  box-shadow: var(--shadow-sm);
}

.card-header {
  margin-bottom: 16px;
}

.card-header h3 {
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 4px;
}

.card-header p {
  font-size: 12px;
  color: var(--text-tertiary);
}

.recommendations-carousel {
  position: relative;
  display: flex;
  align-items: center;
  gap: 8px;
}

.carousel-nav-btn {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 1px solid var(--border-color);
  background-color: white;
  color: var(--text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.carousel-nav-btn:hover {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.carousel-nav-btn svg {
  width: 16px;
  height: 16px;
}

.carousel-items {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  flex: 1;
  -webkit-overflow-scrolling: touch;
  padding: 0 4px;
}

.carousel-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 6px;
  padding: 8px;
  flex-shrink: 0;
  width: 80px;
  cursor: pointer;
  transition: all 0.3s ease;
  border-radius: 6px;
}

.carousel-item:hover {
  background-color: var(--secondary-color);
}

.carousel-item img {
  width: 60px;
  height: 60px;
  border-radius: 6px;
  object-fit: cover;
}

.carousel-item .item-title {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-primary);
  line-height: 1.3;
}

.carousel-item .item-cost {
  font-size: 12px;
  font-weight: 700;
  color: var(--primary-color);
}

.mini-add-btn {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: 1.5px solid var(--primary-color);
  background-color: white;
  color: var(--primary-color);
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.mini-add-btn:hover {
  background-color: var(--primary-color);
  color: white;
}

/* Summary Card */
.summary-card {
  background-color: white;
  border-radius: 8px;
  padding: 16px;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.summary-header {
  margin-bottom: 4px;
}

.summary-header h3 {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
}

.summary-details {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--border-color);
}

.summary-item {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  color: var(--text-secondary);
}

.summary-item .label {
  font-weight: 500;
}

.summary-item .value {
  font-weight: 600;
  color: var(--text-primary);
}

/* Cutlery Option */
.cutlery-option {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
  padding: 12px;
  background-color: #fafafa;
  border-radius: 6px;
  border: 1px solid var(--border-color);
}

.cutlery-header {
  display: flex;
  align-items: center;
  gap: 8px;
}

.utensil-icon {
  font-size: 18px;
  flex-shrink: 0;
}

.cutlery-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.cutlery-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-primary);
}

.cutlery-hint {
  font-size: 11px;
  color: var(--text-tertiary);
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
  flex-shrink: 0;
  margin-top: 2px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  border-radius: 24px;
  transition: 0.3s;
}

.slider::before {
  position: absolute;
  content: '';
  height: 20px;
  width: 20px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  border-radius: 50%;
  transition: 0.3s;
}

input:checked + .slider {
  background-color: var(--primary-color);
}

input:checked + .slider::before {
  transform: translateX(16px);
}

/* Promo Banner */
.promo-banner {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px;
  background: linear-gradient(135deg, rgba(233, 30, 99, 0.08) 0%, rgba(233, 30, 99, 0.04) 100%);
  border-radius: 6px;
  border: 1px solid rgba(233, 30, 99, 0.2);
}

.promo-icon {
  font-size: 18px;
  flex-shrink: 0;
}

.promo-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
  flex: 1;
}

.promo-main {
  font-size: 12px;
  font-weight: 600;
  color: var(--primary-color);
}

.promo-sub {
  font-size: 11px;
  color: var(--text-tertiary);
}

/* Total Section */
.total-section {
  padding-top: 12px;
  border-top: 1px solid var(--border-color);
  margin-bottom: 8px;
}

.total-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.total-label {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-secondary);
}

.total-info {
  text-align: right;
}

.total-amount {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
}

.total-note {
  font-size: 10px;
  color: var(--text-tertiary);
  margin-top: 2px;
}

.summary-link {
  background: none;
  border: none;
  color: var(--primary-color);
  text-decoration: none;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  padding: 0;
  text-align: left;
  transition: all 0.3s ease;
}

.summary-link:hover {
  text-decoration: underline;
}

/* Checkout Button */
.checkout-btn {
  width: 100%;
  padding: 14px 16px;
  background: #d41c5f;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 4px;
}

.checkout-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
}

.checkout-btn:active {
  transform: translateY(0);
}

.checkout-btn svg {
  width: 16px;
  height: 16px;
}

/* Toast Notification */
.toast-notification {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background-color: var(--primary-color);
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 4px 16px rgba(233, 30, 99, 0.4);
  z-index: 1000;
}

.toast-icon {
  font-weight: 700;
  font-size: 16px;
}

.toast-message {
  flex: 1;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(-50%) translateY(-20px);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(-20px);
}

/* Responsive Design */
@media (max-width: 1280px) {
  .order-sidebar {
    width: 320px;
  }
}

@media (max-width: 1024px) {
  .main-container {
    flex-direction: column;
    gap: 20px;
  }

  .order-sidebar {
    width: 100%;
    flex-direction: row;
  }

  .recommendations-card,
  .summary-card {
    flex: 1;
  }

  .menu-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  }
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    gap: 12px;
    padding: 12px 16px;
  }

  .header-left {
    width: 100%;
    flex-direction: column;
    gap: 12px;
  }

  .header-right {
    width: 100%;
    justify-content: space-between;
    margin-left: 0;
  }

  .search-container {
    width: 100%;
  }

  .main-container {
    padding: 16px;
  }

  .menu-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  }

  .order-sidebar {
    width: 100%;
  }

  .user-info {
    display: none;
  }
}

@media (max-width: 480px) {
  .header-info {
    display: none;
  }

  .menu-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }

  .nav-scroll {
    padding: 8px 12px;
    gap: 2px;
  }

  .category-tab {
    padding: 6px 12px;
    font-size: 12px;
  }

  .cat-count {
    display: none;
  }
}
</style>
