<template>
  <div class="card shadow-sm rounded overflow-hidden">
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
import { useRouter } from "vue-router"; // Vue Router untuk redirect

export default {
  name: "HomeCard",
  props: {
    ticket: {
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
      if (this.ticket.image && typeof this.ticket.image === "string") {
        return `/storage/${this.ticket.image}`;
      }
      return "/images/default-ticket.jpg"; // Gambar default jika tidak ada gambar
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
      try {
        const response = await axios.get("/waiting-room-status");
        const { accessGranted } = response.data;

        if (accessGranted) {
          this.router.push(`/tiket/${this.ticket.id}`); // Arahkan ke halaman tiket jika ada akses
        } else {
          alert(
            "Kamu sedang berada dalam waiting room. Harap menunggu sampai giliranmu tiba."
          );
          this.router.push({
            path: "/waiting-room",
            query: { id: this.ticket.id },
          }); // Kirim ID tiket melalui query parameter
        }
      } catch (error) {
        console.error("Error checking access:", error);
        alert(
          "Terjadi kesalahan saat mengecek akses. Silakan coba lagi nanti."
        );
      }
    },
  },
};
</script>
