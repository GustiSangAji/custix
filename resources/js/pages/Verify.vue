<template>
  <div>
    <h2>Verifikasi Tiket</h2>
    <div v-if="verificationStatus === 'valid'" class="alert alert-success text-white">
      Tiket valid! Selamat menikmati acara.
    </div>
    <div v-if="verificationStatus === 'used'" class="alert alert-danger text-white">
      Tiket sudah digunakan.
    </div>
    <div v-if="verificationStatus === 'not_found'" class="alert alert-danger text-white">
      Tiket tidak ditemukan.
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ['order_id'], // Menerima order_id dari route

  data() {
    return {
      verificationStatus: null, // Status verifikasi tiket
    };
  },
  
  mounted() {
    if (this.order_id) {
      this.verifyTicket();
    }
  },
  
  methods: {
    verifyTicket() {
      axios
        .post("https://e6fc-118-99-113-12.ngrok-free.app/api/verify-ticket", { order_id: this.order_id })
        .then((response) => {
          if (response.data.message === 'Tiket valid dan berhasil diverifikasi') {
            this.verificationStatus = "valid";
          }
        })
        .catch((error) => {
          if (error.response && error.response.status === 400) {
            this.verificationStatus = "used"; // Tiket sudah digunakan
          } else if (error.response && error.response.status === 404) {
            this.verificationStatus = "not_found"; // Tiket tidak ditemukan
          }
        });
    },
  },
};
</script>
