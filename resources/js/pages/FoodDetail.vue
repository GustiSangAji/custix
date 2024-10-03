<template>
  <div class="food-detail">
    <Navbar />
    <div class="container">
      <!--Breadcrumb-->
      <div class="row mt-4">
        <div class="col">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/home" class="text-dark">Home</router-link>
              </li>
              <li class="breadcrumb-item">
                <router-link to="/tiket" class="text-dark">Ticket</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Ticket Order
              </li>
            </ol>
          </nav>
        </div>
      </div>

      <!-- Content -->
      <div class="row mt-2">
        <div class="col-md-6">
          <img
            :src="'/media/tiket/' + product.gambar"
            class="img-fluid shadow"
          />
        </div>
        <div class="col-md-6">
          <h2>
            <strong>{{ product.nama }}</strong>
          </h2>
          <hr />
          <h4>
            Harga: <strong>{{ product.harga }}</strong>
          </h4>

          <form class="mt-4" v-on:submit.prevent>
            <div class="form-group d-flex align-items-center">
              <!-- Tombol untuk mengurangi jumlah -->
              <button
                type="button"
                class="btn btn-outline-danger"
                @click="kurangiJumlah"
              >
                -
              </button>

              <!-- Input untuk jumlah pesanan, tanpa arrow -->
              <input
                type="number"
                class="form-control text-center mx-2 no-arrows"
                v-model="pesan.jumlah_pemesanan"
                @input="periksaJumlah"
                min="1"
                style="width: 100px; padding: 0.375rem; font-size: 1rem"
                aria-label="Jumlah Pesan"
              />

              <!-- Tombol untuk menambah jumlah -->
              <button
                type="button"
                class="btn btn-outline-danger"
                @click="tambahJumlah"
              >
                +
              </button>
            </div>

            <button
              type="submit"
              class="btn btn-success d-flex align-items-center"
              @click="pemesanan"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-cart me-2"
                viewBox="0 0 16 16"
              >
                <path
                  d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"
                />
              </svg>
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
  name: "FoodDetail",
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
        this.pesan.products = this.product;
        axios
          .post("http://localhost:3000/keranjangs", this.pesan)
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
      .get("http://localhost:3000/products/" + this.$route.params.id)
      .then((response) => this.setProduct(response.data))
      .catch((error) => console.log(error));
  },
};
</script>