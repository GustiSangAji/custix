<template>
    <div class="orders">
      <Navbar />
      <div class="container mt-4">
        <!-- Alert success order created -->
        <div v-if="orderCreated" class="alert alert-success" role="alert">
          <strong>Order berhasil dibuat!</strong> Order ID: {{ order.order_id }}
        </div>
  
        <!-- Order Details -->
        <div class="card shadow-sm p-4">
          <h2 class="text-center mb-4">Detail Pemesanan</h2>
  
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Nama Event:</strong> {{ product.name }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Jumlah Tiket:</strong> {{ order.jumlah_pemesanan }}</p>
            </div>
          </div>
  
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Total Harga:</strong> IDR {{ formatPrice(order.total_price) }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Status Pembayaran:</strong> {{ paymentStatus(order.payment_status) }}</p>
            </div>
          </div>
  
          <div class="text-center">
            <router-link to="/" class="btn btn-primary">Kembali ke Beranda</router-link>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from "@/components/Navbar.vue";
  import axios from "axios";
  
  export default {
    name: "Orders",
    components: {
      Navbar,
    },
    data() {
      return {
        order: {},
        product: {},
        orderCreated: false,
      };
    },
    methods: {
      formatPrice(value) {
        return value.toLocaleString("id-ID", {
          style: "currency",
          currency: "IDR",
        });
      },
      paymentStatus(status) {
        switch (status) {
          case "1":
            return "Menunggu Pembayaran";
          case "2":
            return "Sudah Dibayar";
          case "3":
            return "Kadaluarsa";
          default:
            return "Tidak Diketahui";
        }
      },
      fetchOrderDetails() {
        const orderId = this.$route.query.id; // Mengambil ID order dari query params
  
        axios
          .get(`http://localhost:8000/api/orders/${orderId}`)
          .then((response) => {
            this.order = response.data;
            this.orderCreated = true;
  
            // Mengambil data produk berdasarkan ID produk dari order
            return axios.get(`http://localhost:8000/api/tickets/${this.order.product_id}`);
          })
          .then((response) => {
            this.product = response.data;
          })
          .catch((error) => {
            console.error("Error fetching order details:", error);
          });
      },
    },
    mounted() {
      this.fetchOrderDetails();
    },
  };
  </script>
  
  <style scoped>
  .orders {
    padding: 20px;
  }
  
  .card {
    border-radius: 8px;
  }
  </style>
  