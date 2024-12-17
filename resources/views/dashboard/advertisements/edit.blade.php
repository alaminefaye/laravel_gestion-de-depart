@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modifier la Publicité</h2>
            <a href="{{ route('dashboard.advertisements.index') }}" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Veuillez corriger les erreurs suivantes :
                    </p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('dashboard.advertisements.update', $advertisement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $advertisement->title) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de Vidéo</label>
                    <div class="flex space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="upload" class="form-radio text-blue-500" 
                                   {{ old('video_type', $advertisement->video_type) === 'upload' ? 'checked' : '' }}
                                   onclick="toggleVideoInput('upload')">
                            <span class="ml-2">Télécharger une vidéo</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="youtube" class="form-radio text-blue-500"
                                   {{ old('video_type', $advertisement->video_type) === 'youtube' ? 'checked' : '' }}
                                   onclick="toggleVideoInput('youtube')">
                            <span class="ml-2">Lien YouTube</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="drive" class="form-radio text-blue-500"
                                   {{ old('video_type', $advertisement->video_type) === 'drive' ? 'checked' : '' }}
                                   onclick="toggleVideoInput('drive')">
                            <span class="ml-2">Lien Google Drive</span>
                        </label>
                    </div>
                </div>

                <div id="upload-section" class="md:col-span-2 {{ old('video_type', $advertisement->video_type) !== 'upload' ? 'hidden' : '' }}">
                    <label for="video" class="block text-sm font-medium text-gray-700">Vidéo</label>
                    @if($advertisement->video_type === 'upload' && $advertisement->video_path)
                    <div class="mt-2 mb-4">
                        <video class="w-full rounded" controls>
                            <source src="{{ Storage::url($advertisement->video_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </div>
                    @endif
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-film text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="video" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>{{ $advertisement->video_type === 'upload' ? 'Changer la vidéo' : 'Télécharger une vidéo' }}</span>
                                    <input id="video" name="video" type="file" class="sr-only" accept="video/mp4,video/quicktime">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">MP4, QuickTime jusqu'à 50MB</p>
                        </div>
                    </div>
                </div>

                <div id="link-section" class="md:col-span-2 {{ old('video_type', $advertisement->video_type) === 'upload' ? 'hidden' : '' }}">
                    <label for="video_link" class="block text-sm font-medium text-gray-700">Lien de la Vidéo</label>
                    <input type="url" name="video_link" id="video_link" 
                           value="{{ old('video_link', $advertisement->video_type !== 'upload' ? $advertisement->video_path : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           placeholder="https://">
                    <p class="mt-1 text-sm text-gray-500">Entrez le lien YouTube ou Google Drive de votre vidéo</p>
                </div>

                <div class="md:col-span-2">
                    <label for="display_order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                    <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $advertisement->display_order) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">L'ordre dans lequel les publicités seront affichées (0 = premier)</p>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $advertisement->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Publicité active
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="window.history.back()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
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

// Initialiser l'état correct au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const currentType = document.querySelector('input[name="video_type"]:checked').value;
    toggleVideoInput(currentType);
});
</script>
@endpush
@endsection
