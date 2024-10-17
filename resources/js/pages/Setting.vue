<template>
  <LayoutLanding>
    <div class="container mt-8">
      <div class="row">

        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
        <router-view /> <!-- Tempat untuk merender komponen anak -->
        
      </div>
    </div>
  </LayoutLanding>
</template>

<script>
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import Sidebar from "@/components/sidebar/Sidebar.vue";

export default {
  name: "Setting",
  components: {
    LayoutLanding,
    Sidebar,
  },
};
</script>
