<template>
    <div class="waiting-room">
      <div class="waiting-card">
        <h1>Ruang Tunggu</h1>
        <p>Silakan tunggu, Anda berada di antrean untuk membeli tiket.</p>
        <div class="countdown">
          <p>Perkiraan waktu tunggu:</p>
          <h2>{{ waitTime }} detik</h2>
        </div>
        <div class="spinner" v-if="waitTime > 0">
          <div class="loader"></div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        waitTime: 5  // Waktu tunggu awal
      };
    },
    mounted() {
      const countdown = setInterval(() => {
        if (this.waitTime > 0) {
          this.waitTime--;
        } else {
          clearInterval(countdown);
          this.$router.push({ path: '/tiket/4' });
        }
      }, 1000);
    },
  };
  </script>
  
  <style scoped>
  .waiting-room {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #333, #111); /* Gelap */
    color: white; /* Teks berwarna putih untuk kontras */
    font-family: 'Arial', sans-serif;
  }
  
  .waiting-card {
    background: rgba(255, 255, 255, 0.1); /* Semi-transparan putih */
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    text-align: center;
    color: white; /* Teks berwarna putih */
  }
  
  .countdown {
    margin: 20px 0;
  }
  
  .spinner {
    display: flex;
    justify-content: center;
    margin-top: 10px;
  }
  
  .loader {
    border: 8px solid rgba(255, 255, 255, 0.3); /* Light gray */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>
  