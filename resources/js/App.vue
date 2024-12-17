<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-4">
        <div class="flex items-center gap-3">
          <img src="/images/logo.svg" alt="ART Luxury Bus" class="h-12 w-12">
          <div>
            <h1 class="text-2xl font-bold text-blue-600">ART Luxury Bus</h1>
            <p class="text-gray-600">L'Excellence du Transport</p>
          </div>
        </div>
      </div>
    </header>

    <!-- Banner -->
    <div class="bg-blue-600 text-white py-2">
      <div class="container mx-auto px-4">
        <div class="flex items-center gap-2">
          <i class="fas fa-wifi"></i>
          <p>Pour votre confort, nos bus sont désormais équipés de WiFi gratuit</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-8">
          <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <VideoPlayer class="w-full h-[400px] rounded-lg overflow-hidden" />
          </div>
        </div>
        
        <!-- Right Column -->
        <div class="lg:col-span-4 space-y-6">
          <CurrentTime />
          <DepartureBoard />
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import VideoPlayer from './components/VideoPlayer.vue'
import DepartureBoard from './components/DepartureBoard.vue'
import CurrentTime from './components/CurrentTime.vue'

export default {
  components: {
    VideoPlayer,
    DepartureBoard,
    CurrentTime
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
  font-family: 'Inter', sans-serif;
}
</style>
