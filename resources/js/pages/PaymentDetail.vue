<template>
  <Navbar />
  <div v-if="orderDetail && ticketDetail">
    <div class="container mt-10">
      <div class="row">
        <!-- Form Pemesanan -->
        <div class="col-md-8">
          <h5 class="card-title">Detail Pemesanan</h5>
          <p class="card-text">
            Isi formulir ini dengan benar karena e-tiket akan dikirim ke alamat
            email sesuai data pemesan.
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
                    value="Dian Rizky Pratama"
                  />
                </div>

                <!-- Nomor Ponsel -->
                <div class="mb-3">
                  <label for="nomorPonsel" class="form-label"
                    >Nomor Ponsel</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="nomorPonsel"
                    placeholder="+62"
                    value="+62 89501215795"
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
                    value="rizdian229@gmail.com"
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
                1 Tiket - Id Pesanan {{ orderDetail.id }}<br />
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
              <button @click="pay" class="btn btn-primary mt-3">
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
</template>


<script>
import axios from "axios";
import Navbar from "@/components/Navbar.vue";

export default {
  name: "PaymentDetail",
  components: {
    Navbar,
  },
  data() {
    return {
      orderId: this.$route.params.orderId,
      orderDetail: null, // Data pesanan
      ticketDetail: null, // Data tiket
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
      axios
        .get(`http://localhost:8000/api/order/${this.orderId}`)
        .then((response) => {
          console.log("Response Data:", response.data);
          this.orderDetail = response.data;

          // Ambil detail tiket menggunakan ticket_id dari orderDetail
          return axios.get(
            `http://localhost:8000/api/tickets/${response.data.ticket_id}`
          );
        })
        .then((ticketResponse) => {
          console.log("Ticket Data:", ticketResponse.data); // Cek respons detail tiket
          this.ticketDetail = ticketResponse.data; // Simpan detail tiket
        })
        .catch((error) => {
          console.error(
            "Terjadi kesalahan saat mengambil detail pesanan:",
            error
          );
        });
    },
    pay() {
      // Request payment data from your backend
      axios
        .post(`http://localhost:8000/api/payment`, { orderId: this.orderId })
        .then((response) => {
          const snapToken = response.data.snapToken; // Assuming the response contains the Snap token

          // Initialize Midtrans Snap
          window.snap.pay(snapToken, {
            onSuccess: function (result) {
              console.log("Payment Success:", result);
              // Handle success (e.g., redirect to success page)
            },
            onPending: function (result) {
              console.log("Payment Pending:", result);
              // Handle pending payment
            },
            onError: function (result) {
              console.log("Payment Error:", result);
              // Handle error
            },
            onClose: function () {
              console.log("Payment popup closed");
              // Handle popup close
            },
          });
        })
        .catch((error) => {
          console.error("Error fetching payment data:", error);
        });
    },
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
