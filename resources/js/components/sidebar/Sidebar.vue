<template>
  <div class="col-md-4 col-lg-3 vh-100">
    <!-- Card component for sidebar -->
    <div class="card text-white h-100">
      <div class="card-body">
        <!-- Profile Section -->
        <h2 class="card-title mb-8 fw-bold text-white">{{ user.nama }}</h2>

        <!-- List group with links -->
        <!-- Informasi Pribadi -->
        <router-link to="/informasi-pribadi" class="card parent-hover mb-3">
          <div class="card-body d-flex align-items-center">
            <!-- Ikon Keenthemes -->
            <span class="svg-icon fs-1">
              <i class="ki-duotone ki-user-edit fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
            </span>

            <!-- Teks Link -->
            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
              Informasi Pribadi
            </span>
          </div>
        </router-link>

        <!-- Tiket Saya -->
        <router-link to="/order" class="card parent-hover mb-3">
          <div class="card-body d-flex align-items-center">
            <span class="svg-icon fs-1">
              <i class="ki-duotone ki-ticket fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
            </span>

            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
              Tiket Saya
            </span>
          </div>
        </router-link>

        <!-- Transaksi -->
        <router-link to="/transaksi" class="card parent-hover mb-3">
          <div class="card-body d-flex align-items-center">
            <span class="svg-icon fs-1">
              <i class="ki-duotone ki-wallet fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
            </span>

            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
              Transaksi
            </span>
          </div>
        </router-link>

        <!-- Syarat & Ketentuan -->
        <router-link to="/syarat-ketentuan" class="card parent-hover mb-3">
          <div class="card-body d-flex align-items-center">
            <span class="svg-icon fs-1">
              <i class="ki-duotone ki-file fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </span>

            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
              Syarat & Ketentuan
            </span>
          </div>
        </router-link>

        <!-- Kebijakan Privasi -->
        <router-link to="/kebijakan-privasi" class="card parent-hover mb-3">
          <div class="card-body d-flex align-items-center">
            <span class="svg-icon fs-1">
              <i class="ki-duotone ki-shield fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </span>

            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
              Kebijakan Privasi
            </span>
          </div>
        </router-link>

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
</style>
