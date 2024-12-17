<template>
  <div class="relative w-full h-full">
    <video
      ref="videoPlayer"
      class="w-full h-full object-cover"
      :src="currentVideo"
      autoplay
      @ended="playNextVideo"
    ></video>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      videos: [],
      currentIndex: 0
    }
  },
  computed: {
    currentVideo() {
      return this.videos[this.currentIndex]?.video_path || '';
    }
  },
  methods: {
    async fetchVideos() {
      try {
        const response = await axios.get('/api/advertisements');
        this.videos = response.data;
        if (this.videos.length > 0 && !this.currentVideo) {
          this.currentIndex = 0;
        }
      } catch (error) {
        console.error('Error fetching videos:', error);
      }
    },
    playNextVideo() {
      this.currentIndex = (this.currentIndex + 1) % this.videos.length;
      this.$refs.videoPlayer.play();
    }
  },
  mounted() {
    this.fetchVideos();
    setInterval(this.fetchVideos, 300000); // Refresh video list every 5 minutes
  }
}
</script>
