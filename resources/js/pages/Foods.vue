<template>
  <LayoutLanding>
    <div class="container">
      <div class="row mt-10">
        <div class="col">
          <h2>Daftar <strong>Tiket</strong></h2>
        </div>
        <div class="col text-end">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
            Pilih Tanggal
          </button>
        </div>
      </div>

      <!-- Tampilkan Pesan Error Jika Ada -->
      <div v-if="errorMessage" class="alert alert-danger mt-4" role="alert">
        {{ errorMessage }}
      </div>
      <div class="row mb-4">
        <transition-group name="fade" tag="div" class="row mb-4">
          <div class="col-md-4 mt-4" v-for="product in products" :key="product.id">
            <TicketProduct :product="product" />
          </div>
        </transition-group>

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

      <div v-if="!loading && products.length === 0 && !errorMessage" class="text-center mt-5">
        <p>Tidak ada tiket yang tersedia saat ini.</p>
      </div>

      <!-- Modal -->
      <div class="modal fade" tabindex="-1" id="kt_modal_1" ref="ktModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title">Pilih Tanggal</h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-success" @click="setToday">Hari Ini</button>
                <button type="button" class="btn btn-success" @click="setTomorrow">Besok</button>
                <button type="button" class="btn btn-secondary" @click="showCustomDate = !showCustomDate">Lainnya</button>
              </div>

              <div v-if="showCustomDate">
                <datepicker v-model="selectedDate" format="yyyy-MM-dd"></datepicker>
                <button type="button" class="btn btn-primary mt-2" @click="setCustomDate">Pilih Tanggal</button>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" @click="resetDate">Reset</button>
              <button type="button" class="btn btn-primary" @click="submitDate">Simpan perubahan</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </LayoutLanding>
</template>

<script>
import TicketProduct from "@/components/TicketProduct.vue";
import axios from "axios";
import LayoutLanding from "../layouts/LayoutLanding.vue";
import Datepicker from 'vue3-datepicker';

export default {
  name: "TicketView",
  components: {
    TicketProduct,
    LayoutLanding,
    Datepicker
  },
  data() {
    return {
      products: [],
      loading: false,
      currentPage: 1,
      totalPages: 1,
      skeletonCount: 6,
      showCustomDate: false,
      selectedDate: null,
      formattedDate: '',
      scheduledTicket: null,
      errorMessage: '' // Menyimpan pesan error jika ada
    };
  },
  mounted() {
    this.fetchTickets();
    window.addEventListener("scroll", this.handleScroll);
  },
  beforeDestroy() {
    window.removeEventListener("scroll", this.handleScroll);
  },
  methods: {
    fetchTickets(page = this.currentPage) {
      this.loading = true;
      axios
        .get(`tickets?page=${page}`)
        .then((response) => {
          setTimeout(() => {
            this.products = [...this.products, ...response.data.data];
            this.totalPages = response.data.last_page;
            this.currentPage = page;
          }, 1000);
        })
        .catch((error) => {
          console.error("Error fetching tickets:", error);
        })
        .finally(() => {
          setTimeout(() => {
            this.loading = false;
          }, 1000);
        });
    },
    handleScroll() {
      const scrollTop = window.scrollY;
      const windowHeight = window.innerHeight;
      const documentHeight = document.documentElement.scrollHeight;

      if (
        scrollTop + windowHeight >= documentHeight - 5 &&
        !this.loading &&
        this.currentPage < this.totalPages
      ) {
        this.currentPage++;
        this.fetchTickets(this.currentPage);
      }
    },
    setToday() {
      const today = new Date();
      this.selectedDate = today;
    },
    setTomorrow() {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      this.selectedDate = tomorrow;
    },
    setCustomDate() {
      if (this.selectedDate) {
        this.showCustomDate = false;
      }
    },
    submitDate() {
      if (this.selectedDate) {
        const formattedDate = this.selectedDate.toISOString().slice(0, 10);
        this.errorMessage = ''; // Reset error message

        axios.get('/tickets-by-date', { params: { date: formattedDate } })
          .then(response => {
            this.scheduledTicket = response.data.data[0] || null;
            this.products = response.data.data;
          })
          .catch(error => {
            if (error.response && error.response.status === 404) {
              this.errorMessage = 'Tidak ada tiket untuk tanggal ini.';
            } else {
              this.errorMessage = 'Terjadi kesalahan saat mengambil data tiket.';
            }
          });
      } else {
        alert("Pilih tanggal terlebih dahulu.");
      }
    },
    resetDate() {
      this.selectedDate = null;
      this.showCustomDate = false;
      this.errorMessage = ''; // Reset error message
    }
  }
};
</script>
