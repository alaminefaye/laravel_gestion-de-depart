@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Annonces</h2>
            <a href="{{ route('dashboard.announcements.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Ajouter une Annonce
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($announcements as $announcement)
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition duration-150">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="prose max-w-none">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ ucfirst($announcement->position) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $announcement->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $announcement->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                                <span class="inline-flex items-center text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <a href="{{ route('dashboard.announcements.edit', $announcement) }}" 
                               class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition duration-150"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition duration-150"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-bullhorn text-4xl mb-4"></i>
                    <p>Aucune annonce n'a été créée pour le moment.</p>
                    <a href="{{ route('dashboard.announcements.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                        Créer votre première annonce
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
