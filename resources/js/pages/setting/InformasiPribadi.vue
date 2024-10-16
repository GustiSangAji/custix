<template>
  <LayoutLanding>
    <div class="container-fluid py-4">
      <div class="row">
        <Sidebar />

        <!-- MainContent -->

        <InformasiPribadiContent />
      </div>
    </div>
  </LayoutLanding>
  
</template>

<script>
  import LayoutLanding from "@/layouts/LayoutLanding.vue";
  import Sidebar from "@/components/sidebar/Sidebar.vue";
  import InformasiPribadiContent from "@/components/setting/informasi/InformasiPribadiContent.vue";

  export default {
    name: "InformasiPribadi",
    components: {
      Sidebar,
      LayoutLanding,
      InformasiPribadiContent,
    },
  };
</script>