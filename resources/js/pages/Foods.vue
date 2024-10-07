<template>
  <div>
    <Navbar />
    <div class="container">
      <div class="row mt-4">
        <div class="col">
          <h2>Daftar <strong>Tiket</strong></h2>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col">
          <div class="input-group mb-3">
            <input
              v-model="cari"
              type="text"
              class="form-control"
              placeholder="Cari Tiket Kesukaan Anda"
              aria-label="Cari"
              aria-describedby="basic-addon1"
            />
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div
          class="col-md-4 mt-4"
          v-for="product in filteredProducts"
          :key="product.id"
        >
          <TicketProduct :product="product" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import TicketProduct from "@/components/TicketProduct.vue";
import axios from "axios";

export default {
  name: "TicketView",
  components: {
    Navbar,
    TicketProduct,
  },
  data() {
    return {
      products: [], // Menyimpan semua produk tiket
      cari: '',     // Nilai input pencarian
    };
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product =>
        product.name.toLowerCase().includes(this.cari.toLowerCase())
      );
    }
  },
  mounted() {
    axios
      .get("http://localhost:8000/api/tickets")  // Panggil API untuk tiket
      .then((response) => {
        this.products = response.data;  // Simpan data dari API ke 'products'
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>
