<template>
  <LayoutLanding>
    <div v-if="orderDetail && ticketDetail">
      <div class="container mt-10">
        <div class="row">
          <!-- Form Pemesanan -->
          <div class="col-md-8">
            <h5 class="card-title">Detail Pemesanan</h5>
            <p class="card-text">
              Isi formulir ini dengan benar karena e-tiket akan dikirim ke
              alamat email sesuai data pemesan.
            </p>
            <div class="card">
              <div class="card-body">
                <form>
                  <!-- Nama Lengkap -->
                  <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input
                      type="text"
                      class="form-control"
                      id="nama"
                      placeholder="Nama Lengkap"
                      v-model="user.nama"
                    />
                  </div>

                  <!-- Nomor Ponsel -->
                  <div class="mb-3">
                    <label for="nomorPonsel" class="form-label">Nomor Ponsel</label>
                    <input
                      type="text"
                      class="form-control"
                      id="nomorPonsel"
                      placeholder="+62"
                      v-model="user.phone"
                    />
                  </div>

                  <!-- Alamat Email -->
                  <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      placeholder="you@example.com"
                      v-model="user.email"
                    />
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Detail Tiket -->
          <div class="col-md-4">
            <div class="card shadow-sm mt-4">
              <div class="card-body">
                <div class="d-flex align-items-center mb-6">
                  <img
                    :src="'/storage/' + ticketDetail.image"
                    alt="Event Image"
                    class="rounded me-2"
                    style="width: 50px"
                  />
                  <div class="flex-grow-1">
                    <h5 class="card-title mb-0">{{ ticketDetail.name }}</h5>
                  </div>
                  <a href="#" class="text-primary">Detail</a>
                </div>
                <p class="card-text">
                  <strong>Tiket: {{ ticketDetail.kode_tiket }}</strong>
                  <br />
                  {{ orderDetail.jumlah_pemesanan }} Tiket - Order ID
                  {{ orderDetail.order_id }}<br />
                  <span class="text-muted">
                    Tanggal Dipilih: {{ formatDate(orderDetail.created_at) }}
                  </span>
                </p>

                <ul class="list-unstyled">
                  <li class="mb-3">
                    <i class="bi bi-x-circle-fill"></i> Tidak bisa refund
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-check-circle-fill"></i> Konfirmasi Instan
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-ticket-fill"></i> Kursi tersedia saat
                    penukaran tiket
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-calendar-check-fill"></i> Berlaku di tanggal
                    terpilih
                  </li>
                </ul>

                <hr />

                <div class="d-flex justify-content-between align-items-center">
                  <h6>Total Pembayaran</h6>
                  <p class="fw-bold">
                    {{ formatPrice(orderDetail.total_harga) }}
                  </p>
                </div>
                <button @click="pay" class="btn btn-primary block-btn mt-3">
                  Bayar Sekarang
                </button>
              </div>
            </div>
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
import Swal from "sweetalert2"; // SweetAlert untuk konfirmasi

export default {
  name: "PaymentDetail",
  components: {
    LayoutLanding,
  },
  setup() {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    return { user };
  },
  data() {
    return {
      orderId: this.$route.params.orderId,
      orderDetail: null, // Data pesanan
      ticketDetail: null, // Data tiket
    };
  },
  mounted() {
    this.orderId = sessionStorage.getItem('orderId'); // Ambil orderId dari sessionStorage
    if (this.orderId) {
      this.getOrderDetails();
    } else {
      // Redirect atau handle jika orderId tidak ditemukan
      this.$router.push("/"); // Kembali ke halaman utama atau tampilan lain
    }
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
      axios
        .get(`order/${this.orderId}`)
        .then((response) => {
          this.orderDetail = response.data;
          return axios.get(
            `tickets/${response.data.ticket_id}`
          );
        })
        .then((ticketResponse) => {
          this.ticketDetail = ticketResponse.data;
        })
        .catch((error) => {
          console.error("Terjadi kesalahan saat mengambil detail pesanan:", error);
        });
    },
    pay() {
      // Gunakan this.orderDetail.id untuk mengambil order_id
      axios
        .post(`/payment/${this.orderId}`, {
          orderId: this.orderId,
        })
        .then((response) => {
          let snapToken = response.data.snap_token;

          if (!snapToken) {
            console.error("Snap Token is undefined or null.");
            return;
          }

          window.snap.pay(snapToken, {
            onSuccess: (result) => {
              this.removeAccess(); // Hapus akses setelah pembayaran sukses
              this.$router.push({
                name: "afterpayment",
                params: { orderId: this.orderDetail.id }, // Menggunakan order_id dari orderDetail
                query: { transaction_status: result.transaction_status },
              });
            },
            onPending: (result) => {
              this.$router.push({
                name: "afterpayment",
                params: { orderId: this.orderDetail.id }, // Menggunakan order_id dari orderDetail
                query: { transaction_status: result.transaction_status },
              });
            },
            onError: (result) => {
              this.$router.push({
                name: "afterpayment",
                params: { orderId: this.orderDetail.id }, // Menggunakan order_id dari orderDetail
                query: { transaction_status: result.transaction_status },
              });
            },
            onClose: () => {
              console.log("Payment popup closed");
            },
          });
        })
        .catch((error) => {
          console.error("Error fetching payment data:", error);
        });
    },
    removeAccess() {
      const userId = localStorage.getItem("userId");
      if (userId) {
        axios
          .post("/remove-access", { user_id: userId })
          .then((response) => {
            console.log("Akses dihentikan:", response.data);
          })
          .catch((error) => {
            console.error("Terjadi kesalahan saat menghentikan akses:", error);
          });
      } else {
        console.error("User ID tidak ditemukan di localStorage.");
      }
    },
  },

  beforeRouteLeave(to, from, next) {
  // Tampilkan SweetAlert sebelum pengguna meninggalkan halaman detail tiket
  // Kecualikan halaman afterpayment dan foodDetail dari pengecekan
  const exemptRoutes = ["afterpayment", "ticket-detail"];

  if (!exemptRoutes.includes(to.name)) {
    Swal.fire({
      title: "Apakah Anda yakin ingin keluar?",
      text: "Jika Anda keluar, kemungkinan Anda akan antri kembali.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Ya, keluar",
      cancelButtonText: "Tidak",
    }).then((result) => {
      if (result.isConfirmed) {
        this.removeAccess(); // Hanya panggil removeAccess jika pengguna memilih "Ya" dan bukan menuju foodDetail
        next(); // Lanjutkan navigasi
      } else {
        next(false); // Batalkan navigasi jika pengguna memilih "Tidak"
      }
    });
  } else {
    // Jika navigasi ke foodDetail, jangan panggil removeAccess
    next(); // Lanjutkan navigasi ke halaman afterpayment atau foodDetail tanpa konfirmasi
  }
},


};


</script>

<style scoped>
.ticket-details {
  border: 1px solid #ccc;
  padding: 10px;
  margin-top: 20px;
}
</style>
