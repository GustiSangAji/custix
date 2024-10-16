<template>
  <LayoutLanding>
    <div class="container-fluid py-4">
      <div class="row">

        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
         <TiketSaya />
        
      </div>
    </div>
  </LayoutLanding>
</template>

<script>
import LayoutLanding from "@/layouts/LayoutLanding.vue";
import Sidebar from "@/components/sidebar/Sidebar.vue";
import TiketSaya from "@/components/tiket/TiketSaya.vue";

export default {
  name: "Setting",
  components: {
    LayoutLanding,
    Sidebar,
    TiketSaya,
  },
};
</script>

<style scoped>
/* Tidak perlu styling tambahan */
</style>
