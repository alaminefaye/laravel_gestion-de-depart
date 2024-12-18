@php
    $settings = \App\Models\SiteSetting::getSettings();
@endphp

<div class="fixed inset-y-0 left-0 w-64 bg-[#1a237e] text-white">
    <!-- Logo et titre -->
    <div class="flex items-center p-4 border-b border-blue-800">
        @if($settings->logo_path)
            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="w-8 h-8 object-contain bg-white rounded">
        @else
            <div class="w-8 h-8 bg-white rounded"></div>
        @endif
        <h1 class="ml-3 text-xl font-bold">{{ $settings->site_name }}</h1>
    </div>

    <!-- Menu de navigation -->
    <nav class="mt-4">
        <a href="{{ route('dashboard.departures.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.departures.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-bus w-6"></i>
            <span class="ml-3">Départs</span>
        </a>

        <a href="{{ route('dashboard.statistics') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.statistics') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-chart-line w-6"></i>
            <span class="ml-3">Statistiques</span>
        </a>

        <a href="{{ route('dashboard.buses.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.buses.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-bus-alt w-6"></i>
            <span class="ml-3">Bus</span>
        </a>

        <a href="{{ route('dashboard.advertisements.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.advertisements.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-ad w-6"></i>
            <span class="ml-3">Publicités</span>
        </a>

        <a href="{{ route('dashboard.announcements.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.announcements.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-bullhorn w-6"></i>
            <span class="ml-3">Annonces</span>
        </a>

        @can('view users')
        <a href="{{ route('dashboard.users.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.users.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-users w-6"></i>
            <span class="ml-3">Utilisateurs</span>
        </a>
        @endcan

        @can('view roles')
        <a href="{{ route('dashboard.roles.index') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.roles.*') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-user-shield w-6"></i>
            <span class="ml-3">Rôles & Permissions</span>
        </a>
        @endcan

        <a href="{{ route('dashboard.settings') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.settings') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-cog w-6"></i>
            <span class="ml-3">Paramètres</span>
        </a>
            <!-- Bouton de déconnexion -->
    <div class="absolute bottom-0 left-0 w-full p-4 border-t border-blue-800">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 text-white hover:bg-blue-800 transition-colors">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span class="ml-3">Déconnexion</span>
            </button>
        </form>
    </div>
    </nav>
</div>
