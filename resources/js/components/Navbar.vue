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
        <form
          ref="searchForm"
          class="d-flex mx-10 search-bar"
          @submit.prevent="searchTickets"
        >
          <div class="input-group">
            <input
              ref="searchInput"
              type="text"
              class="form-control"
              placeholder="Cari di CusTix"
              v-model="searchQuery"
              @input="onInput"
              aria-label="Search"
              aria-describedby="search-addon"
            />
            <span
              class="input-group-text"
              id="search-addon"
              @click="searchTickets"
            >
              <i class="ki-duotone ki-magnifier fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </span>
          </div>
        </form>

        <!-- Preview Search Results -->
        <div
          ref="searchResults"
          v-if="tickets.length > 0"
          class="search-results"
        >
          <ul>
            <li v-for="ticket in tickets" :key="ticket.id">
              <router-link
                :to="{
                  name: 'ticket-detail',
                  params: { name: ticket.name.replace(/\s+/g, '-') },
                }"
              >
                <img :src="ticket.image" class="ticket-image" />
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
            <router-link to="/order" class="nav-link fw-bold fs-6 me-3 btn btn-active-dark">
              Tiket Saya
            </router-link>
            <UserDropdown />
          </li>
          <li v-else class="nav-item">
            <router-link
              to="/sign-in"
              class="btn btn-light-primary rounded me-2"
            >
              Masuk
            </router-link>
            <router-link to="/sign-up" class="btn btn-active-dark rounded">
              Daftar
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
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

    const searchResults = ref(null);
    const searchForm = ref(null);

    const handleScroll = () => {
      isSticky.value = window.scrollY > 0;
    };

    const onInput = async () => {
      if (searchQuery.value.length >= 1) {
        await searchTickets();
      } else {
        tickets.value = [];
      }
    };

    const searchTickets = async () => {
      try {
        const response = await axios.post(`/tickets/search`, {
          query: searchQuery.value, //apakah betul 999
        });
        tickets.value = response.data;
      } catch (error) {
        console.error(
          "Error fetching tickets:",
          error.response ? error.response.data : error.message
        );
      }
    };

    // Function to hide search results when clicking outside
    const handleClickOutside = (event) => {
      if (
        searchResults.value &&
        searchForm.value &&
        !searchResults.value.contains(event.target) &&
        !searchForm.value.contains(event.target)
      ) {
        tickets.value = [];
      }
    };

    // Add event listener on mounted
    onMounted(() => {
      window.addEventListener("scroll", handleScroll);
      document.addEventListener("click", handleClickOutside);
    });

    // Remove event listener before component unmounts
    onBeforeUnmount(() => {
      window.removeEventListener("scroll", handleScroll);
      document.removeEventListener("click", handleClickOutside);
    });

    return {
      isAuthenticated,
      isSticky,
      searchQuery,
      tickets,
      onInput,
      searchTickets,
      searchResults,
      searchForm,
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
  background-color: #0f1014;
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
  background-color: #0f1014;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 100%; /* Responsif: Menyesuaikan lebar penuh */
  max-width: 500px; /* Membatasi ukuran maksimal */
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 8px;
  padding: 10px;
  top: 100%;
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
  padding: 12px;
  border-bottom: 1px solid #26272f;
  transition: background-color 0.2s ease;
}

.search-results li:hover {
  background-color: #333;
}

.ticket-image {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 6px;
  margin-right: 15px;
  flex-shrink: 0;
}

.ticket-info {
  flex-grow: 1;
}

.ticket-info h6 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #fff;
}

.ticket-info p {
  margin: 0;
  font-size: 0.85rem;
  color: #d1d1d1;
}

/* Responsif untuk layar kecil */
@media (max-width: 576px) {
  .search-results {
    max-width: 100%; /* Lebar penuh pada layar kecil */
    left: 0;
    transform: none;
  }

  .ticket-image {
    width: 60px;
    height: 60px;
  }

  .ticket-info h6 {
    font-size: 0.9rem;
  }

  .ticket-info p {
    font-size: 0.8rem;
  }
}

/* Responsif untuk layar besar */
@media (min-width: 992px) {
  .search-results {
    max-width: 600px; /* Ukuran maksimal lebih besar di layar lebar */
  }

  .ticket-info h6 {
    font-size: 1.1rem;
  }

  .ticket-info p {
    font-size: 0.9rem;
  }
}
</style>