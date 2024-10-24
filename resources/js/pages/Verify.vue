<template>
  <div class="container mt-5 d-flex justify-content-center">
    <div class="card">
      <div class="card-body">
        <div class="qrcode-container">
          <qrcode-vue :value="order_id" :size="150" />
        </div>
        <div class="status-container" v-if="verificationStatus === 'valid'">
          <i class="fas fa-check-circle fa-3x text-success"></i>
          <h5 class="text-success mt-2">Valid</h5>
        </div>
        <div class="status-container" v-if="verificationStatus === 'used'">
          <i class="fas fa-times-circle fa-3x text-danger"></i>
          <h5 class="text-danger mt-2">Tiket sudah digunakan.</h5>
        </div>
        <div class="status-container" v-if="verificationStatus === 'not_found'">
          <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
          <h5 class="text-danger mt-2">Tiket tidak ditemukan.</h5>
        </div>
      </div>
      <div class="card-footer text-center">
        <button class="btn btn-primary" @click="goBack">Kembali</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import QrcodeVue from "qrcode.vue";

export default {
  props: ['order_id', 'ticketImage'],
  components: { QrcodeVue },
  data() {
    return {
      verificationStatus: null,
      loading: false,
    };
  },
  
  mounted() {
    if (this.order_id) {
      this.verifyTicket();
    }
  },
  
  methods: {
    verifyTicket() {
      this.loading = true;
      axios
        .post("https://104a-118-99-113-12.ngrok-free.app/api/verify-ticket", { order_id: this.order_id })
        .then((response) => {
          this.loading = false;
          if (response.data.message === 'Tiket valid dan berhasil diverifikasi') {
            this.verificationStatus = "valid";
          }
        })
        .catch((error) => {
          this.loading = false;
          if (error.response && error.response.status === 400) {
            this.verificationStatus = "used";
          } else if (error.response && error.response.status === 404) {
            this.verificationStatus = "not_found";
          }
        });
    },
    goBack() {
      this.$router.go(-1);
    }
  },
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.card {
  width: 100%;
  max-width: 350px;
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.card-body {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2rem;
}

.qrcode-container {
  display: flex;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.status-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 1rem;
}

.card-footer {
  padding: 1rem;
}

.btn-primary {
  padding: 0.5rem 2rem;
}

h5 {
  margin-top: 1rem;
}
</style>
