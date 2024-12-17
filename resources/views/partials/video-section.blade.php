<!-- Video Section -->
<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden hover-up relative h-full">
    <div class="video-container" id="videoCarousel">
        @if($advertisements->count() > 0)
            @foreach($advertisements as $index => $advertisement)
                <div class="video-item {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                    @if($advertisement->video_type === 'upload')
                        <video 
                            id="adVideo_{{ $index }}"
                            src="{{ Storage::url($advertisement->video_path) }}"
                            {{ $index === 0 ? 'autoplay' : '' }}
                            muted
                            playsinline
                            class="w-full h-full object-cover"
                            onended="playNextVideo({{ $index }})">
                        </video>
                        <button onclick="toggleSound('adVideo_{{ $index }}')" class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white p-1.5 rounded-full hover:bg-opacity-75 transition-all z-10">
                            <i id="soundIcon_adVideo_{{ $index }}" class="fas fa-volume-mute text-sm"></i>
                        </button>
                    @elseif($advertisement->video_type === 'youtube')
                        @php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\s*[^\/\n\s]+\/|(?:v|e(?:mbed)?)\/|\s*[^\/\n\s]+\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $advertisement->video_path, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <div id="youtube-container_{{ $index }}">
                                <iframe 
                                    id="youtubeVideo_{{ $index }}"
                                    src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&autoplay={{ $index === 0 ? '1' : '0' }}&mute=1&controls=0&rel=0&modestbranding=1&showinfo=0" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                                <button onclick="toggleYouTubeSound({{ $index }})" class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white p-1.5 rounded-full hover:bg-opacity-75 transition-all z-10">
                                    <i id="soundIcon_youtube_{{ $index }}" class="fas fa-volume-mute text-sm"></i>
                                </button>
                            </div>
                        @endif
                    @elseif($advertisement->video_type === 'drive')
                        @php
                            $driveUrl = $advertisement->video_path;
                            if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches)) {
                                $fileId = $matches[1];
                                $embedUrl = "https://drive.google.com/file/d/" . $fileId . "/preview";
                            }
                        @endphp
                        @if(isset($embedUrl))
                            <div id="drive-video-container_{{ $index }}">
                                <iframe 
                                    id="drive-video_{{ $index }}"
                                    src="{{ $embedUrl }}"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @endif
                    @endif
                    <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                        <h3 class="text-xl font-semibold">
                            <i class="fas fa-star mr-2"></i>{{ $advertisement->title }}
                        </h3>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex items-center justify-center h-full bg-gray-700">
                <p class="text-white text-center p-4">
                    <i class="fas fa-film text-4xl mb-2"></i><br>
                    Aucune publicité disponible
                </p>
            </div>
        @endif
    </div>
</div>

<script>
// Variables globales
let currentVideoIndex = 0;
const totalVideos = {{ $advertisements->count() }};
let youtubePlayers = {};
window.activeAudioId = null;

// Initialisation des lecteurs YouTube
function onYouTubeIframeAPIReady() {
    @foreach($advertisements as $index => $advertisement)
        @if($advertisement->video_type === 'youtube')
            youtubePlayers[{{ $index }}] = new YT.Player('youtubeVideo_{{ $index }}', {
                events: {
                    'onReady': function(event) { event.target.mute(); },
                    'onStateChange': function(event) {
                        if (event.data === YT.PlayerState.ENDED) {
                            playNextVideo({{ $index }});
                        }
                    }
                }
            });
        @endif
    @endforeach
}

// Fonction pour jouer la vidéo suivante
function playNextVideo(currentIndex) {
    // Cacher la vidéo actuelle
    document.querySelector(`.video-item[data-index="${currentIndex}"]`).classList.add('hidden');
    
    // Calculer l'index de la prochaine vidéo
    let nextIndex = (currentIndex + 1) % totalVideos;
    
    // Afficher la prochaine vidéo
    const nextVideo = document.querySelector(`.video-item[data-index="${nextIndex}"]`);
    nextVideo.classList.remove('hidden');
    
    // Gérer la lecture selon le type de vidéo
    const videoElement = document.getElementById(`adVideo_${nextIndex}`);
    if (videoElement) {
        videoElement.play();
    } else if (youtubePlayers[nextIndex]) {
        youtubePlayers[nextIndex].playVideo();
    }
    
    // Mettre à jour l'index courant
    currentVideoIndex = nextIndex;
}

// Fonction pour contrôler le son des vidéos uploadées
function toggleSound(videoId) {
    const video = document.getElementById(videoId);
    const icon = document.getElementById('soundIcon_' + videoId);
    
    if (video) {
        // Couper le son de la vidéo précédente
        if (window.activeAudioId && window.activeAudioId !== videoId) {
            const prevVideo = document.getElementById(window.activeAudioId);
            const prevIcon = document.getElementById('soundIcon_' + window.activeAudioId);
            if (prevVideo) {
                prevVideo.muted = true;
                if (prevIcon) {
                    prevIcon.className = 'fas fa-volume-mute text-sm';
                }
            }
        }

        // Basculer le son de la vidéo courante
        video.muted = !video.muted;
        icon.className = video.muted ? 'fas fa-volume-mute text-sm' : 'fas fa-volume-up text-sm';
        window.activeAudioId = video.muted ? null : videoId;
    }
}

// Fonction pour contrôler le son des vidéos YouTube
function toggleYouTubeSound(index) {
    const player = youtubePlayers[index];
    const icon = document.getElementById(`soundIcon_youtube_${index}`);
    
    if (player && player.isMuted) {
        // Couper le son de la vidéo précédente
        if (window.activeAudioId) {
            const prevVideo = document.getElementById(window.activeAudioId);
            const prevIcon = document.getElementById('soundIcon_' + window.activeAudioId);
            if (prevVideo) {
                prevVideo.muted = true;
                if (prevIcon) {
                    prevIcon.className = 'fas fa-volume-mute text-sm';
                }
            }
        }

        if (player.isMuted()) {
            player.unMute();
            icon.className = 'fas fa-volume-up text-sm';
            window.activeAudioId = `youtubeVideo_${index}`;
        } else {
            player.mute();
            icon.className = 'fas fa-volume-mute text-sm';
            window.activeAudioId = null;
        }
    }
}

// S'assurer que toutes les vidéos sont muettes au chargement
document.addEventListener('DOMContentLoaded', function() {
    const videos = document.getElementsByTagName('video');
    for (let video of videos) {
        video.muted = true;
    }
});
</script>

<!-- Chargement de l'API YouTube -->
<script src="https://www.youtube.com/iframe_api"></script>
