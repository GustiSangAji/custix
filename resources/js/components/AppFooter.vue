<template>
  <footer class="mt-10 py-5 text-white">
    <div class="container">
      <div class="row text-justify">
        <!-- Kolom CusTix.id -->
        <div class="col-md-4 col-12 mb-4">
          
          <div class="text-box"> <!-- Tambahkan div pembungkus untuk gaya -->
            <h4 class="text-primary mb-3">CusTix.id</h4>
            <p>
              CusTix adalah platform digital yang menyediakan Ticketing Management Service (TMS) untuk mendukung penjualan tiket hingga laporan acara. Kami juga menawarkan penjualan tiket konser musik terbaru dengan proses yang aman, mudah, dan cepat. Hubungi
              <router-link to="mailto:event@dewatiket.id" class="text-active-primary">event@CusTix.id</router-link> untuk mulai berjualan.
            </p>
          </div>
        </div>
        <!-- Kolom Tentang CusTix -->
        <div class="col-md-2 col-6 mb-4 text-box">
          <h5 class="text-white mb-3">Tentang CusTix</h5>
          <ul class="list-unstyled">
            <li><router-link to="/about-us" class="text-white">About Us</router-link></li>
            <li><router-link to="/metode-pembayaran" class="text-white">Metode Pembayaran</router-link></li>
            <li><router-link to="/surat-kuasa" class="text-white">Surat Kuasa</router-link></li>
            <li><router-link to="/syarat-ketentuan" class="text-white">Syarat dan Ketentuan</router-link></li>
            <li><router-link to="/kebijakan-privasi" class="text-white">Kebijakan Privasi</router-link></li>
            <li><router-link to="/hubungi-kami" class="text-white">Hubungi Kami</router-link></li>
          </ul>
        </div>
        <!-- Kolom Jadwal Konser Terbaru -->
        <div class="col-md-3 col-6 mb-4 text-box">
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
        <!-- Kolom Fitur -->
        <div class="col-md-3 col-6 mb-4">
          <h5 class="text-white mb-3">Fitur</h5>
          <ul class="list-unstyled">
            <li><router-link to="/fitur/orderflex" class="text-white">CusTix OrderFlex</router-link></li>
            <li><router-link to="/fitur/widget" class="text-white">Widget CusTix</router-link></li>
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
.text-justify {
  text-align: justify; /* Mengatur teks untuk diratakan */
  letter-spacing: 0.5px; /* Menambahkan jarak antar huruf untuk membuat teks lebih rapi */
  line-height: 1.6; /* Mengatur tinggi garis untuk spasi antar baris */
  word-spacing: 1px; /* Menambahkan jarak antar kata untuk meratakan teks */
}

.text-box {
  max-width: 600px; /* Menentukan lebar maksimum untuk teks */
  margin: 0 auto; /* Mengatur margin auto untuk memusatkan */
  border-radius: 8px; /* Membuat sudut kotak menjadi bulat */
}

@media (max-width: 576px) {
  .text-justify {
    text-align: left; /* Nonaktifkan justify pada tampilan kecil */
  }
  
  .col-md-4 {
    flex: 0 0 100%; /* Mengatur kolom CusTix.id menjadi 100% untuk tampilan kecil */
    max-width: 100%; /* Memastikan kolom tidak lebih dari 100% */
  }
  .col-md-2, .col-md-3 {
    flex: 0 0 50%; /* Mengatur kolom Tentang CusTix dan Fitur menjadi 50% */
    max-width: 50%; /* Memastikan kolom tidak lebih dari 50% */
  }
  
  h5 {
    margin-bottom: 0.2rem; /* Mengurangi margin bawah pada judul */
  }
  
  ul {
    margin-top: 0; /* Menghapus margin atas pada daftar */
    padding-left: 0; /* Menghapus padding pada list */
  }
}

/* Penyesuaian untuk ukuran layar kecil */
@media (max-width: 357px) {
  h5 {
    font-size: 1rem; /* Menyesuaikan ukuran font untuk header */
  }

  .mb-3 {
    margin-bottom: 0.5rem; /* Mengurangi margin bawah */
  }

  .mb-4 {
    margin-bottom: 0.5rem; /* Mengurangi margin bawah pada kolom */
  }

  p {
    margin-bottom: 0.5rem; /* Mengurangi margin bawah pada paragraf */
  }
}
</style>

