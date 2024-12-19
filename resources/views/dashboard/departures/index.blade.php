@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-bus text-blue-600 mr-3"></i>
            Gestion des Départs
        </h1>
        <a href="{{ route('dashboard.departures.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Ajouter un Départ
        </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-bus text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total des Départs</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_departures'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Départs Aujourd'hui</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['departures_today'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chair text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Places Disponibles</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['average_places'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Départs en Retard</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['delayed_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtres de Recherche
        </h2>
        <form action="{{ route('dashboard.departures.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher par Route</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Date de Début</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar text-gray-400"></i>
                    </div>
                    <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}"
                           class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Date de Fin</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar text-gray-400"></i>
                    </div>
                    <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}"
                           class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-gray-400"></i>
                    </div>
                    <select name="status" id="status"
                            class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        @foreach(App\Models\Departure::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="bus_id" class="block text-sm font-medium text-gray-700 mb-1">Bus</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-bus text-gray-400"></i>
                    </div>
                    <select name="bus_id" id="bus_id"
                            class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                                Bus N°{{ $bus->numero }} - {{ $bus->modele }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Liste des départs -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Route
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bus
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Horaire
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Places
                        </th>
                        <th scope="col" class="relative px-6 py-4">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($departures as $departure)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $departure->route }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-bus mr-1"></i>
                                N°{{ $departure->bus->numero }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($departure->formatted_delayed_time)
                                    <div class="text-sm font-medium text-gray-900 line-through">{{ $departure->formatted_scheduled_time }}</div>
                                    <div class="text-sm font-medium text-white bg-red-600 px-2 py-0.5 rounded-md flex items-center">
                                        <span class="mr-1">→</span>
                                        {{ $departure->formatted_delayed_time }}
                                    </div>
                                @else
                                    <div class="text-sm font-medium text-gray-900">{{ $departure->formatted_scheduled_time }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'À l\'heure' => 'bg-green-100 text-green-800 border border-green-200',
                                    'En retard' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                    'Annulé' => 'bg-red-100 text-red-800 border border-red-200',
                                    'Inconnu' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$departure->status_label] }}">
                                {{ $departure->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $departure->formatted_price }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-chair mr-1"></i>
                                {{ $departure->places_disponibles }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.departures.show', $departure) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('dashboard.departures.edit', $departure) }}" 
                               class="text-green-600 hover:text-green-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.departures.destroy', $departure) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce départ ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($departures->isEmpty())
            <div class="text-center py-8">
                <div class="text-gray-500">
                    <i class="fas fa-bus text-4xl mb-4"></i>
                    <p>Aucun départ trouvé</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $departures->links() }}
    </div>
</div>
@endsection
