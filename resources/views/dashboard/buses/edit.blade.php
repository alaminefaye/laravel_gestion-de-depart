@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Modifier le Bus</h1>
                <a href="{{ route('dashboard.buses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Retour
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreurs!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.buses.update', $bus->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700">Numéro</label>
                        <input type="text" name="numero" id="numero" value="{{ old('numero', $bus->numero) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
                        <input type="text" name="modele" id="modele" value="{{ old('modele', $bus->modele) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité</label>
                        <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $bus->capacite) }}" required min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700">Année</label>
                        <input type="number" name="annee" id="annee" value="{{ old('annee', $bus->annee) }}" required
                               min="1900" max="{{ date('Y') + 1 }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="statut" id="statut" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Actif" {{ old('statut', $bus->statut) == 'Actif' ? 'selected' : '' }}>Actif</option>
                            <option value="En maintenance" {{ old('statut', $bus->statut) == 'En maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="Hors service" {{ old('statut', $bus->statut) == 'Hors service' ? 'selected' : '' }}>Hors service</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
