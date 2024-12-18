@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Nouvelle Réservation</h2>
            <a href="{{ route('dashboard.reservations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <form action="{{ route('dashboard.reservations.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Départ -->
            <div>
                <label for="departure_id" class="block text-sm font-medium text-gray-700">Départ</label>
                <select name="departure_id" id="departure_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                    <option value="">Sélectionnez un départ</option>
                    @foreach($departures as $departure)
                        <option value="{{ $departure->id }}" 
                                data-prix="{{ $departure->prix }}"
                                data-bus-capacite="{{ $departure->bus->capacite }}"
                                data-places-disponibles="{{ $departure->places_disponibles }}"
                                {{ old('departure_id') == $departure->id ? 'selected' : '' }}>
                            {{ $departure->route }} - {{ $departure->scheduled_time->format('d/m/Y H:i') }} - {{ $departure->prix }} FCFA
                        </option>
                    @endforeach
                </select>
                @error('departure_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informations du client -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom_client" class="block text-sm font-medium text-gray-700">Nom du client</label>
                    <input type="text" name="nom_client" id="nom_client" value="{{ old('nom_client') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nom_client')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Nombre de places -->
            <div>
                <label for="nombre_places" class="block text-sm font-medium text-gray-700">Nombre de places</label>
                <input type="number" name="nombre_places" id="nombre_places" value="{{ old('nombre_places', 1) }}" min="1" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('nombre_places')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection des sièges -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélection des sièges</label>
                <input type="hidden" name="siege_numeros" id="siege_numeros" value="{{ old('siege_numeros') }}">
                <div id="siege-grid" class="grid grid-cols-4 gap-2 mt-2">
                    <!-- Les sièges seront générés ici par JavaScript -->
                </div>
                @error('siege_numeros')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix total -->
            <div>
                <label for="prix_total" class="block text-sm font-medium text-gray-700">Prix total (FCFA)</label>
                <input type="number" name="prix_total" id="prix_total" step="0.01" value="{{ old('prix_total') }}" required readonly
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                @error('prix_total')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                    <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmé" {{ old('statut') == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                    <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                </select>
                @error('statut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard.reservations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Annuler</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Créer la réservation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departureSelect = document.getElementById('departure_id');
    const nombrePlacesInput = document.getElementById('nombre_places');
    const prixTotalInput = document.getElementById('prix_total');
    const siegeGrid = document.getElementById('siege-grid');
    const siegeNumerosInput = document.getElementById('siege_numeros');
    
    let selectedSeats = [];
    let occupiedSeats = [];
    
    function updatePrixTotal() {
        const departureId = departureSelect.value;
        const nombrePlaces = nombrePlacesInput.value;
        
        if (departureId && nombrePlaces) {
            const selectedOption = departureSelect.options[departureSelect.selectedIndex];
            const prixUnitaire = parseFloat(selectedOption.dataset.prix);
            const prixTotal = (prixUnitaire * nombrePlaces).toFixed(2);
            prixTotalInput.value = prixTotal;
        }
    }
    
    function getOccupiedSeats(departureId) {
        fetch(`/api/departures/${departureId}/occupied-seats`)
            .then(response => response.json())
            .then(data => {
                occupiedSeats = data;
                generateSeatGrid();
            });
    }
    
    function generateSeatGrid() {
        const selectedOption = departureSelect.options[departureSelect.selectedIndex];
        if (!selectedOption.value) return;
        
        const busCapacite = parseInt(selectedOption.dataset.busCapacite);
        siegeGrid.innerHTML = '';
        
        for (let i = 1; i <= busCapacite; i++) {
            const seatDiv = document.createElement('div');
            seatDiv.className = `p-2 border rounded cursor-pointer text-center ${
                occupiedSeats.includes(i) ? 'bg-red-200 cursor-not-allowed' :
                selectedSeats.includes(i) ? 'bg-green-200' : 'bg-gray-100 hover:bg-gray-200'
            }`;
            seatDiv.textContent = i;
            
            if (!occupiedSeats.includes(i)) {
                seatDiv.addEventListener('click', () => toggleSeat(i));
            }
            
            siegeGrid.appendChild(seatDiv);
        }
        
        updateSiegeNumerosInput();
    }
    
    function toggleSeat(seatNumber) {
        const nombrePlaces = parseInt(nombrePlacesInput.value);
        const seatIndex = selectedSeats.indexOf(seatNumber);
        
        if (seatIndex === -1) {
            if (selectedSeats.length < nombrePlaces) {
                selectedSeats.push(seatNumber);
            }
        } else {
            selectedSeats.splice(seatIndex, 1);
        }
        
        generateSeatGrid();
    }
    
    function updateSiegeNumerosInput() {
        siegeNumerosInput.value = JSON.stringify(selectedSeats);
    }
    
    departureSelect.addEventListener('change', function() {
        selectedSeats = [];
        if (this.value) {
            getOccupiedSeats(this.value);
        } else {
            siegeGrid.innerHTML = '';
        }
        updatePrixTotal();
    });
    
    nombrePlacesInput.addEventListener('change', function() {
        const maxPlaces = parseInt(departureSelect.options[departureSelect.selectedIndex]?.dataset.placesDisponibles || 0);
        if (this.value > maxPlaces) {
            this.value = maxPlaces;
        }
        selectedSeats = [];
        generateSeatGrid();
        updatePrixTotal();
    });
    
    // Initialisation
    if (departureSelect.value) {
        getOccupiedSeats(departureSelect.value);
        updatePrixTotal();
    }
});
</script>
@endpush
