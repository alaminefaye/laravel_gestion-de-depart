@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Annonces</h1>
        <a href="{{ route('dashboard.announcements.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nouvelle Annonce
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($announcements as $announcement)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $announcement->formatted_position }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('dashboard.announcements.toggle', $announcement) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 text-sm rounded-full {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $announcement->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                        <button onclick="testVoice('{{ $announcement->content }}', '{{ $announcement->voice_type }}')" 
                                class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-volume-up"></i>
                        </button>
                        <a href="{{ route('dashboard.announcements.edit', $announcement) }}" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('dashboard.announcements.destroy', $announcement) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>

@push('scripts')
<script>
function testVoice(text, voiceType) {
    const utterance = new SpeechSynthesisUtterance(text);
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

// Charger les voix au démarrage
speechSynthesis.onvoiceschanged = function() {
    speechSynthesis.getVoices();
};
</script>
@endpush
@endsection
