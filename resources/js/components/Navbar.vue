<template>
  <!-- Navbar -->
  <nav
    class="navbar navbar-expand-lg shadow-sm"
    :class="{ 'sticky-top bg-light': isSticky }"
  >
    <div class="container">
      <!-- Logo -->
      <router-link class="navbar-brand" to="/home">
        <img :src="settings?.logo" alt="Logo" class="logo img-fluid" />
      </router-link>

      <!-- Container untuk dua toggler -->
      <div class="navbar-toggler-container ms-auto d-flex align-items-center">
        <!-- Toggler Magnifier -->
        <button
          class="navbar-toggler"
          type="button"
          @click="toggleSearchSidebar"
        >
          <i class="ki-duotone ki-magnifier fs-3x">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
        </button>

        <!-- Toggler Menu -->
        <button
          v-if="!isOnOrderRoute"
          class="navbar-toggler ms-2"
          type="button"
          @click="toggleSidebar"
        >
          <i class="ki-duotone ki-menu fs-3x">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
          </i>
        </button>
      </div>

      <!-- Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <form
          ref="searchForm"
          class="d-flex mx-auto search-bar"
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

        <!-- Right Menu Items -->
        <ul class="navbar-nav ms-auto align-items-center">
          <li v-if="isAuthenticated" class="d-flex align-items-center">
            <router-link
              to="/order"
              class="nav-link fw-bold fs-6 me-3 btn btn-active-dark"
            >
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

  <!-- Sidebar Pencarian -->
<div :class="['sidebar', { 'sidebar-open': isSearchSidebarOpen }]">
  <div class="sidebar-header">
    <h5 class="mb-0">Cari Tiket</h5>
    <button
      class="close-btn"
      @click="toggleSearchSidebar"
      aria-label="Close Search Sidebar"
    >
      &times;
    </button>
  </div>

  <!-- Form Pencarian -->
  <form
    ref="searchForm"
    class="d-flex mx-auto sidebar-search-bar"
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

  <!-- Preview Hasil Pencarian di Sidebar -->
  <div v-if="tickets.length > 0" class="search-results">
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
            <p>{{ ticket.place }} - {{ formatDate(ticket.datetime) }}</p>
          </div>
        </router-link>
      </li>
    </ul>
  </div>
</div>

  
  <!-- Preview Search Results -->
  <div ref="searchResults" v-if="tickets.length > 0" class="search-results">
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
            <p>{{ ticket.place }} - {{ formatDate(ticket.datetime) }}</p>
          </div>
        </router-link>
      </li>
    </ul>
  </div>

  <!-- Sidebar Overlay -->
  <div
    v-if="isSidebarOpen"
    class="sidebar-overlay"
    @click="toggleSidebar"
  ></div>

  <div :class="['sidebar', { 'sidebar-open': isSidebarOpen }]">
    <div
      class="sidebar-header d-flex justify-content-between align-items-start"
    >
      <img
        :src="settings?.logo || '/media/avatars/default.png'"
        alt="Logo"
        class="sidebar-logo img-fluid"
      />
      <button
        class="close-btn"
        @click="toggleSidebar"
        aria-label="Close Sidebar"
      >
        &times;
      </button>
    </div>

    <!-- Menyembunyikan user-info jika belum login -->
    <div
      v-if="isAuthenticated"
      class="user-info d-flex align-items-center mb-8"
    >
      <div class="symbol symbol-35px me-4">
        <img
          alt="Foto"
          :src="user.photo || '/media/avatars/profz.png'"
          class="user-avatar"
        />
      </div>
      <h5 class="mb-0">{{ user?.nama || "Guest" }}</h5>
      <i class="bi bi-patch-check-fill fs-3 text-primary ms-2"></i>
    </div>

    <ul class="sidebar-menu">
      <li v-if="isAuthenticated">
        <router-link
          class="px-2 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"
          to="/order"
        >
          Tiket Saya
        </router-link>
      </li>
      <li v-if="isAuthenticated">
        <router-link
          class="px-2 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"
          to="/informasi-pribadi"
        >
          Informasi Pribadi
        </router-link>
      </li>
      <li v-if="!isAuthenticated">
        <p class="fw-bold fs-4 mb-6">Masuk ke akunmu</p>
        <div class="d-flex justify-content-between">
          <router-link
            to="/sign-in"
            class="btn btn-bg-secondary btn-color-white btn-light-dark me-2 w-100 fw-bold"
          >
            Masuk
          </router-link>
          <router-link to="/sign-up" class="btn btn-danger fw-bold w-100">
            Daftar
          </router-link>
        </div>
      </li>
      <li v-if="isAuthenticated">
        <router-link
          to="/home"
          @click="logout"
          class="px-2 my-1 menu-item fw-semibold btn btn-active-light-primary d-flex align-items-center"
        >
          Sign Out
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useAuthStore } from "@/stores/auth";
import axios from "axios";
import UserDropdown from "@/components/dropdown/UserDropdown.vue";
import { useRoute } from "vue-router";

export default {
  name: "Navbar",
  components: {
    UserDropdown,
  },
  methods: {
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
    formatShortDate(dateString) {
      const options = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
      };
      return new Date(dateString).toLocaleDateString("id-ID", options);
    },
  },
  setup() {
    const authStore = useAuthStore();
    const searchQuery = ref("");
    const tickets = ref([]);
    const searchResults = ref(null);
    const searchForm = ref(null);
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const user = computed(() => authStore.user);
    const isSticky = ref(false);
    const route = useRoute();
    const isSidebarOpen = ref(false);
    const isSearchSidebarOpen = ref(false);

    const logout = () => {
      authStore.logout();
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
          query: searchQuery.value,
        });
        tickets.value = response.data;
      } catch (error) {
        console.error(
          "Error fetching tickets:",
          error.response ? error.response.data : error.message
        );
      }
    };

    const handleScroll = () => {
      isSticky.value = window.scrollY > 0;
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

    const toggleSidebar = () => {
      isSidebarOpen.value = !isSidebarOpen.value;
    };

    const toggleSearchSidebar = () => {
      isSearchSidebarOpen.value = !isSearchSidebarOpen.value;
      if (isSearchSidebarOpen.value) {
        isSidebarOpen.value = false;
      }
    };

    const settings = ref(null);
    const fetchSettings = async () => {
      try {
        const response = await axios.get("/setting");
        settings.value = response.data;
      } catch (error) {
        console.error("Error fetching settings:", error);
      }
    };

    onMounted(() => {
      window.addEventListener("scroll", handleScroll);
      document.addEventListener("click", handleClickOutside); // Add click listener
      fetchSettings();
    });

    onBeforeUnmount(() => {
      window.removeEventListener("scroll", handleScroll);
      document.removeEventListener("click", handleClickOutside); // Remove click listener
    });

    return {
      isAuthenticated,
      user,
      isSticky,
      searchQuery,
      settings,
      isSidebarOpen,
      toggleSidebar,
      logout,
      isSearchSidebarOpen,
      toggleSearchSidebar,
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
/* Basic Navbar Styling */
.navbar {
  transition: background-color 0.1s ease, box-shadow 0.1s ease;
}
.sticky-top {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.logo {
  width: 100px; /* Navbar logo size */
  height: 40px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

/* Navbar Toggler Container */
.navbar-toggler-container {
  display: flex;
  align-items: center;
}

/* Navbar Toggler Styling */
.navbar-toggler {
  border: none;
  background: none;
}

.navbar-toggler i {
  font-size: 1.5rem;
}

.sidebar-logo {
  width: 80px; /* Sidebar logo size */
  height: auto; /* Maintain aspect ratio */
  margin-top: -30px;
}

.logo:hover {
  transform: scale(1.05);
}

/* Search Bar */
.search-bar {
  width: auto; /* Menyesuaikan lebar agar lebih rapat ke logo */
  margin-left: 0; /* Menghilangkan margin kiri jika ada */
  margin-right: 20px; /* Memberikan sedikit ruang di sebelah kanan */
  transition: width 0.3s ease;
  flex-grow: 1; /* Memberikan search bar lebih banyak ruang */
  max-width: 700px;
}

/* Khusus untuk search bar di sidebar */
.sidebar-search-bar .input-group {
  width: 90%; /* Lebar khusus untuk search bar di sidebar */
  margin-left: 0; /* Pastikan berada di kiri */
}

@media (min-width: 992px) and (max-width: 1192px) {
  .search-bar {
    max-width: 500px; /* Atur lebar maksimum untuk rentang ukuran layar ini */
    width: 100%; /* Membuat lebar search bar fleksibel */
  }
}

@media (max-width: 768px) {
  .search-bar {
    width: 100%; /* Membuat lebar penuh untuk tampilan mobile */
    margin-right: 10px; /* Mengurangi margin kanan pada tampilan kecil */
  }
}

/* Sidebar Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw; /* Menggunakan 100vw untuk memastikan lebar penuh */
  height: 100vh; /* Menggunakan 100vh untuk memastikan tinggi penuh */
  background: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}

/* Sidebar Styles */
.sidebar {
  position: fixed;
  top: 0;
  right: 0;
  height: 100vh; /* Pastikan sidebar memiliki tinggi penuh */
  width: 250px;
  background-color: #111;
  color: #fff;
  z-index: 1050;
  padding: 20px;
  transform: translateX(100%); /* Default: hidden off-screen */
  transition: transform 0.3s ease;
}

.sidebar-open {
  transform: translateX(0); /* Show sidebar */
}

.sidebar-header {
  display: flex;
  justify-content: space-between; /* Align items at the start and end */
  align-items: center; /* Vertically center items */
  width: 100%; /* Make sure the header takes full width */
  margin-bottom: 20px;
}

.user-avatar {
  border-radius: 50%;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
}

.sidebar-menu li {
  margin: 15px 0;
}

.sidebar-menu li a {
  color: #fff;
  text-decoration: none;
}

.close-btn {
  background: none;
  border: none;
  color: #fff;
  font-size: 24px;
  position: absolute;
  top: 10px;
  right: 20px;
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
  position: fixed; /* Mengunci posisi pada layar */
  background-color: #0f1014;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 100%; /* Lebar penuh agar responsif */
  max-width: 450px; /* Batas lebar maksimal */
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 8px;
  padding: 10px;
  top: 8%; /* Sesuaikan agar berada di bawah search bar */
  left: 40%;
  transform: translateX(-62%); /* Menengahkan secara horizontal */
}

@media (min-width: 900px) and (max-width: 1194px) {
  .search-results {
    left: 50%; /* Menempatkan search results di tengah-tengah */
    transform: translateX(-50%); /* Menengahkan secara horizontal */
    max-width: 600px; /* Menyesuaikan lebar preview */
  }
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
/* Styling untuk gambar tiket */
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
</style>
