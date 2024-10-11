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
          <!-- Event description -->
          <div class="card border-0 p-4 fs-4 shadow-sm rounded mb-4">
            <p>✮ Hello Arte-Folks ✮</p>
            <p>
              Are You Ready?! Don't miss out on the
              <strong>Epic CLOSING CEREMONY</strong> by Artefac UNS. ARTEFAC
              merupakan rangkaian acara tahunan yang diselenggarakan oleh
              Fakultas Ekonomi dan Bisnis Universitas Sebelas Maret dengan
              menampilkan berbagai kompetisi <strong>ART</strong> (monolog),
              <strong>SPORT</strong> (basket dan futsal), dan
              <strong>Closing Ceremony</strong> yang paling dinanti setiap
              tahunnya! Get ready for an unforgettable night with stellar
              performances by:
            </p>
            <ul>
              <li>Dewa 19 Ft Ello (Marcello Tahitoe)</li>
              <li>Coldiac</li>
              <li>Juicy Luicy</li>
              <li>And many more exciting guest stars will be announced ♫</li>
            </ul>
            <p>
              <strong>Date:</strong> {{ formatDate(product.datetime) }}<br />
              <strong>Location:</strong> {{ product.place }} ♬
            </p>
            <p>
              Hurry and snag your tickets fast!!! Let's all gather and enjoy the
              music together. Hope to see you there! See you! ♬
            </p>
            <p>
              Further information can be found on our Instagram:
              <strong>@artefacuns</strong> and <strong>@custiket.id</strong>
            </p>
          </div>

          <!-- Line Up Section -->
          <div class="mt-4">
            <h2 class="fs-3">Line Up</h2>
            <div class="card border-0 p-4 shadow-sm rounded mt-3 fs-4">
              <ul>
                <li>Dewa 19 ft Ello</li>
                <li>Juicy Luicy</li>
                <li>Coldiac</li>
              </ul>
            </div>
          </div>

          <!-- Ticket Information Section -->
          <div class="mt-4">
            <h2 class="fs-3">Tentang Tiket</h2>
            <div class="card border-0 p-4 shadow-sm rounded mt-3 fs-4">
              <h4>Tentang Tiket Festival</h4>
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

          <!-- Ticket Category -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h5>PRESALE 2 - CAT 1B</h5>
            <p class="text-muted mb-1">
              <i class="bi bi-x-circle me-2"></i> Tidak bisa refund
            </p>
            <p class="text-muted mb-1">
              <i class="bi bi-ticket me-2"></i> Kursi tersedia saat penukaran
              tiket • Kursi dipilih otomatis di lokasi penukaran.
            </p>
            <a href="#" class="text-primary">Detail</a>
          </div>

          <!-- Event Date -->
          <div class="card border-0 shadow-sm p-4 rounded mb-4">
            <h6>Tanggal Event</h6>
            <div class="d-flex align-items-center mb-2">
              <span class="badge bg-primary p-2 me-2">{{
                formatShortDate(product.datetime)
              }}</span>
              <span
                >Periode Event: {{ formatShortDate(product.expiry_date) }}</span
              >
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
      const options = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
      };
      return new Date(dateString).toLocaleDateString("id-ID", options); // Format date to "Sab 2 Jan"
    },
    tambahJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === ""
      ) {
        // Jika input kosong, atur ke 1 sebagai default
        this.pesan.jumlah_pemesanan = 1;
      } else if (this.pesan.jumlah_pemesanan < 3) {
        // Batas maksimal misalnya 100
        this.pesan.jumlah_pemesanan++;
      }
    },
    kurangiJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === ""
      ) {
        // Jika input kosong, atur ke 1 sebagai default
        this.pesan.jumlah_pemesanan = 1;
      } else if (this.pesan.jumlah_pemesanan > 1) {
        // Batas minimal adalah 1
        this.pesan.jumlah_pemesanan--;
      }
    },
    periksaJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === "" ||
        this.pesan.jumlah_pemesanan < 1
      ) {
        // Pastikan jumlah minimal selalu 1 saat input kosong atau kurang dari 1
        this.pesan.jumlah_pemesanan = 1;
      }
    },
    pemesanan() {
      // Lanjutkan proses pemesanan tanpa pemeriksaan autentikasi
      if (this.pesan.jumlah_pemesanan) {
        const orderData = {
          jumlah_pemesanan: this.pesan.jumlah_pemesanan,
          product: this.product,
        };

        axios
          .post("http://localhost:8000/api/keranjangs", orderData)
          .then(() => {
            this.$router.push({ path: "/keranjang" });
            this.$toast.success("Sukses Masuk Keranjang", {
              type: "success",
              position: "top-right",
              duration: 3000,
              dismissible: true,
            });
          })
          .catch((err) => {
            console.log(err);
            this.$toast.error("Gagal menambahkan ke keranjang", {
              type: "error",
              position: "top-right",
              duration: 3000,
              dismissible: true,
            });
          });
      } else {
        this.$toast.error("Jumlah Pesanan Harus Diisi", {
          type: "error",
          position: "top-right",
          duration: 3000,
          dismissible: true,
        });
      }
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

.btn-outline-secondary {
  min-width: 40px;
}
</style>