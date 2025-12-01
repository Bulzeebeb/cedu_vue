import axios from 'axios'
import './bootstrap'
import Swal from 'sweetalert2'

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = true
s
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


window.Swal = Swal

export default axios
