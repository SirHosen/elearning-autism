/**
 * Game Engine for Sahabat Belajar (Sensory-Safe)
 * Compliant with strict accessibility & sensory safety rules:
 * - NO buzzer, NO red X
 * - Gentle ding via Web Audio API (respects mute setting)
 * - Dimming wrong cards (opacity 0.45)
 * - Auto-hint after 2 consecutive errors
 * - Motor-accessible tap-to-select alternative for drag & drop
 * - Guaranteed >= 1 star vector celebration (no Unicode emojis)
 */

class GameEngine {
    constructor(config) {
        this.gameId = config.gameId;
        this.gameType = config.gameType;
        this.roundsUrl = config.roundsUrl;
        this.sessionUrl = config.sessionUrl;
        this.csrfToken = config.csrfToken;
        this.homeUrl = config.homeUrl;

        this.rounds = [];
        this.currentRoundIndex = 0;
        this.correctFirstTryCount = 0;
        this.startTime = Date.now();
        this.roundAttempts = 0;
        this.consecutiveErrors = 0;

        this.arena = document.getElementById('game-arena');
        this.progressEl = document.getElementById('game-progress');
        this.instructionEl = document.getElementById('game-instruction');
        this.speakerBtn = document.getElementById('btn-speak-instruction');

        this.init();
    }

    getVolume() {
        if (window.audioManager && typeof window.audioManager.volume === 'number') {
            return window.audioManager.volume;
        }
        return 0.7;
    }

    renderStarsSvg(stars) {
        const safeCount = Math.max(1, Math.min(3, stars || 1));
        return Array.from({ length: safeCount }, () => `
            <svg width="48" height="48" viewBox="0 0 24 24" fill="#FBBF24" stroke="#B45309" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        `).join('');
    }

    async init() {
        try {
            const res = await fetch(this.roundsUrl);
            const data = await res.json();
            if (data.success && data.rounds.length > 0) {
                this.rounds = data.rounds;
                this.renderRound();
            } else {
                this.arena.innerHTML = '<div class="st-empty-card"><p style="color: var(--ink-soft); margin: 0;">Materi permainan belum tersedia.</p></div>';
            }
        } catch (e) {
            console.error('Gagal memuat permainan:', e);
            this.arena.innerHTML = '<div class="st-empty-card"><p style="color: var(--ink-soft); margin: 0;">Gagal memuat data permainan. Silakan muat ulang halaman.</p></div>';
        }

        if (this.speakerBtn) {
            this.speakerBtn.addEventListener('click', () => {
                const round = this.rounds[this.currentRoundIndex];
                if (round && round.instruction) {
                    this.speak(round.instruction);
                }
            });
        }
    }

    playGentleDing() {
        const vol = this.getVolume();
        if (vol <= 0) return;

        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const now = ctx.currentTime;

            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(523.25, now);
            osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.1);

            gain.gain.setValueAtTime(0.01, now);
            gain.gain.linearRampToValueAtTime(0.2 * vol, now + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(now);
            osc.stop(now + 0.35);
        } catch (e) {
            // Audio context not allowed or unsupported
        }
    }

    speak(text) {
        if (!text) return;
        if (window.audioManager) {
            window.audioManager.play({ text });
            return;
        }
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        }
    }

    updateProgress() {
        if (this.progressEl) {
            this.progressEl.textContent = `Ronde ${this.currentRoundIndex + 1} dari ${this.rounds.length}`;
        }
    }

    renderRound() {
        this.roundAttempts = 0;
        this.consecutiveErrors = 0;
        this.updateProgress();

        const round = this.rounds[this.currentRoundIndex];
        if (this.instructionEl) {
            this.instructionEl.textContent = round.instruction || 'Selesaikan permainan';
        }

        if (this.gameType === 'pilih_benar') {
            this.renderPilihBenar(round);
        } else if (this.gameType === 'cocokkan') {
            this.renderCocokkan(round);
        } else if (this.gameType === 'seret_tempatkan') {
            this.renderSeretTempatkan(round);
        }
    }

    // --- ENGINE 1: PILIH YANG BENAR ---
    renderPilihBenar(round) {
        this.arena.innerHTML = `
            <div id="hint-banner" class="st-hint-banner">
                Petunjuk: perhatikan kartu yang diberi bingkai kuning ini.
            </div>
            <div class="st-choice-grid">
                ${round.options.map(opt => `
                    <button type="button" class="game-opt-card st-game-opt-card" data-id="${opt.id}" data-correct="${opt.is_correct ? '1' : '0'}">
                        <div class="st-opt-thumb">
                            <img src="${opt.image_url}" alt="${opt.label}">
                        </div>
                        <div class="st-opt-label">${opt.label}</div>
                    </button>
                `).join('')}
            </div>
        `;

        const cards = this.arena.querySelectorAll('.game-opt-card');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (card.disabled) return;
                const isCorrect = card.dataset.correct === '1';

                if (isCorrect) {
                    if (this.roundAttempts === 0) {
                        this.correctFirstTryCount++;
                    }
                    this.playGentleDing();
                    card.style.borderColor = 'var(--success)';
                    card.style.background = 'var(--success-bg)';
                    cards.forEach(c => { c.disabled = true; });

                    setTimeout(() => this.nextRound(), 950);
                } else {
                    this.roundAttempts++;
                    this.consecutiveErrors++;
                    card.style.opacity = '0.45';
                    card.style.cursor = 'default';
                    card.disabled = true;

                    if (this.consecutiveErrors >= 2) {
                        const hintBanner = document.getElementById('hint-banner');
                        if (hintBanner) hintBanner.style.display = 'block';
                        const correctCard = this.arena.querySelector('.game-opt-card[data-correct="1"]');
                        if (correctCard) {
                            correctCard.style.outline = '4px solid var(--hint-line)';
                            correctCard.style.outlineOffset = '4px';
                        }
                    }
                }
            });
        });
    }

    // --- ENGINE 2: COCOKKAN GAMBAR ---
    renderCocokkan(round) {
        let selectedLeft = null;
        let selectedRight = null;
        let matchedPairs = 0;
        const totalPairs = round.left_items.length;

        this.arena.innerHTML = `
            <div class="st-match-grid">
                <div class="st-match-col">
                    <div class="st-match-col-title">Pilih Gambar</div>
                    ${round.left_items.map(item => `
                        <button type="button" class="match-left-item st-match-tile" data-id="${item.id}">
                            <img src="${item.image_url}" alt="Gambar" style="width: 84px; height: 84px; object-fit: contain; pointer-events: none;">
                        </button>
                    `).join('')}
                </div>
                <div class="st-match-col">
                    <div class="st-match-col-title">Pilih Kata</div>
                    ${round.right_items.map(item => `
                        <button type="button" class="match-right-item st-match-tile" data-id="${item.id}">
                            ${item.label}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        const leftBtns = this.arena.querySelectorAll('.match-left-item');
        const rightBtns = this.arena.querySelectorAll('.match-right-item');

        const checkMatch = () => {
            if (!selectedLeft || !selectedRight) return;
            const leftId = selectedLeft.dataset.id;
            const rightId = selectedRight.dataset.id;

            if (leftId === rightId) {
                this.playGentleDing();
                selectedLeft.style.borderColor = 'var(--success)';
                selectedLeft.style.background = 'var(--success-bg)';
                selectedLeft.disabled = true;

                selectedRight.style.borderColor = 'var(--success)';
                selectedRight.style.background = 'var(--success-bg)';
                selectedRight.disabled = true;

                matchedPairs++;
                selectedLeft = null;
                selectedRight = null;

                if (matchedPairs >= totalPairs) {
                    if (this.roundAttempts === 0) {
                        this.correctFirstTryCount++;
                    }
                    setTimeout(() => this.nextRound(), 950);
                }
            } else {
                this.roundAttempts++;
                selectedLeft.style.borderColor = 'var(--line)';
                selectedLeft.style.boxShadow = 'none';
                selectedRight.style.borderColor = 'var(--line)';
                selectedRight.style.boxShadow = 'none';
                selectedLeft = null;
                selectedRight = null;
            }
        };

        leftBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled) return;
                leftBtns.forEach(b => {
                    if (!b.disabled) {
                        b.style.borderColor = 'var(--line)';
                        b.style.boxShadow = 'none';
                    }
                });
                selectedLeft = btn;
                btn.style.borderColor = 'var(--primary)';
                btn.style.boxShadow = '0 0 0 4px rgba(47, 111, 159, 0.22)';
                checkMatch();
            });
        });

        rightBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled) return;
                rightBtns.forEach(b => {
                    if (!b.disabled) {
                        b.style.borderColor = 'var(--line)';
                        b.style.boxShadow = 'none';
                    }
                });
                selectedRight = btn;
                btn.style.borderColor = 'var(--primary)';
                btn.style.boxShadow = '0 0 0 4px rgba(47, 111, 159, 0.22)';
                checkMatch();
            });
        });
    }

    // --- ENGINE 3: SERET & TEMPATKAN ---
    renderSeretTempatkan(round) {
        let isSelectedForMotor = false;

        this.arena.innerHTML = `
            <div class="st-drag-stage">
                <div style="text-align: center;">
                    <div id="drag-source" draggable="true" tabindex="0" role="button" aria-label="${round.target.label}" class="st-drag-source">
                        <img src="${round.target.image_url}" alt="${round.target.label}" style="max-width: 88%; max-height: 88%; object-fit: contain; pointer-events: none;">
                    </div>
                    <small style="color: var(--ink-soft); font-weight: 600; display: block; margin-top: 10px;">
                        Seret gambar atau ketuk untuk memilih, lalu ketuk kotak kata yang sesuai
                    </small>
                </div>

                <div class="st-drop-grid">
                    ${round.targets.map(t => `
                        <div class="drop-target st-drop-target" data-id="${t.id}" data-correct="${t.is_correct ? '1' : '0'}" tabindex="0" role="button">
                            <span style="font-size: 1.1875rem; font-weight: 700; color: var(--ink);">${t.label}</span>
                            <div class="drop-slot" style="margin-top: 10px; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;"></div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        const source = document.getElementById('drag-source');
        const dropTargets = this.arena.querySelectorAll('.drop-target');

        source.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', round.target.id);
            source.style.opacity = '0.5';
        });

        source.addEventListener('dragend', () => {
            source.style.opacity = '1';
        });

        source.addEventListener('click', () => {
            isSelectedForMotor = !isSelectedForMotor;
            if (isSelectedForMotor) {
                source.style.borderColor = 'var(--primary)';
                source.style.boxShadow = '0 0 0 5px rgba(47, 111, 159, 0.25)';
            } else {
                source.style.borderColor = 'var(--line)';
                source.style.boxShadow = '0 8px 20px rgba(31, 41, 51, 0.08)';
            }
        });

        const handlePlacement = (targetEl) => {
            const isCorrect = targetEl.dataset.correct === '1';
            if (isCorrect) {
                if (this.roundAttempts === 0) {
                    this.correctFirstTryCount++;
                }
                this.playGentleDing();
                targetEl.style.borderStyle = 'solid';
                targetEl.style.borderColor = 'var(--success)';
                targetEl.style.background = 'var(--success-bg)';
                const slot = targetEl.querySelector('.drop-slot');
                if (slot) {
                    slot.innerHTML = `<img src="${round.target.image_url}" alt="${round.target.label}" style="width: 54px; height: 54px; object-fit: contain;">`;
                }
                source.style.display = 'none';

                setTimeout(() => this.nextRound(), 950);
            } else {
                this.roundAttempts++;
                source.style.borderColor = 'var(--line)';
                source.style.boxShadow = '0 8px 20px rgba(31, 41, 51, 0.08)';
                isSelectedForMotor = false;
            }
        };

        dropTargets.forEach(target => {
            target.addEventListener('dragover', (e) => {
                e.preventDefault();
                target.style.borderColor = 'var(--primary)';
            });

            target.addEventListener('dragleave', () => {
                target.style.borderColor = 'var(--line)';
            });

            target.addEventListener('drop', (e) => {
                e.preventDefault();
                target.style.borderColor = 'var(--line)';
                handlePlacement(target);
            });

            target.addEventListener('click', () => {
                if (isSelectedForMotor) {
                    handlePlacement(target);
                }
            });
        });
    }

    nextRound() {
        this.currentRoundIndex++;
        if (this.currentRoundIndex < this.rounds.length) {
            this.renderRound();
        } else {
            this.finishGame();
        }
    }

    async finishGame() {
        const totalDuration = Math.round((Date.now() - this.startTime) / 1000);
        const totalRounds = this.rounds.length;
        const accuracy = (this.correctFirstTryCount / totalRounds) * 100;

        let stars = 1;
        if (accuracy >= 100) {
            stars = 3;
        } else if (accuracy >= 70) {
            stars = 2;
        }

        try {
            await fetch(this.sessionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    total_rounds: totalRounds,
                    correct_first_try: this.correctFirstTryCount,
                    duration_seconds: totalDuration,
                }),
            });
        } catch (e) {
            console.error('Gagal mencatat sesi:', e);
        }

        this.arena.innerHTML = `
            <div class="st-celebration-card">
                <div class="st-stars-row" aria-label="Mendapat ${stars} bintang">
                    ${this.renderStarsSvg(stars)}
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 8px; color: var(--ink);">Hebat Sekali!</h2>
                <p style="color: var(--ink-soft); font-size: 1.0625rem; margin: 0 0 28px; line-height: 1.5;">
                    Kamu berhasil menyelesaikan semua ronde dengan baik.
                </p>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-student btn-secondary" onclick="window.location.reload()" style="border-radius: 999px;">
                        Main Lagi
                    </button>
                    <a href="${this.homeUrl}" class="btn btn-student btn-primary" style="border-radius: 999px;">
                        Daftar Permainan
                    </a>
                </div>
            </div>
        `;

        if (this.instructionEl) this.instructionEl.textContent = 'Permainan Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        this.speak('Hebat sekali! Kamu berhasil menyelesaikan permainan.');
    }
}

window.GameEngine = GameEngine;
