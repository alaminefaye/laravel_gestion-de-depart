<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Contenu principal -->
        <main class="flex-1 pl-64">
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        function playAnnouncement(audioUrl) {
            const audio = new Audio(audioUrl);
            audio.play().catch(function(error) {
                console.log("Error playing audio:", error);
            });
        }
    </script>
</body>
</html>
