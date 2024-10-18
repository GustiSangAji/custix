<template>
  <div class="waiting-room text-center">
    <h1>Waiting Room</h1>
    <p v-if="accessGranted">Slot tersedia! Anda akan segera diarahkan...</p>
    <p v-else>
      Anda berada di antrian. Posisi Anda: {{ queuePosition }}. Tunggu hingga
      pengguna selesai.
    </p>

    <!-- Tambahan untuk menampilkan data tiket jika tersedia -->
    <div v-if="ticketData">
      <h3>Detail Tiket</h3>
      <p>Nama Tiket: {{ ticketData.name }}</p>
      <p>Harga: {{ formatPrice(ticketData.price) }}</p>
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
    checkStatus() {
      axios
        .get("/waiting-room-status") // Pastikan URL API benar
        .then((response) => {
          this.accessGranted = response.data.accessGranted;
          this.queuePosition = response.data.queuePosition;

          if (this.accessGranted) {
            // Ambil id dari query parameter dan arahkan ke halaman tiket
            const ticketId = this.$route.query.id;
            if (ticketId) {
              this.$router.push(`/tiket/${ticketId}`); // Arahkan ke halaman tiket dengan ID yang benar
            } else {
              console.error("Ticket ID not found in query parameters.");
            }
          }
        })
        .catch((error) => {
          console.error("Terjadi kesalahan saat memeriksa status:", error);
        }); // Tambahkan titik koma di sini
    },

    // Format harga dalam IDR
    formatPrice(value) {
      return value.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
      });
    },
  },
  mounted() {
    this.checkStatus(); // Cek status antrian
    this.interval = setInterval(this.checkStatus, 5000); // Cek status setiap 5 detik
  },
  beforeUnmount() {
    clearInterval(this.interval); // Hentikan interval saat komponen dihapus
  },
};
</script>

<style scoped>
.waiting-room {
  margin-top: 50px;
}
</style>
