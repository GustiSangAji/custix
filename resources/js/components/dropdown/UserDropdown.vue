<template>
  <li class="nav-item dropdown">
    <router-link
      to="/home"
      class="nav-link dropdown-toggle d-flex align-items-center symbol symbol-35px"
      id="navbarDropdown"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <img
        :src="'/media/avatars/profz.png'"
        alt="User Icon"
        class="rounded-circle animated-icon"
      />
    </router-link>
    <ul class="dropdown-menu dropdown-menu-end fade-in" aria-labelledby="navbarDropdown">
      <!-- Menu User -->
      <li class="menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold py-4 px-5 fs-6 w-275px">
        <div class="menu-content d-flex align-items-center px-5">
          <div class="symbol symbol-40px me-5">
            <img alt="Logo" :src="'/media/avatars/profz.png'" />
          </div>
          <div class="d-flex flex-column">
            <div class="fw-bold d-flex align-items-center fs-5">
              {{ user.name }}
              <span class="badge badge-light-success fw-bold fs-8 px-2 py-2">
                {{ user.role ? user.role.name : 'Tidak ada' }}
              </span>
            </div>
            <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
              {{ user.email || 'Tidak ada email' }}
            </a>
          </div>
        </div>
      </li>
      <li class="separator my-2"></li>
      <li class="menu-item px-5 my-1">
        <router-link
          to="/order"
          class="px-5 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"
        >
          Tiket Saya
        </router-link>
      </li>
      <li class="menu-item px-5 my-1">
        <router-link
          to="/informasi-pribadi"
          class="px-5 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"
        >
          Informasi Pribadi
        </router-link>
      </li>
      <li class="menu-item px-5 my-1"></li>
      <li class="menu-item px-5 my-1">
        <router-link to="/home" @click="logout" class="px-5 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"> Sign Out </router-link>
      </li>
      <!-- Menu Khusus Admin -->
      <li v-if="isAdmin" class="menu-item px-5 my-1">
        <router-link to="/dashboard" class="px-5 my-1 mb-4 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center">
          Dashboard
        </router-link>
      </li>
    </ul>
  </li>
</template>

<script>
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

export default {
  name: "UserDropdown",
  setup() {
    const authStore = useAuthStore();

    const user = computed(() => authStore.user);
    const isAdmin = computed(() => user.value && user.value.role?.name === "admin");

    const logout = () => {
      authStore.logout();
    };

    return {
      user,
      isAdmin,
      logout,
    };
  },
};
</script>

<style scoped>
.dropdown-menu {
  min-width: 200px;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.dropdown-menu.fade-in {
  opacity: 0;
  transform: translateY(-10px);
  animation: fadeIn 0.5s forwards;
}



/* Animasi untuk fade-in efek */
@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(-10px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
