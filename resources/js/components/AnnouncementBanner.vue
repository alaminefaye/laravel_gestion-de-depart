<template>
  <div v-if="announcements.length > 0" class="bg-blue-600 text-white py-2 px-4">
    <div class="flex items-center space-x-4">
      <div class="flex-shrink-0">
        <i class="fas fa-bullhorn"></i>
      </div>
      <div class="flex-1 marquee">
        <div class="marquee-content">
          <span v-for="(announcement, index) in announcements" :key="index" class="mx-8">
            {{ announcement.message }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      announcements: []
    }
  },
  methods: {
    async fetchAnnouncements() {
      try {
        const response = await axios.get('/api/announcements');
        this.announcements = response.data;
      } catch (error) {
        console.error('Error fetching announcements:', error);
      }
    }
  },
  mounted() {
    this.fetchAnnouncements();
    setInterval(this.fetchAnnouncements, 60000); // Refresh every minute
  }
}
</script>

<style scoped>
.marquee {
  overflow: hidden;
  white-space: nowrap;
}

.marquee-content {
  display: inline-block;
  animation: marquee 20s linear infinite;
}

@keyframes marquee {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(-100%);
  }
}
</style>
