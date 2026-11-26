// public/js/core/audio-manager.js
// Singleton Audio Manager & Shared Arena UI Helpers:
// - Satu suara TTS / rekaman pada satu waktu (zero autoplay, menghormati volume siswa)
// - Efek suara lembut Web Audio API (pilih, benar, salah lembut, selebrasi)
// - Helper bersama untuk feedback banner, escapeHtml, dan bintang vektor

class AudioManager {
  constructor() {
    this.currentAudio = null;
    this.playbackTimer = null;
    this.noiseBuffer = null;
    this.voices = [];
    this.isPlaying = false;
    this.volume = 0.7;
    this.sfxEnabled = false;
    this.sfxMuted = false;
    this.audioCtx = null;
    this.activeOnEnd = null;
    this.playbackToken = 0;
    this.initFromSettings();
    this.initVoices();
  }

  initVoices() {
    if (typeof window === 'undefined' || !('speechSynthesis' in window)) return;
    const loadVoices = () => {
      try {
        this.voices = window.speechSynthesis.getVoices() || [];
      } catch (e) {
        this.voices = [];
      }
    };
    loadVoices();
    if (typeof window.speechSynthesis.addEventListener === 'function') {
      window.speechSynthesis.addEventListener('voiceschanged', loadVoices);
    }
  }

  pickVoice(targetLang = 'id-ID') {
    if (typeof window === 'undefined' || !('speechSynthesis' in window)) return null;
    if (!this.voices || this.voices.length === 0) {
      try {
        this.voices = window.speechSynthesis.getVoices() || [];
      } catch (e) {
        return null;
      }
    }
    if (!this.voices.length) return null;

    const normalized = String(targetLang || 'id-ID').toLowerCase().replace('_', '-');
    const base = normalized.split('-')[0];
    const matching = this.voices.filter((v) => {
      const vLang = String(v.lang || '').toLowerCase().replace('_', '-');
      return vLang === normalized || vLang.startsWith(`${base}-`) || vLang === base;
    });
    if (!matching.length) return null;

    const scoreVoice = (v) => {
      const vLang = String(v.lang || '').toLowerCase().replace('_', '-');
      const vName = String(v.name || '').toLowerCase();
      let score = 0;
      if (vLang === normalized) score += 100;
      else if (vLang === 'en-gb' && base === 'en') score += 45;
      else if (vLang === base) score += 20;

      if (vName.includes('google') || vName.includes('natural') || vName.includes('microsoft')) score += 35;
      if (vName.includes('samantha') || vName.includes('daniel') || vName.includes('aria') || vName.includes('jenny')) score += 20;
      if (vName.includes('espeak')) score -= 15;
      return score;
    };

    matching.sort((a, b) => scoreVoice(b) - scoreVoice(a));
    return matching[0];
  }

  initFromSettings() {
    if (typeof document === 'undefined') return;
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

  getNoiseBuffer(ctx) {
    if (this.noiseBuffer && this.noiseBuffer.sampleRate === ctx.sampleRate) {
      return this.noiseBuffer;
    }
    const length = Math.floor(ctx.sampleRate * 0.25);
    const buffer = ctx.createBuffer(1, length, ctx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < length; i++) {
      data[i] = Math.random() * 2 - 1;
    }
    this.noiseBuffer = buffer;
    return buffer;
  }

  playWarmVoice(ctx, freq, start, dur, { preset = 'kalimba', peak = 0.16, vol = 0.7 } = {}) {
    const outLevel = Math.max(0.001, peak * vol);
    const filter = ctx.createBiquadFilter();
    filter.type = 'lowpass';
    filter.Q.value = preset === 'guitar' ? 1.8 : 0.9;

    const cutoffStart = preset === 'guitar' ? 2800 : Math.min(3400, freq * 4.8);
    const cutoffEnd = preset === 'guitar' ? 460 : Math.max(480, freq * 1.9);
    filter.frequency.setValueAtTime(cutoffStart, start);
    filter.frequency.exponentialRampToValueAtTime(cutoffEnd, start + dur * 0.72);

    const env = ctx.createGain();
    const attack = preset === 'guitar' ? 0.008 : 0.015;
    env.gain.setValueAtTime(0.0008, start);
    env.gain.linearRampToValueAtTime(outLevel, start + attack);
    env.gain.exponentialRampToValueAtTime(0.0008, start + dur);

    filter.connect(env);
    env.connect(ctx.destination);

    // Layer 1: Fundamental body oscillator
    const fund = ctx.createOscillator();
    fund.type = preset === 'piano' || preset === 'guitar' ? 'triangle' : 'sine';
    fund.frequency.setValueAtTime(freq, start);
    fund.connect(filter);
    fund.start(start);
    fund.stop(start + dur + 0.03);

    // Layer 2: Soft warm octave shimmer
    const overtone = ctx.createOscillator();
    const overGain = ctx.createGain();
    overtone.type = 'sine';
    overtone.frequency.setValueAtTime(freq * 2, start);
    overGain.gain.setValueAtTime(preset === 'guitar' ? 0.18 : 0.24, start);
    overGain.gain.exponentialRampToValueAtTime(0.001, start + dur * 0.55);
    overtone.connect(overGain);
    overGain.connect(filter);
    overtone.start(start);
    overtone.stop(start + dur * 0.6 + 0.02);

    // Layer 3: Gentle mallet / pluck transient
    const touch = ctx.createOscillator();
    const touchGain = ctx.createGain();
    touch.type = preset === 'guitar' ? 'sawtooth' : 'sine';
    touch.frequency.setValueAtTime(freq * (preset === 'guitar' ? 1 : 3), start);
    touchGain.gain.setValueAtTime(preset === 'guitar' ? 0.14 : 0.06, start);
    touchGain.gain.exponentialRampToValueAtTime(0.001, start + 0.08);
    touch.connect(touchGain);
    touchGain.connect(filter);
    touch.start(start);
    touch.stop(start + 0.1);
  }

  playDrumGroove(ctx, baseTime, vol) {
    const kickAt = (offset, pitchStart = 138, gainScale = 0.28) => {
      const start = baseTime + offset;
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(pitchStart, start);
      osc.frequency.exponentialRampToValueAtTime(46, start + 0.15);
      gain.gain.setValueAtTime(0.001, start);
      gain.gain.linearRampToValueAtTime(gainScale * vol, start + 0.008);
      gain.gain.exponentialRampToValueAtTime(0.001, start + 0.24);
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.start(start);
      osc.stop(start + 0.26);
    };

    const brushAt = (offset, freq = 1480, gainScale = 0.14) => {
      const start = baseTime + offset;
      const src = ctx.createBufferSource();
      src.buffer = this.getNoiseBuffer(ctx);
      const bp = ctx.createBiquadFilter();
      bp.type = 'bandpass';
      bp.frequency.setValueAtTime(freq, start);
      bp.Q.value = 1.25;
      const gain = ctx.createGain();
      gain.gain.setValueAtTime(0.001, start);
      gain.gain.linearRampToValueAtTime(gainScale * vol, start + 0.006);
      gain.gain.exponentialRampToValueAtTime(0.001, start + 0.09);
      src.connect(bp);
      bp.connect(gain);
      gain.connect(ctx.destination);
      src.start(start);
      src.stop(start + 0.11);
    };

    // Soft rhythmic 4-beat groove: Boom - Chik - Boom-boom - Tak
    kickAt(0.00, 138, 0.28);
    brushAt(0.19, 1550, 0.14);
    kickAt(0.36, 155, 0.24);
    brushAt(0.54, 1850, 0.16);
  }

  playToneSequence(notes, { preset = 'kalimba', peak = 0.16, noteGap = 0.08, noteDur = 0.55 } = {}) {
    const vol = this.getEffectiveVolume();
    if (vol <= 0) return;

    try {
      const ctx = this.getAudioContext();
      if (!ctx) return;

      const baseTime = ctx.currentTime;
      notes.forEach((freq, idx) => {
        this.playWarmVoice(ctx, freq, baseTime + idx * noteGap, noteDur, { preset, peak, vol });
      });
    } catch (e) {
      // Audio playback unavailable
    }
  }

  findMusicPresetKey(text) {
    const clean = String(text ?? '').trim().toLowerCase();
    if (!clean) return null;

    const keys = [
      'nada do tinggi',
      'nada do',
      'nada re',
      'nada mi',
      'nada fa',
      'nada sol',
      'nada la',
      'nada si',
      'gitar',
      'piano',
      'drum',
    ];

    for (const k of keys) {
      const escaped = k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      if (new RegExp(`\\b${escaped}\\b`, 'i').test(clean)) {
        return k;
      }
    }
    return null;
  }

  playMusicCue(keyOrText) {
    const key = this.findMusicPresetKey(keyOrText);
    if (!key) return 0;

    const vol = this.getEffectiveVolume();
    if (vol <= 0) return 0;

    const ctx = this.getAudioContext();
    if (!ctx) return 0;

    const baseTime = ctx.currentTime;
    const singleNoteMap = {
      'nada do': 261.63,
      'nada re': 293.66,
      'nada mi': 329.63,
      'nada fa': 349.23,
      'nada sol': 392.00,
      'nada la': 440.00,
      'nada si': 493.88,
      'nada do tinggi': 523.25,
    };

    if (singleNoteMap[key]) {
      const f = singleNoteMap[key];
      this.playWarmVoice(ctx, f, baseTime, 0.92, { preset: 'kalimba', peak: 0.2, vol });
      this.playWarmVoice(ctx, f * 2, baseTime + 0.04, 0.72, { preset: 'piano', peak: 0.07, vol });
      return 640;
    }

    if (key === 'gitar') {
      [196.00, 261.63, 329.63, 392.00, 523.25].forEach((f, idx) => {
        this.playWarmVoice(ctx, f, baseTime + idx * 0.055, 0.95, { preset: 'guitar', peak: 0.14, vol });
      });
      return 780;
    }

    if (key === 'piano') {
      [261.63, 329.63, 392.00, 493.88, 523.25].forEach((f, idx) => {
        this.playWarmVoice(ctx, f, baseTime + idx * 0.075, 1.0, { preset: 'piano', peak: 0.15, vol });
      });
      return 820;
    }

    if (key === 'drum') {
      this.playDrumGroove(ctx, baseTime, vol);
      return 740;
    }

    return 0;
  }

  playSelectSound() {
    this.playToneSequence([523.25], { preset: 'kalimba', peak: 0.1, noteDur: 0.16 });
  }

  playCorrectSound() {
    // Cozy rising major sparkle chord (C5 -> E5 -> G5 -> C6)
    this.playToneSequence([523.25, 659.25, 783.99, 1046.5], {
      preset: 'kalimba',
      peak: 0.16,
      noteGap: 0.075,
      noteDur: 0.56,
    });
  }

  playWrongSound() {
    // Soft wood/marimba gentle nudge (E4 -> C4)
    this.playToneSequence([329.63, 261.63], {
      preset: 'piano',
      peak: 0.13,
      noteGap: 0.12,
      noteDur: 0.34,
    });
  }

  playCelebrationSound() {
    const vol = this.getEffectiveVolume();
    if (vol <= 0) return;

    try {
      const ctx = this.getAudioContext();
      if (!ctx) return;

      const base = ctx.currentTime;
      // Warm sustained chord cushion (C4 + G4)
      this.playWarmVoice(ctx, 261.63, base, 1.25, { preset: 'piano', peak: 0.09, vol });
      this.playWarmVoice(ctx, 392.00, base + 0.04, 1.2, { preset: 'piano', peak: 0.08, vol });

      // Joyful music-box ascending fanfare
      [523.25, 659.25, 783.99, 987.77, 1046.5].forEach((f, idx) => {
        this.playWarmVoice(ctx, f, base + idx * 0.095, 0.72, { preset: 'kalimba', peak: 0.17, vol });
      });
    } catch (e) {
      // Audio playback unavailable
    }
  }

  stop() {
    this.playbackToken++;
    if (this.playbackTimer) {
      clearTimeout(this.playbackTimer);
      this.playbackTimer = null;
    }
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

  play({ audioUrl = null, text = '', segments = null, lang = 'id-ID', musicKey = null, noteNotes = null, onStart = null, onEnd = null }) {
    this.stop();

    if (this.getEffectiveVolume() <= 0) {
      if (onStart) onStart({ muted: true, text: 'Suara dimatikan' });
      this.playbackTimer = setTimeout(() => {
        if (onEnd) onEnd();
      }, 400);
      return;
    }

    const token = ++this.playbackToken;
    this.activeOnEnd = typeof onEnd === 'function' ? onEnd : null;

    const finish = () => {
      if (token !== this.playbackToken) return;
      this.playbackTimer = null;
      this.isPlaying = false;
      this.currentAudio = null;
      const cb = this.activeOnEnd;
      this.activeOnEnd = null;
      if (cb) cb();
    };

    const runSpeech = () => {
      if (Array.isArray(segments) && segments.length > 0) {
        this.playTTSSegments(segments, onStart, finish, token);
      } else {
        this.playTTS(text, onStart, finish, token, lang);
      }
    };

    let leadDelayMs = 0;
    const resolvedMusic = musicKey === false ? null : (musicKey || this.findMusicPresetKey(text));
    if (resolvedMusic) {
      leadDelayMs = this.playMusicCue(resolvedMusic);
    } else if (Array.isArray(noteNotes) && noteNotes.length > 0) {
      this.playToneSequence(noteNotes, { preset: 'kalimba', peak: 0.18, noteGap: 0.12, noteDur: 0.65 });
      leadDelayMs = 520;
    }

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
        runSpeech();
      };

      const startAudio = () => {
        if (token !== this.playbackToken) return;
        audio.play().catch(() => {
          if (token !== this.playbackToken) return;
          this.currentAudio = null;
          runSpeech();
        });
      };

      if (leadDelayMs > 0) {
        if (onStart) onStart({ muted: false, text: 'Mendengarkan nada...' });
        this.playbackTimer = setTimeout(startAudio, leadDelayMs);
      } else {
        startAudio();
      }
    } else if (leadDelayMs > 0) {
      this.isPlaying = true;
      if (onStart) onStart({ muted: false, text: 'Mendengarkan nada...' });
      this.playbackTimer = setTimeout(() => {
        if (token !== this.playbackToken) return;
        runSpeech();
      }, leadDelayMs);
    } else {
      runSpeech();
    }
  }

  createUtterance(text, lang = 'id-ID') {
    const targetLang = lang || 'id-ID';
    const utter = new SpeechSynthesisUtterance(text);
    const voice = this.pickVoice(targetLang);
    if (voice) {
      utter.voice = voice;
      utter.lang = voice.lang || targetLang;
    } else {
      utter.lang = targetLang;
    }

    const isEnglish = targetLang.toLowerCase().startsWith('en');
    utter.rate = isEnglish ? 0.86 : 0.82;
    utter.pitch = isEnglish ? 1.02 : 1.0;
    utter.volume = this.getEffectiveVolume();
    return utter;
  }

  playTTSSegments(segments, onStart, finish, token = this.playbackToken) {
    const clean = segments.filter((s) => s && String(s.text || '').trim() !== '');
    if (!('speechSynthesis' in window) || clean.length === 0) {
      if (onStart) onStart({ unavailable: true, text: 'Suara tidak tersedia' });
      finish();
      return;
    }

    window.speechSynthesis.cancel();
    let idx = 0;

    const speakNext = () => {
      if (token !== this.playbackToken) return;
      if (idx >= clean.length) {
        finish();
        return;
      }

      const item = clean[idx++];
      const utter = this.createUtterance(item.text, item.lang || 'id-ID');
      if (idx === 1) {
        utter.onstart = () => {
          if (token !== this.playbackToken) return;
          this.isPlaying = true;
          if (onStart) onStart({ muted: false, text: 'Sedang berbunyi...' });
        };
      }
      utter.onend = () => {
        if (token !== this.playbackToken) return;
        this.playbackTimer = setTimeout(speakNext, 65);
      };
      utter.onerror = finish;
      window.speechSynthesis.speak(utter);
    };

    speakNext();
  }

  playTTS(text, onStart, finish, token = this.playbackToken, lang = 'id-ID') {
    if (!('speechSynthesis' in window) || !text) {
      if (onStart) onStart({ unavailable: true, text: 'Suara tidak tersedia' });
      finish();
      return;
    }

    window.speechSynthesis.cancel();
    const utter = this.createUtterance(text, lang);

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

  buildBilingualSegments(text, englishTerm = null) {
    const raw = String(text ?? '').trim();
    if (!raw) return [];

    const quotedMatch = raw.match(/^(.*?)"([^"]+)"(.*)$/);
    if (quotedMatch) {
      const segs = [];
      if (quotedMatch[1].trim()) segs.push({ text: quotedMatch[1].trim(), lang: 'id-ID' });
      if (quotedMatch[2].trim()) segs.push({ text: quotedMatch[2].trim(), lang: 'en-US' });
      const tail = quotedMatch[3].replace(/^[?.,!\s]+/, '').trim();
      if (tail) segs.push({ text: tail, lang: 'id-ID' });
      return segs;
    }

    const term = String(englishTerm ?? '').trim();
    if (term) {
      const escaped = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      const m = raw.match(new RegExp(`^(.*?)(${escaped})(.*)$`, 'i'));
      if (m) {
        const segs = [];
        if (m[1].trim()) segs.push({ text: m[1].trim(), lang: 'id-ID' });
        segs.push({ text: m[2].trim(), lang: 'en-US' });
        if (m[3].trim()) segs.push({ text: m[3].trim(), lang: 'id-ID' });
        return segs;
      }
    }

    const promptMatch = raw.match(/^(Mana(?:\s+gambar)?(?:\s+yang\s+merupakan|\s+untuk\s+kata|\s+yang\s+bernama)\s+)(.+?)(\??)$/i);
    if (promptMatch) {
      return [
        { text: promptMatch[1].trim(), lang: 'id-ID' },
        { text: promptMatch[2].trim(), lang: 'en-US' },
      ];
    }

    return [{ text: raw, lang: 'en-US' }];
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
