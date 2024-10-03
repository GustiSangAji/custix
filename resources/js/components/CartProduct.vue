<template>
  <div class="card shadow card-product">
    <img :src="'media/tiket/' + product.gambar " class="card-img-top" alt="Product Image" />
    <div class="card-body">
      <h5 class="card-title">{{product.nama}}</h5>
      <p class="card-text">
        Harga : Rp. {{ product.harga }}
      </p>
      <router-link class="btn btn-success" :to="'/tiket/'+product.id">Pesan</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "CartProduct",
  props: ["product"],
};
</script>

<style>
</style>