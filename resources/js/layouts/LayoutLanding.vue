<template>
  <div class="layout-landing">
    <Navbar />
    <main>
      <!-- Tempat konten seperti Carousel dan komponen lainnya -->
      <slot></slot>
      <AppFooter />
    </main>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import AppFooter from "@/components/AppFooter.vue";

export default {
  name: "LayoutLanding",
  components: {
    Navbar,
    AppFooter,
  },
};
</script>

<style scoped>

</style>
