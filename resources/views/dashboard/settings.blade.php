@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Paramètres du Site</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Logo du Site
                    </label>
                    @if($settings->logo_path)
                        <div class="mb-4">
                            <img src="{{ Storage::url($settings->logo_path) }}" 
                                 alt="Logo actuel" 
                                 class="h-20 object-contain">
                            <p class="text-sm text-gray-500 mt-1">Logo actuel</p>
                        </div>
                    @endif
                    <input type="file" 
                           name="logo" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom du Site -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du Site
                    </label>
                    <input type="text" 
                           name="site_name" 
                           value="{{ old('site_name', $settings->site_name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('site_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email de Contact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email de Contact
                    </label>
                    <input type="email" 
                           name="contact_email" 
                           value="{{ old('contact_email', $settings->contact_email) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('contact_email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone de Contact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone de Contact
                    </label>
                    <input type="text" 
                           name="contact_phone" 
                           value="{{ old('contact_phone', $settings->contact_phone) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('contact_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Deuxième Téléphone de Contact -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deuxième Téléphone de Contact
                                </label>
                                <input type="text" 
                                    name="contact_phone_2" 
                                    value="{{ old('contact_phone_2', $settings->contact_phone_2) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('contact_phone_2')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
                <!-- Slogan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slogan
                    </label>
                    <input type="text" 
                        name="slogan" 
                        value="{{ old('slogan', $settings->slogan) }}"
                        placeholder="Entrez le slogan de votre site"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('slogan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Texte du Pied de Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Texte du Pied de Page
                    </label>
                    <textarea name="footer_text" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('footer_text', $settings->footer_text) }}</textarea>
                    @error('footer_text')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton de Soumission -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Enregistrer les Modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
