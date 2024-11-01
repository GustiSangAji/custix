<template>
  <div class="container mt-5 d-flex justify-content-center">
    <div class="card">
      <div class="card-body">
        <!-- Menampilkan alert status verifikasi tiket -->
        <div v-if="verificationStatus">
          <div
            v-if="verificationStatus === 'valid'"
            class="alert alert-primary d-flex align-items-center p-5"
          >
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"></i>
            <div class="d-flex flex-column">
              <h4 class="mb-4">Tiket Valid</h4>
              <span>Tiket valid dan berhasil diverifikasi.</span>
            </div>
          </div>

          <div
            v-else-if="verificationStatus === 'used'"
            class="alert alert-danger d-flex align-items-center p-5"
          >
            <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"></i>
            <div class="d-flex flex-column">
              <h4 class="mb-4">Tiket Sudah Digunakan</h4>
              <span
                >Tiket sudah digunakan dan tidak bisa diverifikasi lagi.</span
              >
            </div>
          </div>

          <div
            v-else-if="verificationStatus === 'not_found'"
            class="alert alert-warning d-flex align-items-center p-5"
          >
            <i class="ki-duotone ki-shield-tick fs-2hx text-warning me-4"></i>
            <div class="d-flex flex-column">
              <h4 class="mb-4">Tiket Tidak Ditemukan</h4>
              <span>Tiket tidak ditemukan dalam sistem.</span>
            </div>
          </div>
        </div>

        <div class="card-footer text-center">
          <router-link class="btn btn-primary" to="/order">Kembali</router-link>
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
      order_id: null,
      verificationStatus: "",
      loading: false,
    };
  },

  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    this.order_id = urlParams.get("order_id");
    const uniqueId = urlParams.get("unique_id");
    const ticketNumber = urlParams.get("ticket_number");
    const hash = urlParams.get("hash");

    // Set selectedQrCode dengan data dari URL
    this.selectedQrCode = {
      orderId: this.order_id,
      uniqueId: uniqueId,
      ticketNumber: ticketNumber,
      hash: hash,
    };

    if (this.order_id) {
      this.verifyTicket();
    }
  },

  methods: {
    verifyTicket() {
      this.loading = true;
      axios
        .post("https://22c9-114-10-47-147.ngrok-free.app/api/verify-ticket", {
          order_id: this.order_id,
          unique_id: this.selectedQrCode.uniqueId,
          ticket_number: this.selectedQrCode.ticketNumber,
          hash: this.selectedQrCode.hash,
        })
        .then((response) => {
          this.loading = false;
          if (
            response.data.message === "Tiket valid dan berhasil diverifikasi"
          ) {
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
  padding: 2rem;
}

.alert {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  border-radius: 8px;
}

.card-footer {
  padding: 1rem;
}

.btn-primary {
  padding: 0.5rem 2rem;
}

h4 {
  margin-bottom: 0.5rem;
}
</style>
