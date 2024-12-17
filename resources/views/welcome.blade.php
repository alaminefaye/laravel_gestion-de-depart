<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ART Luxury Bus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
            border-radius: 0.5rem;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 0.5rem;
        }
        .gradient-text {
            background: linear-gradient(45deg, #4F46E5, #2563EB);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hover-up { transition: transform 0.3s ease; }
        .hover-up:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bus text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">ART Luxury Bus</h1>
                        <p class="text-gray-600">L'Excellence du Transport</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-2">
                        <i class="fas fa-phone-alt"></i>
                        <span>Contact</span>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-2">
                        <i class="fas fa-info-circle"></i>
                        <span>À propos</span>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8">
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
            <!-- Video Section -->
            <div class="lg:col-span-4 bg-gray-800 rounded-lg shadow-lg overflow-hidden hover-up">
                <div class="video-container">
                    <iframe 
                        src="https://www.youtube.com/embed/VIDEO_ID" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h3 class="text-xl font-semibold mb-2">
                        <i class="fas fa-star mr-2"></i>Découvrez notre service premium
                    </h3>
                    <p class="text-sm opacity-90">Voyagez dans le confort et le luxe avec ART Luxury Bus</p>
                </div>
            </div>

            <!-- Time and Departures -->
            <div class="lg:col-span-3 space-y-4">
                <!-- Current Time -->
                <div class="bg-white rounded-lg shadow p-4 hover-up">
                    <h2 class="flex items-center text-xl font-semibold text-gray-800 mb-3">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Heure Actuelle
                    </h2>
                    <div class="text-4xl font-bold gradient-text text-center" id="current-time"></div>
                    <div class="text-gray-600 text-center mt-1" id="current-date"></div>
                </div>

                <!-- Departures -->
                <div class="bg-white rounded-lg shadow p-4 hover-up">
                    <h2 class="flex items-center text-xl font-semibold text-gray-800 mb-3">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        Horaires de Départ
                    </h2>
                    @if(count($departures) > 0)
                        <div class="overflow-x-auto -mx-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">
                                        <th class="px-4 pb-2 w-1/6">
                                            <i class="fas fa-map-marker-alt mr-1"></i> Destination
                                        </th>
                                        <th class="px-4 pb-2 w-1/6">
                                            <i class="fas fa-clock mr-1"></i> Départ
                                        </th>
                                        <th class="px-4 pb-2 w-1/6">
                                            <i class="fas fa-bus mr-1"></i> Bus
                                        </th>
                                        <th class="px-4 pb-2 w-1/6">
                                            <i class="fas fa-money-bill-wave mr-1"></i> Prix
                                        </th>
                                        <th class="px-4 pb-2 w-1/6">
                                            <i class="fas fa-info-circle mr-1"></i> Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departures as $departure)
                                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                            <td class="px-4 py-2">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $departure->destination }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="text-sm text-gray-900">
                                                    {{ $departure->departure_time }}
                                                    @if(isset($departure->new_time))
                                                        <span class="text-red-500 ml-2 inline-flex items-center text-xs">
                                                            <i class="fas fa-arrow-right text-xs mr-1"></i>
                                                            {{ $departure->new_time }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="text-sm text-gray-900">
                                                    @if(isset($departure->bus_number))
                                                        <span class="font-medium">{{ $departure->bus_number }}</span>
                                                    @else
                                                        <span class="font-medium">Bus 01</span>
                                                    @endif
                                                    <br>
                                                    @if(isset($departure->bus_plate))
                                                        <span class="text-xs text-gray-600">{{ $departure->bus_plate }}</span>
                                                    @else
                                                        <span class="text-xs text-gray-600">DK-0001-AA</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if(isset($departure->price))
                                                        {{ number_format($departure->price, 0, ',', ' ') }} FCFA
                                                    @else
                                                        15 000 FCFA
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($departure->status === 'À l\'heure')
                                                    <span class="inline-flex items-center text-xs font-medium text-green-700">
                                                        <i class="fas fa-check mr-1"></i>
                                                        {{ $departure->status }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center text-xs font-medium text-yellow-700">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $departure->status }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-bus text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-600">Aucun départ prévu pour l'instant, merci.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- WiFi Announcement -->
    <div class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-4 text-center z-50 shadow-lg">
        <p class="text-lg flex items-center justify-center">
            <i class="fas fa-wifi mr-2 animate-pulse"></i>
            Pour votre confort, nos bus sont désormais équipés de WiFi gratuit
        </p>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            const dateElement = document.getElementById('current-date');
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            dateElement.textContent = now.toLocaleDateString('fr-FR', options);
        }

        updateTime();
        setInterval(updateTime, 1000);

        // Header shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 0) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>
