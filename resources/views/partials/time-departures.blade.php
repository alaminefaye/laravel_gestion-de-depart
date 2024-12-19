<!-- Time and Departures -->
<div class="flex flex-col h-full">
    <!-- Current Time - Fixed -->
    <div class="flex-none mb-6">
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
    </div>

    <!-- Departures - Scrollable -->
    <div class="flex-1 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <h2 class="flex items-center text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bus mr-3 text-blue-600"></i>
                Horaires de Départ
            </h2>
            @if(count($departures) > 0)
                <div class="grid gap-4">
                    @foreach($departures as $departure)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <!-- Route et Bus -->
                                <div class="flex-1 min-w-[200px]">
                                    <div class="text-lg font-bold text-gray-900">{{ $departure->route }}</div>
                                    <div class="flex items-center gap-2">
                                        <div class="inline-block px-3 py-1 bg-blue-600 text-white rounded-full text-sm font-bold shadow-sm">
                                            N°{{ $departure->bus->numero }}
                                        </div>
                                        <div class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200">
                                            <i class="fas fa-chair mr-1"></i>
                                            {{ $departure->places_disponibles ?? 0 }} places
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Horaires -->
                                <div class="flex-1 min-w-[200px]">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-gray-400"></i>
                                            <div>
                                                <div class="text-xs text-gray-500">{{ $departure->formatted_scheduled_date }}</div>
                                                <div class="text-sm font-bold text-gray-900">{{ $departure->formatted_scheduled_time }}</div>
                                            </div>
                                        </div>
                                        @if($departure->formatted_delayed_time)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-history text-red-400"></i>
                                                <div>
                                                    <div class="text-xs text-gray-500">{{ $departure->formatted_delayed_date }}</div>
                                                    <div class="text-sm font-bold text-red-600">{{ $departure->formatted_delayed_time }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Prix -->
                                <div class="flex-none">
                                    <div class="price-animation px-4 py-2 rounded-lg text-white relative overflow-hidden group cursor-pointer">
                                        <div class="relative z-10">
                                            <div class="text-xs uppercase tracking-wide text-green-100">Prix</div>
                                            <div class="text-xl font-bold">{{ $departure->formatted_price }}</div>
                                        </div>
                                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="flex-none">
                                    @php
                                        $statusClasses = [
                                            'À l\'heure' => 'bg-green-500 text-white border border-green-700',
                                            'En retard' => 'bg-orange-500 text-white border border-orange-700',
                                            'Annulé' => 'bg-red-500 text-white border border-red-700',
                                            'Inconnu' => 'bg-gray-500 text-white border border-gray-700',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusClasses[$departure->status_label] }}">
                                        {{ $departure->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Aucun départ prévu pour le moment.</p>
                </div>
            @endif
        </div>
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
