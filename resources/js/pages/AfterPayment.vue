<template>
  <LayoutLanding>
    <div v-if="isLoading" class="text-center mt-5">
      <p>Memuat...</p>
    </div>
    <div v-else-if="orderDetail && ticketDetail">
      <div class="container mt-5">
        <h5 class="text-center mb-4">Status Pembayaran</h5>

        <!-- Detail Pesanan -->
        <div class="row justify-content-center">
          <div class="col-md-10 col-lg-5">
            <div class="position-fixed top-0 end-0 p-3 z-index-3">
              <div
                id="kt_docs_toast_toggle"
                class="toast"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                :class="{ show: showToast }"
                :style="{ display: showToast ? 'block' : 'none' }"
                @transitionend="showToast = false"
              >
                <div class="toast-header">
                  <i
                    v-if="
                      paymentStatus === 'settlement' ||
                      paymentStatus === 'capture'
                    "
                    class="ki-duotone ki-abstract-19 fs-2 text-success me-3"
                  >
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <i
                    v-else
                    class="ki-duotone ki-abstract-19 fs-2 text-danger me-3"
                  >
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <strong class="me-auto">
                    {{
                      paymentStatus === "settlement" ||
                      paymentStatus === "capture"
                        ? "Pembayaran Berhasil!"
                        : "Pembayaran Gagal"
                    }}
                  </strong>
                  <button
                    type="button"
                    class="btn-close"
                    @click="showToast = false"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="toast-body">
                  {{
                    paymentStatus === "settlement" ||
                    paymentStatus === "capture"
                      ? "E-tiket telah dikirim ke email Anda."
                      : "Silakan coba lagi atau hubungi layanan pelanggan."
                  }}
                </div>
              </div>
            </div>

            <div class="card card-flush mt-4 p-4 shadow">
              <!-- Header Kartu: Kode Pesanan dan Tanggal -->
              <div
                class="card-header d-flex justify-content-between align-items-center p-4"
              >
                <div class="d-flex align-items-center">
                  <p class="text-muted fw-bold mb-0">Kode Pesanan:</p>
                  <p class="fw-bold mb-0 ms-2">{{ orderDetail.order_id }}</p>
                </div>
                <p class="text-muted mb-0">
                  {{ formatDate(orderDetail.created_at) }}
                </p>
              </div>

              <hr class="mx-4 mt-0" />

              <div class="card-body p-4">
                <div class="row">
                  <!-- Kolom Kiri: Gambar dan Nama Event -->
                  <div class="col-md-4 d-flex flex-column align-items-center">
                    <div class="img-container w-100">
                      <img
                        :src="`/storage/${ticketDetail.image}`"
                        class="img-fluid mb-3"
                        alt="Gambar Event"
                        style="max-height: 200px; object-fit: cover"
                      />
                    </div>
                    <h6 class="text-center mt-4 w-100">
                      {{ ticketDetail.name }}
                    </h6>
                  </div>

                  <!-- Garis Vertikal sebagai Pemisah -->
                  <div class="col-md-1 d-flex justify-content-center">
                    <div class="vertical-line"></div>
                  </div>

                  <!-- Kolom Kanan: Jadwal, Lokasi, dan Info Tiket -->
                  <div class="col-md-7">
                    <div class="mb-4">
                      <h6 class="fw-bold fs-5 mb-4">Jadwal dan Lokasi</h6>
                      <p class="mb-2">
                        <i class="bi bi-calendar3 me-3"></i
                        >{{ formatDate(ticketDetail.datetime) }}
                      </p>
                      <p class="mb-2">
                        <i class="bi bi-clock me-3"></i
                        >{{ formatTime(ticketDetail.datetime) }}
                      </p>
                      <p>
                        <i class="bi bi-geo-alt-fill me-3"></i
                        >{{ ticketDetail.place }}
                      </p>
                    </div>

                    <div class="mb-0">
                      <h6 class="fw-bold fs-5 mb-4">Info Tiket</h6>
                      <p class="mb-2">
                        <i class="bi bi-ticket-detailed me-2"></i
                        >{{ orderDetail.jumlah_pemesanan }} Tiket
                      </p>
                      <p class="mb-2">
                        <i class="ki-duotone ki-user me-2">
                          <span class="path1"></span>
                          <span class="path2"></span> </i
                        >{{ user.nama }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--begin::Alert-->
            <div
              class="alert alert-dismissible bg-light-light d-flex flex-column flex-md-row align-items-md-center p-5 mt-6"
            >
              <i
                :class="{
                  'ki-duotone ki-shield-tick fs-2hx me-4 text-success':
                    paymentStatus === 'settlement' ||
                    paymentStatus === 'capture',
                  'ki-duotone ki-shield-cross fs-2hx me-4 text-danger':
                    paymentStatus === 'deny' ||
                    paymentStatus === 'cancel' ||
                    paymentStatus === 'failure' ||
                    paymentStatus === 'pending',
                  'ki-duotone ki-shield-cross fs-2hx me-4 text-warning':
                    paymentStatus === 'expire',
                }"
              >
                <span class="path1"></span>
                <span class="path2"></span>
              </i>

              <div class="flex-grow-1 ms-md-3 mt-3 mt-md-0">
                <span>
                  {{
                    paymentStatus === "settlement" ||
                    paymentStatus === "capture"
                      ? "Udah kami simpenin nih tiketnya, coba cek dulu."
                      : "Pembayaran gagal, silakan coba lagi."
                  }}
                </span>
              </div>

              <div class="w-50 mt-3 mt-md-0 ms-md-3">
                <router-link
                  v-if="
                    paymentStatus === 'settlement' ||
                    paymentStatus === 'capture'
                  "
                  to="/order"
                  class="btn btn-outline btn-outline-success btn-active-light-success w-100"
                  >Cek Tiket Saya</router-link
                >
                <router-link
                  v-else
                  to="/home"
                  class="btn btn-outline btn-outline-danger btn-active-light-danger w-100"
                  >Silakan Coba Lagi</router-link
                >
              </div>
            </div>
            <!--end::Alert-->
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
import QrcodeVue from "qrcode.vue";

export default {
  name: "AfterPayment",
  components: { LayoutLanding, QrcodeVue },
  setup() {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    return { user };
  },
  data() {
    return {
      orderId: this.$route.params.orderId,
      orderDetail: null,
      ticketDetail: null,
      paymentStatus: null,
      isLoading: true,
      showToast: false,
    };
  },
  mounted() {
    this.getPaymentStatus();
    this.getOrderDetails(); // Pastikan detail pesanan diambil juga
  },
  methods: {
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    formatTime(date) {
      const options = { hour: "2-digit", minute: "2-digit" };
      return new Date(date).toLocaleTimeString("id-ID", options);
    },
    getPaymentStatus() {
      const queryParams = new URLSearchParams(window.location.search);
      this.paymentStatus = queryParams.get("transaction_status") || "unpaid";

      // Tampilkan toast hanya setelah data berhasil dimuat
      setTimeout(() => {
        this.showToast = true; // Tampilkan toast setelah 1 detik
      }, 1000);
    },
    getOrderDetails() {
      axios
        .get(`http://localhost:8000/api/order-detail/${this.orderId}`)
        .then((response) => {
          this.orderDetail = response.data.order;
          this.ticketDetail = response.data.ticket;
          this.user = response.data.user;

          // Hanya simpan QR code jika pembayaran berhasil
          if (
            this.paymentStatus === "settlement" ||
            this.paymentStatus === "capture"
          ) {
            this.saveQrCodeToDatabase();
          }
        })
        .catch((error) => console.error("Kesalahan:", error))
        .finally(() => {
          this.isLoading = false; // Pastikan isLoading diatur ke false setelah data dimuat
        });
    },
    saveQrCodeToDatabase() {
      if (this.orderDetail) {
        const qrCodeValue = this.orderDetail.order_id; // Ambil order_id untuk QR code
        const orderId = this.orderDetail.order_id; // Ambil order_id yang benar

        axios
          .post(`http://localhost:8000/api/save-qr-code`, {
            order_id: orderId, // Kirim order_id yang benar
            qr_code: qrCodeValue, // Nilai QR code, yang merupakan order_id
          })
          .then((response) => {
            console.log(
              "QR code berhasil disimpan ke database:",
              response.data
            );
          })
          .catch((error) => {
            console.error(
              "Gagal menyimpan QR code ke database:",
              error.response ? error.response.data : error
            );
          });
      } else {
        console.error("orderDetail tidak tersedia saat menyimpan QR code");
      }
    },
    removeUserAccess() {
      axios
        .post(`http://localhost:8000/api/remove-access`, {
          user_id: this.user.id,
        })
        .then((response) => {
          console.log("Akses pengguna berhasil dihapus:", response.data);
        })
        .catch((error) => {
          console.error("Gagal menghapus akses pengguna:", error);
        });
    },
  },
};
</script>

<style scoped>
/* Sesuaikan CSS untuk memastikan tampilan rapi */
.card-body {
  padding: 20px;
}

.vertical-line {
  width: 1px;
  background-color: #4d4f53;
  height: 100%;
}

.img-container {
  max-height: 200px; /* Menyediakan batasan tinggi yang konsisten */
  overflow: hidden; /* Menghindari gambar keluar dari wadah */
}
</style>
