<template>
  <div class="card shadow-sm rounded overflow-hidden">
    <img :src="imageUrl" class="card-img-top" :alt="ticket.name" v-if="imageUrl" />
    <div class="card-body p-4">
      <h5 class="card-title fw-bold text-uppercase mb-3">{{ ticket.name }}</h5>
      <p class="card-text fs-6 mb-3 text-muted">
        <i class="bi bi-calendar me-2"></i> {{ formatDate(ticket.datetime) }} &nbsp;
        <i class="bi bi-geo-alt me-2"></i> {{ ticket.place }}
      </p>
      <div class="d-flex justify-content-between align-items-center">
        <div class="bg-success fw-bold text-light px-3 py-2 rounded">
          Mulai Dari Rp. {{ ticket.price }}
        </div>
        <router-link :to="'/waiting-room/'" class="btn btn-danger px-4 py-2 fw-bold">
          Beli Tiket
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HomeCard",
  props: {
    ticket: {
      type: Object,
      required: true,
    },
  },
  computed: {
    imageUrl() {
      if (this.ticket.image && typeof this.ticket.image === 'string') {
        return `/storage/${this.ticket.image}`; // Pastikan format path sesuai
      }
      return ''; // Mengembalikan string kosong jika tidak ada gambar
    },
  },
  methods: {
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
  },
};
</script>
