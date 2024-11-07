<template>
  <div class="search-results" ref="searchResults">
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
.search-results {
  position: absolute;
  top: 100%; /* Position right below the search bar */
  left: 0;
  width: 100%; /* Match width of the search bar */
  background-color: #0f1014;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 8px;
  padding: 10px;
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
  border-radius: 4px;
}

/* Styling untuk gambar tiket */
.ticket-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-right: 10px;
}

.ticket-info {
  flex-grow: 1;
  display: inline-block;
  vertical-align: top;
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
