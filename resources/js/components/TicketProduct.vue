<template>
  <div class="card shadow-sm rounded overflow-hidden position-relative">
    <!-- Gambar Produk -->
    <img
      :src="imageUrl"
      class="card-img-top"
      :alt="product.name"
      v-if="imageUrl"
      @error="onImageError"
    />
    <span v-if="isUnavailable" class="badge badge-light-dark text-white position-absolute p-2 top-0 start-0 m-2">
      Habis
    </span>

    <div class="card-body p-4">
      <!-- Nama Produk -->
      <h5 class="card-title fw-bold text-uppercase mb-3">
        {{ product.name }}
      </h5>

      <!-- Informasi Tanggal dan Tempat -->
      <p class="card-text fs-6 mb-3 text-muted">
        <i class="bi bi-calendar me-2"></i> {{ formatDate(product.datetime) }}
        &nbsp;
        <i class="bi bi-geo-alt me-2"></i> {{ product.place }}
      </p>

      <!-- Harga dan Tombol Beli -->
      <div class="d-flex justify-content-between align-items-center">
        <div class="bg-success fw-bold text-light px-3 py-2 rounded">
          Mulai Dari Rp. {{ product.price }}
        </div>

        <!-- Tombol Beli Tiket -->
        <button
          @click="checkAccessAndRedirect"
          class="btn btn-danger block-btn px-4 py-2 fw-bold"
        >
          Beli Tiket
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router"; // Vue Router untuk redirect
import Swal from "sweetalert2";

export default {
  name: "TicketProduct",
  props: {
    product: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const router = useRouter(); // Inisialisasi router
    return { router };
  },
  computed: {
    imageUrl() {
      // Menghasilkan URL gambar
      if (this.product.image && typeof this.product.image === "string") {
        return `/storage/${this.product.image}`;
      }
      return "/images/default-ticket.jpg"; // Gambar default jika tidak ada gambar
    },
    isUnavailable() {
      // Cek apakah tiket tidak tersedia
      return this.product.status !== 'available'; // Sesuaikan dengan nilai status yang tepat
    },
  },
  methods: {
    onImageError(event) {
      // Mengatur gambar default saat terjadi error
      event.target.src = "/images/default-ticket.jpg";
    },
    formatDate(date) {
      // Memformat tanggal ke format lokal
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    async checkAccessAndRedirect() {
  const userId = localStorage.getItem("userId");

  if (!userId) {
    Swal.fire({
      title: "Anda harus login",
      text: "Silakan login untuk memesan tiket.",
      icon: "warning",
      confirmButtonText: "Login",
      cancelButtonText: "Batal",
      showCancelButton: true,
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        this.router.push({ name: "sign-in" });
      }
    });
  } else {
    try {
      const response = await axios.get("/waiting-room-status", {
        params: { ticket_id: this.product.id }, // Sesuaikan di sini
      });
      const { accessGranted } = response.data;

      if (accessGranted) {
        const ticketName = this.product.name.replace(/\s+/g, "-");
        const sanitizedTicketName = encodeURIComponent(ticketName);

        this.router.push({
          name: "ticket-detail",
          params: { name: sanitizedTicketName },
        });
      } else {
        Swal.fire({
          title: "Menunggu Giliran",
          text: "Kamu sedang berada dalam waiting room. Harap menunggu sampai giliranmu tiba.",
          icon: "info",
          confirmButtonText: "OK",
        });
        this.router.push({
          path: "/waiting-room",
          query: { id: this.product.id, name: this.product.name }, // Sesuaikan di sini
        });
      }
    } catch (error) {
      console.error("Error checking access:", error);
      Swal.fire({
        title: "Kesalahan",
        text: "Terjadi kesalahan saat mengecek akses. Silakan coba lagi nanti.",
        icon: "error",
        confirmButtonText: "OK",
      });
    }
  }
},

  },
};
</script>
