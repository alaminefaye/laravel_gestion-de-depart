<!-- Formulaire de départ -->
<form action="{{ isset($departure) ? route('departures.update', $departure->id) : route('departures.store') }}" method="POST" class="space-y-4">
    @csrf
    @if(isset($departure))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Route -->
        <div>
            <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
            <input type="text" name="route" id="route" value="{{ old('route', $departure->route ?? '') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Heure de départ -->
        <div>
            <label for="scheduled_time" class="block text-sm font-medium text-gray-700">Heure de départ</label>
            <input type="time" name="scheduled_time" id="scheduled_time" value="{{ old('scheduled_time', isset($departure) ? \Carbon\Carbon::parse($departure->scheduled_time)->format('H:i') : '') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Prix -->
        <div>
            <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
            <input type="number" name="prix" id="prix" value="{{ old('prix', $departure->prix ?? '') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Bus -->
        <div>
            <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
            <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Sélectionner un bus</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" {{ (old('bus_id', $departure->bus_id ?? '') == $bus->id) ? 'selected' : '' }}>
                        {{ $bus->numero }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Places disponibles -->
        <div>
            <label for="places_disponibles" class="block text-sm font-medium text-gray-700">Places disponibles</label>
            <input type="number" name="places_disponibles" id="places_disponibles" value="{{ old('places_disponibles', $departure->places_disponibles ?? '') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Statut -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="toggleDelayedTime(this.value)">
                <option value="On time" {{ (old('status', $departure->status ?? '') == 'On time') ? 'selected' : '' }}>À l'heure</option>
                <option value="Delayed" {{ (old('status', $departure->status ?? '') == 'Delayed') ? 'selected' : '' }}>Retardé</option>
                <option value="Cancelled" {{ (old('status', $departure->status ?? '') == 'Cancelled') ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <!-- Heure retardée (initialement caché) -->
        <div id="delayed_time_container" style="display: none;">
            <label for="delayed_time" class="block text-sm font-medium text-gray-700">Heure retardée</label>
            <input type="time" name="delayed_time" id="delayed_time" value="{{ old('delayed_time', isset($departure) ? \Carbon\Carbon::parse($departure->delayed_time)->format('H:i') : '') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('departures.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Annuler
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            {{ isset($departure) ? 'Mettre à jour' : 'Créer' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
function toggleDelayedTime(status) {
    const delayedTimeContainer = document.getElementById('delayed_time_container');
    if (status === 'Delayed') {
        delayedTimeContainer.style.display = 'block';
    } else {
        delayedTimeContainer.style.display = 'none';
    }
}

// Exécuter au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    toggleDelayedTime(document.getElementById('status').value);
});
</script>
@endpush
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4 mt-8">
        <a href="{{ route('departures.index') }}" 
            class="inline-flex justify-center rounded-md bg-white px-8 py-3 text-lg font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50">
            Annuler
        </a>
        <button type="submit" 
            class="inline-flex justify-center rounded-md bg-blue-500 px-8 py-3 text-lg font-medium text-white shadow-sm hover:bg-blue-600">
            {{ isset($departure) ? 'Mettre à jour' : 'Créer le départ' }}
        </button>
    </div>
</form>

<script>
    // Attendre que la page soit chargée
    window.onload = function() {
        // Récupérer les éléments
        var statusSelect = document.getElementById('status');
        var delayedTimeContainer = document.getElementById('delayed_time_container');

        // Fonction pour afficher/masquer le champ d'heure retardée
        function handleStatusChange() {
            console.log('Status changed to:', statusSelect.value);
            if (statusSelect.value === 'En retard') {
                delayedTimeContainer.style.display = 'block';
            } else {
                delayedTimeContainer.style.display = 'none';
            }
        }

        // Ajouter l'écouteur d'événement
        statusSelect.addEventListener('change', handleStatusChange);

        // Exécuter au chargement
        handleStatusChange();
    };
</script>
