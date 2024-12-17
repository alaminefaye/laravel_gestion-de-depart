@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ajouter un Départ</h2>
            <a href="{{ route('dashboard.departures.index') }}" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Veuillez corriger les erreurs suivantes :
                    </p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('dashboard.departures.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
                    <input type="text" name="route" id="route" value="{{ old('route') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                    <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Sélectionner un bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                Bus N°{{ $bus->numero }} ({{ $bus->capacite }} places)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date de départ</label>
                    <input type="date" name="scheduled_date" id="scheduled_date" value="{{ old('scheduled_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="scheduled_time" class="block text-sm font-medium text-gray-700">Heure de départ</label>
                    <input type="time" name="scheduled_time" id="scheduled_time" value="{{ old('scheduled_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="prix" id="prix" step="1" min="0" value="{{ old('prix', 0) }}" class="block w-full pr-12 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">CFA</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach(App\Models\Departure::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="delayed_time_container" style="display: none;">
                    <label for="delayed_time" class="block text-sm font-medium text-gray-700">Heure retardée</label>
                    <input type="time" name="delayed_time" id="delayed_time" 
                           value="{{ old('delayed_time') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="places_disponibles" class="block text-sm font-medium text-gray-700">Nombre de places disponibles</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="places_disponibles" id="places_disponibles" 
                               min="0" 
                               value="{{ old('places_disponibles', 0) }}" 
                               class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required>
                        <div class="mt-1 text-sm text-gray-500" id="max-places"></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Créer le départ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('bus_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const capacite = selectedOption.textContent.match(/\((\d+) places\)/)?.[1] || 0;
    const placesInput = document.getElementById('places_disponibles');
    placesInput.max = capacite;
    document.getElementById('max-places').textContent = `Maximum: ${capacite} places`;
});

// Set initial max places if a bus is already selected
document.addEventListener('DOMContentLoaded', function() {
    const busSelect = document.getElementById('bus_id');
    if (busSelect.value) {
        busSelect.dispatchEvent(new Event('change'));
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const delayedTimeContainer = document.getElementById('delayed_time_container');
    const delayedTimeInput = document.getElementById('delayed_time');

    function toggleDelayedTime() {
        const isDelayed = statusSelect.value === '{{ App\Models\Departure::STATUS_DELAYED }}';
        delayedTimeContainer.style.display = isDelayed ? 'block' : 'none';
        delayedTimeInput.required = isDelayed;
        
        if (!isDelayed) {
            delayedTimeInput.value = '';
        }
    }

    // Set initial state
    toggleDelayedTime();

    // Listen for changes
    statusSelect.addEventListener('change', toggleDelayedTime);
});
</script>
@endpush
