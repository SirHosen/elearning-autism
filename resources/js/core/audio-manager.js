// public/js/core/audio-manager.js
// Singleton Audio Manager & Shared Arena UI Helpers:
// - Satu suara TTS / rekaman pada satu waktu (zero autoplay, menghormati volume siswa)
// - Efek suara lembut Web Audio API (pilih, benar, salah lembut, selebrasi)
// - Helper bersama untuk feedback banner, escapeHtml, dan bintang vektor

class AudioManager {
  constructor() {
    this.currentAudio = null;
    this.isPlaying = false;
    this.volume = 0.7;
    this.sfxEnabled = false;
    this.sfxMuted = false;
    this.audioCtx = null;
    this.activeOnEnd = null;
    this.playbackToken = 0;
    this.initFromSettings();
  }

  initFromSettings() {
    const settingEl = document.getElementById('user-settings-data');
    if (settingEl) {
      try {
        const data = JSON.parse(settingEl.textContent);
        this.volume = Math.max(0, Math.min(1, (data.volume ?? 70) / 100));
        this.sfxEnabled = Boolean(data.sfx_enabled);
        this.sfxMuted = this.volume <= 0;
      } catch (e) {
        // Fallback default
      }
    }
  }

  setVolume(vol) {
    this.volume = Math.max(0, Math.min(1, vol / 100));
    this.sfxMuted = this.volume <= 0;
    if (this.currentAudio) {
      this.currentAudio.volume = this.volume;
    }
  }

  setMuted(muted) {
    this.sfxMuted = Boolean(muted);
    if (this.sfxMuted) {
      this.stop();
    }
  }

  getEffectiveVolume() {
    return this.sfxMuted ? 0 : this.volume;
  }

  getAudioContext() {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!AudioContext) return null;
    if (!this.audioCtx) {
      this.audioCtx = new AudioContext();
    }
    if (this.audioCtx.state === 'suspended') {
      this.audioCtx.resume().catch(() => {});
    }
    return this.audioCtx;
  }

  playToneSequence(notes, { type = 'sine', peak = 0.16, noteGap = 0.09, noteDur = 0.24 } = {}) {
    const vol = this.getEffectiveVolume();
    if (vol <= 0) return;

    try {
      const ctx = this.getAudioContext();
      if (!ctx) return;

      const baseTime = ctx.currentTime;
      notes.forEach((freq, idx) => {
        const start = baseTime + idx * noteGap;
        const stop = start + noteDur;

        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = type;
        osc.frequency.setValueAtTime(freq, start);

        gain.gain.setValueAtTime(0.001, start);
        gain.gain.linearRampToValueAtTime(peak * vol, start + 0.025);
        gain.gain.exponentialRampToValueAtTime(0.001, stop);

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.start(start);
        osc.stop(stop + 0.02);
      });
    } catch (e) {
      // Audio playback unavailable
    }
  }

  playSelectSound() {
    this.playToneSequence([440], { type: 'sine', peak: 0.08, noteDur: 0.08 });
  }

  playCorrectSound() {
    // Warm cheerful rising chime (C5 -> E5 -> G5)
    this.playToneSequence([523.25, 659.25, 783.99], { type: 'triangle', peak: 0.16, noteGap: 0.08, noteDur: 0.28 });
  }

  playWrongSound() {
    // Gentle non-startling marimba correction nudge (E4 -> C4)
    this.playToneSequence([329.63, 261.63], { type: 'sine', peak: 0.13, noteGap: 0.11, noteDur: 0.22 });
  }

  playCelebrationSound() {
    // Harmonious completion fanfare (C5 -> E5 -> G5 -> C6)
    this.playToneSequence([523.25, 659.25, 783.99, 1046.5], { type: 'triangle', peak: 0.18, noteGap: 0.1, noteDur: 0.38 });
  }

  stop() {
    this.playbackToken++;
    if (this.currentAudio) {
      this.currentAudio.onplay = null;
      this.currentAudio.onended = null;
      this.currentAudio.onerror = null;
      this.currentAudio.pause();
      this.currentAudio.currentTime = 0;
      this.currentAudio = null;
    }
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
    this.isPlaying = false;
    if (typeof this.activeOnEnd === 'function') {
      const cb = this.activeOnEnd;
      this.activeOnEnd = null;
      cb();
    }
  }

  play({ audioUrl = null, text = '', onStart = null, onEnd = null }) {
    this.stop();

    if (this.getEffectiveVolume() <= 0) {
      if (onStart) onStart({ muted: true, text: 'Suara dimatikan' });
      setTimeout(() => {
        if (onEnd) onEnd();
      }, 400);
      return;
    }

    const token = ++this.playbackToken;
    this.activeOnEnd = typeof onEnd === 'function' ? onEnd : null;

    const finish = () => {
      if (token !== this.playbackToken) return;
      this.isPlaying = false;
      this.currentAudio = null;
      const cb = this.activeOnEnd;
      this.activeOnEnd = null;
      if (cb) cb();
    };

    if (audioUrl) {
      const audio = new Audio(audioUrl);
      audio.volume = this.volume;
      this.currentAudio = audio;
      this.isPlaying = true;

      audio.onplay = () => {
        if (token === this.playbackToken && onStart) {
          onStart({ muted: false, text: 'Sedang berbunyi...' });
        }
      };

      audio.onended = finish;

      audio.onerror = () => {
        if (token !== this.playbackToken) return;
        this.currentAudio = null;
        this.playTTS(text, onStart, finish, token);
      };

      audio.play().catch(() => {
        if (token !== this.playbackToken) return;
        this.currentAudio = null;
        this.playTTS(text, onStart, finish, token);
      });
    } else {
      this.playTTS(text, onStart, finish, token);
    }
  }

  playTTS(text, onStart, finish, token = this.playbackToken) {
    if (!('speechSynthesis' in window) || !text) {
      if (onStart) onStart({ unavailable: true, text: 'Suara tidak tersedia' });
      finish();
      return;
    }

    window.speechSynthesis.cancel();
    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = 'id-ID';
    utter.rate = 0.82;
    utter.pitch = 1.0;
    utter.volume = this.getEffectiveVolume();

    utter.onstart = () => {
      if (token !== this.playbackToken) return;
      this.isPlaying = true;
      if (onStart) onStart({ muted: false, text: 'Sedang berbunyi...' });
    };

    utter.onend = finish;
    utter.onerror = finish;

    window.speechSynthesis.speak(utter);
  }
}

export const ArenaHelpers = {
  escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, (ch) => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    })[ch]);
  },

  formatCueHtml(message) {
    let html = this.escapeHtml(message);
    html = html.replace(/&quot;([^&]+?)&quot;/g, '<mark class="st-cue-word">$1</mark>');
    html = html.replace(/\(\+(\d+\s*poin)\)/gi, '<span class="st-cue-points">+$1</span>');
    return html;
  },

  formatTargetHtml(text, targetLabel) {
    const safeText = this.escapeHtml(text);
    const label = String(targetLabel ?? '').trim();
    if (!label) return this.formatCueHtml(text);

    const safeLabel = this.escapeHtml(label);
    const escapedRegex = safeLabel.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const replaced = safeText.replace(
      new RegExp(`(${escapedRegex})`, 'i'),
      '<mark class="st-cue-word st-cue-word--inline">$1</mark>'
    );
    return replaced === safeText ? this.formatCueHtml(text) : replaced;
  },

  formatPromptHtml(text) {
    const raw = String(text ?? '');
    const m = raw.match(/^(Mana yang merupakan\s+)(.+?)(\??)$/i);
    if (m) {
      return `${this.escapeHtml(m[1])}<mark class="st-cue-word st-cue-word--inline">${this.escapeHtml(m[2])}</mark>${this.escapeHtml(m[3])}`;
    }
    return this.formatCueHtml(raw);
  },

  renderStarsSvg(stars) {
    const safeCount = Math.max(1, Math.min(3, stars || 1));
    return Array.from({ length: safeCount }, () => `
      <svg width="48" height="48" viewBox="0 0 24 24" fill="#FBBF24" stroke="#B45309" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    `).join('');
  },

  setFeedback(feedbackEl, feedbackTextEl, variant, message) {
    if (!feedbackEl || !feedbackTextEl) return;

    feedbackEl.classList.remove('st-arena-feedback--neutral', 'st-arena-feedback--success', 'st-arena-feedback--hint');
    feedbackEl.classList.add(`st-arena-feedback--${variant}`);

    const iconWrap = feedbackEl.querySelector('.st-arena-feedback__icon');
    if (iconWrap) {
      if (variant === 'success') {
        iconWrap.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="8.5 12.5 11 15 16 9.5"/>
          </svg>
        `;
      } else if (variant === 'hint') {
        iconWrap.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 18h6"/>
            <path d="M10 22h4"/>
            <path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.2 1 2h6c0-.8.4-1.5 1-2A7 7 0 0 0 12 2z"/>
          </svg>
        `;
      } else {
        iconWrap.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        `;
      }
    }

    feedbackTextEl.innerHTML = this.formatCueHtml(message);
  },

  bindSfxToggle(btnEl, labelEl, audioMgr, onToggle) {
    if (!btnEl || !audioMgr) return;

    const syncUi = () => {
      const muted = audioMgr.sfxMuted;
      btnEl.classList.toggle('is-muted', muted);
      btnEl.setAttribute('aria-pressed', muted ? 'false' : 'true');
      if (labelEl) {
        labelEl.textContent = muted ? 'Suara Mati' : 'Suara Aktif';
      }
    };

    syncUi();
    btnEl.addEventListener('click', () => {
      audioMgr.setMuted(!audioMgr.sfxMuted);
      syncUi();
      if (!audioMgr.sfxMuted) {
        audioMgr.playSelectSound();
      }
      if (onToggle) onToggle(audioMgr.sfxMuted);
    });
  },
};

export const audioManager = new AudioManager();
if (typeof window !== 'undefined') {
  window.audioManager = audioManager;
  window.ArenaHelpers = ArenaHelpers;
}
