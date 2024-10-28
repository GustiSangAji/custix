<template>
  <nav
    class="navbar navbar-expand-lg shadow-sm"
    :class="{ 'sticky-top bg-light': isSticky }"
  >
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
        <form class="d-flex mx-auto search-bar" @submit.prevent="searchTickets">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Cari di CusTix"
              v-model="searchQuery"
              @input="onInput"
              aria-label="Search"
              aria-describedby="search-addon"
            />
            <span class="input-group-text" id="search-addon">
              <i class="ki-duotone ki-magnifier fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </span>
          </div>
        </form>

        <!-- Preview Search Results -->
        <div v-if="tickets.length > 0" class="search-results">
          <ul>
            <li v-for="ticket in tickets" :key="ticket.id">
              <router-link :to="`/tickets/${ticket.id}`">
                <img
                  :src="ticket.image"
                  alt="ticket image"
                  class="ticket-image"
                />
                <div class="ticket-info">
                  <h6>{{ ticket.name }}</h6>
                  <p>{{ ticket.place }} - {{ ticket.datetime }}</p>
                </div>
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Right Menu Items -->
        <ul class="navbar-nav ms-auto align-items-center">
          <li v-if="isAuthenticated" class="d-flex align-items-center">
            <router-link to="/order" class="nav-link fw-bold fs-6 me-3"
              >Tiket Saya</router-link
            >
            <UserDropdown />
          </li>
          <li v-else class="nav-item">
            <router-link
              to="/sign-in"
              class="btn btn-light-primary rounded me-2"
              >Masuk</router-link
            >
            <router-link to="/sign-up" class="btn btn-active-dark rounded"
              >Daftar</router-link
            >
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import axios from "axios";
import UserDropdown from "@/components/dropdown/UserDropdown.vue";

export default {
  name: "Navbar",
  components: {
    UserDropdown,
  },
  setup() {
    const authStore = useAuthStore();
    const searchQuery = ref("");
    let tickets = ref([]);
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const isSticky = ref(false);

    const handleScroll = () => {
      isSticky.value = window.scrollY > 0;
    };

    const onInput = async () => {
      if (searchQuery.value.length >= 3) {
        await searchTickets();
      } else {
        tickets.value = [];
      }
    };

    const searchTickets = async () => {
      try {
        const response = await axios.post(`/tickets/search`);
        tickets.value = response.data;
      } catch (error) {
        console.error(
          "Error fetching tickets:",
          error.response ? error.response.data : error.message
        );
      }
    };

    window.addEventListener("scroll", handleScroll);

    return {
      isAuthenticated,
      isSticky,
      searchQuery,
      tickets,
      onInput,
      searchTickets,
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
.search-results {
  position: absolute;
  background-color: #0F1014;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 50%;
  max-height: 300px;
  overflow-y: auto;
  z-index: 1000;
}
.ticket-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-right: 10px;
}
.ticket-info {
  display: inline-block;
  vertical-align: top;
}

.search-results {
  position: absolute;
  background-color: #0F1014;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  width: 50%; /* Pastikan sama dengan lebar search-bar */
  max-height: 300px;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 5px;
  padding: 10px;
  top: 100%; /* Membuat dropdown tepat di bawah search bar */
  left: 50%;
  transform: translateX(-50%);
}

.search-results ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.search-results li {
  display: flex;
  align-items: center;
  padding: 8px;
  border-bottom: 1px solid #26272F;
  transition: background-color 0.2s ease;
}

.search-results li:hover {
  background-color: #26272F;
}

.ticket-image {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 10px;
}

.ticket-info h6 {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: #343a40;
}

.ticket-info p {
  margin: 0;
  font-size: 0.8rem;
  color: #6c757d;
}

</style>
