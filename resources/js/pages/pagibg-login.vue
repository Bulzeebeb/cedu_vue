<template>
  <div class="pag-ibig-container">
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo-section">
          <img src="/images/pagibig.png" alt="VIRTUAL Pag-IBIG" class="logo" />
        </div>
        <nav class="nav-links">
          <a href="#" class="nav-link">Pag-IBIG Fund Website</a>
          <a href="#" class="nav-link">FAQs</a>
          <a href="#" class="nav-link">Contact Us</a>
          <button class="home-btn">Home</button>
        </nav>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="content-wrapper">
        <!-- Left Section - Login Form -->
        <div class="left-section">
          <div class="form-container">
            <!-- Security Badge -->
            <div class="security-badge">
              <svg class="lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <span>Secure Login</span>
            </div>

            <!-- Primary Action: Login -->
            <div class="login-section-primary">
              <h2 class="form-title">Sign In to Your Account</h2>
              
              <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                  <input 
                    id="email"
                    type="email" 
                    placeholder="Enter your email" 
                    class="form-input"
                    :class="{ 'input-error': errors.email }"
                    v-model="form.email"
                    @blur="validateEmail"
                    aria-label="Email Address"
                  />
                </div>
                <span v-if="errors.email" class="error-message">
                  {{ errors.email }}
                </span>
              </div>
              
              <div class="form-group">
                <div class="label-row">
                  <label for="password" class="form-label">Password</label>
                  <a href="#" class="forgot-link">Forgot password?</a>
                </div>
                <div class="input-wrapper">
                  <input 
                    id="password"
                    type="password" 
                    placeholder="Enter your password" 
                    class="form-input"
                    :class="{ 'input-error': errors.password }"
                    v-model="form.password"
                    @blur="validatePassword"
                    aria-label="Password"
                  />
                </div>
                <span v-if="errors.password" class="error-message">
                  {{ errors.password }}
                </span>
              </div>

              <!-- Login Error Message -->
              <div v-if="errors.loginError" class="alert alert-error">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="currentColor">
                  <circle cx="12" cy="12" r="10"></circle>
                  <text x="12" y="16" text-anchor="middle" fill="white" font-size="20">!</text>
                </svg>
                {{ errors.loginError }}
              </div>

              <!-- Primary Login Button -->
              <button 
                class="login-btn" 
                @click="handleLogin"
                :disabled="isLoading"
                :class="{ 'is-loading': isLoading }"
              >
                <span v-if="!isLoading" class="btn-text">Sign In</span>
                <span v-else class="btn-text">
                  <span class="spinner"></span> Signing In...
                </span>
              </button>

              <!-- Divider -->
              <div class="divider">
                <span>OR</span>
              </div>

              <!-- Help Links Group -->
              <div class="help-links-group">
                <p class="help-label">Need Help?</p>
                <div class="help-links">
                  <a href="#" class="help-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                    </svg>
                    First Time Log In
                  </a>
                  <a href="#" class="help-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                    </svg>
                    Unlock Account
                  </a>
                </div>
              </div>

              <!-- Cancel Button -->
              <button class="cancel-btn" @click="handleCancel">
                Cancel
              </button>
            </div>

            <!-- Secondary Action: Create Account -->
            <div class="create-account-section">
              <p class="section-label">Don't have an account?</p>
              <button class="create-account-btn">Create Account</button>
            </div>
          </div>
        </div>

        <!-- Right Section - Image -->
        <div class="right-section">
          <div class="hero-image-container">
            <img 
              src="/images/pagibigmo.png" 
              alt="Virtual Pag-IBIG" 
              class="hero-image"
            />
            <div class="background-design"></div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <!-- Browse Column 1 -->
        <div class="footer-column">
          <h3 class="footer-title">BROWSE OUR WEBSITE</h3>
          <ul class="footer-links">
            <li><a href="#" class="footer-link">Membership</a></li>
            <li><a href="#" class="footer-link">Benefits</a></li>
            <li><a href="#" class="footer-link">Programs</a></li>
            <li><a href="#" class="footer-link">Services</a></li>
          </ul>
        </div>

        <!-- Browse Column 2 -->
        <div class="footer-column">
          <h3 class="footer-title">BROWSE OUR WEBSITE</h3>
          <ul class="footer-links">
            <li><a href="#" class="footer-link">People Behind</a></li>
            <li><a href="#" class="footer-link">News & Updates</a></li>
            <li><a href="#" class="footer-link">Careers</a></li>
            <li><a href="#" class="footer-link">Downloads</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="footer-column contact-column">
          <h3 class="footer-title">CONTACT INFORMATION</h3>
          <div class="contact-info">
            <p class="contact-item">
              <span class="contact-label">Address:</span>
              Pag-IBIG Center, Diliman, Quezon City
            </p>
            <p class="contact-item">
              <span class="contact-label">Phone:</span>
              1-800-Pag-IBIG
            </p>
            <p class="contact-item">
              <span class="contact-label">Email:</span>
              support@pagibig.gov.ph
            </p>
          </div>
        </div>

        <!-- Logo Section -->
        <div class="footer-logo-section">
          <img src="/images/pagibig.png" alt="Pag-IBIG" class="footer-logo" />
        </div>
      </div>

      <!-- Chat Widget -->
      <div class="chat-widget">
        <div class="chat-bubble">
          <p class="chat-text">Hi, click here to chat with us!</p>
          <div class="chat-avatar">😊</div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  email: '',
  password: ''
})

const errors = ref({
  email: '',
  password: '',
  loginError: ''
})

const isLoading = ref(false)

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!form.value.email) {
    errors.value.email = 'Email is required'
  } else if (!emailRegex.test(form.value.email)) {
    errors.value.email = 'Please enter a valid email address'
  } else {
    errors.value.email = ''
  }
}

const validatePassword = () => {
  if (!form.value.password) {
    errors.value.password = 'Password is required'
  } else if (form.value.password.length < 6) {
    errors.value.password = 'Password must be at least 6 characters'
  } else {
    errors.value.password = ''
  }
}

const validateForm = () => {
  validateEmail()
  validatePassword()
  return !errors.value.email && !errors.value.password
}

const handleLogin = async () => {
  errors.value.loginError = ''
  
  if (!validateForm()) {
    return
  }

  isLoading.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    console.log('Login attempt with:', form.value)
    // Add login logic here
    // If successful:
    // window.location.href = '/dashboard'
    
  } catch (error) {
    errors.value.loginError = 'Login failed. Please check your credentials and try again.'
    console.error('Login error:', error)
  } finally {
    isLoading.value = false
  }
}

const handleCancel = () => {
  form.value.email = ''
  form.value.password = ''
  errors.value.email = ''
  errors.value.password = ''
  errors.value.loginError = ''
  console.log('Login cancelled')
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.pag-ibig-container {
  min-height: 100vh;
  background-color: #f5f5f5;
  display: flex;
  flex-direction: column;
}

/* Header Styles */
.header {
  background-color: #ffffff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 1rem 2rem;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo {
  height: 50px;
  width: auto;
}

.nav-links {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.nav-link {
  color: #333;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 500;
  transition: color 0.3s;
}

.nav-link:hover {
  color: #003da5;
}

.home-btn {
  background-color: #003da5;
  color: white;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s;
}

.home-btn:hover {
  background-color: #002570;
}

/* Main Content */
.main-content {
  flex: 1;
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
}

.content-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* Left Section */
.left-section {
  padding: 3.5rem;
}

.form-container {
  max-width: 420px;
}

/* Security Badge */
.security-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #e8f4f8;
  color: #00796b;
  padding: 0.75rem 1rem;
  border-radius: 6px;
  margin-bottom: 2rem;
  font-size: 0.9rem;
  font-weight: 600;
}

.lock-icon {
  width: 18px;
  height: 18px;
}

/* Form Title */
.form-title {
  font-size: 1.8rem;
  color: #1a1a1a;
  margin-bottom: 2rem;
  font-weight: 700;
  line-height: 1.3;
}

/* Form Groups */
.login-section-primary {
  margin-bottom: 2.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-size: 0.95rem;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 0.5rem;
  transition: color 0.2s;
}

.form-label:focus-within {
  color: #003da5;
}

.input-wrapper {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 0.9rem 1rem;
  border: 2px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  font-family: inherit;
  transition: all 0.3s ease;
  background-color: #fafafa;
}

.form-input:focus {
  outline: none;
  border-color: #003da5;
  background-color: #ffffff;
  box-shadow: 0 0 0 3px rgba(0, 61, 165, 0.08);
}

.form-input::placeholder {
  color: #999;
}

.form-input.input-error {
  border-color: #e74c3c;
  background-color: #fff5f5;
}

.form-input.input-error:focus {
  box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.08);
}

/* Error Message */
.error-message {
  display: block;
  color: #e74c3c;
  font-size: 0.85rem;
  margin-top: 0.4rem;
  font-weight: 500;
}

/* Label Row */
.label-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.forgot-link {
  color: #003da5;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.3s;
}

.forgot-link:hover {
  color: #002570;
  text-decoration: underline;
}

.forgot-link:focus {
  outline: 2px solid #003da5;
  outline-offset: 2px;
  border-radius: 2px;
}

/* Alert Messages */
.alert {
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  font-size: 0.95rem;
  font-weight: 500;
}

.alert-error {
  background-color: #ffe8e8;
  color: #c0392b;
  border-left: 4px solid #e74c3c;
}

.alert-icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  margin-top: 2px;
}

/* Login Button */
.login-btn {
  width: 100%;
  background: linear-gradient(135deg, #003da5 0%, #005db3 100%);
  color: white;
  border: none;
  padding: 1rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 61, 165, 0.3);
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 1.5rem;
}

.login-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #002570 0%, #003da5 100%);
  box-shadow: 0 6px 16px rgba(0, 61, 165, 0.4);
  transform: translateY(-2px);
}

.login-btn:focus {
  outline: 2px solid #003da5;
  outline-offset: 2px;
}

.login-btn:active:not(:disabled) {
  transform: translateY(0);
}

.login-btn:disabled,
.login-btn.is-loading {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-text {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.6rem;
}

.spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Divider */
.divider {
  position: relative;
  margin: 1.5rem 0;
  text-align: center;
  font-size: 0.9rem;
  color: #999;
  font-weight: 600;
}

.divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background-color: #ddd;
  z-index: 0;
}

.divider span {
  position: relative;
  background-color: white;
  padding: 0 1rem;
  z-index: 1;
}

/* Help Links Group */
.help-links-group {
  margin-bottom: 1.5rem;
}

.help-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #666;
  margin-bottom: 0.75rem;
}

.help-links {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.help-link {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.6rem 0.8rem;
  color: #003da5;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
  border-radius: 4px;
  transition: all 0.3s;
}

.help-link svg {
  width: 18px;
  height: 18px;
}

.help-link:hover {
  background-color: #f0f4f8;
  color: #002570;
}

.help-link:focus {
  outline: 2px solid #003da5;
  outline-offset: 2px;
}

/* Cancel Button */
.cancel-btn {
  width: 100%;
  background-color: #f0f0f0;
  color: #333;
  border: 2px solid #ddd;
  padding: 0.9rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.cancel-btn:hover {
  background-color: #e8e8e8;
  border-color: #999;
}

.cancel-btn:focus {
  outline: 2px solid #999;
  outline-offset: 2px;
}

.cancel-btn:active {
  background-color: #d8d8d8;
}

/* Create Account Section */
.create-account-section {
  padding-top: 2rem;
  border-top: 1px solid #eee;
  text-align: center;
}

.section-label {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 1rem;
  font-weight: 500;
}

.create-account-btn {
  width: 100%;
  background-color: #f5f5f5;
  color: #003da5;
  border: 2px solid #003da5;
  padding: 0.9rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.create-account-btn:hover {
  background-color: #003da5;
  color: white;
  box-shadow: 0 4px 12px rgba(0, 61, 165, 0.3);
  transform: translateY(-2px);
}

.create-account-btn:focus {
  outline: 2px solid #003da5;
  outline-offset: 2px;
}

.create-account-btn:active {
  transform: translateY(0);
}

/* Right Section */
.right-section {
  position: relative;
  background: linear-gradient(135deg, #003da5 0%, #6b9dd1 100%);
  overflow: hidden;
  height: 100%;
}

.hero-image-container {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: flex-end;
  justify-content: center;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: relative;
  z-index: 2;
  border: 8px solid white;
  box-sizing: border-box;
}

.background-design {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><path d="M50,50 L150,100 L250,50 L350,150 L300,250 L150,300 L50,250 L0,150 Z" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M80,80 L180,130 L280,80 L320,180 L270,270 L130,310 L50,270 L20,160 Z" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="2"/></svg>') no-repeat center;
  background-size: cover;
  opacity: 0.3;
}

/* Footer Styles */
.footer {
  background-color: #003d6b;
  color: white;
  padding: 3rem 2rem 2rem;
  position: relative;
}

.footer-content {
  max-width: 1400px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(3, 1fr) auto;
  gap: 3rem;
  margin-bottom: 2rem;
}

.footer-column {
  flex: 1;
}

.footer-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: white;
  margin-bottom: 1.5rem;
  letter-spacing: 0.5px;
  border-bottom: 3px solid #003da5;
  padding-bottom: 0.75rem;
  text-transform: uppercase;
}

.footer-links {
  list-style: none;
}

.footer-links li {
  margin-bottom: 0.8rem;
}

.footer-link {
  color: #b0c4de;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s;
  display: inline-block;
}

.footer-link:hover {
  color: white;
  padding-left: 0.3rem;
}

.footer-link:focus {
  outline: 2px solid white;
  outline-offset: 2px;
}

.contact-column {
  grid-column: 3;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.contact-item {
  color: #b0c4de;
  font-size: 0.95rem;
  line-height: 1.6;
  font-weight: 500;
}

.contact-label {
  font-weight: 700;
  color: white;
  display: block;
  margin-bottom: 0.3rem;
}

.footer-logo-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.footer-logo {
  height: 80px;
  width: auto;
  margin-bottom: 1rem;
}

/* Chat Widget */
.chat-widget {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 999;
}

.chat-bubble {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #333;
  border-radius: 50%;
  width: 110px;
  height: 110px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
  cursor: pointer;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  text-align: center;
  padding: 1rem;
  border: 3px solid white;
  font-weight: 700;
}

.chat-bubble:hover {
  transform: scale(1.15) translateY(-5px);
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
}

.chat-bubble:focus {
  outline: 2px solid #003da5;
  outline-offset: 2px;
}

.chat-bubble:active {
  transform: scale(1.05);
}

.chat-text {
  font-size: 0.8rem;
  font-weight: 700;
  line-height: 1.2;
}

.chat-avatar {
  font-size: 2.2rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .content-wrapper {
    grid-template-columns: 1fr;
  }

  .right-section {
    min-height: 300px;
  }

  .footer-content {
    grid-template-columns: 1fr 1fr;
  }

  .contact-column {
    grid-column: auto;
  }

  .left-section {
    padding: 2.5rem;
  }
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1.5rem;
  }

  .nav-links {
    flex-direction: column;
    gap: 0.8rem;
    width: 100%;
  }

  .nav-link {
    text-align: center;
    font-size: 0.9rem;
  }

  .left-section {
    padding: 2rem;
  }

  .form-title {
    font-size: 1.5rem;
  }

  .footer-content {
    grid-template-columns: 1fr;
  }

  .footer-logo-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  .help-links {
    flex-direction: row;
    gap: 0.5rem;
  }

  .help-link {
    flex: 1;
    justify-content: center;
    padding: 0.5rem;
    font-size: 0.8rem;
  }
}
</style>
