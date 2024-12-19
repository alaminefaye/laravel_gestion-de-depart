<!-- Time and Departures -->
<div class="space-y-6">
    <!-- Current Time -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg shadow-lg p-4 hover:shadow-xl transition-all duration-300 transform hover:scale-[1.01] w-full">
        <h2 class="flex items-center justify-center text-lg font-bold text-white mb-3 animate-fade-in">
            <i class="fas fa-clock mr-2 text-blue-200 animate-pulse"></i>
            <span class="relative group">
                Heure Actuelle
                <div class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-200 transform origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
            </span>
        </h2>
        <div class="relative group">
            <div class="text-5xl font-bold text-white text-center tracking-tight transition-all duration-300 transform group-hover:scale-105" id="current-time"></div>
            <div class="absolute -inset-0.5 bg-blue-400 rounded-lg opacity-0 group-hover:opacity-10 transition duration-300 blur"></div>
        </div>
        <div class="text-base text-blue-100 text-center mt-2 font-medium transition-all duration-300 hover:text-white" id="current-date"></div>
    </div>

    <!-- Departures -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <h2 class="flex items-center text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-bus mr-3 text-blue-600"></i>
            Horaires de Départ
        </h2>
        @if(count($departures) > 0)
            @php
                $statusClasses = [
                    'À l\'heure' => 'bg-green-500 text-white border border-green-700',
                    'En retard' => 'bg-orange-500 text-white border border-orange-700',
                    'Annulé' => 'bg-red-500 text-white border border-red-700',
                    'Inconnu' => 'bg-gray-500 text-white border border-gray-700',
                ];
            @endphp
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Départ Prévu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Départ Effectif</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bus</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($departures as $departure)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $departure->route }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-500">{{ $departure->formatted_scheduled_date }}</div>
                                    <div class="text-sm font-bold text-gray-900">{{ $departure->formatted_scheduled_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($departure->formatted_delayed_time)
                                        <div class="text-xs text-gray-500">{{ $departure->formatted_delayed_date }}</div>
                                        <div class="text-sm font-bold text-red-600">{{ $departure->formatted_delayed_time }}</div>
                                    @else
                                        <div class="text-sm font-bold text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">N°{{ $departure->bus->numero }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                        {{ $departure->formatted_price }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusClasses[$departure->status_label ?? 'Inconnu'] }}">
                                        {{ $departure->status_label ?? 'Inconnu' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-gray-500">
                Aucun départ prévu
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        const dateElement = document.getElementById('current-date');
        
        // Format personnalisé pour H:MM:S
        const hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const customTime = `${hours}:${minutes}:${seconds}`;
        
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        timeElement.textContent = customTime;
        dateElement.textContent = now.toLocaleDateString('fr-FR', dateOptions).charAt(0).toUpperCase() + now.toLocaleDateString('fr-FR', dateOptions).slice(1);
    }

    updateTime();
    setInterval(updateTime, 1000);
</script>
@endpush
