@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Gestion des Bus
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Gérez votre flotte de bus et leur maintenance
                </p>
            </div>
            <button onclick="openNewBusModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un bus
            </button>
        </div>

        <!-- Liste des bus -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($buses as $bus)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-bus text-2xl text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        Bus #{{ $bus->numero }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $bus->modele }} - Capacité: {{ $bus->capacite }} places
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($bus->statut === 'En service') bg-green-100 text-green-800
                                    @elseif($bus->statut === 'En maintenance') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $bus->statut }}
                                </span>
                                <button onclick="openUpdateBusModal({{ $bus->id }})" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('dashboard.buses.destroy', $bus->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bus ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-4 py-4 sm:px-6">
                    <div class="text-center text-gray-500">
                        Aucun bus enregistré
                    </div>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Modal pour nouveau bus -->
<div id="newBusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter un nouveau bus</h3>
            <form id="newBusForm" method="POST" action="{{ route('dashboard.store-bus') }}">
                @csrf
                <div class="mb-4">
                    <label for="numero" class="block text-sm font-medium text-gray-700">Numéro</label>
                    <input type="text" name="numero" id="numero" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
                    <input type="text" name="modele" id="modele" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité</label>
                    <input type="number" name="capacite" id="capacite" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" onclick="closeNewBusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour mettre à jour le statut -->
<div id="updateBusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mettre à jour le statut du bus</h3>
            <form id="updateBusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="En service">En service</option>
                        <option value="En maintenance">En maintenance</option>
                        <option value="Hors service">Hors service</option>
                    </select>
                </div>
                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" onclick="closeUpdateBusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/bus-management.js') }}"></script>
@endpush
@endsection
