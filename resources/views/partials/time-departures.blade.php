<!-- Time and Departures -->
<div class="space-y-4">
    <!-- Current Time -->
    <div class="bg-white rounded-lg shadow p-4 hover-up">
        <h2 class="flex items-center text-2xl font-semibold text-gray-800 mb-3">
            <i class="fas fa-clock mr-2 text-blue-600"></i>
            Heure Actuelle
        </h2>
        <div class="text-5xl font-bold text-blue-600 text-center" id="current-time"></div>
        <div class="text-lg text-gray-600 text-center mt-1" id="current-date"></div>
    </div>

    <!-- Departures -->
    <div class="bg-white rounded-lg shadow p-4 hover-up">
        <h2 class="flex items-center text-2xl font-semibold text-gray-800 mb-3">
            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
            Horaires de Départ
        </h2>
        @if(count($departures) > 0)
            @php
                $statusClasses = [
                    App\Models\Departure::STATUS_ON_TIME => 'bg-green-100 text-green-800',
                    App\Models\Departure::STATUS_DELAYED => 'bg-yellow-100 text-yellow-800',
                    App\Models\Departure::STATUS_CANCELLED => 'bg-red-100 text-red-800',
                ];
            @endphp
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-2 px-4 border">Route</th>
                            <th class="py-2 px-4 border">Heure prévue</th>
                            <th class="py-2 px-4 border">Heure retardée</th>
                            <th class="py-2 px-4 border">Statut</th>
                            <th class="py-2 px-4 border">Bus</th>
                            <th class="py-2 px-4 border">Places</th>
                            <th class="py-2 px-4 border">Prix</th>
                            <th class="py-2 px-4 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($departures as $departure)
                            <tr>
                                <td class="py-2 px-4 border">{{ $departure->route }}</td>
                                <td class="py-2 px-4 border">{{ $departure->formatted_scheduled_time }}</td>
                                <td class="py-2 px-4 border">
                                    @if($departure->status === App\Models\Departure::STATUS_DELAYED && $departure->delayed_time)
                                        {{ $departure->formatted_delayed_time }}
                                    @else
                                        --:--
                                    @endif
                                </td>
                                <td class="py-2 px-4 border">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$departure->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $departure->status_label }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border">{{ optional($departure->bus)->name ?? '--' }}</td>
                                <td class="py-2 px-4 border">{{ $departure->places_disponibles }}</td>
                                <td class="py-2 px-4 border">{{ number_format($departure->prix, 2, ',', ' ') }} €</td>
                                <td class="py-2 px-4 border">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dashboard.departures.edit', $departure) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.departures.destroy', $departure) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce départ ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <p class="text-lg text-gray-500">Aucun départ n'est programmé.</p>
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
        
        // Format time
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}`;
        
        // Format date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.textContent = now.toLocaleDateString('fr-FR', options);
    }

    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);
</script>
@endpush
