<div class="fixed inset-y-0 left-0 w-64 bg-[#1a237e] text-white">
    <!-- Logo et titre -->
    <div class="flex items-center p-4 border-b border-blue-800">
        <div class="w-8 h-8 bg-white rounded"></div>
        <h1 class="ml-3 text-xl font-bold">Art Luxury Bus</h1>
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

        <a href="{{ route('dashboard.settings') }}" 
           class="flex items-center px-4 py-3 text-white hover:bg-blue-800 transition-colors {{ request()->routeIs('dashboard.settings') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-cog w-6"></i>
            <span class="ml-3">Paramètres</span>
        </a>
    </nav>
</div>
