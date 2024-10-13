<template>
  <LayoutLanding>
    <div class="container">
      <div class="row mt-10">
        <div class="col">
          <h2>Daftar <strong>Tiket</strong></h2>
        </div>
      </div>

      <div class="row mb-4">
        <div
          class="col-md-4 mt-4"
          v-for="product in products"
          :key="product.id"
        >
          <TicketProduct :product="product" />
        </div>
      </div>
    </div>
</LayoutLanding>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import TicketProduct from "@/components/TicketProduct.vue";
import axios from "axios";
import LayoutLanding from "../layouts/LayoutLanding.vue";

export default {
  name: "TicketView",
  components: {
    Navbar,
    TicketProduct,
    LayoutLanding,
  },
  data() {
    return {
      products: [],
    };
  },
  mounted() {
    axios
      .get("http://localhost:8000/api/tickets") 
      .then((response) => {
        this.products = response.data;  
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>
