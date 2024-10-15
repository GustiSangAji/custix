<template>
  <LayoutLanding>
    <div class="container mt-8">
      <Carousel />
      <BestConcert />
      <div class="row mt-6">
        <div class="col-md-6 mb-4" v-for="ticket in tickets" :key="ticket.id">
          <HomeCard :ticket="ticket" />
        </div>
      </div>
      <div v-if="tickets.length === 0" class="text-center mt-5">
        <p>Tidak ada tiket yang tersedia saat ini.</p>
      </div>
    </div>
  </LayoutLanding>
</template>

<script>
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import Carousel from "@/components/Carousel.vue";
import BestConcert from "@/components/BestConcert.vue";
import HomeCard from "@/components/HomeCard.vue";
import axios from "axios";

export default {
  name: "HomeView",
  components: {
    LayoutLanding,
    Carousel,
    BestConcert,
    HomeCard,
  },
  data() {
    return {
      tickets: [], // Data tiket
    };
  },
  mounted() {
    this.fetchTickets();
  },
  methods: {
    fetchTickets() {
      axios
        .get("http://localhost:8000/api/tickets/limited")
        .then((response) => {
          this.tickets = response.data;
        })
        .catch((error) => {
          console.error("Error fetching tickets:", error);
        });
    },
  },
};
</script>
