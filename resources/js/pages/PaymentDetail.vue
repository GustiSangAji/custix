<template>
  <div v-if="orderDetail && ticketDetail">
    <h1>Detail Pembayaran</h1>
    <p>Pesanan ID: {{ orderDetail.id }}</p>
    <p>Jumlah Pemesanan: {{ orderDetail.jumlah_pemesanan }}</p>
    <p>Total Harga: {{ formatPrice(orderDetail.total_harga) }}</p>
    <p>Tanggal Pemesanan: {{ formatDate(orderDetail.created_at) }}</p>
    <p>Status: {{ orderDetail.status }}</p>
    
    <div class="ticket-details">
      <h3>Detail Tiket</h3>
      <img :src="ticketDetail.image" alt="Event Image" class="img-thumbnail" style="width: 100px; height: auto;"/>
      <p><strong>Nama Tiket: {{ ticketDetail.name }}</strong></p>
      <p><strong>Kode Tiket: {{ ticketDetail.kode_tiket }}</strong></p>
      <p><strong>Tanggal Event: {{ formatDate(ticketDetail.datetime) }}</strong></p>
      <p><strong>Lokasi: {{ ticketDetail.place }}</strong></p>
    </div>
  </div>
  <div v-else>
    <p>Memuat detail pembayaran...</p>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  name: 'PaymentDetail',
  data() {
  return {
    orderId: this.$route.params.orderId,
    orderDetail: null, // Data pesanan
    ticketDetail: null // Data tiket
  };
},

  mounted() {
    console.log("Order ID:", this.orderId); // Log ID pesanan
    this.getOrderDetails(); // Panggil API untuk mendapatkan detail pesanan
  },
  methods: {
  formatPrice(value) {
    return value.toLocaleString("id-ID", {
      style: "currency",
      currency: "IDR",
    });
  },
  formatDate(date) {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
  },
  getOrderDetails() {
  axios.get(`http://localhost:8000/api/order/${this.orderId}`)
    .then(response => {
      console.log("Response Data:", response.data);
      this.orderDetail = response.data;

      // Ambil detail tiket menggunakan ticket_id dari orderDetail
      return axios.get(`http://localhost:8000/api/tickets/${response.data.ticket_id}`);
    })
    .then(ticketResponse => {
      console.log("Ticket Data:", ticketResponse.data); // Cek respons detail tiket
      this.ticketDetail = ticketResponse.data; // Simpan detail tiket
    })
    .catch(error => {
      console.error('Terjadi kesalahan saat mengambil detail pesanan:', error);
    });
}

}
};
</script>

<style scoped>
.ticket-details {
  border: 1px solid #ccc;
  padding: 10px;
  margin-top: 20px;
}
</style>
