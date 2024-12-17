<template>
  <div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex items-center gap-3 mb-6">
      <i class="fas fa-bus text-blue-600 text-xl"></i>
      <h2 class="text-xl font-semibold text-gray-800">Horaires de Départ</h2>
    </div>
    
    <div class="space-y-4">
      <div class="grid grid-cols-12 text-sm text-gray-500 px-4">
        <div class="col-span-5">ROUTE</div>
        <div class="col-span-4">HEURE</div>
        <div class="col-span-3">STATUT</div>
      </div>
      
      <div v-for="departure in departures" :key="departure.id" 
           class="grid grid-cols-12 items-center bg-white p-4 rounded-lg hover:bg-gray-50">
        <div class="col-span-5 font-medium text-gray-900">{{ departure.route }}</div>
        <div class="col-span-4 text-gray-600">{{ departure.scheduled_time }}</div>
        <div class="col-span-3">
          <span :class="getStatusClass(departure.status)" class="px-3 py-1 rounded-full text-sm font-medium">
            {{ getStatusText(departure.status) }}
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
      departures: []
    }
  },
  methods: {
    async fetchDepartures() {
      try {
        const response = await axios.get('/api/departures');
        this.departures = response.data;
      } catch (error) {
        console.error('Error fetching departures:', error);
      }
    },
    getStatusClass(status) {
      return {
        'on_time': 'bg-green-100 text-green-800',
        'delayed': 'bg-yellow-100 text-yellow-800',
        'cancelled': 'bg-red-100 text-red-800'
      }[status];
    },
    getStatusText(status) {
      return {
        'on_time': "À l'heure",
        'delayed': 'Retardé',
        'cancelled': 'Annulé'
      }[status];
    }
  },
  mounted() {
    this.fetchDepartures();
    setInterval(this.fetchDepartures, 30000);
  }
}
</script>
