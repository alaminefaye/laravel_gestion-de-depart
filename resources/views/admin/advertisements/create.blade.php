@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Ajouter une Publicité</h1>
        <a href="{{ route('admin.advertisements.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf
        
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
            <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded-lg" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Type de Vidéo</label>
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="video_type" value="upload" class="form-radio" checked 
                           onclick="toggleVideoInput('upload')">
                    <span class="ml-2">Télécharger une vidéo</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="video_type" value="youtube" class="form-radio"
                           onclick="toggleVideoInput('youtube')">
                    <span class="ml-2">Lien YouTube</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="video_type" value="drive" class="form-radio"
                           onclick="toggleVideoInput('drive')">
                    <span class="ml-2">Lien Google Drive</span>
                </label>
            </div>
        </div>

        <div id="upload-section" class="mb-6">
            <label for="video" class="block text-sm font-medium text-gray-700 mb-2">Vidéo</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                <div class="space-y-1 text-center">
                    <i class="fas fa-film text-4xl text-gray-400"></i>
                    <div class="flex text-sm text-gray-600">
                        <label for="video" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                            <span>Télécharger une vidéo</span>
                            <input id="video" name="video" type="file" class="sr-only" accept="video/mp4,video/webm">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">MP4, WebM jusqu'à 50MB</p>
                </div>
            </div>
        </div>

        <div id="link-section" class="mb-6 hidden">
            <label for="video_link" class="block text-sm font-medium text-gray-700 mb-2">Lien de la Vidéo</label>
            <input type="url" name="video_link" id="video_link" class="w-full px-3 py-2 border rounded-lg" 
                   placeholder="https://">
            <p class="mt-1 text-sm text-gray-500">Entrez le lien YouTube ou Google Drive de votre vidéo</p>
        </div>

        <div class="mb-6">
            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                Ordre d'affichage
            </label>
            <input type="number" name="display_order" id="display_order" class="w-full px-3 py-2 border rounded-lg" 
                   value="0" min="0">
            <p class="mt-1 text-sm text-gray-500">L'ordre dans lequel les publicités seront affichées (0 = premier)</p>
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="form-checkbox" checked>
                <span class="ml-2">Activer la publicité immédiatement</span>
            </label>
        </div>

        <div class="flex justify-end space-x-4">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 border rounded-lg">
                Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Ajouter la Publicité
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleVideoInput(type) {
    const uploadSection = document.getElementById('upload-section');
    const linkSection = document.getElementById('link-section');
    const videoInput = document.getElementById('video');
    const linkInput = document.getElementById('video_link');

    if (type === 'upload') {
        uploadSection.classList.remove('hidden');
        linkSection.classList.add('hidden');
        videoInput.required = true;
        linkInput.required = false;
    } else {
        uploadSection.classList.add('hidden');
        linkSection.classList.remove('hidden');
        videoInput.required = false;
        linkInput.required = true;
    }
}
</script>
@endpush
