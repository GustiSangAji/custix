<template>
  <div id="kt_carousel_1_carousel" class="carousel carousel-custom slide" data-bs-ride="carousel" data-bs-interval="8000">
    <!--begin::Heading-->
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <!--begin::Carousel Indicators-->
      <ol class="p-0 m-0 carousel-indicators carousel-indicators-dots">
        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="0" class="ms-1 active"></li>
        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="1" class="ms-1"></li>
        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="2" class="ms-1"></li>
      </ol>
      <!--end::Carousel Indicators-->
    </div>
    <!--end::Heading-->

    <!--begin::Carousel-->
    <div class="carousel-inner rounded shadow-sm">
      <!--begin::Item-->
      <div class="carousel-item active">
        <img src="/media/carousel/closing-ceremony.png" class="d-block w-100 img-fluid" alt="Carousel Slide 1">
      </div>
      <!--end::Item-->

      <!--begin::Item-->
      <div class="carousel-item">
        <img src="/media/carousel/langit-dewa19.png" class="d-block w-100 img-fluid" alt="Carousel Slide 2">
      </div>
      <!--end::Item-->

      <!--begin::Item-->
      <div class="carousel-item">
        <img src="/media/carousel/swara-semesta-menyapa-tulungagung.jpg" class="d-block w-100 img-fluid" alt="Carousel Slide 3">
      </div>
      <!--end::Item-->
    </div>
    <!--end::Carousel-->
  </div>
</template>

<script>
import { nextTick, onMounted } from 'vue';
import { initializeComponents } from "@/core/plugins/keenthemes"; // Sesuaikan path jika perlu

export default {
  name: "Carousel",
  mounted() {
    onMounted(() => {
      nextTick(() => {
        initializeComponents(); // Panggil fungsi ini untuk inisialisasi komponen Bootstrap, termasuk Carousel
        // Kamu bisa menambahkan log jika ingin memastikan fungsi ini bekerja
        console.log("Bootstrap components initialized");
      });
    });
  }
};
</script>

<!-- Custom CSS -->
<style scoped>
/* Tambahkan custom styling di sini jika diperlukan */
</style>
