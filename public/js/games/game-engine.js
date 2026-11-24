/**
 * Game Engine for EduAutis (Sensory-Safe)
 * Compliant with strict accessibility & sensory safety rules:
 * - NO buzzer, NO red X
 * - Gentle ding via Web Audio API
 * - Dimming wrong cards (opacity 0.45)
 * - Auto-hint after 2 consecutive errors
 * - Motor-accessible tap-to-select alternative for drag & drop
 * - Guaranteed >= 1 star celebration
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

        // Container references
        this.arena = document.getElementById('game-arena');
        this.progressEl = document.getElementById('game-progress');
        this.instructionEl = document.getElementById('game-instruction');
        this.speakerBtn = document.getElementById('btn-speak-instruction');

        this.init();
    }

    async init() {
        try {
            const res = await fetch(this.roundsUrl);
            const data = await res.json();
            if (data.success && data.rounds.length > 0) {
                this.rounds = data.rounds;
                this.renderRound();
            } else {
                this.arena.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 2rem;">Materi permainan belum tersedia.</p>';
            }
        } catch (e) {
            console.error('Gagal memuat permainan:', e);
            this.arena.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 2rem;">Gagal memuat data permainan. Silakan muat ulang halaman.</p>';
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
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const now = ctx.currentTime;

            // Two gentle ascending tones (C5 -> E5)
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(523.25, now); // C5
            osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.1); // E5

            gain.gain.setValueAtTime(0.01, now);
            gain.gain.linearRampToValueAtTime(0.2, now + 0.05);
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
            <div id="hint-banner" style="display: none; background: #FEF3C7; color: #92400E; padding: 10px 16px; border-radius: var(--radius-md); font-weight: 600; text-align: center; margin-bottom: 1.5rem; border: 2px solid #F59E0B;">
                💡 Lihat yang ini.
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--space-6); justify-items: center;">
                ${round.options.map((opt, idx) => `
                    <button type="button" class="game-opt-card card" data-id="${opt.id}" data-correct="${opt.is_correct ? '1' : '0'}" style="width: 100%; max-width: 240px; padding: var(--space-4); border: 3px solid var(--border); border-radius: var(--radius-lg); background: var(--surface); cursor: pointer; text-align: center; transition: all 0.2s ease;">
                        <div style="width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; background: var(--surface-alt); border-radius: var(--radius-md); overflow: hidden; margin-bottom: var(--space-3);">
                            <img src="${opt.image_url}" alt="${opt.label}" style="max-width: 85%; max-height: 85%; object-fit: contain;">
                        </div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--text);">${opt.label}</div>
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
                    card.style.borderColor = 'var(--success, #246B4D)';
                    card.style.background = 'var(--success-bg, #E3F2EA)';
                    cards.forEach(c => c.disabled = true);

                    setTimeout(() => this.nextRound(), 1000);
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
                            correctCard.style.outline = '4px solid #D97706';
                            correctCard.style.outlineOffset = '3px';
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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-8); max-width: 680px; margin: 0 auto;">
                <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div style="font-weight: 600; color: var(--text-muted); font-size: 0.9375rem; text-align: center;">Gambar</div>
                    ${round.left_items.map(item => `
                        <button type="button" class="match-left-item card" data-id="${item.id}" style="padding: var(--space-3); border: 3px solid var(--border); border-radius: var(--radius-md); background: var(--surface); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <img src="${item.image_url}" alt="Gambar" style="width: 80px; height: 80px; object-fit: contain;">
                        </button>
                    `).join('')}
                </div>
                <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div style="font-weight: 600; color: var(--text-muted); font-size: 0.9375rem; text-align: center;">Kata</div>
                    ${round.right_items.map(item => `
                        <button type="button" class="match-right-item card" data-id="${item.id}" style="padding: var(--space-3); min-height: 86px; border: 3px solid var(--border); border-radius: var(--radius-md); background: var(--surface); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700; color: var(--text);">
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
                selectedLeft.style.borderColor = 'var(--success, #246B4D)';
                selectedLeft.style.background = 'var(--success-bg, #E3F2EA)';
                selectedLeft.disabled = true;

                selectedRight.style.borderColor = 'var(--success, #246B4D)';
                selectedRight.style.background = 'var(--success-bg, #E3F2EA)';
                selectedRight.disabled = true;

                matchedPairs++;
                selectedLeft = null;
                selectedRight = null;

                if (matchedPairs >= totalPairs) {
                    if (this.roundAttempts === 0) {
                        this.correctFirstTryCount++;
                    }
                    setTimeout(() => this.nextRound(), 1000);
                }
            } else {
                this.roundAttempts++;
                // Gentle deselect without penalty or buzzer
                selectedLeft.style.borderColor = 'var(--border)';
                selectedRight.style.borderColor = 'var(--border)';
                selectedLeft = null;
                selectedRight = null;
            }
        };

        leftBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled) return;
                leftBtns.forEach(b => {
                    if (!b.disabled) b.style.borderColor = 'var(--border)';
                });
                selectedLeft = btn;
                btn.style.borderColor = 'var(--primary, #5A8DEE)';
                checkMatch();
            });
        });

        rightBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled) return;
                rightBtns.forEach(b => {
                    if (!b.disabled) b.style.borderColor = 'var(--border)';
                });
                selectedRight = btn;
                btn.style.borderColor = 'var(--primary, #5A8DEE)';
                checkMatch();
            });
        });
    }

    // --- ENGINE 3: SERET & TEMPATKAN ---
    renderSeretTempatkan(round) {
        let isSelectedForMotor = false;

        this.arena.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; gap: var(--space-8); max-width: 680px; margin: 0 auto;">
                <div style="text-align: center;">
                    <div id="drag-source" draggable="true" tabindex="0" role="button" aria-label="${round.target.label}" style="width: 140px; height: 140px; padding: var(--space-2); border: 3px solid var(--border); border-radius: var(--radius-lg); background: var(--surface); cursor: grab; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); transition: all 0.2s ease;">
                        <img src="${round.target.image_url}" alt="${round.target.label}" style="max-width: 90%; max-height: 90%; object-fit: contain; pointer-events: none;">
                    </div>
                    <small style="color: var(--text-muted); display: block; margin-top: var(--space-2);">Seret gambar atau klik untuk memilih</small>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: var(--space-4); width: 100%;">
                    ${round.targets.map(t => `
                        <div class="drop-target card" data-id="${t.id}" data-correct="${t.is_correct ? '1' : '0'}" tabindex="0" role="button" style="min-height: 120px; border: 3px dashed var(--border); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: var(--space-3); cursor: pointer; background: var(--surface-alt); transition: all 0.2s ease;">
                            <span style="font-size: 1.125rem; font-weight: 700; color: var(--text);">${t.label}</span>
                            <div class="drop-slot" style="margin-top: var(--space-2); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"></div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        const source = document.getElementById('drag-source');
        const dropTargets = this.arena.querySelectorAll('.drop-target');

        // Drag start & end
        source.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', round.target.id);
            source.style.opacity = '0.5';
        });

        source.addEventListener('dragend', () => {
            source.style.opacity = '1';
        });

        // Motor accessibility click alternative
        source.addEventListener('click', () => {
            isSelectedForMotor = !isSelectedForMotor;
            if (isSelectedForMotor) {
                source.style.borderColor = 'var(--primary, #5A8DEE)';
                source.style.boxShadow = '0 0 0 4px rgba(90, 141, 238, 0.3)';
            } else {
                source.style.borderColor = 'var(--border)';
                source.style.boxShadow = 'var(--shadow-sm)';
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
                targetEl.style.borderColor = 'var(--success, #246B4D)';
                targetEl.style.background = 'var(--success-bg, #E3F2EA)';
                const slot = targetEl.querySelector('.drop-slot');
                if (slot) {
                    slot.innerHTML = `<img src="${round.target.image_url}" style="width: 50px; height: 50px; object-fit: contain;">`;
                }
                source.style.display = 'none';

                setTimeout(() => this.nextRound(), 1000);
            } else {
                this.roundAttempts++;
                // Gentle reset without harsh feedback
                source.style.borderColor = 'var(--border)';
                source.style.boxShadow = 'var(--shadow-sm)';
                isSelectedForMotor = false;
            }
        };

        dropTargets.forEach(target => {
            target.addEventListener('dragover', (e) => {
                e.preventDefault();
                target.style.borderColor = 'var(--primary, #5A8DEE)';
            });

            target.addEventListener('dragleave', () => {
                target.style.borderColor = 'var(--border)';
            });

            target.addEventListener('drop', (e) => {
                e.preventDefault();
                target.style.borderColor = 'var(--border)';
                handlePlacement(target);
            });

            // Motor-accessible tap
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

        const starEmojis = '⭐'.repeat(stars);

        this.arena.innerHTML = `
            <div class="card" style="text-align: center; padding: var(--space-8); max-width: 480px; margin: 0 auto; border-radius: var(--radius-lg); background: var(--surface);">
                <div style="font-size: 3.5rem; letter-spacing: 6px; margin-bottom: var(--space-2); animation: floatSlow 3s ease-in-out infinite;">
                    ${starEmojis}
                </div>
                <h2 style="font-size: 1.75rem; margin-bottom: var(--space-2); color: var(--text);">Hebat Sekali!</h2>
                <p style="color: var(--text-muted); font-size: 1.125rem; margin-bottom: var(--space-6);">
                    Kamu berhasil menyelesaikan semua ronde dengan baik.
                </p>
                <div style="display: flex; justify-content: center; gap: var(--space-4); flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary" onclick="window.location.reload()" style="font-size: 1.125rem; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full);">
                        Main Lagi
                    </button>
                    <a href="${this.homeUrl}" class="btn btn-primary" style="font-size: 1.125rem; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full);">
                        Menu Utama
                    </a>
                </div>
            </div>
            <style>
                @keyframes floatSlow {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-8px); }
                }
            </style>
        `;

        if (this.instructionEl) this.instructionEl.textContent = 'Permainan Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        this.speak('Hebat sekali! Kamu berhasil menyelesaikan permainan.');
    }
}

window.GameEngine = GameEngine;
