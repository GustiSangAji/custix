<template>
    <div class="order-detail">
      <Navbar />
      <div class="container mt-4">
        <!-- Order Summary Card -->
        <div class="card shadow-lg rounded p-4 mb-4">
          <h2 class="text-center mb-4">Detail Transaksi</h2>
          <div class="row">
            <!-- Product Image -->
            <div class="col-md-4 text-center">
              <img
                :alt="order.product.name"
                :src="'/storage/' + order.product.image"
                class="img-fluid rounded shadow-sm"
              />
            </div>
  
            <!-- Transaction Details -->
            <div class="col-md-8">
              <h3>{{ order.product.name }}</h3>
              <p><i class="bi bi-calendar-event me-2"></i> {{ formatDate(order.product.datetime) }}</p>
              <p><i class="bi bi-geo-alt me-2"></i> {{ order.product.place }}</p>
  
              <hr />
  
              <!-- Order Information -->
              <h4 class="mt-4">Informasi Pemesanan</h4>
              <p>
                <strong>Jumlah Tiket:</strong> {{ order.jumlah_pemesanan }}<br />
                <strong>Harga per Tiket:</strong> Rp {{ formatCurrency(order.product.price) }}<br />
                <strong>Total Harga:</strong> Rp {{ formatCurrency(order.total_price) }}<br />
                <strong>Status Pembayaran:</strong> {{ order.payment_status ? "Sudah Dibayar" : "Belum Dibayar" }}
              </p>
  
              <!-- Payment Button -->
              <button class="btn btn-success w-100 mt-4" v-if="!order.payment_status" @click="payOrder">
                Bayar Sekarang
              </button>
              <p v-else class="text-success mt-3">Pembayaran telah berhasil!</p>
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
    name: "OrderDetail",
    components: {
      Navbar,
    },
    data() {
      return {
        order: {
          product: {},
          jumlah_pemesanan: 1,
          total_price: 0,
          payment_status: false,
        },
      };
    },
    methods: {
      setOrder(data) {
        this.order = data;
      },
      formatDate(date) {
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
      },
      formatCurrency(amount) {
        return amount.toLocaleString("id-ID", {
          style: "currency",
          currency: "IDR",
        }).replace('IDR', '').trim(); // Menghapus IDR agar sesuai format Rupiah lokal
      },
      payOrder() {
        // Logika pembayaran bisa dimasukkan di sini, seperti memanggil API Midtrans atau gateway lainnya
        axios
          .post("http://localhost:8000/api/payments", { order_id: this.order.id })
          .then((response) => {
            this.order.payment_status = true;
            this.$toast.success("Pembayaran Berhasil!", {
              type: "success",
              position: "top-right",
              duration: 3000,
              dismissible: true,
            });
          })
          .catch((error) => {
            this.$toast.error("Pembayaran Gagal", {
              type: "error",
              position: "top-right",
              duration: 3000,
              dismissible: true,
            });
            console.error(error);
          });
      },
    },
    mounted() {
      axios
        .get("http://localhost:8000/api/orders/" + this.$route.params.id)
        .then((response) => this.setOrder(response.data))
        .catch((error) => console.log(error));
    },
  };
  </script>
  