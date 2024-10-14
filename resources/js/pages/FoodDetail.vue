<template>
  <div class="ticket-detail">
    <Navbar />
    <div class="container mt-4">
      <div class="col-md-4"></div>

      <!-- Image Section -->
      <div class="shadow-lg rounded mb-4">
        <img
          :src="'/storage/' + product.banner"
          class="img-fluid rounded shadow-lg"
          alt="Event Banner"
        />
      </div>

      <!-- Title Section -->
      <div class="mt-10 text-center">
        <h1 class="fs-1 mb-4">{{ product.name }}</h1>
        <p class="d-flex align-items-center justify-content-center mb-6">
          <i class="bi bi-calendar-event me-2"></i>{{ formatDate(product.datetime) }}
          <span class="mx-3">|</span>
          <i class="bi bi-geo-alt me-2"></i>{{ product.place }}
        </p>
        <hr />
      </div>

      <!-- Content Section -->
      <div class="row mt-4">
        <!-- Event Details Section -->
        <div class="col-md-8">
          <!-- Event description -->
          <div class="card border-0 p-4 fs-4 shadow-sm rounded mb-4">
            <p>✮ Hello Arte-Folks ✮</p>
            <p>{{ product.description }}</p>

            <p>
              <strong>Date:</strong> {{ formatDate(product.datetime) }}<br />
              <strong>Location:</strong> {{ product.place }} ♬
            </p>
          </div>

          <!-- Tentang Tiket Section -->
          <div class="mt-4">
            <h2 class="fs-3">Tentang Tiket</h2>
            <div class="card border-0 p-4 shadow-sm rounded mt-3 fs-4">
    
              <ul>
                <li>
                  Tiket Festival adalah kategori berdiri, dengan pengalaman
                  menonton terbaik, dimana kamu bisa berdiri dekat sekali dengan
                  panggung dan tentunya idola kamu.
                </li>
                <li>Untuk usia minimum penonton Festival 5 Tahun</li>
                <li>
                  Periode Presale 1 akan dimulai pada 8 Juli 2024 dengan harga
                  spesial
                </li>
                <li>
                  Tiket yang sudah dibeli tidak dapat dikembalikan kecuali acara
                  dibatalkan oleh penyelenggara (No Refund, No Upgrade /
                  Downgrade)
                </li>
              </ul>
              <h4 class="mt-4">Tentang Tiket Festival EARLY ENTRY</h4>
              <p>Syarat & Ketentuan</p>
              <ul>
                <li>
                  Pemilik tiket Early Entry WAJIB sudah masuk venue maksimal
                  pukul 16.30 WIB
                </li>
                <li>
                  Apabila melebihi pukul 16.30 WIB, maka tiket akan hangus (dan
                  harus membeli tiket lagi jika ingin tetap menonton konser)
                </li>
                <li>
                  Penukaran tiket membawa ID Card Asli (tidak dapat diwakilkan)
                </li>
              </ul>
              <p>*Harga belum termasuk pajak dan biaya admin</p>
            </div>
          </div>
        </div>

        <!-- Image and Form Section -->
        <div class="col-md-4">
          <!-- Product Image -->
          <div class="card border-0 shadow-sm rounded mb-4">
            <img
              :alt="product.name"
              :src="'/storage/' + product.image"
              class="img-fluid rounded shadow-sm"
            />
          </div>

          <!-- Event Date -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Tanggal Event</h6>
            <div class="d-flex align-items-center mb-2">
              <span class="badge bg-primary p-2 me-2">{{ formatShortDate(product.datetime) }}</span>
              <span>Periode Event: {{ formatShortDate(product.expiry_date) }}</span>
            </div>
          </div>

          <!-- Ticket Quantity Selection -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Jumlah Tiket</h6>
            <div class="d-flex align-items-center mb-2">
              <span class="text-danger me-2">IDR {{ product.price }}</span>
              <!-- Input for quantity -->
              <div class="input-group">
                <button
                  type="button"
                  class="btn btn-outline-danger"
                  @click="kurangiJumlah"
                >
                  -
                </button>
                <input
                  type="number"
                  class="form-control text-center mx-2 no-arrows"
                  v-model="pesan.jumlah_pemesanan"
                  @input="periksaJumlah"
                  min="1"
                  style="width: 100px; padding: 0.375rem; font-size: 1rem"
                  aria-label="Jumlah Pesan"
                />
                <button
                  type="button"
                  class="btn btn-outline-danger"
                  @click="tambahJumlah"
                >
                  +
                </button>
              </div>
            </div>
          </div>

          <!-- Total Price -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Total ({{ this.pesan.jumlah_pemesanan }} pax):</h6>
            <p class="fs-5">
              IDR {{ formatPrice(this.pesan.jumlah_pemesanan * product.price) }}
            </p>
            <button
              type="submit"
              class="btn btn-primary w-100"
              @click="pemesanan"
            >
              Pesan
            </button>
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
    formatShortDate(dateString) {
      const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('id-ID', options); // Format date to "Sab 2 Jan"
    },
    tambahJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === ""
      ) {
        this.pesan.jumlah_pemesanan = 1;
      } else if (this.pesan.jumlah_pemesanan < 3) {
        this.pesan.jumlah_pemesanan++;
      }
    },
    kurangiJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === ""
      ) {
        this.pesan.jumlah_pemesanan = 1;
      } else if (this.pesan.jumlah_pemesanan > 1) {
        this.pesan.jumlah_pemesanan--;
      }
    },
    periksaJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === "" ||
        this.pesan.jumlah_pemesanan < 1
      ) {
        this.pesan.jumlah_pemesanan = 1;
      }
    },
    pemesanan() {
      const userId = localStorage.getItem('user_id');
      const payload = {
        jumlah_pemesanan: this.pesan.jumlah_pemesanan,
        product: {
          id: this.product.id,
          nama_tiket: this.product.name,
          total_harga: this.pesan.jumlah_pemesanan * this.product.price,
        },
        user_id: userId,
        tanggal: this.formatShortDate(this.product.datetime),
        tiket: `#${this.product.name.replace(/\s+/g, '')}`,
      };

      axios.post('/order', payload)
        .then(response => {
          console.log('Pemesanan berhasil:', response.data);
          this.$router.push({
            name: 'paymentDetail',
            params: {
              orderId: response.data.cart.id,
            },
          });
        })
        .catch(error => {
          console.error('Terjadi kesalahan:', error);
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
  border-radius: 10px;
}

.shadow-lg {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.fs-1 {
  font-size: 2.5rem;
}

.fs-4 {
  font-size: 1.5rem;
}

.fs-5 {
  font-size: 1.25rem;
}

.text-danger {
  color: #dc3545;
}

.text-center {
  text-align: center;
}
</style>
