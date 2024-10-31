<script setup lang="ts">
import { nextTick, onBeforeMount, onMounted } from "vue";
import { RouterView, useRoute } from "vue-router";
import { useConfigStore } from "@/stores/config";
import { useThemeStore } from "@/stores/theme";
import { useBodyStore } from "@/stores/body";
import { themeConfigValue } from "@/layouts/default-layout/config/helper";
import { initializeComponents } from "@/core/plugins/keenthemes";

const configStore = useConfigStore();
const themeStore = useThemeStore();
const bodyStore = useBodyStore();
const route = useRoute(); // Menggunakan useRoute untuk akses objek route

onBeforeMount(() => {
    configStore.overrideLayoutConfig();
    themeStore.setThemeMode(themeConfigValue.value);
});

onMounted(() => {
    nextTick(() => {
        initializeComponents();
        bodyStore.removeBodyClassName("page-loading");
    });
});
</script>

<template>
    <router-view :key="route.fullPath"></router-view> <!-- Gunakan route.fullPath sebagai key -->
</template>


<style lang="scss">
@import "bootstrap-icons/font/bootstrap-icons.css";
@import "apexcharts/dist/apexcharts.css";
@import "animate.css";
@import "sweetalert2/dist/sweetalert2.css";
@import "nouislider/dist/nouislider.css";
@import "@fortawesome/fontawesome-free/css/all.min.css";
@import "socicon/css/socicon.css";
@import "line-awesome/dist/line-awesome/css/line-awesome.css";
@import "@vueform/multiselect/themes/default.css";
@import "prism-themes/themes/prism-shades-of-purple.css";
@import "element-plus/dist/index.css";

// Main demo style scss
@import "assets/keenicons/duotone/style.css";
@import "assets/keenicons/outline/style.css";
@import "assets/keenicons/solid/style.css";
@import "assets/sass/element-ui.dark";
@import "assets/sass/plugins";
@import "assets/sass/style";

#app {
    display: contents;
}
</style>
