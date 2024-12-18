@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Statistiques</h2>
        </div>

        <!-- Comparaison mensuelle -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Mois en cours -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $monthlyStats['current']['month_name'] }}</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Réservations</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $monthlyStats['current']['reservations'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Chiffre d'affaires</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyStats['current']['revenue'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Départs</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $monthlyStats['current']['departures'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Mois précédent -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $monthlyStats['last']['month_name'] }}</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Réservations</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $monthlyStats['last']['reservations'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Chiffre d'affaires</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyStats['last']['revenue'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <p class="text-sm text-gray-600">Départs</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $monthlyStats['last']['departures'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Autres statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Top 5 des destinations -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 des destinations</h3>
                <div class="space-y-3">
                    @foreach($topDestinations as $destination)
                    <div class="bg-white rounded-lg p-3 shadow flex justify-between items-center">
                        <span class="text-gray-600">{{ $destination->route }}</span>
                        <span class="font-semibold text-blue-600">{{ $destination->total }} voyages</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistiques des réservations -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Réservations par statut</h3>
                <div class="space-y-3">
                    @foreach($reservationsByStatus as $status)
                    <div class="bg-white rounded-lg p-3 shadow flex justify-between items-center">
                        <span class="text-gray-600">{{ $status->statut }}</span>
                        <span class="font-semibold text-blue-600">{{ $status->total }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
