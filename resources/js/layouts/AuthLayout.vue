<template>
    <!--begin::Authentication Layout -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div
            class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1"
        >
            <!--begin::Form-->
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10">
                    <router-view></router-view>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Form-->

            <!--begin::Footer-->
            <div class="d-flex flex-center flex-wrap px-5"></div>
            <!--end::Footer-->
        </div>
        <!--end::Body-->

        <!--begin::Aside-->
        <div
            class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
            :style="`background-image: url('/media/misc/banar.jpg');`"
        >
            <!--begin::Content-->
            <div
                class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100"
            >
                <!--begin::Logo-->
                <router-link to="/" class="mb-0 mb-lg-12"></router-link>
                <!--end::Logo-->

                <!--begin::Image-->
                <img
                    class="d-none d-lg-block mx-auto w-50 mb-10 mb-lg-20"
                    src="/media/misc/aminkan.png"
                    alt=""
                />

                <!--end::Image-->

                <!--begin::Title-->
                <h1
                    class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7"
                >
                    Custix
                </h1>
                <!--end::Title-->

                <!--begin::Text-->
                <div class="d-none d-lg-block text-white fs-base text-center">
                    Welcome to **Custix**, tempat lo dapetin tiket keren buat
                    acara seru! 🎟️ Di sini, kita gak cuma jualan tiket, tapi
                    bikin pengalaman lo dapetin tiket jadi lebih mudah dan
                    cepat.
                    <a
                        href="#"
                        class="opacity-75-hover text-warning fw-bold me-1"
                        >Explore event</a
                    >
                    seru yang lagi happening sekarang! Gak cuma nonton, lo juga
                    bisa tau lebih lanjut tentang artis, performer, atau tim
                    acara favorit lo. Kenalin siapa yang ada di balik acara kece
                    ini, dari mereka yang
                    <a
                        href="#"
                        class="opacity-75-hover text-warning fw-bold me-1"
                        >bikin panggung</a
                    >
                    jadi hidup, sampe ke
                    <a
                        href="#"
                        class="opacity-75-hover text-warning fw-bold me-1"
                        >performer</a
                    >
                    yang bikin lo gak sabar nungguin event selanjutnya. Cus,
                    pesan tiket lo sekarang di Custix! 🎉
                </div>
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Aside-->
    </div>
    <!--end::Authentication Layout -->
</template>

<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, onMounted } from "vue";
import LayoutService from "@/core/services/LayoutService";
import { useBodyStore } from "@/stores/body";
import { useSetting } from "@/services";

export default defineComponent({
    name: "auth-layout",
    components: {},
    setup() {
        const store = useBodyStore();
        const { data: setting = {} } = useSetting();

        onMounted(() => {
            LayoutService.emptyElementClassesAndAttributes(document.body);

            store.addBodyClassname("app-blank");
            store.addBodyClassname("bg-body");
        });

        return {
            getAssetPath,
            setting,
        };
    },
});
</script>
