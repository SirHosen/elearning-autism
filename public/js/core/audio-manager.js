// resources/js/core/audio-manager.js
// Singleton Audio Manager: hanya satu suara pada satu waktu, mendukung rekaman audio & fallback Web Speech TTS id-ID

class AudioManager {
  constructor() {
    this.currentAudio = null;
    this.isPlaying = false;
    this.volume = 0.7;
    this.sfxEnabled = false;
    this.initFromSettings();
  }

  initFromSettings() {
    const settingEl = document.getElementById('user-settings-data');
    if (settingEl) {
      try {
        const data = JSON.parse(settingEl.textContent);
        this.volume = Math.max(0, Math.min(1, (data.volume ?? 70) / 100));
        this.sfxEnabled = Boolean(data.sfx_enabled);
      } catch (e) {
        // Fallback default
      }
    }
  }

  setVolume(vol) {
    this.volume = Math.max(0, Math.min(1, vol / 100));
    if (this.currentAudio) {
      this.currentAudio.volume = this.volume;
    }
  }

  stop() {
    if (this.currentAudio) {
      this.currentAudio.pause();
      this.currentAudio.currentTime = 0;
      this.currentAudio = null;
    }
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
    this.isPlaying = false;
  }

  play({ audioUrl = null, text = '', onStart = null, onEnd = null }) {
    this.stop();

    if (this.volume <= 0) {
      if (onStart) onStart({ muted: true, text: 'Suara dimatikan' });
      setTimeout(() => {
        if (onEnd) onEnd();
      }, 400);
      return;
    }

    if (audioUrl) {
      const audio = new Audio(audioUrl);
      audio.volume = this.volume;
      this.currentAudio = audio;
      this.isPlaying = true;

      audio.onplay = () => {
        if (onStart) onStart({ muted: false, text: 'Sedang berbunyi...' });
      };

      audio.onended = () => {
        this.isPlaying = false;
        this.currentAudio = null;
        if (onEnd) onEnd();
      };

      audio.onerror = () => {
        // Fallback ke Web Speech API TTS jika berkas gagal dimuat
        this.playTTS(text, onStart, onEnd);
      };

      audio.play().catch(() => {
        this.playTTS(text, onStart, onEnd);
      });
    } else {
      this.playTTS(text, onStart, onEnd);
    }
  }

  playTTS(text, onStart, onEnd) {
    if (!('speechSynthesis' in window) || !text) {
      if (onStart) onStart({ unavailable: true, text: 'Suara tidak tersedia' });
      if (onEnd) onEnd();
      return;
    }

    window.speechSynthesis.cancel();
    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = 'id-ID';
    utter.rate = 0.82;
    utter.pitch = 1.0;
    utter.volume = this.volume;

    utter.onstart = () => {
      this.isPlaying = true;
      if (onStart) onStart({ muted: false, text: 'Sedang berbunyi...' });
    };

    utter.onend = () => {
      this.isPlaying = false;
      if (onEnd) onEnd();
    };

    utter.onerror = () => {
      this.isPlaying = false;
      if (onEnd) onEnd();
    };

    window.speechSynthesis.speak(utter);
  }
}

export const audioManager = new AudioManager();
if (typeof window !== 'undefined') {
  window.audioManager = audioManager;
}
