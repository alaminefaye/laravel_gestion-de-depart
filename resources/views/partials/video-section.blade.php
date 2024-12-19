<!-- Video Section -->
<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden hover-up h-full">
    <div class="aspect-w-16 aspect-h-9 relative w-full h-full" id="videoCarousel">
        @php
            $activeAds = $advertisements->where('is_active', true)->values();
        @endphp
        @if($activeAds->count() > 0)
            @foreach($activeAds as $index => $advertisement)
                <div class="video-item absolute inset-0 {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                    @if($advertisement->video_type === 'upload')
                        <video 
                            id="adVideo_{{ $index }}"
                            src="{{ Storage::url($advertisement->video_path) }}"
                            {{ $index === 0 ? 'autoplay' : '' }}
                            muted
                            playsinline
                            class="w-full h-full object-contain bg-black"
                            onended="playNextVideo({{ $index }}, {{ $activeAds->count() }})">
                        </video>
                        <div class="video-controls absolute bottom-4 right-4 bg-black bg-opacity-50 rounded-lg p-2">
                            <button onclick="toggleSound('adVideo_{{ $index }}')" class="text-white">
                                <i id="soundIcon_adVideo_{{ $index }}" class="fas fa-volume-mute"></i>
                                <span class="text-sm ml-1">Son</span>
                            </button>
                        </div>
                    @elseif($advertisement->video_type === 'youtube')
                        @php
                            $videoId = '';
                            // Gérer différents formats d'URL YouTube
                            if (strpos($advertisement->video_path, 'youtu.be/') !== false) {
                                $videoId = substr($advertisement->video_path, strrpos($advertisement->video_path, '/') + 1);
                            } elseif (strpos($advertisement->video_path, 'watch?v=') !== false) {
                                parse_str(parse_url($advertisement->video_path, PHP_URL_QUERY), $params);
                                $videoId = $params['v'] ?? '';
                            } elseif (strpos($advertisement->video_path, 'embed/') !== false) {
                                $videoId = substr($advertisement->video_path, strrpos($advertisement->video_path, '/') + 1);
                            }
                            // Nettoyer l'ID de la vidéo
                            $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);
                        @endphp
                        @if($videoId)
                            <div class="relative w-full h-full" id="youtubeContainer_{{ $index }}">
                                <div id="youtubePlayer_{{ $index }}" class="absolute inset-0 w-full h-full"></div>
                            </div>
                        @endif
                    @elseif($advertisement->video_type === 'drive')
                        <iframe 
                            id="driveVideo_{{ $index }}"
                            src="{{ $advertisement->video_path }}"
                            frameborder="0"
                            class="absolute inset-0 w-full h-full"
                            allowfullscreen>
                        </iframe>
                    @endif
                </div>
            @endforeach
        @else
            <div class="flex items-center justify-center h-full bg-gray-900">
                <p class="text-white text-lg">Aucune publicité disponible</p>
            </div>
        @endif
    </div>
</div>

<script>
let youtubePlayers = {};
let currentVideoIndex = 0;

// Configuration des joueurs YouTube
function onYouTubeIframeAPIReady() {
    @foreach($activeAds as $index => $advertisement)
        @if($advertisement->video_type === 'youtube')
            @php
                $videoId = '';
                if (strpos($advertisement->video_path, 'youtu.be/') !== false) {
                    $videoId = substr($advertisement->video_path, strrpos($advertisement->video_path, '/') + 1);
                } elseif (strpos($advertisement->video_path, 'watch?v=') !== false) {
                    parse_str(parse_url($advertisement->video_path, PHP_URL_QUERY), $params);
                    $videoId = $params['v'] ?? '';
                } elseif (strpos($advertisement->video_path, 'embed/') !== false) {
                    $videoId = substr($advertisement->video_path, strrpos($advertisement->video_path, '/') + 1);
                }
                $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);
            @endphp
            youtubePlayers[{{ $index }}] = new YT.Player('youtubePlayer_{{ $index }}', {
                videoId: '{{ $videoId }}',
                playerVars: {
                    'autoplay': {{ $index === 0 ? 1 : 0 }},
                    'mute': 1,
                    'controls': 1,
                    'rel': 0,
                    'modestbranding': 1,
                    'playsinline': 1,
                    'origin': '{{ url("/") }}'
                },
                events: {
                    'onReady': function(event) {
                        if ({{ $index }} === 0) {
                            event.target.playVideo();
                        }
                    },
                    'onStateChange': function(event) {
                        if (event.data === YT.PlayerState.ENDED) {
                            playNextVideo({{ $index }}, {{ $activeAds->count() }});
                        }
                    },
                    'onError': function(event) {
                        console.log('YouTube Error:', event.data);
                        // En cas d'erreur, passer à la vidéo suivante
                        playNextVideo({{ $index }}, {{ $activeAds->count() }});
                    }
                }
            });
        @endif
    @endforeach
}

function playNextVideo(currentIndex, totalVideos) {
    // Masquer la vidéo actuelle
    const currentVideo = document.querySelector(`[data-index="${currentIndex}"]`);
    if (currentVideo) {
        currentVideo.classList.add('hidden');
    }

    // Arrêter la vidéo YouTube actuelle si elle existe
    if (youtubePlayers[currentIndex]) {
        youtubePlayers[currentIndex].stopVideo();
    }

    // Calculer l'index de la prochaine vidéo
    const nextIndex = (currentIndex + 1) % totalVideos;
    currentVideoIndex = nextIndex;

    // Afficher la prochaine vidéo
    const nextVideo = document.querySelector(`[data-index="${nextIndex}"]`);
    if (nextVideo) {
        nextVideo.classList.remove('hidden');
        
        // Gérer la lecture selon le type de vidéo
        const nextHtmlVideo = document.getElementById(`adVideo_${nextIndex}`);
        if (nextHtmlVideo) {
            nextHtmlVideo.currentTime = 0;
            nextHtmlVideo.play();
        } else if (youtubePlayers[nextIndex]) {
            youtubePlayers[nextIndex].playVideo();
        }
    }
}

function toggleSound(videoId) {
    const video = document.getElementById(videoId);
    const soundIcon = document.getElementById(`soundIcon_${videoId}`);
    
    if (video) {
        video.muted = !video.muted;
        soundIcon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
    }
}

// Charger l'API YouTube
const tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
const firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// Gérer les erreurs globales de l'API YouTube
window.onYouTubeIframeAPIError = function(error) {
    console.error('YouTube API Error:', error);
};
</script>
