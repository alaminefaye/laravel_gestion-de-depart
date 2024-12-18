@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modifier la Réservation</h2>
            <a href="{{ route('dashboard.reservations.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>

        <form action="{{ route('dashboard.reservations.update', $reservation) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de la Réservation</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Référence</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->reference }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" id="statut" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @foreach($statuts as $statut)
                                <option value="{{ $statut }}" {{ $reservation->statut === $statut ? 'selected' : '' }}>
                                    {{ $statut }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes_admin" class="block text-sm font-medium text-gray-700 mb-2">Notes administratives</label>
                        <textarea name="notes_admin" id="notes_admin" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                  placeholder="Ajoutez des notes internes ici...">{{ old('notes_admin', $reservation->notes_admin) }}</textarea>
                        @error('notes_admin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations client et voyage -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Client et Voyage</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                        <p class="text-gray-900">{{ $reservation->nom_client }}</p>
                        <p class="text-gray-600 text-sm">{{ $reservation->email }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Départ</label>
                        <p class="text-gray-900">{{ $reservation->departure->route }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ $reservation->departure->scheduled_time->format('d/m/Y à H:i') }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Places réservées</label>
                        <p class="text-gray-900">{{ $reservation->nombre_places }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant total</label>
                        <p class="text-gray-900 font-medium">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
