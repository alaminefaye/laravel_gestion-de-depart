@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ajouter une Annonce</h2>
            <a href="{{ route('dashboard.announcements.index') }}" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <form action="{{ route('dashboard.announcements.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Contenu de l'annonce</label>
                <textarea name="content" id="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                <select name="position" id="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="header" {{ old('position') == 'header' ? 'selected' : '' }}>En-tête</option>
                    <option value="footer" {{ old('position') == 'footer' ? 'selected' : '' }}>Pied de page</option>
                    <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>Barre latérale</option>
                </select>
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Activer l'annonce</label>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="testVoice()" class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none">
                    <i class="fas fa-volume-up mr-1"></i>Tester la voix
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ajouter l'Annonce
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function testVoice() {
    const content = document.getElementById('content').value;
    const voiceType = document.getElementById('voice_type').value;
    
    if (content) {
        const utterance = new SpeechSynthesisUtterance(content);
        utterance.lang = 'fr-FR';
        
        // Récupérer toutes les voix disponibles
        const voices = speechSynthesis.getVoices();
        
        // Filtrer les voix françaises
        const frenchVoices = voices.filter(voice => voice.lang.startsWith('fr'));
        
        // Sélectionner une voix en fonction du type choisi
        if (frenchVoices.length > 0) {
            if (voiceType === 'female') {
                utterance.voice = frenchVoices.find(voice => voice.name.toLowerCase().includes('female')) || frenchVoices[0];
            } else {
                utterance.voice = frenchVoices.find(voice => voice.name.toLowerCase().includes('male')) || frenchVoices[0];
            }
        }
        
        speechSynthesis.speak(utterance);
    }
}

// Charger les voix au démarrage
speechSynthesis.onvoiceschanged = function() {
    speechSynthesis.getVoices();
};
</script>
@endpush
@endsection
