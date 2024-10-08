<template>
  <div class="card shadow-sm rounded overflow-hidden">
    <img
      :src="imageUrl"
      class="card-img-top"
      :alt="product.name"
    />
    <div class="card-body p-4">
      <!-- Padding kiri dan kanan ditambah menggunakan px-4 -->
      <h5 class="card-title fw-bold text-uppercase mb-3">{{ product.name }}</h5>
      <p class="card-text text-muted mb-3">
        <i class="bi bi-calendar-event"></i> {{ formattedDate }}<br />
        <i class="bi bi-geo-alt"></i> {{ product.place }}
      </p>
      <router-link :to="'/tiket/'+product.id" class="btn btn-danger w-100 fw-bold">BELI TIKET</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "TicketProduct",
  props: ["product"],
  computed: {
    imageUrl() {
      return `http://localhost:8000/storage/${this.product.image}`;
    },
    formattedDate() {
      const date = new Date(this.product.datetime);
      return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
      });
    },
  },
};
</script>
