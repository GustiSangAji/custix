<template>
  <div id="kt_carousel_3_carousel" class="carousel carousel-custom slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
        <li v-for="(item, index) in carouselItems" :key="item.id" :data-bs-target="'#kt_carousel_3_carousel'" :data-bs-slide-to="index" :class="{'active': index === 0}" class="ms-1"></li>
      </ol>
    </div>

    <div class="carousel-inner rounded shadow-sm">
      <div v-for="(item, index) in carouselItems" :key="item.id" :class="['carousel-item', { active: index === 0 }]">
        <img :src="item.image" class="d-block w-100 img-fluid" :alt="item.alt">
      </div>
    </div>
  </div>
</template>

<script>
import { nextTick, onMounted, ref } from 'vue';
import { initializeComponents } from "@/core/plugins/keenthemes";
import axios from 'axios';

export default {
  name: "Carousel",
  setup() {
    const carouselItems = ref([]);

    const fetchCarouselItems = async () => {
      try {
        const response = await axios.get('/setting'); // Mengambil data dari endpoint Laravel
        const setting = response.data;

        // Menambahkan gambar carousel ke dalam array
        carouselItems.value = [
          { id: 1, image: setting.carousel1, alt: 'Gambar Carousel 1' },
          { id: 2, image: setting.carousel2, alt: 'Gambar Carousel 2' },
          { id: 3, image: setting.carousel3, alt: 'Gambar Carousel 3' },
        ].filter(item => item.image); // Filter hanya gambar yang ada
      } catch (error) {
        console.error("Error fetching carousel items:", error);
      }
    };

    onMounted(() => {
      nextTick(() => {
        fetchCarouselItems();
        initializeComponents();
        console.log("Bootstrap components initialized");
      });
    });

    return {
      carouselItems
    };
  }
};
</script>

<style scoped>
/* Tambahkan custom styling di sini jika diperlukan */
</style>