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
                <label for="kewarganegaraan" class="form-label">Kewarganegaraan</label>
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
      <div class="col-md-4">
        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <img
                src="ticket.image" 
                alt="Event Image"
                class="img-thumbnail me-2"
                style="width: 50px"
              />
              <div class="flex-grow-1">
                <h5 class="card-title mb-0">Bernadya</h5>
              </div>
              <a href="#" class="text-primary">Detail</a>
            </div>
            <p class="card-text">
              <strong>Tiket: JKT-01</strong>
              <br />
              1 Tiket - Pax 2 <br />
              <span class="text-muted">
                Tanggal Dipilih: 10 November 2024
              </span>
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
              <p class="fw-bold">IDR 120000</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";


export default {
  name: "Keranjang",
  components: {
    Navbar,
  },
};
</script>
  