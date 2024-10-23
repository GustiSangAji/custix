<template>
  <LayoutLanding>
    <div v-if="orderDetail && ticketDetail">
      <div class="container mt-5">
        <h5 class="text-center mb-4">Status Pembayaran</h5>

        <!-- Menampilkan pesan berdasarkan status pembayaran -->
        <div v-if="paymentStatus === 'settlement'" class="alert alert-success text-center">
          Pembayaran berhasil! E-tiket telah dikirim ke email Anda.
        </div>
        <div v-if="paymentStatus === 'capture'" class="alert alert-success text-center">
          Pembayaran berhasil! E-tiket telah dikirim ke email Anda.
        </div>
        <div v-if="paymentStatus === 'pending'" class="alert alert-danger text-center">
          Pembayaran gagal. Silakan coba lagi atau hubungi layanan pelanggan.
        </div>
        <div v-if="paymentStatus === 'unpaid'" class="alert alert-danger text-center">
          Pembayaran gagal. Silakan coba lagi atau hubungi layanan pelanggan.
        </div>

        <!-- Detail Pesanan -->
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <div class="card mt-4 shadow">
              <div class="card-body">
                <h5 class="card-title">Detail Pesanan</h5>
                <p class="card-text">
                  <strong>Order ID:</strong> {{ orderDetail.order_id }}<br />
                  <strong>Nama Tiket:</strong> {{ ticketDetail.name }}<br />
                  <strong>Jumlah:</strong> {{ orderDetail.jumlah_pemesanan }}<br />
                  <strong>Nama Pemesan:</strong> {{ user.nama }}<br />
                  <strong>Nomor Ponsel:</strong> {{ user.phone }}<br />
                  <strong>Email:</strong> {{ user.email }}<br />
                  <strong>Tanggal Pemesanan:</strong> {{ formatDate(orderDetail.created_at) }}
                </p>

                <div class="d-flex justify-content-between align-items-center mt-3">
                  <h6>Total Pembayaran</h6>
                  <p class="fw-bold">{{ formatPrice(orderDetail.total_harga) }}</p>
                </div>
                <!-- Menampilkan QR Code berdasarkan Order ID -->
                <h5 class="text-center mt-4">QR Code Tiket Masuk</h5>
                <div class="d-flex justify-content-center bg-white">
                  <qrcode-vue :value="`https://2037-118-99-113-12.ngrok-free.app/verify?order_id=${orderDetail.order_id}`" :size="200" />
                </div>

                <!-- Tombol Selesai di Tengah -->
                <div class="text-center mt-6">
                  <button @click="handleBackToHome" class="btn btn-primary block-btn">
                    Selesai
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <p class="text-center">Memuat detail pembayaran...</p>
    </div>
  </LayoutLanding>
</template>

<script>
import axios from "axios";
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import QrcodeVue from 'qrcode.vue'; // Import QrcodeVue

export default {
  name: "AfterPayment",
  components: {
    LayoutLanding,
    QrcodeVue, // Tambahkan QrcodeVue ke dalam komponen
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
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },

    getPaymentStatus() {
      const queryParams = new URLSearchParams(window.location.search);
      this.paymentStatus = queryParams.get("transaction_status") || 'unpaid'; // Default ke 'unpaid'
      console.log("Payment status dari URL:", this.paymentStatus);
    },

    getOrderDetails() {
      axios
        .get(`http://192.168.61.123:8000/api/order/${this.orderId}`)
        .then((response) => {
          this.orderDetail = response.data;

          // Ambil detail tiket berdasarkan ticket_id dari orderDetail
          return axios.get(
            `http://192.168.61.123:8000/api/tickets/${response.data.ticket_id}`
          );
        })
        .then((ticketResponse) => {
          this.ticketDetail = ticketResponse.data;

          // Panggil fungsi untuk menyimpan QR code di sini
          if (this.paymentStatus === 'settlement' || this.paymentStatus === 'capture') {
            this.saveQrCodeToDatabase();
          }
        })
        .catch((error) => {
          console.error("Terjadi kesalahan saat mengambil detail pesanan:", error);
        });
    },

    saveQrCodeToDatabase() {
      if (this.orderDetail) {
        const qrCodeValue = this.orderDetail.order_id; // Menggunakan order_id sebagai nilai QR code

        axios
          .post(`http://192.168.61.123:8000/api/save-qr-code`, {
            order_id: this.orderDetail.order_id, // Kirim order_id
            qr_code: qrCodeValue, // Nilai QR code
          })
          .then((response) => {
            console.log('QR code berhasil disimpan ke database:', response.data);
          })
          .catch((error) => {
            console.error('Gagal menyimpan QR code ke database:', error);
          });
      } else {
        console.error('orderDetail tidak tersedia saat menyimpan QR code');
      }
    },

    handleBackToHome() {
      this.removeUserAccess(); // Panggil metode untuk menghapus akses
      this.$router.push("/"); // Redirect ke beranda
    },
    
    removeUserAccess() {
      axios
        .post(`http://192.168.61.123:8000/api/remove-access`, {
          user_id: this.user.id,
        })
        .then((response) => {
          console.log("Akses pengguna berhasil dihapus:", response.data);
        })
        .catch((error) => {
          console.error("Gagal menghapus akses pengguna:", error);
        });
    },

    formatPrice(value) {
      return value.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
      });
    }
  },
};
</script>
