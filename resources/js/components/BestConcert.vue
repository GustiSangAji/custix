<!-- src/components/BestConcert.vue -->
<template>
  <div class="d-flex justify-content-between align-items-center mt-10 mb-10">
    <h2>Konser Terlaris</h2>
    <router-link to="/tiket" class="btn btn-active-dark fw-bold">
      Lihat semua
    </router-link>
  </div>
</template>

<script>
export default {
  name: "BestConcert",
};
</script>

<style scoped>
/* Tambahkan styling di sini jika perlu */
</style>
