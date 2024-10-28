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
                <div
                  v-for="order in orders"
                  :key="order.id"
                  class="ticket mb-6 rounded d-flex flex-column flex-md-row p-6 align-self-start w-100"
                >
                  <div class="ticket-info flex-grow-1">
                    <h5 class="event-name font-weight-bold mb-4 fs-4">
                      {{ order.ticket.name }}
                    </h5>
                    <h3 class="text-muted mb-2 fw-normal fs-6">
                      {{ order.ticket.datetime }} |
                      {{ order.jumlah_pemesanan }} Tiket
                    </h3>
                    <router-link
                      :to="{ name: 'OrderDetail', params: { id: order.id } }"
                      class="btn btn-primary mt-6"
                    >
                      Lihat Detail
                    </router-link>
                  </div>
                  <div
                    class="ticket-image d-flex justify-content-center align-items-center me-1 mb-3 mb-md-0"
                  >
                    <img
                      :src="'/storage/' + order.ticket.image"
                      alt="Event Image"
                      class="rounded"
                      style="width: 200px; height: 100px; object-fit: cover"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div
                class="d-flex flex-column align-items-center justify-content-center mt-4"
              >
                <img
                  src="/media/icons/ticket_tiket-saya.png"
                  alt="Empty Ticket"
                  class="img-fluid mb-6"
                  style="width: 100px; height: auto"
                />
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
        .get(`http:/192.168.61.123/api/user/orders`)
        .then((response) => {
          this.orders = response.data;
        })
        .catch((error) => {
          console.error(
            "Terjadi kesalahan saat mengambil data pesanan:",
            error
          );
        });
    },
  },
};
</script>

<style scoped>
.ticket {
  background-color: #1b1c22;
  overflow: hidden;
}
</style>
