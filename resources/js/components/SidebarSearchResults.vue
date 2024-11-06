<template>
  <div class="search-results">
    <ul>
      <li v-for="ticket in tickets" :key="ticket.id">
        <router-link
          :to="{
            name: 'ticket-detail',
            params: { name: ticket.name.replace(/\s+/g, '-') },
          }"
          class="ticket-item"
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
  max-width: 100%;
  max-height: 80%;
  overflow-y: auto;
  border-radius: 8px;
  padding-top: 10px;
}

.search-results ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.search-results li {
  padding: 12px;
  transition: background-color 0.2s ease;
}

.search-results li:hover {
  background-color: #333;
  border-radius: 4px;
}

.ticket-item {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: inherit;
}

.ticket-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 15px;
}

.ticket-info {
  display: flex;
  flex-direction: column;
}

.ticket-info h6 {
  font-size: 1rem;
  font-weight: 600;
  color: #fff;
  margin: 0;
}

.ticket-info p {
  margin: 0;
  font-size: 0.85rem;
  color: #d1d1d1;
}
</style>
