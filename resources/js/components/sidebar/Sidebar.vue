<template>
  <div class="col-md-4 col-lg-3 vh-100">
    <!-- Card component for sidebar -->
    <div class="card mb-5 mb-xl-10">
      <div class="card-header border-0 cursor-pointer">
        <!-- Profile Section -->
        <div class="card-title m-0">
          <div class="symbol symbol-40px me-4">
            <img alt="Foto profil" :src="user.photo" />
          </div>
          <h2 class="mb-0 fw-bold">
            {{ user.nama }}
            <i class="bi bi-patch-check-fill fs-3 text-primary"></i>
          </h2>
        </div>
      </div>
      <div class="collapse show">
        <div class="card">
          <div class="card-body mx-0 px-8">
            <!-- List group with links -->
            <!-- Informasi Pribadi -->
            <router-link
              to="/informasi-pribadi"
               exact-active-class="active-class"
              class="btn btn-active-light-primary mb-3 px-2 w-100"
            >
              <div class="d-flex align-items-center">
                <!-- Ikon Keenthemes -->
                <span class="svg-icon fs-1">
                  <i class="ki-duotone ki-user-edit fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                </span>

                <!-- Teks Link -->
                <div class="ms-3 text-gray-700">Informasi Pribadi</div>
              </div>
            </router-link>

            <!-- Tiket Saya -->
            <router-link to="/order" 
             exact-active-class="active-class"
            class="btn btn-active-light-primary mb-3 px-2 w-100">
              <div class="d-flex align-items-center">
                <span class="svg-icon fs-1">
                  <i class="ki-duotone ki-wallet fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                </span>

                <div class="ms-3 text-gray-700">Tiket Saya</div>
              </div>
            </router-link>

            <!-- Syarat & Ketentuan -->
            <router-link
              to="/syarat-ketentuan"
              class="btn btn-active-light-primary mb-3 px-2 w-100"
            >
              <div class="d-flex align-items-center">
                <span class="svg-icon fs-1">
                  <i class="ki-duotone ki-file fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>

                <span class="ms-3 text-gray-700"> Syarat & Ketentuan </span>
              </div>
            </router-link>

            <!-- Kebijakan Privasi -->
            <router-link
              to="/kebijakan-privasi"
              class="btn btn-active-light-primary mb-3 px-2 w-100"
            >
              <div class="d-flex align-items-center">
                <span class="svg-icon fs-1">
                  <i class="ki-duotone ki-shield fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>

                <div class="ms-3 text-gray-700">Kebijakan Privasi</div>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

export default {
  name: "Sidebar",
  setup() {
    const authStore = useAuthStore();

    const user = computed(() => authStore.user);

    return {
      user,
    };
  },
};
</script>

<style scoped>
/* Custom styles can be added here if necessary */
.active-class {
  background-color: #172331;
}
</style>
