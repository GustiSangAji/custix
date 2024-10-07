<!-- src/components/BestConcert.vue -->
<template>
  <div class="d-flex justify-content-between align-items-center mt-10">
    <h2>Best <strong>Concert</strong></h2>
    <router-link to="/tiket" class="btn btn-success">
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
