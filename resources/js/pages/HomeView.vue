<!-- src/views/HomeView.vue -->
<template>
  <LayoutLanding>
    <div class="container mt-4">
      <Carousel />
      <BestConcert />
      <div class="row">
        <HomeCard />
        <HomeCard />
        <!-- Tambahkan lebih banyak HomeCard jika diperlukan -->
      </div>
    </div>
  </LayoutLanding>
</template>


<script>
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import Carousel from "@/components/Carousel.vue";
import BestConcert from "@/components/BestConcert.vue";
import HomeCard from "@/components/HomeCard.vue";

export default {
  name: "HomeView",
  components: {
    LayoutLanding,
    Carousel,
    BestConcert,
    HomeCard,
  },
};
</script>

<style scoped>
/* Tambahkan style khusus untuk HomeView jika perlu */
</style>
