<!-- Time and Departures -->
<div class="space-y-6">
    <!-- Current Time -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <h2 class="flex items-center text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-clock mr-3 text-blue-600"></i>
            Heure Actuelle
        </h2>
        <div class="text-6xl font-bold text-blue-600 text-center tracking-tight" id="current-time"></div>
        <div class="text-xl text-gray-600 text-center mt-2 font-medium" id="current-date"></div>
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
                    'À l\'heure' => 'bg-green-100 text-green-800 border border-green-200',
                    'En retard' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                    'Annulé' => 'bg-red-100 text-red-800 border border-red-200',
                ];
            @endphp
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Départ Prévu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Heure Retardée</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bus</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prix</th>
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
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusClasses[$departure->status_label] }}">
                                        {{ $departure->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">N°{{ $departure->bus->numero }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <span class="font-bold text-gray-900">{{ number_format($departure->prix, 0, ',', ' ') }}</span>
                                        <span class="text-gray-500 font-bold">CFA</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-500 text-lg">Aucun départ disponible pour le moment</div>
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
        
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        timeElement.textContent = now.toLocaleTimeString('fr-FR', timeOptions);
        dateElement.textContent = now.toLocaleDateString('fr-FR', dateOptions).charAt(0).toUpperCase() + now.toLocaleDateString('fr-FR', dateOptions).slice(1);
    }

    updateTime();
    setInterval(updateTime, 1000);
</script>
@endpush
