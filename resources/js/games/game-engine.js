/**
 * Game Engine for Sahabat Belajar (Sensory-Safe & Interactive)
 * - Symmetrical layouts across Pilih Benar, Cocokkan, and Seret & Tempatkan
 * - Calm Web Audio feedback + TTS speaking via shared window.audioManager & window.ArenaHelpers
 * - Live cumulative positive score & round progress dots
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
        this.score = 0;
        this.startTime = Date.now();
        this.roundAttempts = 0;
        this.consecutiveErrors = 0;

        this.arena = document.getElementById('game-arena');
        this.progressEl = document.getElementById('game-progress');
        this.progressDotsEl = document.getElementById('game-progress-dots');
        this.scoreValEl = document.getElementById('game-score-val');
        this.instructionEl = document.getElementById('game-instruction');
        this.speakerBtn = document.getElementById('btn-speak-instruction');
        this.sfxToggleBtn = document.getElementById('btn-toggle-sfx');
        this.sfxToggleLabel = document.getElementById('sfx-toggle-label');
        this.feedbackEl = document.getElementById('game-feedback');
        this.feedbackTextEl = document.getElementById('game-feedback-text');

        this.init();
    }

    esc(str) {
        return window.ArenaHelpers ? window.ArenaHelpers.escapeHtml(str) : String(str ?? '');
    }

    setFeedback(variant, message) {
        if (window.ArenaHelpers) {
            window.ArenaHelpers.setFeedback(this.feedbackEl, this.feedbackTextEl, variant, message);
        } else if (this.feedbackTextEl) {
            this.feedbackTextEl.textContent = message;
        }
    }

    sfx(method) {
        if (window.audioManager && typeof window.audioManager[method] === 'function') {
            window.audioManager[method]();
        }
    }

    speak(text) {
        if (!text) return;
        if (window.audioManager) {
            if (window.audioManager.getEffectiveVolume() > 0) {
                window.audioManager.play({ text });
            }
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

    addScore(points) {
        this.score += points;
        if (this.scoreValEl) {
            this.scoreValEl.textContent = String(this.score);
        }
    }

    updateProgress() {
        if (this.progressEl) {
            this.progressEl.textContent = `Ronde ${this.currentRoundIndex + 1} dari ${this.rounds.length}`;
        }
        if (this.progressDotsEl) {
            this.progressDotsEl.innerHTML = this.rounds.map((_, idx) => `
                <span class="quiz-dot ${idx === this.currentRoundIndex ? 'is-current' : (idx < this.currentRoundIndex ? 'is-done' : '')}"></span>
            `).join('');
        }
    }

    async init() {
        if (window.ArenaHelpers && window.audioManager) {
            window.ArenaHelpers.bindSfxToggle(
                this.sfxToggleBtn,
                this.sfxToggleLabel,
                window.audioManager,
                (muted) => {
                    this.setFeedback(
                        'neutral',
                        muted
                            ? 'Mode hening aktif. Ketuk lagi kapan saja untuk menyalakan suara.'
                            : 'Suara efek dan pelafalan diaktifkan.'
                    );
                }
            );
        }

        try {
            const res = await fetch(this.roundsUrl);
            const data = await res.json();
            if (data.success && data.rounds.length > 0) {
                this.rounds = data.rounds;
                this.renderRound();
            } else {
                this.setFeedback('hint', 'Materi permainan pada kategori ini belum tersedia.');
                this.arena.innerHTML = '<div class="st-empty-card"><p class="st-arena-loading">Materi permainan belum tersedia.</p></div>';
            }
        } catch (e) {
            console.error('Gagal memuat permainan:', e);
            this.setFeedback('hint', 'Gagal memuat data permainan. Silakan muat ulang halaman.');
            this.arena.innerHTML = '<div class="st-empty-card"><p class="st-arena-loading">Gagal memuat data permainan. Silakan muat ulang halaman.</p></div>';
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

    renderRound() {
        this.roundAttempts = 0;
        this.consecutiveErrors = 0;
        this.updateProgress();

        const round = this.rounds[this.currentRoundIndex];
        const instr = round.instruction || 'Selesaikan permainan';
        const targetWord = round.target_label || round.target?.label || '';
        if (this.instructionEl) {
            if (window.ArenaHelpers) {
                this.instructionEl.innerHTML = window.ArenaHelpers.formatTargetHtml(instr, targetWord);
            } else {
                this.instructionEl.textContent = instr;
            }
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
        this.setFeedback('neutral', `Ketuk kartu gambar: "${round.target_label}"`);

        this.arena.innerHTML = `
            <div id="hint-banner" class="st-hint-banner">
                Petunjuk: perhatikan kartu yang diberi bingkai kuning ini.
            </div>
            <div class="st-choice-grid">
                ${round.options.map(opt => `
                    <button type="button" class="game-opt-card st-game-opt-card" data-id="${opt.id}" data-label="${this.esc(opt.label)}" data-correct="${opt.is_correct ? '1' : '0'}">
                        <div class="st-opt-thumb">
                            <img src="${this.esc(opt.image_url)}" alt="${this.esc(opt.label)}">
                        </div>
                        <div class="st-opt-label">${this.esc(opt.label)}</div>
                    </button>
                `).join('')}
            </div>
        `;

        const cards = this.arena.querySelectorAll('.game-opt-card');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (card.disabled) return;
                const isCorrect = card.dataset.correct === '1';
                const label = card.dataset.label || '';

                if (isCorrect) {
                    const earned = this.roundAttempts === 0 ? 10 : 5;
                    if (this.roundAttempts === 0) {
                        this.correctFirstTryCount++;
                    }
                    this.addScore(earned);
                    this.sfx('playCorrectSound');
                    card.classList.add('is-correct');
                    card.classList.remove('is-hinted');
                    card.insertAdjacentHTML('beforeend', '<span class="st-opt-badge">Benar</span>');
                    cards.forEach(c => { c.disabled = true; });
                    this.setFeedback('success', `Hebat! Itu memang "${label}" (+${earned} poin).`);

                    setTimeout(() => this.nextRound(), 1050);
                } else {
                    this.roundAttempts++;
                    this.consecutiveErrors++;
                    this.sfx('playWrongSound');
                    card.classList.add('is-wrong', 'is-dimmed');
                    card.disabled = true;
                    this.setFeedback('hint', `Itu "${label}". Yuk cari gambar "${round.target_label}"!`);

                    if (this.consecutiveErrors >= 2) {
                        const hintBanner = document.getElementById('hint-banner');
                        if (hintBanner) hintBanner.classList.add('is-visible');
                        const correctCard = this.arena.querySelector('.game-opt-card[data-correct="1"]');
                        if (correctCard) correctCard.classList.add('is-hinted');
                    }
                }
            });
        });
    }

    // --- ENGINE 2: COCOKKAN GAMBAR (SIMETRIS & INTERAKTIF) ---
    renderCocokkan(round) {
        let selectedLeft = null;
        let selectedRight = null;
        let matchedPairs = 0;
        let roundPairError = false;
        let locked = false;
        const totalPairs = round.left_items.length;

        this.setFeedback('neutral', 'Ketuk satu "Gambar" di kiri, lalu ketuk "Kata" pasangannya di kanan.');

        this.arena.innerHTML = `
            <div class="st-match-subbar">
                <span class="st-match-subbar__guide">Pasangkan gambar dengan kata yang tepat</span>
                <span id="match-pair-counter" class="st-topic-pill st-topic-pill--blue">Cocok: 0 dari ${totalPairs}</span>
            </div>

            <div class="st-match-grid">
                <div class="st-match-col">
                    <div class="st-match-col-title">Pilih Gambar</div>
                    ${round.left_items.map(item => `
                        <button type="button"
                                class="match-left-item st-match-tile"
                                data-id="${item.id}"
                                data-label="${this.esc(item.label)}"
                                aria-label="Gambar ${this.esc(item.label)}">
                            <div class="st-match-thumb">
                                <img src="${this.esc(item.image_url)}" alt="Gambar ${this.esc(item.label)}">
                            </div>
                        </button>
                    `).join('')}
                </div>
                <div class="st-match-col">
                    <div class="st-match-col-title">Pilih Kata</div>
                    ${round.right_items.map(item => `
                        <button type="button"
                                class="match-right-item st-match-tile"
                                data-id="${item.id}"
                                data-label="${this.esc(item.label)}">
                            <span class="st-match-word-icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                                </svg>
                            </span>
                            <span class="st-match-word-text">${this.esc(item.label)}</span>
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        const leftBtns = this.arena.querySelectorAll('.match-left-item');
        const rightBtns = this.arena.querySelectorAll('.match-right-item');
        const pairCounterEl = document.getElementById('match-pair-counter');

        const clearHints = () => {
            leftBtns.forEach(b => b.classList.remove('is-hinted'));
            rightBtns.forEach(b => b.classList.remove('is-hinted'));
        };

        const checkMatch = () => {
            if (!selectedLeft || !selectedRight) return;
            const leftEl = selectedLeft;
            const rightEl = selectedRight;
            const leftId = leftEl.dataset.id;
            const rightId = rightEl.dataset.id;

            clearHints();

            if (leftId === rightId) {
                const earned = !roundPairError ? 10 : 5;
                this.addScore(earned);
                this.consecutiveErrors = 0;
                roundPairError = false;
                this.sfx('playCorrectSound');

                leftEl.classList.remove('is-selected', 'is-wrong');
                leftEl.classList.add('is-matched');
                leftEl.disabled = true;
                leftEl.insertAdjacentHTML('beforeend', '<span class="st-match-check">Cocok</span>');

                rightEl.classList.remove('is-selected', 'is-wrong');
                rightEl.classList.add('is-matched');
                rightEl.disabled = true;
                rightEl.insertAdjacentHTML('beforeend', '<span class="st-match-check">Cocok</span>');

                matchedPairs++;
                if (pairCounterEl) {
                    pairCounterEl.textContent = `Cocok: ${matchedPairs} dari ${totalPairs}`;
                }

                const matchedWord = rightEl.dataset.label || '';
                this.setFeedback('success', `Bagus! Gambar cocok dengan "${matchedWord}" (+${earned} poin).`);

                selectedLeft = null;
                selectedRight = null;

                if (matchedPairs >= totalPairs) {
                    if (this.roundAttempts === 0) {
                        this.correctFirstTryCount++;
                    }
                    setTimeout(() => this.nextRound(), 1050);
                }
            } else {
                this.roundAttempts++;
                this.consecutiveErrors++;
                roundPairError = true;
                locked = true;
                this.sfx('playWrongSound');

                leftEl.classList.remove('is-selected');
                rightEl.classList.remove('is-selected');
                leftEl.classList.add('is-wrong');
                rightEl.classList.add('is-wrong');

                this.setFeedback('hint', 'Belum cocok. Ayo coba pasangkan dengan teliti ya!');

                if (this.consecutiveErrors >= 2) {
                    const targetRight = Array.from(rightBtns).find(b => b.dataset.id === leftId && !b.disabled);
                    if (targetRight) {
                        leftEl.classList.add('is-hinted');
                        targetRight.classList.add('is-hinted');
                        this.setFeedback('hint', `Petunjuk: coba pasangkan kartu yang berkilau kuning ("${targetRight.dataset.label}").`);
                    }
                }

                setTimeout(() => {
                    leftEl.classList.remove('is-wrong');
                    rightEl.classList.remove('is-wrong');
                    selectedLeft = null;
                    selectedRight = null;
                    locked = false;
                }, 520);
            }
        };

        leftBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled || locked) return;
                if (selectedLeft === btn) {
                    btn.classList.remove('is-selected');
                    selectedLeft = null;
                    this.setFeedback('neutral', 'Pilihan gambar dibatalkan. Silakan pilih gambar lain.');
                    return;
                }

                leftBtns.forEach(b => {
                    if (!b.disabled) b.classList.remove('is-selected');
                });
                selectedLeft = btn;
                btn.classList.add('is-selected');
                this.sfx('playSelectSound');

                if (!selectedRight) {
                    this.setFeedback('neutral', 'Gambar dipilih! Sekarang ketuk kata yang sesuai di kolom kanan.');
                }
                checkMatch();
            });
        });

        rightBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled || locked) return;
                const wordLabel = btn.dataset.label || '';

                if (selectedRight === btn) {
                    btn.classList.remove('is-selected');
                    selectedRight = null;
                    this.setFeedback('neutral', 'Pilihan kata dibatalkan. Silakan pilih kata lain.');
                    return;
                }

                rightBtns.forEach(b => {
                    if (!b.disabled) b.classList.remove('is-selected');
                });
                selectedRight = btn;
                btn.classList.add('is-selected');
                this.sfx('playSelectSound');

                if (!selectedLeft) {
                    this.speak(wordLabel);
                    this.setFeedback('neutral', `Kata "${wordLabel}" dipilih! Sekarang ketuk gambarnya di kolom kiri.`);
                }
                checkMatch();
            });
        });
    }

    // --- ENGINE 3: SERET & TEMPATKAN ---
    renderSeretTempatkan(round) {
        let isSelectedForMotor = false;
        let roundCompleted = false;
        this.setFeedback('neutral', `Seret atau ketuk gambar, lalu pilih kotak "${round.target.label}".`);

        this.arena.innerHTML = `
            <div class="st-drag-stage">
                <div class="st-drag-source-wrap">
                    <div id="drag-source" draggable="true" tabindex="0" role="button" aria-label="${this.esc(round.target.label)}" class="st-drag-source">
                        <img src="${this.esc(round.target.image_url)}" alt="${this.esc(round.target.label)}">
                    </div>
                    <small class="st-drag-helper">
                        Ketuk gambar untuk memilih atau seret langsung ke salah satu kotak di bawah
                    </small>
                </div>

                <div class="st-drop-grid">
                    ${round.targets.map(t => `
                        <div class="drop-target st-drop-target" data-id="${t.id}" data-label="${this.esc(t.label)}" data-correct="${t.is_correct ? '1' : '0'}" tabindex="0" role="button">
                            <span class="st-drop-label">${this.esc(t.label)}</span>
                            <div class="drop-slot st-drop-slot"></div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        const source = document.getElementById('drag-source');
        const dropTargets = this.arena.querySelectorAll('.drop-target');

        source.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', String(round.target.id));
            source.classList.add('is-dragging');
        });

        source.addEventListener('dragend', () => {
            source.classList.remove('is-dragging');
        });

        source.addEventListener('click', () => {
            if (roundCompleted) return;
            isSelectedForMotor = !isSelectedForMotor;
            source.classList.toggle('is-selected', isSelectedForMotor);
            if (isSelectedForMotor) {
                this.sfx('playSelectSound');
                this.setFeedback('neutral', `Gambar siap! Sekarang ketuk kotak "${round.target.label}" di bawah.`);
            } else {
                this.setFeedback('neutral', `Seret gambar atau ketuk untuk memilih kotak "${round.target.label}".`);
            }
        });

        source.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                source.click();
            }
        });

        const handlePlacement = (targetEl) => {
            if (roundCompleted) return;
            const isCorrect = targetEl.dataset.correct === '1';
            if (isCorrect) {
                roundCompleted = true;
                isSelectedForMotor = false;
                const earned = this.roundAttempts === 0 ? 10 : 5;
                if (this.roundAttempts === 0) {
                    this.correctFirstTryCount++;
                }
                this.addScore(earned);
                this.sfx('playCorrectSound');

                dropTargets.forEach(t => t.classList.remove('is-hinted', 'is-wrong'));
                targetEl.classList.add('is-correct');
                const slot = targetEl.querySelector('.drop-slot');
                if (slot) {
                    slot.innerHTML = `<img src="${this.esc(round.target.image_url)}" alt="${this.esc(round.target.label)}">`;
                }
                source.style.display = 'none';
                this.setFeedback('success', `Hebat! Gambar masuk ke kotak "${round.target.label}" (+${earned} poin).`);

                setTimeout(() => this.nextRound(), 1050);
            } else {
                this.roundAttempts++;
                this.consecutiveErrors++;
                this.sfx('playWrongSound');
                targetEl.classList.add('is-wrong');
                this.setFeedback('hint', `Itu kotak "${targetEl.dataset.label}". Coba letakkan di kotak "${round.target.label}" ya!`);

                if (this.consecutiveErrors >= 2) {
                    const correctBox = this.arena.querySelector('.drop-target[data-correct="1"]');
                    if (correctBox) correctBox.classList.add('is-hinted');
                }

                setTimeout(() => {
                    targetEl.classList.remove('is-wrong');
                }, 520);
            }
        };

        dropTargets.forEach(target => {
            target.addEventListener('dragover', (e) => {
                e.preventDefault();
                target.classList.add('is-drag-over');
            });

            target.addEventListener('dragleave', () => {
                target.classList.remove('is-drag-over');
            });

            target.addEventListener('drop', (e) => {
                e.preventDefault();
                target.classList.remove('is-drag-over');
                handlePlacement(target);
            });

            target.addEventListener('click', () => {
                if (isSelectedForMotor) {
                    handlePlacement(target);
                } else {
                    this.speak(target.dataset.label || '');
                    this.setFeedback('neutral', `Kotak "${target.dataset.label}". Ketuk gambar di atas dulu untuk meletakkannya.`);
                }
            });

            target.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    target.click();
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
        const accuracy = totalRounds > 0 ? Math.round((this.correctFirstTryCount / totalRounds) * 100) : 100;

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

        if (this.progressDotsEl) {
            this.progressDotsEl.querySelectorAll('.quiz-dot').forEach(dot => {
                dot.classList.remove('is-current');
                dot.classList.add('is-done');
            });
        }

        this.sfx('playCelebrationSound');
        this.setFeedback('success', `Permainan selesai dengan total ${this.score} poin!`);

        const starsHtml = window.ArenaHelpers
            ? window.ArenaHelpers.renderStarsSvg(stars)
            : '';

        this.arena.innerHTML = `
            <div class="st-celebration-card">
                <div class="st-stars-row" aria-label="Mendapat ${stars} bintang">
                    ${starsHtml}
                </div>
                <h2 class="st-celebration-title">Hebat Sekali!</h2>
                <p class="st-celebration-desc">
                    Kamu berhasil menyelesaikan semua ${totalRounds} ronde permainan dengan tenang dan fokus.
                </p>

                <div class="st-score-summary-grid">
                    <div class="st-score-summary-box">
                        <span class="st-score-summary-box__label">Total Skor</span>
                        <strong class="st-score-summary-box__val">${this.score} Poin</strong>
                    </div>
                    <div class="st-score-summary-box">
                        <span class="st-score-summary-box__label">Tepat Percobaan Pertama</span>
                        <strong class="st-score-summary-box__val">${this.correctFirstTryCount} / ${totalRounds} (${accuracy}%)</strong>
                    </div>
                </div>

                <div class="st-celebration-actions">
                    <button type="button" class="btn btn-student btn-secondary st-celebration-btn" onclick="window.location.reload()">
                        Main Lagi
                    </button>
                    <a href="${this.esc(this.homeUrl)}" class="btn btn-student btn-primary st-celebration-btn">
                        Daftar Permainan
                    </a>
                </div>
            </div>
        `;

        if (this.instructionEl) this.instructionEl.textContent = 'Permainan Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        this.speak(`Hebat sekali! Kamu mendapat ${this.score} poin.`);
    }
}

window.GameEngine = GameEngine;
