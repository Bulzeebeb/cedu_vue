import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import axios from 'axios'; // ✅ Added


const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    // ✅ Remove baseURL so Axios uses Laravel web.php routes
    // axios.defaults.baseURL = 'http://localhost:8000/api' ← Removed

    // ✅ Add CSRF token for POST/PUT/DELETE requests to web.php
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content')

    if (csrfToken) {
      axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
    } else {
      console.warn('CSRF token not found in meta tag.')
    }

    // Optional: If you're using auth tokens stored in localStorage
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})

initializeTheme()