<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Luxury Bus - @yield('title', 'Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Logo et liens principaux -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-blue-600">Art Luxury Bus</a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('dashboard.departures.index') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">
                                Horaires de Départ
                            </a>
                            <a href="{{ route('dashboard.advertisements.index') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">
                                Publicités
                            </a>
                            <a href="{{ route('dashboard.announcements.index') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">
                                Annonces
                            </a>
                        </div>
                    </div>

                    <!-- Menu mobile -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-expanded="false">
                            <span class="sr-only">Ouvrir le menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menu mobile -->
            <div x-show="open" class="sm:hidden" x-cloak>
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="text-gray-900 block pl-3 pr-4 py-2 border-l-4 border-blue-500 text-base font-medium bg-blue-50">
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.departures.index') }}" class="text-gray-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-base font-medium">
                        Horaires de Départ
                    </a>
                    <a href="{{ route('dashboard.advertisements.index') }}" class="text-gray-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-base font-medium">
                        Publicités
                    </a>
                    <a href="{{ route('dashboard.announcements.index') }}" class="text-gray-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 text-base font-medium">
                        Annonces
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2">
                            <li>
                                <i class="fas fa-phone mr-2"></i>
                                +1 234 567 890
                            </li>
                            <li>
                                <i class="fas fa-envelope mr-2"></i>
                                contact@artluxurybus.com
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                123 Luxury Street, City
                            </li>
                        </ul>
                    </div>

                    <!-- Liens rapides -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                        <ul class="space-y-2">
                            <li><a href="#about" class="hover:text-blue-400">À propos</a></li>
                            <li><a href="#services" class="hover:text-blue-400">Services</a></li>
                            <li><a href="#reservation" class="hover:text-blue-400">Réservation</a></li>
                            <li><a href="#contact" class="hover:text-blue-400">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Suivez-nous</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-blue-400"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="hover:text-blue-400"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="hover:text-blue-400"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                    <p>&copy; 2024 Art Luxury Bus. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle menu mobile
        document.querySelector('[aria-controls="mobile-menu"]').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>
