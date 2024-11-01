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
              <p class="fw-bold fs-6">{{ order?.ticket?.expiry_date }}</p>
              <p class="mb-1 text-muted">Status Tiket</p>
              <p
                :class="
                  order?.ticket?.status === 'used'
                    ? 'text-danger'
                    : 'text-success'
                "
              >
                {{
                  order?.ticket?.status === "used"
                    ? "Sudah Digunakan"
                    : "Belum Digunakan"
                }}
              </p>
              <p class="mb-1 text-muted">Nama Pemesan</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.event_time }}</p>
              <p class="mb-1 text-muted">Lokasi</p>
              <p class="fw-bold fs-6">{{ order?.ticket?.event_location }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import QrcodeVue from "qrcode.vue";

export default {
  name: "OrderDetail",
  props: ["id", "qrIndex"], // qrIndex untuk memilih QR code tertentu
  components: {
    QrcodeVue,
  },
  data() {
    return {
      order: null,
      selectedQrCode: "", // Data untuk QR code khusus tiket
    };
  },
  mounted() {
    this.getOrderDetail();
  },
  methods: {
    getOrderDetail() {
      axios
        .get(`/user/orders/${this.id}`)
        .then((response) => {
          this.order = response.data;
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
        })
        .catch((error) => {
          console.error("Error fetching order detail:", error);
        });
    },

    updateTicketStatus(newStatus) {
      axios
        .post(
          `/orders/${this.selectedQrCode.orderId}/tickets/${this.selectedQrCode.ticketNumber}/update-status`,
          {
            status: newStatus,
          }
        )
        .then((response) => {
          this.order.ticket.status = newStatus;
          console.log(response.data.message);
        })
        .catch((error) => {
          console.error("Gagal memperbarui status tiket:", error);
        });
    },

    handleVerificationSuccess() {
      this.updateTicketStatus("used");
    },

    generateQRCodeValue(qrData) {
      const baseUrl = "https://22c9-114-10-47-147.ngrok-free.app/verify";
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
