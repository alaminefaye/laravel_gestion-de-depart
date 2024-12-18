@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Réservations</h2>
            <a href="{{ route('dashboard.reservations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Nouvelle Réservation
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Filtres -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <form action="{{ route('dashboard.reservations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           placeholder="Référence, nom...">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut }}" {{ request('status') == $statut ? 'selected' : '' }}>
                                {{ $statut }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                    <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                    <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Places</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->reference }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->nom_client }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->departure->route }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->departure->scheduled_time ? $reservation->departure->scheduled_time->format('d/m/Y') : 'Non planifié' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reservation->nombre_places }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $reservation->statut === 'Confirmé' ? 'bg-green-100 text-green-800' : 
                                       ($reservation->statut === 'Annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $reservation->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('dashboard.reservations.show', $reservation) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.reservations.edit', $reservation) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.reservations.destroy', $reservation) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucune réservation trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservations->hasPages())
            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
