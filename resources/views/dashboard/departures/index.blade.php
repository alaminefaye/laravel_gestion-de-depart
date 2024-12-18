@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gestion des Départs</h1>
        <a href="{{ route('dashboard.departures.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Ajouter un Départ
        </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-500">Total des Départs</h3>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['total_departures'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-500">Départs Aujourd'hui</h3>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['departures_today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-500">Places Disponibles (Moyenne)</h3>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['average_places'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-500">Départs en Retard</h3>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['delayed_count'] }}</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form action="{{ route('dashboard.departures.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Rechercher par Route</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700">Date de Début</label>
                <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700">Date de Fin</label>
                <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous</option>
                    @foreach(App\Models\Departure::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                <select name="bus_id" id="bus_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                            {{ $bus->numero }} - {{ $bus->modele }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Liste des départs -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Route
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bus
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de départ
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de retard
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prix
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Places
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($departures as $departure)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $departure->route }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        Bus N°{{ $departure->bus->numero }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $departure->formatted_scheduled_time }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $departure->formatted_delayed_time ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($departure->status === App\Models\Departure::STATUS_ON_TIME) bg-green-100 text-green-800
                            @elseif($departure->status === App\Models\Departure::STATUS_DELAYED) bg-yellow-100 text-yellow-800
                            @elseif($departure->status === App\Models\Departure::STATUS_CANCELLED) bg-red-100 text-red-800
                            @endif">
                            {{ $departure->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ number_format($departure->prix, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $departure->places_disponibles }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('dashboard.departures.edit', $departure) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            Modifier
                        </a>
                        <form action="{{ route('dashboard.departures.destroy', $departure) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce départ ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $departures->links() }}
    </div>
</div>
@endsection
