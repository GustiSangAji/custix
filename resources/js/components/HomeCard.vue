<template>
  <div class="card shadow-sm rounded overflow-hidden position-relative">
    <!-- Badge Habis -->
    <span 
      v-if="ticket.status === 'unavailable'" 
      class="badge badge-light-dark text-white position-absolute p-2 top-0 start-0 m-2">
      Habis
    </span>
    
    <!-- Gambar Tiket -->
    <img
      :src="imageUrl"
      class="card-img-top"
      :alt="ticket.name"
      v-if="imageUrl"
      @error="onImageError"
    />
    <div class="card-body p-4">
      <!-- Nama Tiket -->
      <h5 class="card-title fw-bold text-uppercase mb-3">
        {{ ticket.name }}
      </h5>

      <!-- Informasi Tanggal dan Tempat -->
      <p class="card-text fs-6 mb-3 text-muted">
        <i class="bi bi-calendar me-2"></i> {{ formatDate(ticket.datetime) }}
        &nbsp;
        <i class="bi bi-geo-alt me-2"></i> {{ ticket.place }}
      </p>

      <!-- Harga dan Tombol Beli -->
      <div class="d-flex justify-content-between align-items-center">
        <div class="bg-success fw-bold text-light px-3 py-2 rounded">
          Mulai Dari Rp. {{ ticket.price }}
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
import { useRouter } from "vue-router";
import Swal from "sweetalert2";

export default {
  name: "HomeCard",
  props: {
    ticket: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  computed: {
    imageUrl() {
      if (this.ticket.image && typeof this.ticket.image === "string") {
        return `/storage/${this.ticket.image}`;
      }
      return "/images/default-ticket.jpg";
    },
  },
  methods: {
    onImageError(event) {
      event.target.src = "/images/default-ticket.jpg";
    },
    formatDate(date) {
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
      const response = await axios.get("/waiting-room-status");
      const { accessGranted } = response.data;

      if (accessGranted) {
        // Sanitasi nama tiket untuk URL
        const ticketName = this.ticket.name.replace(/\s+/g, '-');
        const sanitizedTicketName = encodeURIComponent(ticketName); // Encode untuk URL

        this.router.push({
          name: "ticket-detail",
          params: { name: sanitizedTicketName }, // Pastikan 'name' sesuai dengan parameter di rute.
        });
      } else {
        Swal.fire({
          title: "Menunggu Giliran",
          text: "Kamu sedang berada dalam waiting room. Harap menunggu sampai giliranmu tiba.",
          icon: "info",
          confirmButtonText: "OK",
        });
        this.router.push({
          name: "waiting-room", // Pastikan ada rute 'waiting-room'.
          query: { name: this.ticket.name },
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

<style scoped>

</style>
