@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Détails de la Réservation</h2>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard.reservations.edit', $reservation) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('dashboard.reservations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de la réservation -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de la Réservation</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Référence</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->reference }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Statut</p>
                        <span class="px-2 py-1 text-sm font-semibold rounded-full 
                            {{ $reservation->statut === 'Confirmé' ? 'bg-green-100 text-green-800' : 
                               ($reservation->statut === 'Annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $reservation->statut }}
                        </span>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Date de réservation</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Montant total</p>
                        <p class="text-lg font-semibold text-green-600">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>

            <!-- Informations du client -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du Client</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Nom complet</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->nom_client }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->email }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->telephone }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations du voyage -->
            <div class="bg-gray-50 rounded-lg p-6 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du Voyage</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Route</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->departure->route }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Date et heure de départ</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $reservation->departure->scheduled_time->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Bus</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->departure->bus->name }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Nombre de places</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->nombre_places }}</p>
                    </div>
                </div>
            </div>

            @if($reservation->notes_admin)
            <!-- Notes administratives -->
            <div class="bg-gray-50 rounded-lg p-6 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes Administratives</h3>
                <div class="bg-white rounded-lg p-4 shadow">
                    <p class="text-gray-800 whitespace-pre-line">{{ $reservation->notes_admin }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
