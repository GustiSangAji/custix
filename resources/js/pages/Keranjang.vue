<template>
  <Navbar />
  <div class="container mt-10">
    <div class="row">
      <!-- Form Pemesanan -->
      <div class="col-md-8">
        <h5 class="card-title">Detail Pemesanan</h5>
        <p class="card-text">
          Isi formulir ini dengan benar karena e-tiket akan dikirim ke alamat
          email sesuai data pemesan.
        </p>
        <div class="card">
          <div class="card-body">
            <form>
              <!-- Nama Lengkap -->
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input
                  type="text"
                  class="form-control"
                  id="nama"
                  placeholder="Nama Lengkap"
                  value="Dian Rizky Pratama"
                />
              </div>

              <!-- Nomor Ponsel -->
              <div class="mb-3">
                <label for="nomorPonsel" class="form-label">Nomor Ponsel</label>
                <input
                  type="text"
                  class="form-control"
                  id="nomorPonsel"
                  placeholder="+62"
                  value="+62 89501215795"
                />
              </div>

              <!-- Alamat Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="you@example.com"
                  value="rizdian229@gmail.com"
                />
              </div>

              <!-- Kewarganegaraan -->
              <div class="mb-3">
                <label for="kewarganegaraan" class="form-label"
                  >Kewarganegaraan</label
                >
                <select class="form-select" id="kewarganegaraan">
                  <option selected>Pilih kewarganegaraan</option>
                  <option value="1">Indonesia</option>
                  <option value="2">Lainnya</option>
                </select>
                <div class="text-danger">Isi kewarganegaraan dulu, ya.</div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Detail Tiket -->
      <div class="col-md-4" v-for="(item, index) in keranjang" :key="index">
        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <img
                :src="item.image_url"
                alt="Event Image"
                class="img-thumbnail me-2"
                style="width: 50px"
              />
              <div class="flex-grow-1">
                <h5 class="card-title mb-0">{{ item.event_name }}</h5>
              </div>
              <a href="#" class="text-primary">Detail</a>
            </div>
            <p class="card-text">
              <strong>{{ item.ticket_type }}</strong
              ><br />
              {{ item.jumlah_pemesanan }} Tiket • Pax {{ item.pax }}<br />
              <span class="text-muted"
                >Tanggal Dipilih: {{ item.selected_date }}</span
              >
            </p>

            <ul class="list-unstyled">
              <li><i class="bi bi-x-circle-fill"></i> Tidak bisa refund</li>
              <li><i class="bi bi-check-circle-fill"></i> Konfirmasi Instan</li>
              <li>
                <i class="bi bi-ticket-fill"></i> Kursi tersedia saat penukaran
                tiket
              </li>
              <li>
                <i class="bi bi-calendar-check-fill"></i> Berlaku di tanggal
                terpilih
              </li>
            </ul>

            <hr />

            <div class="d-flex justify-content-between align-items-center">
              <h6>Total Pembayaran</h6>
              <p class="fw-bold">IDR {{ item.total_harga }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import axios from "axios";

export default {
  name: "Keranjang",
  components: {
    Navbar,
  },
  data() {
    return {
      keranjang: [], // Untuk menyimpan data keranjang
    };
  },
  mounted() {
    // Panggil API saat halaman dimuat
    this.ambilDataKeranjang();
  },
  methods: {
  async ambilDataKeranjang() {
    try {
      const response = await axios.get("http://localhost:8000/api/keranjangs");
      this.keranjang = response.data;
    } catch (error) {
      console.error("Gagal mengambil data keranjang", error);
    }
  },
},


};
</script>