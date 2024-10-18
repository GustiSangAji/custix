  <template>
    <div class="container mt-5 d-flex justify-content-center">
      <div class="card" style="width: 300px;">
        <div class="card-body d-flex flex-column align-items-center">
          <qrcode-vue :value="order_id" :size="150" />
          <div v-if="verificationStatus === 'valid'" class="text-success mt-3 text-center">
            <i class="fas fa-check-circle fa-3x"></i>
            <h5>Valid</h5>
          </div>
          <div v-if="verificationStatus === 'used'" class="text-danger mt-3 text-center">
            <i class="fas fa-times-circle fa-3x"></i>
            <h5>Tiket sudah digunakan.</h5>
          </div>
          <div v-if="verificationStatus === 'not_found'" class="text-danger mt-3 text-center">
            <i class="fas fa-exclamation-circle fa-3x"></i>
            <h5>Tiket tidak ditemukan.</h5>
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
  import QrcodeVue from "qrcode.vue"; // Pastikan Anda mengimpor qrcode.vue

  export default {
    props: ['order_id', 'ticketImage'], // Menerima order_id dan ticketImage dari route
    components: { QrcodeVue }, // Daftarkan komponen QRCode
    data() {
      return {
        verificationStatus: null, // Status verifikasi tiket
        loading: false, // Status loading
      };
    },
    
    mounted() {
      if (this.order_id) {
        this.verifyTicket();
      }
    },
    
    methods: {
      verifyTicket() {
        this.loading = true; // Menunjukkan loading
        axios
          .post("https://7269-118-99-113-12.ngrok-free.app/api/verify-ticket", { order_id: this.order_id })
          .then((response) => {
            this.loading = false; // Menghentikan loading
            if (response.data.message === 'Tiket valid dan berhasil diverifikasi') {
              this.verificationStatus = "valid";
            }
          })
          .catch((error) => {
            this.loading = false; // Menghentikan loading
            if (error.response && error.response.status === 400) {
              this.verificationStatus = "used"; // Tiket sudah digunakan
            } else if (error.response && error.response.status === 404) {
              this.verificationStatus = "not_found"; // Tiket tidak ditemukan
            }
          });
      },
      goBack() {
        this.$router.go(-1); // Kembali ke halaman sebelumnya
      }
    },
  };
  </script>

  <style scoped>
  .card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  .card-header {
    background-color: #f8f9fa;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
  }
  .card-body {
    padding: 2rem;
  }
  .ticket-image {
    width: 40px; /* Sesuaikan ukuran gambar tiket */
    height: auto; /* Menjaga rasio aspek */
  }
  </style>
