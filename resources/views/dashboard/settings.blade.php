@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Paramètres</h2>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Paramètres du Site -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-globe mr-2"></i>
                    Paramètres du Site
                </h3>
                <form action="{{ route('dashboard.settings.update-site') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Nom du Site</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', config('app.name')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email de Contact</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', config('mail.from.address')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Numéro de Téléphone</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', '+225 00000000') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les Paramètres du Site
                    </button>
                </form>
            </div>

            <!-- Paramètres de Notification -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bell mr-2"></i>
                    Paramètres de Notification
                </h3>
                <form action="{{ route('dashboard.settings.update-notifications') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="notify_new_reservation" id="notify_new_reservation" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked>
                            <label for="notify_new_reservation" class="ml-2 block text-sm text-gray-700">
                                Notification pour nouvelle réservation
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="notify_departure_status" id="notify_departure_status" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked>
                            <label for="notify_departure_status" class="ml-2 block text-sm text-gray-700">
                                Notification pour changement de statut de départ
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="notify_low_seats" id="notify_low_seats" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked>
                            <label for="notify_low_seats" class="ml-2 block text-sm text-gray-700">
                                Notification pour places limitées
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les Paramètres de Notification
                    </button>
                </form>
            </div>

            <!-- Paramètres de Maintenance -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-tools mr-2"></i>
                    Paramètres de Maintenance
                </h3>
                <form action="{{ route('dashboard.settings.update-maintenance') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="maintenance_interval" class="block text-sm font-medium text-gray-700">
                            Intervalle de Maintenance (en jours)
                        </label>
                        <input type="number" name="maintenance_interval" id="maintenance_interval" min="1" value="30"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="maintenance_threshold" class="block text-sm font-medium text-gray-700">
                            Seuil d'Alerte de Maintenance (en jours)
                        </label>
                        <input type="number" name="maintenance_threshold" id="maintenance_threshold" min="1" value="7"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les Paramètres de Maintenance
                    </button>
                </form>
            </div>

            <!-- Paramètres de Réservation -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Paramètres de Réservation
                </h3>
                <form action="{{ route('dashboard.settings.update-booking') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="booking_time_limit" class="block text-sm font-medium text-gray-700">
                            Délai de Paiement (en heures)
                        </label>
                        <input type="number" name="booking_time_limit" id="booking_time_limit" min="1" value="24"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="max_seats_per_booking" class="block text-sm font-medium text-gray-700">
                            Nombre Maximum de Places par Réservation
                        </label>
                        <input type="number" name="max_seats_per_booking" id="max_seats_per_booking" min="1" value="5"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="allow_overbooking" id="allow_overbooking" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="allow_overbooking" class="ml-2 block text-sm text-gray-700">
                            Autoriser la surréservation
                        </label>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les Paramètres de Réservation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
