<template>
  <div class="waiting-room text-center">
    <h1>Waiting Room</h1>
    <p v-if="accessGranted">Slot tersedia! Anda akan segera diarahkan...</p>
    <p v-else>
      Anda berada di antrian. Posisi Anda: {{ queuePosition }}. Tunggu hingga pengguna selesai.
    </p>
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
        const response = await axios.get("/waiting-room-status");
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
      const userId = localStorage.getItem('userId');
      if (userId) {
        try {
          const response = await axios.post('/clear-access', { user_id: userId });
          console.log('User removed from ticket queue:', response.data);
        } catch (error) {
          console.error('Terjadi kesalahan saat menghapus dari antrian:', error);
        }
      } else {
        console.error('User ID tidak ditemukan di localStorage.');
      }
    },

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
  beforeRouteLeave(to, from, next) {
    this.removeFromQueue(); // Panggil fungsi untuk menghapus dari antrian
    next(); // Melanjutkan navigasi
  },
};
</script>

<style scoped>
.waiting-room {
  margin-top: 50px;
}
</style>
