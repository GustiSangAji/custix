<template>
  <div class="col-md-8 col-lg-9">
    <div class="card mb-5 mb-xl-10">
      <div class="card-header border-0 cursor-pointer">
        <div class="card-title m-0">
          <h3 class="fw-bold m-0">Tiket Saya</h3>
        </div>
      </div>
      <div class="collapse show">
        <div class="card">
          <div class="card-body">
            <div v-if="orders.length > 0">
              <div class="d-flex flex-column align-items-start">
                <!-- Mengulangi pesanan berdasarkan jumlah pemesanan -->
                <div v-for="order in orders" :key="order.id" class="col-12 mb-4">
                  <div v-for="(ticketDetail, index) in order.ticket_details" :key="ticketDetail.ticket_number" class="ticket mb-6 rounded d-flex p-6 align-items-center justify-content-between w-100">
                    <!-- Konten sebelah kiri -->
                    <div class="ticket-info flex-grow-1">
                      <h5 class="event-name font-weight-bold mb-4 fs-4">
                        {{ order.ticket.name }}
                      </h5>

                      <h3 class="text-muted mb-2 fw-normal fs-6">
                        {{ formatDate(order.ticket.datetime) }} | {{ order.jumlah_pemesanan }} Tiket
                      </h3>

                      <h3 class="text-muted mb-2 fw-normal fs-6">
                        Pembelian pada {{ formatDate(order.ticket.created_at) }}
                      </h3>

                      <!-- Badge dan tombol dalam satu kolom vertikal -->
                      <div class="d-flex flex-column align-items-start mt-3">
                        <!-- Badge Status -->
                        <span
                          :class="[
                            'badge',
                            ticketDetail.status === 'Used' ? 'badge-light-danger' : 'badge-light-success'
                          ]"
                          class="mb-4"
                        >
                          {{ ticketDetail.status === 'Used' ? 'Sudah Digunakan' : 'Belum Digunakan' }}
                        </span>

                        <!-- Tombol Lihat Detail -->
                        <router-link
                          :to="{ name: 'OrderDetail', params: { id: order.id, qrIndex: index } }"
                          class="btn btn-light-primary mt-2"
                        >
                          Lihat Detail
                        </router-link>
                      </div>
                    </div>
                    <!-- Gambar di sebelah kanan -->
                    <div class="ticket-image d-flex justify-content-center align-items-center ms-4">
                      <img :src="'/storage/' + order.ticket.image" alt="Event Image" class="rounded" style="width: 400px; height: 200px; object-fit: cover" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div class="d-flex flex-column align-items-center justify-content-center mt-4">
                <img src="/media/icons/ticket_tiket-saya.png" alt="Empty Ticket" class="img-fluid mb-6" style="width: 100px; height: auto" />
                <p class="text-muted fs-4 fw-bold">
                  Kamu belum memiliki tiket, silakan beli tiket terlebih dahulu.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
import axios from "axios";

export default {
  name: "TiketSaya",
  data() {
    return {
      orders: [],
    };
  },
  mounted() {
    this.getOrders();
  },
  methods: {
    getOrders() {
      axios
        .get(`/user/orders`)
        .then((response) => {
          this.orders = response.data;
        })
        .catch((error) => {
          console.error("Terjadi kesalahan saat mengambil data pesanan:", error);
        });
    },
    formatDate(date) {
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    },
    formatShortDate(dateString) {
        const options = {
            weekday: "short",
            year: "numeric",
            month: "short",
            day: "numeric",
        };
        return new Date(dateString).toLocaleDateString("id-ID", options);
    },
  },
};
</script>

<style scoped>
.ticket {
  background-color: #1b1c22;
  overflow: hidden;
}
.col-12.mb-4 {
  margin-bottom: 1rem;
}
</style>
