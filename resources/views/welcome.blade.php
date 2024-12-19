<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ART Luxury Bus - Service de transport premium">
    <title>ART Luxury Bus</title>
    
    <!-- Styles -->
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

        /* Animations des prix */
        @keyframes priceGlow {
            0% { transform: scale(1); box-shadow: 0 0 0 rgba(16, 185, 129, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); }
            100% { transform: scale(1); box-shadow: 0 0 0 rgba(16, 185, 129, 0.4); }
        }
        
        @keyframes priceShine {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        
        .price-animation {
            animation: priceGlow 2s infinite ease-in-out;
            background: linear-gradient(90deg, #10B981, #059669, #10B981);
            background-size: 200% auto;
            animation: priceShine 3s linear infinite;
            transition: all 0.3s ease;
        }
        
        .price-animation:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    @include('partials.header')

    <!-- Main Content -->
    <main class="w-full py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 px-0 lg:px-4">
            <div class="lg:col-span-6 h-[600px]">
                @include('partials.video-section')
            </div>
            <div class="lg:col-span-6 px-4 lg:px-0">
                <div class="h-[600px] overflow-y-auto relative">
                    @include('partials.time-departures')
                </div>
            </div>
        </div>
    </main>

    <!-- Announcements Section -->
    @php
        $announcements = Cache::remember('active_announcements', 300, function () {
            return App\Models\Announcement::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        });
    @endphp

    <div class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-4 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            @if($announcements->count() > 0)
                <p id="announcement-text" class="text-lg flex items-center flex-1">
                    <i class="fas fa-bullhorn mr-2"></i>
                    <span>{{ $announcements->first()->content }}</span>
                </p>
                <div class="flex items-center space-x-2">
                    <button id="tts-toggle" onclick="window.carouselInstance.toggleTTS()" 
                            class="bg-white text-blue-600 p-2 rounded-full hover:bg-blue-50 transition-colors">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    @if($announcements->first()->audio_file)
                        <button onclick="window.carouselInstance.playAnnouncement('{{ asset($announcements->first()->audio_file) }}')" 
                                class="bg-white text-blue-600 p-2 rounded-full hover:bg-blue-50 transition-colors">
                            <i class="fas fa-play"></i>
                        </button>
                    @endif
                </div>
            @else
                <p class="text-lg flex items-center justify-center w-full">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Pas d'annonces disponibles pour l'instant</span>
                </p>
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Pass PHP variables to JavaScript
        const announcements = @json($announcements);
        window.addEventListener('DOMContentLoaded', (event) => {
            window.carouselInstance = new AnnouncementCarousel(announcements);
        });
    </script>
    <script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>