<template>
  <div v-if="isVisible" class="fixed inset-0 backdrop-blur-sm z-50 flex justify-center items-center">
    <div class="bg-white text-black rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto p-4 sm:p-6 lg:p-8 relative border border-gray-300 mx-4">
      <!-- Header -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-1 h-6 bg-maroon rounded-sm"></div>
        <h2 class="text-xl font-semibold text-amber-600">GENERATED POS</h2>
        <button @click="close" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-red-500">×</button>
      </div>

      <div v-if="!loading">
        <div ref="printArea" class="print-area">
          <!-- Logo and Title -->
          <div class="flex flex-col sm:flex-row items-center sm:items-stretch mb-6 gap-4">
            <div class="w-full sm:w-1/5 flex items-center justify-center">
              <img src="/images/PayToPark/logo.png" alt="Logo" class="h-full max-h-[120px] object-contain" />
            </div>
            <div class="w-full sm:w-4/5 text-center px-4">
              <h1 class="text-base">Republic of the Philippines</h1>
              <h2 class="text-xl font-bold">University of Southeastern Philippines</h2>
              <h3 class="text-sm italic">Resource Management Division (RMD)</h3><br />
              <h2 class="text-2xl font-bold tracking-wide mt-1">ORDER OF PAYMENT</h2>
            </div>
          </div>

          <!-- Info -->
          <div class="border border-gray-300 rounded-md p-4 text-sm mb-6 space-y-2 shadow-sm">
            <div><span class="font-semibold">Control No.:</span> {{ controlNumber }}</div>
            <div><span class="font-semibold">Name:</span> {{ clientData.name || 'Loading...' }}</div>
            <div><span class="font-semibold">Organization:</span> ___________________________</div>
            <div><span class="font-semibold">Date Applied:</span> {{ currentDate }}</div>
            <div><span class="font-semibold">Validity Period:</span> ________________________</div>
          </div>

          <!-- Facility to Use -->
          <div class="text-sm mb-6">
            <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
              <span>Facility</span>
              <span>Cost</span>
            </div>
            <div v-for="(facility, index) in facilities" :key="index" class="flex justify-between">
              <span>☐ {{ facility }}</span>
              <span>_____________</span>
            </div>
            <div class="flex justify-between mt-2">
              <span>☑ Others (specify): PAY TO PARK</span>
              <span>{{ totalAmount.toLocaleString() }}</span>
            </div>
          </div>

          <!-- Additional Payment -->
          <div class="text-sm mb-6">
            <div class="flex justify-between font-semibold border-b border-black pb-1 mb-1">
              <span>Payment</span>
              <span>Cost</span>
            </div>
            <div class="flex justify-between"><span>☐ Excess Hour/s</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Overtime Pay of Staff</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Use of Generator</span><span>_____________</span></div>
            <div class="flex justify-between"><span>☐ Others (specify): __________________</span><span>_____________</span></div>
          </div>

          <!-- Total -->
          <div class="text-sm mb-6 flex justify-between font-semibold">
            <span>Total Cost: Php</span>
            <span>{{ totalAmount.toLocaleString() }}</span>
          </div>

          <!-- Signatures -->
          <div class="flex flex-col sm:flex-row justify-between mt-8 text-sm gap-8">
            <div class="text-center">
              <span class="block mb-6">By: Staff</span>
              <div class="border-t border-gray-400 w-40 mx-auto"></div>
            </div>
            <div class="text-center">
              <span class="block mb-6">Noted by: CEDU Head / RMD Director</span>
              <div class="border-t border-gray-400 w-60 mx-auto"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Download Button -->
      <div class="mt-10 flex justify-center gap-4">
        <button
          @click="downloadPOS"
          class="w-full bg-maroon text-white py-2 rounded-full hover:bg-red-800 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ isDownloading ? 'Downloading...' : 'Download POS' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'
import html2canvas from 'html2canvas'
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

const props = defineProps({
  transactionId: Number,
  clientId: Number
})

const emit = defineEmits(['close'])
const isVisible = ref(true)
const loading = ref(true)
const isDownloading = ref(false)

const clientData = ref({ name: '' })
const totalAmount = ref(0)
const printArea = ref(null)

const close = () => emit('close')

const currentDate = new Date().toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
})

const controlNumber = computed(() => {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const paddedId = String(props.transactionId).padStart(3, '0')
  return `${year}-${month}-000${paddedId}`
})

const facilities = [
  'Billboard Posting',
  'Classrooms',
  'Commercial Stall',
  'Consultancy',
  'Covered Court',
  'Farmer’s Training Center',
  'Fitness Center',
  'Gymnasium',
  'Hostel Dining Hall',
  'Hostel Rooms',
  'Hostel Training Hall',
  'Open Space/Ground',
  'Printing Press',
  'Social Hall'
]

const transactionData = ref(null)  

onMounted(async () => {
  try {
    const [clientRes, transactionRes] = await Promise.all([
      axios.get(`/pay_park_client/${props.clientId}`),
      axios.get(`/paypark_transactions/${props.transactionId}`)
    ])
    clientData.value = clientRes.data
    transactionData.value = transactionRes.data  
    totalAmount.value = transactionData.value.total_payment || 0
  } catch (err) {
    console.error('Error fetching data', err)
  } finally {
    loading.value = false
  }
})


const downloadPOS = async () => {
  if (!clientData.value || !transactionData.value) return;

  isDownloading.value = true;

  try {
    const pdf = new jsPDF('p', 'mm', 'a4');
    const marginLeft = 14;
    let y = 15;

    const name = clientData.value.name || 'Unknown';
    const dateToday = new Date().toLocaleDateString();
    const controlNo = controlNumber.value || '2025-08-000063';
    const totalCost = transactionData.value.total || 195.0;

    // === HEADER ===
    pdf.setTextColor(0, 0, 0);
    pdf.setFontSize(10);
    pdf.setFont('helvetica', 'normal');
    pdf.text('Republic of the Philippines', 105, y, { align: 'center' });
    y += 5;
    pdf.setFont('helvetica', 'bold');
    pdf.text('University of Southeastern Philippines', 105, y, { align: 'center' });
    y += 5;
    pdf.setFont('helvetica', 'italic');
    pdf.text('Resource Management Division (RMD)', 105, y, { align: 'center' });
    y += 6;

    pdf.setFont('helvetica', 'bold');
    pdf.setFontSize(12);
    pdf.text('ORDER OF PAYMENT', 105, y, { align: 'center' });
    y += 10;

    // === INFO BOX ===
    pdf.setFontSize(9);
    pdf.setFont('helvetica', 'normal');
    pdf.rect(marginLeft, y, 182, 32);

    pdf.text(`Control No.: ${controlNo}`, marginLeft + 2, y + 6);
    pdf.text(`Name: ${name}`, marginLeft + 2, y + 12);
    pdf.text(`Organization: ____________________________`, marginLeft + 2, y + 18);
    pdf.text(`Date Applied: ${dateToday}`, marginLeft + 2, y + 24);
    pdf.text(`Validity Period: ____________________________`, marginLeft + 2, y + 30);
    y += 38;

    // === FACILITY TABLE ===
    pdf.setFont('helvetica', 'bold');
    pdf.setFontSize(10);
    pdf.text('FACILITY', marginLeft, y);
    y += 4;

    const facilities = [
      'Billboard Posting',
      'Classrooms',
      'Commercial Stall',
      'Consultancy',
      'Covered Court',
      'Farmer’s Training Center',
      'Fitness Center',
      'Gymnasium',
      'Hostel Dining Hall',
      'Hostel Rooms',
      'Hostel Training Hall',
      'Open Space/Ground',
      'Printing Press',
      'Social Hall',
      'Others (specify): PAY TO PARK'
    ];

    const facilityRows = facilities.map(item => {
      const isChecked = item.includes('Others');
      return {
        select: isChecked ? '☑' : '☐',
        facility: item,
        cost: isChecked ? totalCost.toFixed(2) : ''
      };
    });

    autoTable(pdf, {
      startY: y,
      head: [['', 'Facility', 'Cost']],
      body: facilityRows.map(row => [row.select, row.facility, row.cost]),
      theme: 'grid',
      headStyles: {
        fillColor: [200, 200, 200],
        textColor: 20,
        fontSize: 9,
        fontStyle: 'bold',
        halign: 'center'
      },
      bodyStyles: {
        fontSize: 9,
        valign: 'middle'
      },
      columnStyles: {
        0: { cellWidth: 10, halign: 'center' },
        1: { cellWidth: 120 },
        2: { cellWidth: 52, halign: 'right' }
      }
    });

    y = pdf.lastAutoTable.finalY + 6;

    // === PAYMENT TABLE ===
    pdf.setFont('helvetica', 'bold');
    pdf.setFontSize(10);
    pdf.text('PAYMENT', marginLeft, y);
    y += 4;

    const payments = [
      'Excess Hour/s',
      'Overtime Pay of Staff',
      'Use of Generator',
      'Others (specify): ______________________'
    ];

    const paymentRows = payments.map(item => ({
      select: '☐',
      description: item,
      cost: ''
    }));

    autoTable(pdf, {
      startY: y,
      head: [['', 'Description', 'Cost']],
      body: paymentRows.map(row => [row.select, row.description, row.cost]),
      theme: 'grid',
      headStyles: {
        fillColor: [200, 200, 200],
        textColor: 20,
        fontSize: 9,
        fontStyle: 'bold',
        halign: 'center'
      },
      bodyStyles: {
        fontSize: 9,
        valign: 'middle'
      },
      columnStyles: {
        0: { cellWidth: 10, halign: 'center' },
        1: { cellWidth: 120 },
        2: { cellWidth: 52, halign: 'right' }
      }
    });

    y = pdf.lastAutoTable.finalY + 6;

    // === TOTAL COST ===
    pdf.setFont('helvetica', 'bold');
    pdf.setFontSize(10);
    pdf.text(`Total Cost: Php`, 135, y);
    pdf.text(totalCost.toFixed(2), 195, y, { align: 'right' });
    y += 18;

    // === SIGNATURE SECTION ===
    pdf.setFont('helvetica', 'normal');
    pdf.setFontSize(10);
    pdf.text('By: Staff', marginLeft, y);
    pdf.text('Noted by: CEDU Head / RMD Director', 130, y);
    y += 6;
    pdf.text('_____________________', marginLeft, y);
    pdf.text('_____________________', 130, y);

    // === DOWNLOAD PDF ===
    pdf.save(`POS_${name.replace(/\s+/g, '_')}.pdf`);
  } catch (err) {
    console.error('Error generating PDF:', err);
  } finally {
    isDownloading.value = false;
  }
};


</script>

<style scoped>
.bg-maroon {
  background-color: #800000;
}
.bg-maroon:hover {
  background-color: #a30000;
}
</style>
