<template>
  <div class="col-md-8 col-lg-9">
    <div class="row justify-content-start"> <!-- Menggunakan justify-content-start untuk merapikan ke kiri -->
      
      <!-- Card Kiri -->
      <div class="col-12 col-md-5 mb-4">
        <div class="card card-flush text-start">
          <div class="card-header">
            <img
              class="go-pay-icon"
              v-if="order?.ticket?.image"
              :src="`/storage/${order.ticket.image}`"
              alt="event-image"
            />
          </div>
          <div class="card-body py-3">
            <div class="card card-body">
              <div class="d-flex align-items-center">
                <span class="svg-icon fs-1 me-2">
                  <i class="ki-duotone ki-wallet fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                </span>
                <h4 class="fw-bold fs-5 mb-0">GoPay</h4>
              </div>
              <hr />
              <p class="mb-1 text-muted">Kode Pesanan</p>
              <p class="fw-bold fs-6">{{ order?.order_id }}</p>
              <!-- Menampilkan kode pesanan dari order -->
              <p class="mb-1 text-muted">Total Pembayaran</p>
              <p class="fw-bold fs-6">{{ formatPrice(order?.total_harga) }}</p>
              <!-- Menampilkan total pembayaran dari order -->
            </div>
          </div>
          <div class="card-footer text-center" style="margin-top: -30px">
            <div
              class="card card-body bg-white d-flex flex-column align-items-center"
            >
              <!-- Menampilkan QR Code menggunakan qrcode-vue -->
              <h4 class="fw-bold fs-6 mb-3 text-dark">Scan kode QR di bawah ini</h4>
              <qrcode-vue 
                :value="`https://104a-118-99-113-12.ngrok-free.app/verify?order_id=${order?.order_id}`" 
                :size="200" 
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Card Kanan -->
      <div class="col-12 col-md-7 mb-4">
        <div class="card card-flush text-start">
          <div class="card-body">
            <h5 class="fw-bold">Detail Pesanan</h5>
            <hr />
            <div class="card card-dashed p-6">
              <p class="mb-1 text-muted">Nama Event</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.name }}</p> <!-- Nama Event -->
              <p class="mb-1 text-muted">Masa Berlaku</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.expiry_date }}</p> <!-- Tanggal Event -->
              <p class="mb-1 text-muted">Nama Pemesan</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.event_time }}</p> <!-- Waktu Event -->
              <p class="mb-1 text-muted">Lokasi</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.event_location }}</p> <!-- Lokasi Event -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import QrcodeVue from "qrcode.vue"; // Import qrcode-vue

export default {
  name: "OrderDetail",
  props: ["id"],
  components: {
    QrcodeVue, // Tambahkan komponen QrcodeVue
  },
  data() {
    return {
      order: null,
    };
  },
  mounted() {
    this.getOrderDetail();
  },
  methods: {
    getOrderDetail() {
      const orderId = this.id; // Ambil ID dari props
      axios
        .get(`/user/orders/${orderId}`) // Memperbaiki URL dengan backticks
        .then((response) => {
          this.order = response.data;
        })
        .catch((error) => {
          console.error("Error fetching order detail:", error);
        });
    },
    // Fungsi formatPrice untuk format harga
    formatPrice(price) {
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      }).format(price);
    },
  },
};
</script>

<style scoped>
.card {
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  overflow: hidden; /* Menyembunyikan konten yang keluar dari batas card */
  position: relative; /* Agar child dengan position absolute tetap teratur */
}

.card-body {
  margin-top: 20px; /* Anda bisa menyesuaikan ini sesuai kebutuhan */
}

/* Gaya untuk gambar GoPay di bagian atas */
.go-pay-icon {
  position: absolute; /* Mengatur posisi gambar */
  left: 0; /* Mengatur gambar mulai dari tepi kiri */
  right: 0; /* Mengatur gambar mencapai tepi kanan */
  top: 0; /* Mengatur gambar berada di bagian atas card */
  width: 100%; /* Lebar penuh */
  height: 150px; /* Atur tinggi tetap agar terlihat baik */
  object-fit: cover; /* Mengisi area penuh, tanpa distorsi */
  object-position: center; /* Memastikan gambar berada di tengah */
}

/* Gaya untuk gambar QR Code */
.qr-code {
  width: 100%; /* Membuat gambar mengisi lebar card */
  height: auto; /* Menjaga rasio aspek gambar */
  max-width: 150px; /* Mengatur lebar maksimum agar tidak terlalu besar */
}
</style>
