@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modifier l'Annonce</h2>
            <a href="{{ route('dashboard.announcements.index') }}" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <form action="{{ route('dashboard.announcements.update', $announcement) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="mt-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Contenu de l'annonce</label>
                <textarea id="content" name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $announcement->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="voice_type" class="block text-sm font-medium text-gray-700">Type de voix</label>
                <div class="flex items-center space-x-2">
                    <select name="voice_type" id="voice_type" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="male" {{ old('voice_type', $announcement->voice_type) === 'male' ? 'selected' : '' }}>Voix masculine</option>
                        <option value="female" {{ old('voice_type', $announcement->voice_type) === 'female' ? 'selected' : '' }}>Voix féminine</option>
                    </select>
                    <button type="button" onclick="testVoice()" class="mt-1 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-volume-up mr-2"></i>
                        Tester la voix
                    </button>
                </div>
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                <select name="position" id="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="footer" {{ old('position', $announcement->position) == 'footer' ? 'selected' : '' }}>Pied de page</option>
                    <option value="header" {{ old('position', $announcement->position) == 'header' ? 'selected' : '' }}>En-tête</option>
                    <option value="sidebar" {{ old('position', $announcement->position) == 'sidebar' ? 'selected' : '' }}>Barre latérale</option>
                </select>
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Activer l'annonce</label>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard.announcements.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Mettre à jour l'Annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let availableVoices = [];

function loadVoices() {
    availableVoices = window.speechSynthesis.getVoices();
    console.log('Voix disponibles:', availableVoices.map(v => `${v.name} (${v.lang})`));
}

if ('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = loadVoices;
    loadVoices();
}

function findVoice(gender) {
    // Filtrer les voix françaises
    let frenchVoices = availableVoices.filter(voice => 
        voice.lang.startsWith('fr') || voice.lang.startsWith('fr-FR')
    );

    if (frenchVoices.length === 0) {
        frenchVoices = availableVoices;
    }

    // Chercher une voix correspondant au genre
    let voice = frenchVoices.find(voice => {
        const voiceName = voice.name.toLowerCase();
        if (gender === 'female') {
            return voiceName.includes('female') || 
                   voiceName.includes('woman') || 
                   voiceName.includes('girl') || 
                   (voiceName.includes('f') && !voiceName.includes('male'));
        } else {
            return voiceName.includes('male') || 
                   voiceName.includes('man') || 
                   voiceName.includes('boy');
        }
    });

    // Si aucune voix du bon genre n'est trouvée, prendre la première voix française
    if (!voice && frenchVoices.length > 0) {
        voice = frenchVoices[0];
    }

    return voice;
}

function testVoice() {
    const content = document.getElementById('content').value;
    if (!content) {
        alert('Veuillez entrer du texte avant de tester la voix.');
        return;
    }

    window.speechSynthesis.cancel();

    const voiceType = document.getElementById('voice_type').value;
    const utterance = new SpeechSynthesisUtterance(content);
    utterance.lang = 'fr-FR';
    
    if (voiceType === 'female') {
        utterance.pitch = 1.2;
        utterance.rate = 1.0;
    } else {
        utterance.pitch = 0.9;
        utterance.rate = 0.95;
    }

    const voice = findVoice(voiceType);
    if (voice) {
        utterance.voice = voice;
        console.log('Utilisation de la voix:', voice.name);
    } else {
        console.warn('Aucune voix appropriée trouvée pour le genre:', voiceType);
        alert(`Aucune voix ${voiceType === 'female' ? 'féminine' : 'masculine'} n'a été trouvée. La voix par défaut sera utilisée.`);
    }

    window.speechSynthesis.speak(utterance);
}
</script>
@endpush
