<template>
  <div class="col-md-8 col-lg-9">
    <div class="row justify-content-start">
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
              <p class="mb-1 text-muted">Total Pembayaran</p>
              <p class="fw-bold fs-6">{{ formatPrice(order?.total_harga) }}</p>
              <p class="mb-1 text-muted">Status Tiket</p>
              <span
                :class="[
                  'badge',
                  badgeClass,
                  'fs-6',
                  'fw-bold',
                  'me-2',
                  'd-inline-block',
                ]"
              >
                {{ ticketStatus }}
              </span>
            </div>
          </div>
          <div class="card-footer text-center" style="margin-top: -30px">
            <div
              class="card card-body bg-white d-flex flex-column align-items-center"
            >
              <h4 class="fw-bold fs-6 mb-3 text-dark">
                Scan kode QR di bawah ini
              </h4>
              <qrcode-vue
                v-if="selectedQrCode"
                :value="generateQRCodeValue(selectedQrCode)"
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
              <p class="fw-bold fs-6">{{ order?.ticket?.name }}</p>
              <p class="mb-1 text-muted">Masa Berlaku</p>
              <p class="fw-bold fs-6">
                {{ formatDate(order?.ticket?.expiry_date) }}
              </p>
              <p class="mb-1 text-muted">Nama Pemesan</p>
              <p class="fw-bold fs-6">{{ user.nama }}</p>
              <p class="mb-1 text-muted">Email</p>
              <p class="fw-bold fs-6">{{ user.email }}</p>
              <p class="mb-1 text-muted">Lokasi</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.place }}</p>
            </div>
          </div>
        </div>

        <!-- Card Detail Pesanan Kedua -->
        <div class="card card-flush text-start mt-4">
          <!-- Tambahkan mb-4 untuk margin bawah -->
          <div class="card-body">
            <h5 class="fw-bold">Petunjuk Verifikasi Qr Code</h5>
            <!-- Ubah judul jika perlu -->
            <hr />
            <div class="card card-dashed p-6">
              <p class="fw-bold fs-6">1. Buka Website CusTix</p>
              <p class="fw-bold fs-6">2. Masuk ke halaman Tiket Saya</p>
              <p class="fw-bold fs-6">3. Pilih tiket anda</p>
              <p class="fw-bold fs-6">4. Klik Lihat Detail</p>
              <p class="fw-bold fs-6">5. Tunjukan Qr Code ke Petugas</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";
import QrcodeVue from "qrcode.vue";

export default {
  name: "OrderDetail",
  props: ["id", "qrIndex"],
  components: {
    QrcodeVue,
  },
  setup() {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    return { user };
  },
  data() {
    return {
      order: null,
      selectedQrCode: "",
    };
  },
  computed: {
    ticketStatus() {
      // Menggunakan qrIndex untuk mendapatkan status dari ticket_details
      return this.order?.ticket_details[this.qrIndex]?.status === "Used"
        ? "Sudah Digunakan"
        : "Belum Digunakan";
    },
    badgeClass() {
      // Mengatur class badge sesuai status
      return this.order?.ticket_details[this.qrIndex]?.status === "Used"
        ? "badge-light-danger"
        : "badge-light-success";
    },
  },

  mounted() {
    this.getOrderDetail();
  },
  methods: {
    async getOrderDetail() {
      try {
        const response = await axios.get(`/user/orders/${this.id}`);
        this.order = response.data;
        console.log("Order detail response:", response.data);

        if (this.order?.qr_code) {
          const qrData = JSON.parse(this.order.qr_code);
          const ticketData = qrData.tickets[this.qrIndex];
          if (ticketData) {
            this.selectedQrCode = {
              orderId: qrData.orderId,
              uniqueId: qrData.uniqueId,
              ticketNumber: ticketData.ticketNumber,
              hash: qrData.hash,
              date: qrData.date,
            };
          }
        }
      } catch (error) {
        console.error("Error fetching order detail:", error);
      }
    },
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    formatShortDate(dateString) {
      const options = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
      };
      return new Date(dateString).toLocaleDateString("id-ID", options);
    },

    generateQRCodeValue(qrData) {
      const baseUrl = "http://192.168.1.8:8000/verify";
      return `${baseUrl}?order_id=${qrData.orderId}&unique_id=${qrData.uniqueId}&ticket_number=${qrData.ticketNumber}&hash=${qrData.hash}`;
    },

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
card {
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
