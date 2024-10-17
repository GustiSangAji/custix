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
import axios from 'axios';

export default {
  data() {
    return {
      accessGranted: false,
      queuePosition: null,
    };
  },
  methods: {
    checkStatus() {
      axios.get('/waiting-room-status') // Pastikan URL API benar
        .then(response => {
          this.accessGranted = response.data.accessGranted;
          this.queuePosition = response.data.queuePosition;

          if (this.accessGranted) {
            // Arahkan ke halaman pembelian tiket jika slot tersedia
            this.$router.push(`/tiket/${this.$route.params.id}`);
          }
        })
        .catch(error => {
          console.error('Terjadi kesalahan saat memeriksa status:', error);
        });

        
    }
  },
  mounted() {
    // Cek status setiap 5 detik
    this.checkStatus();
    this.interval = setInterval(this.checkStatus, 5000);
  },
  beforeUnmount() {
    clearInterval(this.interval);
  }
};
</script>

<style scoped>
.waiting-room {
  margin-top: 50px;
}
</style>
