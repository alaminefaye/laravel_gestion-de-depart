@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Détails du Départ</h1>
            <a href="{{ route('dashboard.departures.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold mb-4">Informations Générales</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Route</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->route }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date et Heure Prévues</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->scheduled_time->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($departure->prix, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <p class="mt-1 text-sm">
                            @switch($departure->status)
                                @case('A l\'heure')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $departure->status }}
                                    </span>
                                    @break
                                @case('En retard')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $departure->status }}
                                    </span>
                                    @break
                                @case('Annule')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $departure->status }}
                                    </span>
                                    @break
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-4">Informations du Bus</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro du Bus</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->bus->numero }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modèle</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->bus->modele }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacité</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->bus->capacite }} places</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Places Disponibles</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $departure->places_disponibles }} places</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="text-xl font-semibold">Détails du départ</h2>
            <div class="mt-2">
                <p><strong>Route:</strong> {{ $departure->route }}</p>
                <p><strong>Heure prévue:</strong> {{ $departure->scheduled_time ?: '--:--' }}</p>
                <p><strong>Heure retardée:</strong> {{ $departure->delayed_time ?: '--:--' }}</p>
                <p><strong>Statut:</strong> {{ $departure->status }}</p>
                <p><strong>Bus:</strong> {{ $departure->bus->numero }}</p>
                <p><strong>Prix:</strong> {{ number_format($departure->prix, 2) }} €</p>
                <p><strong>Places disponibles:</strong> {{ $departure->places_disponibles }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('dashboard.departures.edit', $departure->id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Modifier
            </a>
            <form action="{{ route('dashboard.departures.destroy', $departure->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" 
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce départ ?')">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

            <form action="{{ route('dashboard.departures.destroy', $departure->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" 
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce départ ?')">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
