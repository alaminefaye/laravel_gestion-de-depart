@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modifier le Départ</h2>
            <a href="{{ route('dashboard.departures.index') }}" class="text-blue-500 hover:text-blue-700">
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

        <form action="{{ route('dashboard.departures.update', $departure) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
                    <input type="text" name="route" id="route" value="{{ old('route', $departure->route) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                    <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Sélectionner un bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id', $departure->bus_id) == $bus->id ? 'selected' : '' }}>
                                Bus N°{{ $bus->numero }} ({{ $bus->capacite }} places)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach(App\Models\Departure::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $departure->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="prix" id="prix" step="1" min="0" value="{{ old('prix', $departure->prix) }}" 
                               class="block w-full pr-12 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">CFA</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="places_disponibles" class="block text-sm font-medium text-gray-700">Places disponibles</label>
                    <input type="number" name="places_disponibles" id="places_disponibles" min="0" 
                           value="{{ old('places_disponibles', $departure->places_disponibles) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="scheduled_time" class="block text-sm font-medium text-gray-700">Date et heure de départ</label>
                    <input type="datetime-local" name="scheduled_time" id="scheduled_time" 
                           value="{{ old('scheduled_time', $departure->scheduled_time ? $departure->scheduled_time->format('Y-m-d\TH:i') : '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div id="delayed_time_container">
                    <label for="delayed_time" class="block text-sm font-medium text-gray-700">Date et heure de retard</label>
                    <input type="datetime-local" name="delayed_time" id="delayed_time" 
                           value="{{ old('delayed_time', $departure->delayed_time ? $departure->delayed_time->format('Y-m-d\TH:i') : '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const delayedTimeInput = document.getElementById('delayed_time');
    const scheduledTimeInput = document.getElementById('scheduled_time');
    const delayedTimeContainer = document.getElementById('delayed_time_container');

    function updateDelayedTime() {
        const selectedStatus = statusSelect.value;
        const scheduledTime = scheduledTimeInput.value;
        
        if (selectedStatus === '2') { // En retard
            if (!delayedTimeInput.value && scheduledTime) {
                const date = new Date(scheduledTime);
                date.setHours(date.getHours() + 1);
                delayedTimeInput.value = date.toISOString().slice(0, 16);
            }
        } else {
            delayedTimeInput.value = '';
        }
    }

    statusSelect.addEventListener('change', updateDelayedTime);
    scheduledTimeInput.addEventListener('change', function() {
        if (statusSelect.value === '2') {
            updateDelayedTime();
        }
    });

    // Initialisation
    updateDelayedTime();
});
</script>
@endpush
