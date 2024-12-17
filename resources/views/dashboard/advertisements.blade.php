@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Publicités</h2>
            <a href="{{ route('dashboard.advertisements.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-2"></i>Ajouter une Publicité
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($advertisements as $ad)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="aspect-w-16 aspect-h-9 mb-4">
                    <video class="w-full rounded" controls>
                        <source src="{{ asset($ad->video_url) }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </div>
                <h3 class="font-semibold text-lg mb-2">{{ $ad->title }}</h3>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Statut:</span>
                        <span class="px-2 py-1 rounded text-sm {{ $ad->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ad->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('dashboard.advertisements.destroy', $ad) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $advertisements->links() }}
        </div>
    </div>
</div>
@endsection
