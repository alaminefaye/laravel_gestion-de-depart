@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Publicités</h2>
            <a href="{{ route('dashboard.advertisements.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Publicité
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

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($advertisements as $ad)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $ad->display_order }}</span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($ad->video_type === 'upload')
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-file-video text-blue-500 mr-2"></i>
                                            Fichier vidéo
                                        </span>
                                    @elseif($ad->video_type === 'youtube')
                                        <span class="inline-flex items-center">
                                            <i class="fab fa-youtube text-red-500 mr-2"></i>
                                            YouTube
                                        </span>
                                    @else
                                        <span class="inline-flex items-center">
                                            <i class="fab fa-google-drive text-green-500 mr-2"></i>
                                            Google Drive
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($ad->video_type === 'upload')
                                        @if($ad->video_file)
                                            <button onclick="previewVideo('{{ Storage::url($ad->video_file) }}')" 
                                                    class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-play mr-1"></i> Voir la vidéo
                                            </button>
                                        @else
                                            <span class="text-gray-500">Aucun fichier</span>
                                        @endif
                                    @else
                                        <a href="{{ $ad->video_link }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-external-link-alt mr-1"></i> Voir le lien
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ad->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ad->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('dashboard.advertisements.edit', $ad) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.advertisements.destroy', $ad) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucune publicité trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($advertisements->hasPages())
            <div class="mt-4">
                {{ $advertisements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal pour l'aperçu vidéo -->
<div id="videoPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Aperçu de la vidéo</h3>
            <button onclick="closeVideoPreview()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="aspect-w-16 aspect-h-9">
            <video id="previewVideo" controls class="w-full h-full rounded">
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewVideo(url) {
        const modal = document.getElementById('videoPreviewModal');
        const video = document.getElementById('previewVideo');
        video.src = url;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeVideoPreview() {
        const modal = document.getElementById('videoPreviewModal');
        const video = document.getElementById('previewVideo');
        video.pause();
        video.src = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
