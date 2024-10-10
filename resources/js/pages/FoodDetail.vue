<template>
  <div class="ticket-detail">
    <Navbar />
    <div class="container mt-4">
      <div class="col-md-4"></div>

      <!-- Image Section -->
      <div class="shadow-lg rounded mb-4">
        <img
          src="/media/carousel/closing-ceremony.png"
          class="img-fluid rounded shadow-lg"
          alt="Closing Ceremony"
        />
      </div>

      <!-- Title Section -->
      <div class="mt-10 text-center">
        <h1 class="fs-1 mb-4">{{ product.name }}</h1>
        <p class="d-flex align-items-center justify-content-center mb-6">
          <i class="bi bi-calendar-event me-2"></i
          >{{ formatDate(product.datetime) }}
          <span class="mx-3">|</span>
          <i class="bi bi-geo-alt me-2"></i>{{ product.place }}
        </p>
        <hr />
      </div>

      <!-- Content Section -->
      <div class="row mt-4">
        <!-- Event Details Section -->
        <div class="col-md-8">
          <div class="card border-0 p-4 fs-4 shadow-sm rounded mb-4">
            <!-- Event Description -->
            <p>✮ Hello Arte-Folks ✮</p>
            <p>Don't miss out on the <strong>Epic CLOSING CEREMONY</strong>!</p>
          </div>
        </div>

        <!-- Image and Form Section -->
        <div class="col-md-4">
          <!-- Ticket Quantity Selection -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Jumlah Tiket</h6>
            <div class="d-flex align-items-center mb-2">
              <span class="text-danger me-2">IDR {{ product.price }}</span>
              <!-- Input for quantity -->
              <div class="input-group">
                <button type="button" class="btn btn-outline-danger" @click="kurangiJumlah">-</button>
                <input
                  type="number"
                  class="form-control text-center mx-2 no-arrows"
                  v-model="pesan.jumlah_pemesanan"
                  @input="periksaJumlah"
                  min="1"
                  style="width: 100px; padding: 0.375rem; font-size: 1rem"
                  aria-label="Jumlah Pesan"
                />
                <button type="button" class="btn btn-outline-danger" @click="tambahJumlah">+</button>
              </div>
            </div>
          </div>

          <!-- Total Price -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Total ({{ pesan.jumlah_pemesanan }} pax):</h6>
            <p class="fs-5">IDR {{ formatPrice(pesan.jumlah_pemesanan * product.price) }}</p>
            <button type="submit" class="btn btn-primary w-100" @click="detailTransaksi">Pesan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import axios from "axios";

export default {
  name: "TicketDetail",
  components: {
    Navbar,
  },
  data() {
    return {
      ticketQuantity: 1,
      ticketPrice: 1694252, // IDR per ticket
      product: {},
      pesan: {
        jumlah_pemesanan: 1, // Jumlah awal
      },
    };
  },
  methods: {
    formatPrice(value) {
      return value.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
      });
    },
    setProduct(data) {
      this.product = data;
    },
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    tambahJumlah() {
      if (this.pesan.jumlah_pemesanan < 3) {
        this.pesan.jumlah_pemesanan++;
      }
    },
    kurangiJumlah() {
      if (this.pesan.jumlah_pemesanan > 1) {
        this.pesan.jumlah_pemesanan--;
      }
    },
    periksaJumlah() {
      if (this.pesan.jumlah_pemesanan < 1) {
        this.pesan.jumlah_pemesanan = 1;
      }
    },
    detailTransaksi() {
      const totalHarga = this.pesan.jumlah_pemesanan * this.product.price;

      // Data yang akan dikirim ke API
      const orderData = {
        product_id: String(this.product.id),
        jumlah_pemesanan: this.pesan.jumlah_pemesanan,
        total_price: totalHarga,
        kode_tiket: this.product.kode_tiket,  // Menggunakan kode_tiket dari API tiket
      };

      // Mengirim data ke API order
      axios.post("http://localhost:8000/api/orders", orderData)
        .then((response) => {
          // Redirect ke halaman orders dengan ID dari response API
          this.$router.push({
            path: `/tiket/${this.product.id}/orders`,
            query: {
              id: response.data.id, // ID order dari API
            },
          });
          this.$toast.success("Order berhasil dibuat!", {
            type: "success",
            position: "top-right",
            duration: 3000,
            dismissible: true,
          });
        })
        .catch((error) => {
          console.log(error);
          this.$toast.error("Gagal membuat order", {
            type: "error",
            position: "top-right",
            duration: 3000,
            dismissible: true,
          });
        });
    },
  },
  mounted() {
    axios
      .get("http://localhost:8000/api/tickets/" + this.$route.params.id)
      .then((response) => this.setProduct(response.data))
      .catch((error) => console.log(error));
  },
};
</script>

<style scoped>
.ticket-detail {
  padding: 20px;
}

.card {
  border-radius: 8px;
}

.input-group {
  max-width: 250px;
}
</style>
