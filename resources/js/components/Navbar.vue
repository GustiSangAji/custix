<template>
  <nav class="navbar navbar-expand-lg shadow-sm" :class="{'sticky-top bg-light': isSticky}">
    <div class="container">
      <!-- Logo -->
      <router-link class="navbar-brand" to="/home">
        <img src="/media/hero/custiket.png" alt="Logo" class="logo img-fluid" />
      </router-link>
      <!-- Navbar Toggler -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Center Search Bar -->
        <form class="d-flex mx-auto search-bar">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Cari di CusTix"
              aria-label="Recipient's username"
              aria-describedby="basic-addon2"
            />
            <span class="input-group-text" id="basic-addon2">
              <i class="ki-duotone ki-magnifier fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </span>
          </div>
        </form>

        <!-- Right Menu Items -->
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- Jika user sudah login -->
          <li v-if="isAuthenticated" class="d-flex align-items-center">
            <router-link to="/order" class="nav-link fw-bold fs-6 me-3">Tiket Saya</router-link>
            <UserDropdown />
          </li>
          <!-- Jika user belum login -->
          <li v-else class="nav-item">
            <router-link to="/sign-in" class="btn btn-light-primary rounded me-2">Masuk</router-link>
            <router-link to="/sign-up" class="btn btn-active-dark rounded">Daftar</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { useAuthStore } from "@/stores/auth";
import { computed, onMounted, ref } from "vue";
import UserDropdown from "@/components/dropdown/UserDropdown.vue";

export default {
  name: "Navbar",
  components: {
    UserDropdown,
  },
  setup() {
    const authStore = useAuthStore();

    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const isSticky = ref(false);

    const handleScroll = () => {
      isSticky.value = window.scrollY > 0; // Menentukan kapan navbar menjadi sticky
    };

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
    });

    return {
      isAuthenticated,
      isSticky,
    };
  },
};
</script>

<style scoped>
.navbar {
  transition: background-color 0.1s ease, box-shadow 0.1s ease;
}
.sticky-top {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.logo {
  width: 100px;
  height: 40px;
  object-fit: cover;
  transition: transform 0.3s ease;
}
.logo:hover {
  transform: scale(1.05);
}
.search-bar {
  width: 50%;
  transition: width 0.3s ease;
}
.search-bar:hover {
  width: 55%;
}
</style>
