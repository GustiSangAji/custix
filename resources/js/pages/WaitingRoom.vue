<template>
  <div class="container text-center">
    <h1>Anda berada di Waiting Room</h1>
    <p>Silakan tunggu, Anda akan diarahkan ke halaman penjualan tiket segera setelah ada slot yang tersedia.</p>
    <p>Posisi Anda dalam antrean: <span id="queuePosition">{{ queuePosition }}</span></p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      queuePosition: 0,
    };
  },
  mounted() {
    this.checkWaitingRoomStatus();
  },
  methods: {
    checkWaitingRoomStatus() {
      setInterval(() => {
        fetch('/api/waiting-room-status')
          .then(response => response.json())
          .then(data => {
            if (data.entered) {
              window.location.href = '/tiket/1'; // Redirect to ticket sale page
            } else {
              this.queuePosition = data.position;
            }
          });
      }, 5000); // Cek status antrean setiap 5 detik
    },
  },
};
</script>

<style scoped>
.container {
  margin-top: 50px;
}
</style>
