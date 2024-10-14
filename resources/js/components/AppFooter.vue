<template>
  <footer class="mt-10 py-5">
    <div class="container">
      <div class="row">
        <!-- Logo dan Deskripsi -->
        <div class="col-md-4 mb-4">
          <h4 class="text-primary mb-3">CusTix.id</h4>
          <p class="text-white">
            CusTix adalah platform digital yang menyediakan Ticketing Management Service (TMS) untuk mendukung penjualan tiket hingga laporan acara. Kami juga menawarkan penjualan tiket konser musik terbaru dengan proses yang aman, mudah dan cepat. Hubungi
            <router-link to="mailto:event@dewatiket.id" class="text-active-primary">event@CusTix.id</router-link> untuk mulai berjualan.
          </p>
        </div>
        <!-- Tentang Dewatiket -->
        <div class="col-md-2 mb-4">
          <h5 class="text-white mb-3">Tentang Dewatiket</h5>
          <ul class="list-unstyled">
            <li><router-link to="/about-us" class="text-white">About Us</router-link></li>
            <li><router-link to="/metode-pembayaran" class="text-white">Metode Pembayaran</router-link></li>
            <li><router-link to="/surat-kuasa" class="text-white">Surat Kuasa</router-link></li>
            <li><router-link to="/syarat-ketentuan" class="text-white">Syarat dan Ketentuan</router-link></li>
            <li><router-link to="/kebijakan-privasi" class="text-white">Kebijakan Privasi</router-link></li>
            <li><router-link to="/pengembalian-dana" class="text-white">Pengembalian Dana</router-link></li>
            <li><router-link to="/hubungi-kami" class="text-white">Hubungi Kami</router-link></li>
          </ul>
        </div>
        <!-- Jadwal Konser Terbaru -->
        <div class="col-md-3 mb-4">
          <h5 class="text-white mb-3">Jadwal Konser Terbaru</h5>
          <ul class="list-unstyled">
            <li><router-link to="/jadwal-konser/dewa-19" class="text-white">Dewa 19</router-link></li>
            <li><router-link to="/jadwal-konser/ndx-aka" class="text-white">NDX AKA</router-link></li>
            <li><router-link to="/jadwal-konser/nadin-amizah" class="text-white">Nadin Amizah</router-link></li>
            <li><router-link to="/jadwal-konser/maliq-dessentials" class="text-white">Maliq & D'Essentials</router-link></li>
            <li><router-link to="/jadwal-konser/juicy-luicy" class="text-white">Juicy Luicy</router-link></li>
            <li><router-link to="/jadwal-konser/semua-artis" class="text-white">Semua Artis</router-link></li>
          </ul>
        </div>
        <!-- Fitur -->
        <div class="col-md-3 mb-4">
          <h5 class="text-white mb-3">Fitur</h5>
          <ul class="list-unstyled">
            <li><router-link to="/fitur/orderflex" class="text-white">Dewatiket OrderFlex</router-link></li>
            <li><router-link to="/fitur/widget" class="text-white">Widget Dewatiket</router-link></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterComponent",
};
</script>

<style scoped>
/* Tidak ada style tambahan karena semua menggunakan Bootstrap */
</style>
