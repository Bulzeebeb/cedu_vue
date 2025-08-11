import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = true
s
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


export default axios
