<template>
  <div class="layout-landing">
    <Navbar />
    <main>
      <!-- Tempat konten seperti Carousel dan komponen lainnya -->
      <slot></slot>
    </main>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";

export default {
  name: "LayoutLanding",
  components: {
    Navbar,
  },
};
</script>

<style scoped>

</style>
