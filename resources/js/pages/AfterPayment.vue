<template>
  <LayoutLanding>
    <div v-if="orderDetail && ticketDetail">
      <div class="container mt-10">
        <h5 class="text-center">Status Pembayaran</h5>

        <!-- Menampilkan pesan berdasarkan status pembayaran -->
        <div v-if="paymentStatus === 'settlement'" class="alert alert-success">
          Pembayaran berhasil! E-tiket telah dikirim ke email Anda.
        </div>
        <div v-if="paymentStatus === 'capture'" class="alert alert-success">
          Pembayaran berhasil! E-tiket telah dikirim ke email Anda.
        </div>
        <div v-if="paymentStatus === 'pending'" class="alert alert-danger">
          Pembayaran gagal. Silakan coba lagi atau hubungi layanan pelanggan.
        </div>
        <div v-if="paymentStatus === 'unpaid'" class="alert alert-danger">
          Pembayaran gagal. Silakan coba lagi atau hubungi layanan pelanggan.
        </div>

        <!-- Detail Pesanan -->
        <div class="card mt-4">
          <div class="card-body">
            <h5 class="card-title">Detail Pesanan</h5>
            <p class="card-text">
              Order ID: {{orderDetail.order_id}}<br />
              Nama Tiket: {{ ticketDetail.name }}<br />
              Jumlah: {{ orderDetail.jumlah_pemesanan }}<br />
              Nama Pemesan: {{ user.nama }}<br />
              Nomor Ponsel: {{ user.phone }}<br />
              Email: {{ user.email }}<br />
              Tanggal Pemesanan: {{ formatDate(orderDetail.created_at) }}
            </p>

            <div class="d-flex justify-content-between align-items-center">
              <h6>Total Pembayaran</h6>
              <p class="fw-bold">{{ formatPrice(orderDetail.total_harga) }}</p>
            </div>

            <router-link to="/" class="btn btn-primary mt-3">Kembali ke Beranda</router-link>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <p>Memuat detail pembayaran...</p>
    </div>
  </LayoutLanding>
</template>

<script>
import axios from "axios";
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

export default {
  name: "AfterPayment",
  components: {
    LayoutLanding,
  },
  setup() {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    return {
      user,
    };
  },
  data() {
    return {
      orderId: this.$route.params.orderId, // Mengambil orderId dari URL
      orderDetail: null, // Data pesanan
      ticketDetail: null, // Data tiket
      paymentStatus: null, // Status pembayaran (success, pending, failure)
    };
  },
  mounted() {
    this.getPaymentStatus(); // Ambil status pembayaran saat halaman dimuat
    this.getOrderDetails(); // Ambil detail pesanan dan tiket
  },
  methods: {
    // Mendapatkan status pembayaran dari query parameter
    getPaymentStatus() {
  const queryParams = new URLSearchParams(window.location.search);
  this.paymentStatus = queryParams.get("transaction_status") || 'Unpaid'; // Default ke 'Unpaid'

  console.log("Payment status dari URL:", this.paymentStatus);
},


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
      axios
        .get(`http://localhost:8000/api/order/${this.orderId}`)
        .then((response) => {
          this.orderDetail = response.data;

          // Ambil detail tiket berdasarkan ticket_id dari orderDetail
          return axios.get(`http://localhost:8000/api/tickets/${response.data.ticket_id}`);
        })
        .then((ticketResponse) => {
          this.ticketDetail = ticketResponse.data;
        })
        .catch((error) => {
          console.error("Terjadi kesalahan saat mengambil detail pesanan:", error);
        });
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
}
.card {
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
