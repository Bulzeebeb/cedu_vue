import axios from 'axios'
import './bootstrap'
import Swal from 'sweetalert2'
import jsPDF from 'jspdf'
import 'jspdf-autotable'
import { Document, Packer, Table, TableRow, TableCell, Paragraph, TextRun, WidthType } from 'docx'

window.jspdf = { jsPDF }
window.docx = { Document, Packer, Table, TableRow, TableCell, Paragraph, TextRun, WidthType }

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = true
s
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


window.Swal = Swal

export default axios
