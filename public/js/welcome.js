// Time update functionality
function updateTime() {
    const now = new Date();
    const timeElement = document.getElementById('current-time');
    const dateElement = document.getElementById('current-date');
    
    const timeString = now.toLocaleTimeString('fr-FR', { 
        hour: '2-digit', 
        minute: '2-digit'
    });
    
    const dateString = now.toLocaleDateString('fr-FR', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    if (timeElement) timeElement.textContent = timeString;
    if (dateElement) dateElement.textContent = dateString;
}

// Announcement carousel functionality
class AnnouncementCarousel {
    constructor(announcements) {
        // Filtrer pour ne garder que les annonces actives
        this.announcements = announcements.filter(announcement => announcement.is_active);
        this.currentIndex = 0;
        this.isTTSEnabled = false;
        this.currentAudio = null;
        this.useFemaleTTS = false; // Pour alterner entre les voix
        this.voices = null;
        this.init();
    }

    init() {
        // Initialiser les voix
        if ('speechSynthesis' in window) {
            // Charger les voix disponibles
            window.speechSynthesis.onvoiceschanged = () => {
                this.voices = window.speechSynthesis.getVoices();
            };
            this.voices = window.speechSynthesis.getVoices();
        }

        // Start carousel if there are multiple active announcements
        if (this.announcements.length > 1) {
            setInterval(() => this.updateAnnouncement(), 20000);
        }
    }

    getVoice() {
        if (!this.voices) return null;

        // Filtrer les voix françaises
        const frenchVoices = this.voices.filter(voice => 
            voice.lang.startsWith('fr') || voice.lang.startsWith('fr-FR')
        );

        // Si pas de voix française, utiliser la première voix disponible
        if (frenchVoices.length === 0) return this.voices[0];

        // Alterner entre voix masculine et féminine
        this.useFemaleTTS = !this.useFemaleTTS;
        
        // Essayer de trouver une voix qui correspond au genre souhaité
        const voiceNames = {
            female: ['amelie', 'marie', 'julie', 'celine', 'female', 'femme'],
            male: ['thomas', 'nicolas', 'male', 'homme']
        };
        
        const voice = frenchVoices.find(v => {
            const name = v.name.toLowerCase();
            if (this.useFemaleTTS) {
                return voiceNames.female.some(femaleName => name.includes(femaleName));
            } else {
                return voiceNames.male.some(maleName => name.includes(maleName));
            }
        });

        // Si aucune voix spécifique n'est trouvée, utiliser la première voix française
        return voice || frenchVoices[0];
    }

    toggleTTS() {
        this.isTTSEnabled = !this.isTTSEnabled;
        const button = document.getElementById('tts-toggle');
        button.innerHTML = `<i class="fas fa-volume-${this.isTTSEnabled ? 'up' : 'mute'}"></i>`;
        
        if (this.isTTSEnabled) {
            this.speakAnnouncement(this.announcements[this.currentIndex].content);
        } else if (window.speechSynthesis) {
            window.speechSynthesis.cancel();
        }
    }

    updateAnnouncement() {
        // Ne mettre à jour que s'il y a des annonces actives
        if (this.announcements.length > 0) {
            this.currentIndex = (this.currentIndex + 1) % this.announcements.length;
            const announcement = this.announcements[this.currentIndex];
            
            // Update text
            const textElement = document.getElementById('announcement-text');
            if (textElement && textElement.querySelector('span')) {
                textElement.querySelector('span').textContent = announcement.content;
            }
            
            // If TTS is enabled, speak the new announcement
            if (this.isTTSEnabled) {
                this.speakAnnouncement(announcement.content);
            }
        }
    }

    speakAnnouncement(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); // Stop any ongoing speech
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'fr-FR';
            
            // Définir la voix
            const voice = this.getVoice();
            if (voice) {
                utterance.voice = voice;
                // Ajuster le pitch et le taux selon le genre
                if (this.useFemaleTTS) {
                    utterance.pitch = 1.0; // Voix plus naturelle pour la femme
                    utterance.rate = 1.0;  // Vitesse normale
                    utterance.volume = 1.0; // Volume maximum
                } else {
                    utterance.pitch = 0.9; // Voix légèrement plus grave pour Thomas
                    utterance.rate = 0.9;
                    utterance.volume = 1.0;
                }
            }
            
            window.speechSynthesis.speak(utterance);
        }
    }

    playAnnouncement(audioUrl) {
        if (this.currentAudio) {
            this.currentAudio.pause();
            this.currentAudio = null;
        }
        this.currentAudio = new Audio(audioUrl);
        this.currentAudio.play().catch(function(error) {
            console.log("Error playing audio:", error);
        });
    }
}

// Initialize time update
updateTime();
setInterval(updateTime, 1000);

// Initialize announcements when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // The announcements variable will be defined in the blade template
    if (typeof announcements !== 'undefined') {
        window.carouselInstance = new AnnouncementCarousel(announcements);
    }
});
