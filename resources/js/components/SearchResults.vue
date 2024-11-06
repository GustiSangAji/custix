<template>
  <div class="search-results">
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
</template>

<script>
export default {
  props: {
    tickets: Array,
  },
  methods: {
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString("id-ID", options);
    },
  },
};
</script>

<style scoped>
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
