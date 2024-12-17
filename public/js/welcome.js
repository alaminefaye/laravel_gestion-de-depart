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
        this.announcements = announcements;
        this.currentIndex = 0;
        this.isTTSEnabled = false;
        this.currentAudio = null;
        this.init();
    }

    init() {
        // Start carousel if there are multiple announcements
        if (this.announcements.length > 1) {
            setInterval(() => this.updateAnnouncement(), 20000);
        }
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
        this.currentIndex = (this.currentIndex + 1) % this.announcements.length;
        const announcement = this.announcements[this.currentIndex];
        
        // Update text
        document.getElementById('announcement-text').querySelector('span').textContent = announcement.content;
        
        // If TTS is enabled, speak the new announcement
        if (this.isTTSEnabled) {
            this.speakAnnouncement(announcement.content);
        }
    }

    speakAnnouncement(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); // Stop any ongoing speech
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'fr-FR';
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
