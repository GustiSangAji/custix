<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div
      class="card text-center shadow p-9"
      style="max-width: 400px; width: 100%"
    >
      <h1 class="card-title mb-1">Waiting Room...</h1>

      <div v-if="accessGranted" class="card-body">
        <p class="text-success">Slot tersedia! Anda akan segera diarahkan...</p>
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div v-else class="card-body">
        <p class="text-primary">
          Terlalu banyak pengguna mengakses halaman ini. Posisi Anda:
          <span class="fw-bold">{{ queuePosition }}</span>
        </p>
        <p>Silakan tunggu hingga pengguna selesai.</p>
        <p>Estimasi 5 Menit</p>
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      accessGranted: false,
      queuePosition: null,
      ticketData: null, // Menyimpan data tiket
    };
  },
  methods: {
    async checkStatus() {
      try {
        const response = await axios.get("waiting-room-status");
        console.log("Response dari waiting-room-status:", response.data);
        this.accessGranted = response.data.accessGranted;
        this.queuePosition = response.data.queuePosition;

        if (this.accessGranted) {
          await this.removeFromQueue(); // Tunggu hingga proses penghapusan selesai

          const ticketId = this.$route.query.id;
          if (ticketId) {
            this.$router.push(`/tiket/${ticketId}`);
          } else {
            console.error("Ticket ID not found in query parameters.");
          }
        }
      } catch (error) {
        console.error("Terjadi kesalahan saat memeriksa status:", error);
      }
    },

    async removeFromQueue() {
      const userId = localStorage.getItem("userId");
      if (userId) {
        try {
          const response = await axios.post("/clear-access", {
            user_id: userId,
          });
          console.log("User removed from ticket queue:", response.data);
        } catch (error) {
          console.error(
            "Terjadi kesalahan saat menghapus dari antrian:",
            error
          );
        }
      } else {
        console.error("User ID tidak ditemukan di localStorage.");
      }
    },
  },
  mounted() {
    this.checkStatus(); // Cek status antrian
    this.interval = setInterval(this.checkStatus, 5000); // Cek status setiap 5 detik
  },
  beforeUnmount() {
    clearInterval(this.interval); // Hentikan interval saat komponen dihapus
  },
  beforeRouteLeave(to, from, next) {
    this.removeFromQueue(); // Panggil fungsi untuk menghapus dari antrian
    next(); // Melanjutkan navigasi
  },
};
</script>