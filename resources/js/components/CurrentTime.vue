<template>
  <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <i class="far fa-clock text-blue-600 text-xl"></i>
      <h2 class="text-xl font-semibold text-gray-800">Heure Actuelle</h2>
    </div>
    
    <div class="text-center">
      <div class="text-4xl font-bold text-blue-600">{{ currentTime }}</div>
      <div class="text-gray-500 mt-2">{{ currentDate }}</div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentTime: '',
      currentDate: ''
    }
  },
  methods: {
    updateTime() {
      const now = new Date();
      this.currentTime = now.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
      this.currentDate = now.toLocaleDateString('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    }
  },
  mounted() {
    this.updateTime();
    setInterval(this.updateTime, 1000);
  }
}
</script>
