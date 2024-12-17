@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Détails du Bus</h1>
                <a href="{{ route('dashboard.buses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Retour
                </a>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Numéro</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bus->numero }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Modèle</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bus->modele }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Capacité</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bus->capacite }} places</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Année</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $bus->annee }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <p class="mt-1">
                        @switch($bus->statut)
                            @case('Actif')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $bus->statut }}
                                </span>
                                @break
                            @case('En maintenance')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $bus->statut }}
                                </span>
                                @break
                            @case('Hors service')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $bus->statut }}
                                </span>
                                @break
                        @endswitch
                    </p>
                </div>

                <div class="border-t pt-6 mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Départs programmés</h2>
                    @if($bus->departures->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bus->departures->sortBy('scheduled_time') as $departure)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $departure->route }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $departure->scheduled_time->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Aucun départ programmé pour ce bus.</p>
                    @endif
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('dashboard.buses.edit', $bus->id) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Modifier
                    </a>
                    <form action="{{ route('dashboard.buses.destroy', $bus->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bus ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
