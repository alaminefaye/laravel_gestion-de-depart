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
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Activer l'annonce</label>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="voice_type" class="block text-sm font-medium text-gray-700">Type de voix</label>
                <select name="voice_type" id="voice_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="updateVoiceType(this.value)">
                    <option value="male">Voix masculine</option>
                    <option value="female">Voix féminine</option>
                </select>
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
let availableVoices = [];
let currentVoiceType = 'male';

// Définir les voix françaises préférées
const PREFERRED_VOICES = {
    male: ['Thomas', 'Nicolas', 'Daniel', 'Jean'],
    female: ['Amélie', 'Marie', 'Joanna', 'Alice']
};

function updateVoiceType(type) {
    currentVoiceType = type;
    console.log('Type de voix sélectionné:', type);
}

function loadVoices() {
    availableVoices = window.speechSynthesis.getVoices();
    console.log('Voix disponibles:', availableVoices.map(v => `${v.name} (${v.lang})`));
}

if ('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = loadVoices;
    loadVoices();
}

function isVoiceFemale(voiceName) {
    voiceName = voiceName.toLowerCase();
    return PREFERRED_VOICES.female.some(name => voiceName.includes(name.toLowerCase())) ||
           voiceName.includes('female') ||
           voiceName.includes('woman') ||
           voiceName.includes('girl') ||
           voiceName.includes('f') && !voiceName.includes('male');
}

function isVoiceMale(voiceName) {
    voiceName = voiceName.toLowerCase();
    return PREFERRED_VOICES.male.some(name => voiceName.includes(name.toLowerCase())) ||
           voiceName.includes('male') ||
           voiceName.includes('man') ||
           voiceName.includes('boy');
}

function findVoice(gender) {
    console.log('Recherche d\'une voix', gender);
    
    // Filtrer les voix françaises
    let frenchVoices = availableVoices.filter(voice => 
        voice.lang.startsWith('fr') || voice.lang.startsWith('fr-FR')
    );

    if (frenchVoices.length === 0) {
        console.log('Aucune voix française trouvée, utilisation de toutes les voix');
        frenchVoices = availableVoices;
    }

    // Fonction de vérification selon le genre
    const isCorrectGender = gender === 'female' ? isVoiceFemale : isVoiceMale;

    // Chercher d'abord dans les voix françaises
    let voice = frenchVoices.find(voice => isCorrectGender(voice.name));

    // Si aucune voix française du bon genre n'est trouvée, chercher dans toutes les voix
    if (!voice) {
        voice = availableVoices.find(voice => isCorrectGender(voice.name));
    }

    // Logging pour le débogage
    if (voice) {
        console.log(`Voix ${gender} trouvée:`, voice.name);
    } else {
        console.log(`Aucune voix ${gender} trouvée`);
    }

    return voice;
}

function testVoice() {
    const content = document.getElementById('content').value;
    if (!content) {
        alert('Veuillez entrer du texte avant de tester la voix.');
        return;
    }

    // Arrêter toute synthèse vocale en cours
    window.speechSynthesis.cancel();

    const voiceType = document.getElementById('voice_type').value;
    console.log('Test avec le type de voix:', voiceType);

    const utterance = new SpeechSynthesisUtterance(content);
    utterance.lang = 'fr-FR';
    
    // Ajuster les paramètres selon le genre
    if (voiceType === 'female') {
        utterance.pitch = 1.2;  // Plus aigu pour les voix féminines
        utterance.rate = 1.0;   // Vitesse normale
    } else {
        utterance.pitch = 0.9;  // Plus grave pour les voix masculines
        utterance.rate = 0.95;  // Légèrement plus lent
    }

    const voice = findVoice(voiceType);
    if (voice) {
        utterance.voice = voice;
        console.log('Utilisation de la voix:', voice.name);
    } else {
        console.warn('Aucune voix appropriée trouvée pour le genre:', voiceType);
        alert(`Aucune voix ${voiceType === 'female' ? 'féminine' : 'masculine'} n'a été trouvée. La voix par défaut sera utilisée.`);
    }

    // Événements de suivi
    utterance.onstart = () => console.log('Début de la synthèse vocale');
    utterance.onend = () => console.log('Fin de la synthèse vocale');
    utterance.onerror = (event) => console.error('Erreur de synthèse vocale:', event);

    window.speechSynthesis.speak(utterance);
}
</script>
@endpush
@endsection
