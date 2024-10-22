<template>
  <LayoutLanding>
    <div class="container">
      <div class="row mt-10">
        <div class="col">
          <h2>Daftar <strong>Tiket</strong></h2>
        </div>
      </div>

      <div class="row mb-4">
        <!-- Menampilkan produk yang sudah dimuat -->
        <transition-group name="fade" tag="div" class="row mb-4">
          <div
            class="col-md-4 mt-4"
            v-for="product in products"
            :key="product.id"
          >
            <TicketProduct :product="product" />
          </div>
        </transition-group>

        <!-- Menampilkan skeleton jika loading -->
        <div v-if="loading" class="row mb-4">
          <div v-for="n in skeletonCount" :key="`skeleton-${n}`" class="col-md-4 mt-4">
            <div class="skeleton-item"></div>
          </div>
          <div class="text-center spinner-container">
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="!loading && products.length === 0" class="text-center mt-5">
        <p>Tidak ada tiket yang tersedia saat ini.</p>
      </div>
    </div>
  </LayoutLanding>
</template>

<script>
import TicketProduct from "@/components/TicketProduct.vue";
import axios from "axios";
import LayoutLanding from "../layouts/LayoutLanding.vue";

export default {
  name: "TicketView",
  components: {
    TicketProduct,
    LayoutLanding,
  },
  data() {
    return {
      products: [],
      loading: false,
      currentPage: 1,
      totalPages: 1,
      skeletonCount: 6, // Jumlah skeleton yang ditampilkan
    };
  },
  mounted() {
    this.fetchTickets(); // Ambil tiket untuk halaman pertama
    window.addEventListener("scroll", this.handleScroll); // Tambahkan event listener untuk scroll
  },
  beforeDestroy() {
    window.removeEventListener("scroll", this.handleScroll); // Hapus event listener saat komponen dihapus
  },
  methods: {
    fetchTickets(page = this.currentPage) {
      this.loading = true;
      axios
        .get(`http://localhost:8000/api/tickets?page=${page}`)
        .then((response) => {
          // Menunggu 1 detik sebelum menambahkan produk
          setTimeout(() => {
            // Tambahkan produk baru ke daftar produk
            this.products = [...this.products, ...response.data.data];
            this.totalPages = response.data.last_page;
            this.currentPage = page;
          }, 1000); // Jeda 1 detik
        })
        .catch((error) => {
          console.error("Error fetching tickets:", error);
        })
        .finally(() => {
          // Ubah status loading setelah 1 detik
          setTimeout(() => {
            this.loading = false;
          }, 1000); // Jeda 1 detik
        });
    },
    handleScroll() {
      const scrollTop = window.scrollY;
      const windowHeight = window.innerHeight;
      const documentHeight = document.documentElement.scrollHeight;

      // Menentukan apakah scroll mencapai bagian bawah
      if (scrollTop + windowHeight >= documentHeight - 5 && !this.loading && this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchTickets(this.currentPage);
      }
    },
  },
};
</script>

<style>
.skeleton-item {
  height: 200px;
  background-color: #e0e0e0;
  border-radius: 4px;
  margin-bottom: 10px;
  animation: pulse 1.5s infinite; /* Animasi skeleton */
}

.spinner-container {
  display: flex;
  justify-content: center;
  margin: 20px 0; /* Sesuaikan margin untuk memisahkan spinner dari skeleton */
}

/* Animasi Pulse untuk Skeleton */
@keyframes pulse {
  0% {
    opacity: 0.7;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0.7;
  }
}

/* Animasi Fade */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active di versi <2.1.8 */ {
  opacity: 0;
}

/* Tambahkan gaya untuk transisi produk */
.fade-enter {
  transform: translateY(20px);
  opacity: 0;
}
.fade-enter-active {
  transform: translateY(0);
  opacity: 1;
  transition: transform 0.5s, opacity 0.5s; /* Transisi transform dan opacity */
}
</style>
