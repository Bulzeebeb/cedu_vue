<template>
  <div class="shopee-cart">
    <!-- Header -->
    <div class="header">
      <div class="header-top">
        <a href="#" class="header-link">Seller Centre</a>
        <a href="#" class="header-link">Start Selling</a>
        <a href="#" class="header-link">Download</a>
        <a href="#" class="header-link">Follow us on</a>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <div class="header-right">
          <a href="#" class="header-link"><i class="fas fa-bell"></i> Notifications</a>
          <a href="#" class="header-link"><i class="fas fa-question-circle"></i> Help</a>
          <a href="#" class="header-link"><i class="fas fa-globe"></i> English</a>
          <a href="#" class="header-link"><i class="fas fa-user"></i> aleniscocoalerl</a>
        </div>
      </div>
      <div class="header-main">
        <div class="logo">
          <i class="fas fa-shopping-bag"></i>
          <span>Shopee</span>
        </div>
        <span class="checkout-title">Checkout</span>
      </div>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Main Content -->
    <div class="container">
      <!-- Delivery Address Section -->
      <div class="section delivery-address">
        <div class="section-header">
          <i class="fas fa-map-marker-alt"></i>
          <span>Delivery Address</span>
        </div>
        <div class="address-content">
          <div class="address-info">
            <p class="address-name">Alexis Nicole Bughao (+63) 906 476 1241</p>
            <p class="address">Prk. Rancho, Pagsabangan, Tagum City, Mindanao, Davao Del Norte 8100</p>
            <span class="badge">Default</span>
          </div>
          <a href="#" class="change-link">Change</a>
        </div>
      </div>

      <!-- Products Ordered Section -->
      <div class="section products-section">
        <div class="section-header">
          <i class="fas fa-shopping-bag"></i>
          <span>Products Ordered</span>
        </div>
        
        <!-- Shop Item -->
        <div class="shop-item">
          <div class="shop-header">
            <span class="shop-badge">★ Firebase</span>
            <span class="shop-name">Sunflower House</span>
            <span class="chat-badge"><i class="fas fa-comments"></i> Chat now</span>
          </div>

          <!-- Product -->
          <div class="product">
            <div class="product-image">
              <img src="/images/orderpow.png" alt="Product">
            </div>
            <div class="product-details">
              <p class="product-name">【BUY 1 TAKE 1】Oil Control Face Powder Matte I</p>
              <p class="product-variation">Variation: #01 Classic Ivory</p>
            </div>
            <div class="product-price">₱68</div>
            <div class="product-quantity">1</div>
            <div class="product-subtotal">₱68</div>
          </div>

          <!-- E-Invoice and Options -->
          <div class="product-footer">
            <div class="e-invoice">
              <a href="#"><i class="fas fa-file-invoice"></i> E-Invoice</a>
              <a href="#" class="request-now">Request Now</a>
            </div>
            <div class="product-options">
              <label class="checkbox">
                <input type="checkbox">
                <span>Shop Voucher</span>
              </label>
              <a href="#" class="select-voucher">Select Voucher</a>
            </div>
          </div>

          <!-- Message for Sellers -->
          <div class="message-section">
            <label>Message for Sellers:</label>
            <input type="text" placeholder="Please leave a message">
          </div>

          <!-- Shipping Option -->
          <div class="shipping-section">
            <div class="shipping-header">
              <span>Shipping Option:</span>
              <a href="#" class="change-link">Change</a>
            </div>
            <div class="shipping-info">
              <p class="shipping-method">Standard Local</p>
              <p class="shipping-time">Guaranteed to get by 27 Dec - 7 Jan</p>
              <p class="shipping-note">Get a ₱50 voucher if no delivery was attempted by 7 Jan 2025</p>
            </div>
            <p class="delivery-guarantee">
              <i class="fas fa-check-circle"></i>
              Guaranteed to get by 24 - 29 Dec with Shopee Self Pick-up
            </p>
            <p class="shipping-price">₱130</p>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-compact">
          <div class="summary-row">
            <span>Order Total (1 item)</span>
            <span class="price">₱198</span>
          </div>
        </div>
      </div>

      <!-- Shopee Voucher Section -->
      <div class="section voucher-section">
        <div class="voucher-header">
          <i class="fas fa-ticket-alt"></i>
          <span>Shopee Voucher</span>
          <a href="#" class="select-voucher">Select Voucher</a>
        </div>
      </div>

      <!-- Shopee Coins Section -->
      <div class="section coins-section">
        <div class="coins-header">
          <i class="fas fa-coins"></i>
          <span>Shopee Coins</span>
          <span class="coins-note">Coins cannot be redeemed</span>
        </div>
        <div class="coins-amount">
          <span>[ Fill ]</span>
        </div>
      </div>

      <!-- Payment Method Section -->
      <div class="section payment-section">
        <div class="payment-header">
          <div class="header-with-icon">
            <i class="fas fa-credit-card"></i>
            <span>Payment Method</span>
          </div>
          <a href="#" class="change-link" @click="isPaymentSelected = true">CHANGE</a>
        </div>
        <div class="payment-method">
          <div class="payment-option">
            <label class="radio-label">
              <input type="radio" v-model="selectedPayment" value="cash" @change="isPaymentSelected = true">
              <span>Cash on Delivery</span>
            </label>
          </div>
          <div class="payment-option">
            <label class="radio-label">
              <input type="radio" v-model="selectedPayment" value="credit">
              <span>Credit/Debit Card</span>
            </label>
          </div>
          <div class="payment-option">
            <label class="radio-label">
              <input type="radio" v-model="selectedPayment" value="ewallet">
              <span>E-Wallet</span>
            </label>
          </div>
          <div v-if="!isPaymentSelected" class="payment-hint">
            <i class="fas fa-info-circle"></i>
            <span>Please select a payment method</span>
          </div>
        </div>
      </div>

      <!-- Security Trust Section -->
      <div class="section security-section">
        <div class="trust-badge">
          <i class="fas fa-lock"></i>
          <div class="trust-content">
            <p class="trust-title">Secure Checkout</p>
            <p class="trust-text">✓ Shopee Buyer Protection Enabled</p>
            <p class="trust-text">✓ Your payment information is encrypted</p>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="section order-summary-section">
        <div class="summary-title">Order Summary</div>
        <div class="summary-item">
          <span>Merchandise Subtotal</span>
          <span class="price">₱68</span>
        </div>
        <div class="summary-item">
          <span>Shipping Subtotal</span>
          <span class="price">₱130</span>
        </div>
        <div class="summary-item total">
          <span>Total Payment</span>
          <span class="price">₱198</span>
        </div>
      </div>

      <!-- Place Order Button -->
      <div class="button-section">
        <button 
          class="place-order-btn" 
          :disabled="!isPaymentSelected"
          @click="placeOrder"
          :class="{ disabled: !isPaymentSelected }"
        >
          <i class="fas fa-check-circle"></i>
          Place Order Now
        </button>
        <p v-if="!isPaymentSelected" class="button-hint">
          <i class="fas fa-exclamation-circle"></i>
          Complete all required fields to continue
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ShopeeCart',
  data() {
    return {
      deliveryAddress: {
        name: 'Alexis Nicole Bughao',
        phone: '(+63) 906 476 1241',
        address: 'Prk. Rancho, Pagsabangan, Tagum City, Mindanao, Davao Del Norte 8100',
        isDefault: true
      },
      shop: {
        name: 'Sunflower House',
        badge: 'Firebase'
      },
      product: {
        name: '【BUY 1 TAKE 1】Oil Control Face Powder Matte I',
        variation: '#01 Classic Ivory',
        unitPrice: 68,
        quantity: 1,
        subtotal: 68,
        image: 'https://via.placeholder.com/50'
      },
      shipping: {
        method: 'Standard Local',
        timeframe: 'Guaranteed to get by 27 Dec - 7 Jan',
        note: 'Get a ₱50 voucher if no delivery was attempted by 7 Jan 2025',
        price: 130,
        guarantee: 'Guaranteed to get by 24 - 29 Dec with Shopee Self Pick-up'
      },
      selectedPayment: 'cash',
      isPaymentSelected: true,
      paymentMethod: 'Cash on Delivery',
      orderSummary: {
        merchandiseSubtotal: 68,
        shippingSubtotal: 130,
        totalPayment: 198
      }
    };
  },
  methods: {
    placeOrder() {
      if (!this.selectedPayment) {
        alert('Please select a payment method');
        return;
      }
      alert('Order placed successfully with ' + this.selectedPayment + ' payment method!');
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.shopee-cart {
  background-color: #f5f5f5;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

/* Header Styles */
.header {
  background-color: #fff;
  padding: 10px 0;
}

.header-top {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  font-size: 12px;
  gap: 20px;
  border-bottom: 1px solid #f0f0f0;
}

.header-link {
  text-decoration: none;
  color: #333;
  font-size: 12px;
}

.header-link:hover {
  color: #ee4d2d;
}

.social-icons {
  display: flex;
  gap: 10px;
}

.social-icons a {
  color: #333;
  text-decoration: none;
}

.header-right {
  margin-left: auto;
  display: flex;
  gap: 20px;
}

.header-main {
  padding: 15px 20px;
  display: flex;
  align-items: center;
  gap: 30px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 24px;
  font-weight: bold;
  color: #ee4d2d;
}

.logo i {
  background-color: #ee4d2d;
  color: #fff;
  padding: 5px 8px;
  border-radius: 3px;
  font-size: 20px;
}

.checkout-title {
  font-size: 14px;
  color: #333;
}

/* Divider */
.divider {
  height: 8px;
  background: linear-gradient(90deg, #ff6b6b, #ffa5a5, #4ecdc4, #ffa5a5, #ff6b6b);
  margin-bottom: 30px;
}

/* Container */
.container {
  max-width: 800px;
  margin: 0 auto;
  padding-bottom: 40px;
}

/* Section Styles */
.section {
  background-color: #fff;
  margin-bottom: 20px;
  padding: 20px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f0f0f0;
}

.section-header i {
  color: #ee4d2d;
  font-size: 16px;
}

/* Delivery Address */
.delivery-address {
  padding: 16px;
}

.address-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.address-info {
  flex: 1;
}

.address-name {
  font-size: 14px;
  font-weight: 500;
  color: #333;
  margin-bottom: 4px;
}

.address {
  font-size: 13px;
  color: #666;
  margin-bottom: 8px;
}

.badge {
  display: inline-block;
  background-color: #fff0ee;
  color: #ee4d2d;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.change-link {
  color: #0066cc;
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
}

.change-link:hover {
  text-decoration: underline;
}

/* Products Section */
.products-section {
  padding: 0;
}

.shop-item {
  border: 1px solid #f0f0f0;
}

.shop-header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
}

.shop-badge {
  background-color: #ee4d2d;
  color: #fff;
  padding: 2px 6px;
  border-radius: 2px;
  font-weight: 600;
  font-size: 12px;
}

.shop-name {
  font-weight: 500;
  color: #333;
  flex: 1;
}

.chat-badge {
  background-color: #fff;
  border: 1px solid #0066cc;
  color: #0066cc;
  padding: 4px 8px;
  border-radius: 2px;
  font-size: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 4px;
}

.chat-badge:hover {
  background-color: #f0f8ff;
}

/* Product */
.product {
  display: grid;
  grid-template-columns: 50px 1fr 60px 60px 80px;
  gap: 12px;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
}

.product-image {
  width: 50px;
  height: 50px;
  background-color: #f0f0f0;
  border-radius: 2px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-details {
  flex: 1;
}

.product-name {
  font-size: 13px;
  color: #333;
  font-weight: 500;
  margin-bottom: 4px;
}

.product-variation {
  font-size: 12px;
  color: #999;
}

.product-price,
.product-quantity,
.product-subtotal {
  text-align: center;
  color: #333;
}

.product-subtotal {
  font-weight: 600;
}

/* Product Footer */
.product-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
}

.e-invoice {
  display: flex;
  gap: 12px;
}

.e-invoice a {
  color: #0066cc;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 4px;
}

.request-now {
  color: #0066cc !important;
}

.product-options {
  display: flex;
  align-items: center;
  gap: 12px;
}

.checkbox {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  user-select: none;
}

.checkbox input {
  cursor: pointer;
  width: 16px;
  height: 16px;
}

.select-voucher {
  color: #0066cc;
  text-decoration: none;
}

/* Message Section */
.message-section {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
}

.message-section label {
  display: block;
  font-size: 13px;
  color: #666;
  margin-bottom: 8px;
  font-weight: 500;
}

.message-section input {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #ddd;
  border-radius: 2px;
  font-size: 13px;
  font-family: inherit;
}

.message-section input::placeholder {
  color: #ccc;
}

.message-section input:focus {
  outline: none;
  border-color: #0066cc;
}

/* Shipping Section */
.shipping-section {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
}

.shipping-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.shipping-header span {
  font-size: 13px;
  font-weight: 500;
  color: #333;
}

.shipping-info {
  margin-bottom: 8px;
}

.shipping-method {
  font-size: 13px;
  color: #333;
  font-weight: 500;
  margin-bottom: 4px;
}

.shipping-time {
  font-size: 12px;
  color: #666;
  margin-bottom: 4px;
}

.shipping-note {
  font-size: 12px;
  color: #999;
  margin-bottom: 8px;
}

.delivery-guarantee {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #16a34a;
  margin-bottom: 8px;
}

.delivery-guarantee i {
  color: #16a34a;
}

.shipping-price {
  font-size: 13px;
  color: #333;
  font-weight: 500;
  text-align: right;
}

/* Order Summary Compact */
.order-summary-compact {
  padding: 12px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fafafa;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  font-size: 13px;
}

.summary-row .price {
  color: #333;
  font-weight: 600;
}

/* Voucher Section */
.voucher-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.voucher-header {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
}

.voucher-header i {
  color: #ee4d2d;
  font-size: 16px;
}

.voucher-header span {
  font-size: 13px;
  color: #333;
  font-weight: 500;
}

/* Coins Section */
.coins-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.coins-header {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
}

.coins-header i {
  color: #ffa500;
  font-size: 16px;
}

.coins-note {
  font-size: 12px;
  color: #999;
  margin-left: auto;
}

.coins-amount {
  color: #666;
  font-size: 13px;
}

/* Payment Section */
.payment-section {
  display: block;
}

.payment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f0f0f0;
}

.payment-header span {
  font-size: 14px;
  font-weight: 600;
  color: #333;
}

.header-with-icon {
  display: flex;
  align-items: center;
  gap: 8px;
}

.header-with-icon i {
  color: #ee4d2d;
  font-size: 16px;
}

.payment-method {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.payment-option {
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.payment-option:hover {
  border-color: #ee4d2d;
  background-color: #fafafa;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  user-select: none;
  font-size: 13px;
  color: #333;
  margin: 0;
}

.radio-label input[type="radio"] {
  cursor: pointer;
  width: 16px;
  height: 16px;
  accent-color: #ee4d2d;
  margin: 0;
}

.payment-hint {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px;
  background-color: #fff3f0;
  border-left: 3px solid #ee4d2d;
  border-radius: 2px;
  font-size: 13px;
  color: #ee4d2d;
  margin-top: 8px;
}

.payment-hint i {
  font-size: 14px;
}

/* Security Section */
.security-section {
  background: linear-gradient(135deg, #f0f9ff 0%, #fff0f5 100%);
  border: 1px solid #e0f2fe;
  padding: 16px;
}

.trust-badge {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.trust-badge i {
  color: #16a34a;
  font-size: 24px;
  margin-top: 2px;
}

.trust-content {
  flex: 1;
}

.trust-title {
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 6px;
}

.trust-text {
  font-size: 12px;
  color: #666;
  margin: 4px 0;
}

/* Order Summary Section */
.order-summary-section {
  padding: 20px;
  background-color: #fff;
}

.summary-title {
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f0f0f0;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  font-size: 13px;
  color: #666;
  border-bottom: 1px solid #f0f0f0;
}

.summary-item.total {
  border-bottom: none;
  color: #333;
  font-weight: 600;
  font-size: 16px;
  padding: 12px 0;
}

.summary-item .price {
  color: #333;
  font-weight: 500;
}

.summary-item.total .price {
  color: #ee4d2d;
  font-size: 18px;
}

/* Button Section */
.button-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 24px 20px;
  text-align: center;
}

.place-order-btn {
  background-color: #ee4d2d;
  color: #fff;
  border: none;
  padding: 18px 60px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(238, 77, 45, 0.3);
}

.place-order-btn:hover:not(:disabled) {
  background-color: #d63d1d;
  box-shadow: 0 6px 16px rgba(238, 77, 45, 0.4);
  transform: translateY(-2px);
}

.place-order-btn:active:not(:disabled) {
  background-color: #c43214;
  transform: translateY(0);
}

.place-order-btn:disabled,
.place-order-btn.disabled {
  background-color: #ccc;
  cursor: not-allowed;
  box-shadow: none;
  transform: none;
}

.button-hint {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 12px;
  color: #ee4d2d;
  margin-top: -8px;
}

.button-hint i {
  font-size: 14px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .product {
    grid-template-columns: 50px 1fr 50px;
    gap: 8px;
  }

  .product-price,
  .product-subtotal {
    display: none;
  }

  .product-quantity {
    text-align: right;
  }

  .header-top {
    flex-wrap: wrap;
    gap: 10px;
  }

  .header-right {
    width: 100%;
    flex-wrap: wrap;
  }

  .product-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .button-section {
    padding: 20px 16px;
  }

  .place-order-btn {
    width: 100%;
    padding: 16px;
    font-size: 15px;
  }

  .section {
    padding: 16px;
  }

  .trust-badge {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .trust-badge i {
    font-size: 28px;
  }

  .payment-option {
    padding: 10px;
  }
}
</style>
