<template>
  <div
          class="col-md-3 col-lg-2 bg-dark text-white vh-100 sticky-top py-3 fs-5 fw-bold"
        >
          <ul class="list-group list-group-flush">
            <router-link
              to="/informasi-pribadi"
              class="list-group-item list-group-item-action bg-dark text-white mb-5 mt-5"
              >Informasi Pribadi</router-link
            >
            <router-link
              to="/tiketsaya"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Tiket Saya</router-link
            >
            <router-link
              to="/transaksi"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Transaksi</router-link
            >
            <li class="menu-item px-5 my-10"></li>
            <router-link
              to="/tentang"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Tentang CusTix</router-link
            >
            <router-link
              to="/syarat-ketentuan"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Syarat & Ketentuan</router-link
            >
            <router-link
              to="/kebijakan-privasi"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Kebijakan Privasi</router-link
            >
            <router-link
              to="/pengembalian-dana"
              class="list-group-item list-group-item-action bg-dark text-white mb-5"
              >Pengembalian Dana</router-link
            >
          </ul>
        </div>
</template>

<script>
export default {
  name: "Sidebar",
};
</script>

<style scoped>
/* Tambahkan styling jika diperlukan */
</style>
