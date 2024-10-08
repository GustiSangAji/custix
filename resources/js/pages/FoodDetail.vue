<template>
  <div class="ticket-detail">
    <Navbar />
    <div class="container mt-4">
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
        <h1 class="fs-1 mb-4">{{product.name}}</h1>
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
            <p>
              Are You Ready?! Don't miss out on the
              <strong>Epic CLOSING CEREMONY</strong> by Artefac UNS. ARTEFAC
              merupakan rangkaian acara tahunan yang diselenggarakan oleh Fakultas
              Ekonomi dan Bisnis Universitas Sebelas Maret dengan menampilkan
              berbagai kompetisi <strong>ART</strong> (monolog),
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
              <strong>Location:</strong> {{product.place}} ♬
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
                  Tiket Festival adalah kategori berdiri, dengan pengalaman menonton
                  terbaik, dimana kamu bisa berdiri dekat sekali dengan panggung dan
                  tentunya idola kamu.
                </li>
                <li>Untuk usia minimum penonton Festival 5 Tahun</li>
                <li>
                  Periode Presale 1 akan dimulai pada 8 Juli 2024 dengan harga spesial
                </li>
                <li>
                  Tiket yang sudah dibeli tidak dapat dikembalikan kecuali acara
                  dibatalkan oleh penyelenggara (No Refund, No Upgrade / Downgrade)
                </li>
              </ul>
              <h4 class="mt-4">Tentang Tiket Festival EARLY ENTRY</h4>
              <p>Syarat & Ketentuan</p>
              <ul>
                <li>
                  Pemilik tiket Early Entry WAJIB sudah masuk venue maksimal pukul
                  16.30 WIB
                </li>
                <li>
                  Apabila melebihi pukul 16.30 WIB, maka tiket akan hangus (dan harus
                  membeli tiket lagi jika ingin tetap menonton konser)
                </li>
                <li>Penukaran tiket membawa ID Card Asli (tidak dapat diwakilkan)</li>
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

          <!-- Order Form -->
          <form class="mt-4 p-3 shadow-sm rounded" v-on:submit.prevent>
            <div class="form-group d-flex align-items-center">
              <!-- Button to Decrease Quantity -->
              <button
                type="button"
                class="btn btn-outline-danger"
                @click="kurangiJumlah"
              >
                -
              </button>

              <!-- Input for Order Quantity, Without Arrows -->
              <input
                type="number"
                class="form-control text-center mx-2 no-arrows"
                v-model="pesan.jumlah_pemesanan"
                @input="periksaJumlah"
                min="1"
                style="width: 100px; padding: 0.375rem; font-size: 1rem"
                aria-label="Jumlah Pesan"
              />

              <!-- Button to Increase Quantity -->
              <button
                type="button"
                class="btn btn-outline-danger"
                @click="tambahJumlah"
              >
                +
              </button>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              class="btn btn-success d-flex align-items-center mt-4 w-100"
              @click="pemesanan"
            >
              Pesan
            </button>
          </form>
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
    setProduct(data) {
      this.product = data;
    },
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    tambahJumlah() {
      if (
        this.pesan.jumlah_pemesanan === null ||
        this.pesan.jumlah_pemesanan === ""
      ) {
        // Jika input kosong, atur ke 1 sebagai default
        this.pesan.jumlah_pemesanan = 1;
      } else if (this.pesan.jumlah_pemesanan < 100) {
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
      if (this.pesan.jumlah_pemesanan) {
        this.pesan.product = this.product;
        axios
          .post("http://localhost:8000/api/keranjangs", this.pesan)
          .then(() => {
            this.$router.push({ path: "/keranjang" });
            this.$toast.success("Sukses Masuk Keranjang", {
              type: "success",
              position: "top-right",
              duration: 3000,
              dismissible: true,
            });
          })
          .catch((err) => console.log(err));
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
