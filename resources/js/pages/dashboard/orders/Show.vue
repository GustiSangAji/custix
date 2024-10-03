<template>
  <div>
    <!-- Header -->
    <div
      class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow"
    >
      <h5 class="my-0 mr-md-auto font-weight-normal">Lvl Midtrans</h5>
    </div>

    <!-- Order Information -->
    <div class="container pb-5 pt-5">
      <div class="row">
        <div class="col-12 col-md-8">
          <div class="card shadow">
            <div class="card-header">
              <h5>Data Order</h5>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-condensed">
                <tr>
                  <td>ID</td>
                  <td>
                    <b>#{{ order.number }}</b>
                  </td>
                </tr>
                <tr>
                  <td>Total Harga</td>
                  <td>
                    <b>Rp {{ formatPrice(order.total_price) }}</b>
                  </td>
                </tr>
                <tr>
                  <td>Status Pembayaran</td>
                  <td>
                    <b>{{ getPaymentStatus(order.payment_status) }}</b>
                  </td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>
                    <b>{{ formatDate(order.created_at) }}</b>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Payment Information -->
        <div class="col-12 col-md-4">
          <div class="card shadow">
            <div class="card-header">
              <h5>Pembayaran</h5>
            </div>
            <div class="card-body">
              <button
                v-if="order.payment_status === 1"
                class="btn btn-primary"
                @click="payNow"
              >
                Bayar Sekarang
              </button>
              <span v-else>Pembayaran berhasil</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script lang="ts">
import { defineComponent } from "vue";

interface Order {
  id: number;
  number: string;
  total_price: number;
  payment_status: number; // 1: Menunggu Pembayaran, 2: Sudah Dibayar, 3: Kadaluarsa
  created_at: Date;
}

export default defineComponent({
  data() {
    return {
      order: {
        id: 1,
        number: "001",
        total_price: 50000,
        payment_status: 1, // 1: Menunggu Pembayaran, 2: Sudah Dibayar, 3: Kadaluarsa
        created_at: new Date(),
      } as Order,
      snapToken: "YOUR_SNAP_TOKEN", // Snap token from Midtrans
    };
  },
  methods: {
    formatPrice(value: number): string {
      return value.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
      });
    },
    formatDate(date: Date): string {
      const options: Intl.DateTimeFormatOptions = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    getPaymentStatus(status: number): string {
      if (status === 1) return "Menunggu Pembayaran";
      if (status === 2) return "Sudah Dibayar";
      return "Kadaluarsa";
    },
    payNow() {
      // @ts-ignore
      window.snap.pay(this.snapToken, {
        onSuccess: (result: any) => {
          console.log("Success:", result);
        },
        onPending: (result: any) => {
          console.log("Pending:", result);
        },
        onError: (result: any) => {
          console.log("Error:", result);
        },
      });
    },
  },
  mounted() {
    const script = document.createElement("script");
    script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    script.setAttribute("data-client-key", "YOUR_MIDTRANS_CLIENT_KEY"); // Replace with your actual client key
    document.body.appendChild(script);
  },
});
</script>