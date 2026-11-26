/**
 * Quiz Engine for Sahabat Belajar (Sensory-Safe & Interactive)
 * - 1 question per screen, pure visual options (no text distractors inside option cards)
 * - No countdown timer, explicit 'Lanjut' confirmation
 * - Calm Web Audio feedback + live score badge + vector star celebration via shared helpers
 */

class QuizEngine {
    constructor(config) {
        this.quizId = config.quizId;
        this.startUrl = config.startUrl;
        this.answerUrl = config.answerUrl;
        this.finishUrl = config.finishUrl;
        this.csrfToken = config.csrfToken;
        this.homeUrl = config.homeUrl;

        this.attemptId = null;
        this.questions = [];
        this.currentIndex = 0;
        this.selectedMaterialId = null;
        this.score = 0;

        this.arena = document.getElementById('quiz-arena');
        this.progressText = document.getElementById('quiz-progress-text');
        this.progressDots = document.getElementById('quiz-progress-dots');
        this.scoreValEl = document.getElementById('quiz-score-val');
        this.promptText = document.getElementById('quiz-prompt-text');
        this.speakerBtn = document.getElementById('btn-speak-prompt');
        this.sfxToggleBtn = document.getElementById('btn-toggle-quiz-sfx');
        this.sfxToggleLabel = document.getElementById('quiz-sfx-label');
        this.feedbackEl = document.getElementById('quiz-feedback');
        this.feedbackTextEl = document.getElementById('quiz-feedback-text');

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
                            : 'Suara efek dan pertanyaan diaktifkan.'
                    );
                }
            );
        }

        try {
            const res = await fetch(this.startUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success && data.questions.length > 0) {
                this.attemptId = data.attempt_id;
                this.questions = data.questions;
                this.renderDots();
                this.renderQuestion();
            } else {
                this.setFeedback('hint', 'Soal kuis belum tersedia saat ini.');
                this.arena.innerHTML = '<div class="st-empty-card"><p class="st-arena-loading">Soal kuis belum tersedia.</p></div>';
            }
        } catch (e) {
            console.error('Gagal memulai kuis:', e);
            this.setFeedback('hint', 'Gagal memuat kuis. Silakan muat ulang halaman.');
            this.arena.innerHTML = '<div class="st-empty-card"><p class="st-arena-loading">Gagal memuat kuis. Silakan muat ulang halaman.</p></div>';
        }

        if (this.speakerBtn) {
            this.speakerBtn.addEventListener('click', () => {
                const q = this.questions[this.currentIndex];
                if (q && q.prompt_text) {
                    this.speak(q.prompt_text);
                }
            });
        }
    }

    renderDots() {
        if (!this.progressDots) return;
        this.progressDots.innerHTML = this.questions.map((_, i) => `
            <span class="quiz-dot ${i === 0 ? 'is-current' : ''}" data-idx="${i}"></span>
        `).join('');
    }

    updateProgress() {
        if (this.progressText) {
            this.progressText.textContent = `Soal ${this.currentIndex + 1} dari ${this.questions.length}`;
        }
        if (this.progressDots) {
            const dots = this.progressDots.querySelectorAll('.quiz-dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('is-current', i === this.currentIndex);
                dot.classList.toggle('is-done', i < this.currentIndex);
            });
        }
    }

    renderQuestion() {
        this.selectedMaterialId = null;
        this.updateProgress();
        this.setFeedback('neutral', 'Pilih satu gambar di bawah, lalu ketuk tombol "Lanjut".');

        const q = this.questions[this.currentIndex];
        if (this.promptText) {
            if (window.ArenaHelpers) {
                this.promptText.innerHTML = window.ArenaHelpers.formatPromptHtml(q.prompt_text);
            } else {
                this.promptText.textContent = q.prompt_text;
            }
        }

        this.arena.innerHTML = `
            <div class="st-quiz-stack">
                <div class="st-choice-grid">
                    ${q.options.map(opt => `
                        <button type="button" class="quiz-opt-btn st-quiz-opt-card" data-id="${opt.material_id}" aria-label="Pilihan gambar">
                            <img src="${this.esc(opt.image_url)}" alt="Pilihan Jawaban">
                        </button>
                    `).join('')}
                </div>

                <div class="st-quiz-next-wrap">
                    <button id="btn-next-question" type="button" class="btn btn-student btn-primary st-quiz-next-btn" disabled>
                        Lanjut &rarr;
                    </button>
                </div>
            </div>
        `;

        const optBtns = this.arena.querySelectorAll('.quiz-opt-btn');
        const nextBtn = document.getElementById('btn-next-question');

        optBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                optBtns.forEach(b => b.classList.remove('is-selected'));

                this.selectedMaterialId = btn.dataset.id;
                btn.classList.add('is-selected');
                this.sfx('playSelectSound');
                this.setFeedback('neutral', 'Bagus, pilihan tersimpan! Ketuk tombol "Lanjut" di bawah.');

                if (nextBtn) {
                    nextBtn.disabled = false;
                }
            });
        });

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (!this.selectedMaterialId) return;
                this.submitCurrentAnswer();
            });
        }
    }

    async submitCurrentAnswer() {
        const q = this.questions[this.currentIndex];
        const nextBtn = document.getElementById('btn-next-question');
        const optBtns = this.arena.querySelectorAll('.quiz-opt-btn');
        const selectedBtn = this.arena.querySelector(`.quiz-opt-btn[data-id="${this.selectedMaterialId}"]`);

        if (nextBtn) nextBtn.disabled = true;
        optBtns.forEach(b => { b.disabled = true; });

        let isCorrect = null;
        try {
            const res = await fetch(this.answerUrl.replace(':attempt', this.attemptId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    question_id: q.id,
                    selected_material_id: this.selectedMaterialId,
                    is_first_try: true,
                }),
            });
            const data = await res.json();
            if (res.ok && typeof data.is_correct === 'boolean') {
                isCorrect = data.is_correct;
            }
        } catch (e) {
            console.error('Gagal mengirim jawaban:', e);
        }

        if (isCorrect === null) {
            this.sfx('playWrongSound');
            optBtns.forEach(b => { b.disabled = false; });
            if (nextBtn) nextBtn.disabled = false;
            this.setFeedback('hint', 'Koneksi terganggu. Ketuk tombol "Lanjut" sekali lagi ya.');
            return;
        }

        if (isCorrect === true) {
            this.score += 10;
            if (this.scoreValEl) this.scoreValEl.textContent = String(this.score);
            this.sfx('playCorrectSound');
            if (selectedBtn) {
                selectedBtn.classList.remove('is-selected');
                selectedBtn.classList.add('is-correct');
                selectedBtn.insertAdjacentHTML('beforeend', '<span class="st-opt-badge">Benar</span>');
            }
            this.setFeedback('success', 'Hebat! "Jawaban Tepat" (+10 poin).');
        } else {
            this.sfx('playWrongSound');
            if (selectedBtn) {
                selectedBtn.classList.remove('is-selected');
                selectedBtn.classList.add('is-wrong');
            }
            this.setFeedback('hint', 'Belum tepat, tidak apa-apa! Ayo lanjut ke soal berikutnya.');
        }

        setTimeout(() => {
            this.currentIndex++;
            if (this.currentIndex < this.questions.length) {
                this.renderQuestion();
            } else {
                this.finishQuiz();
            }
        }, 850);
    }

    async finishQuiz() {
        let finishData = {
            stars: 1,
            percentage: 100,
            correct_count: 0,
            total_questions: this.questions.length,
            feedback: 'Luar Biasa!',
        };

        try {
            const res = await fetch(this.finishUrl.replace(':attempt', this.attemptId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success) {
                finishData = data;
            }
        } catch (e) {
            console.error('Gagal menyelesaikan kuis:', e);
        }

        this.sfx('playCelebrationSound');
        this.setFeedback('success', `Kuis selesai dengan total ${this.score} poin!`);

        const totalQuestions = finishData.total_questions || this.questions.length;
        const correctCount = finishData.correct_count ?? 0;
        const starsHtml = window.ArenaHelpers
            ? window.ArenaHelpers.renderStarsSvg(finishData.stars || 1)
            : '';

        this.arena.innerHTML = `
            <div class="st-celebration-card">
                <div class="st-stars-row" aria-label="Mendapat ${finishData.stars || 1} bintang">
                    ${starsHtml}
                </div>
                <h2 class="st-celebration-title">${this.esc(finishData.feedback)}</h2>
                <p class="st-celebration-desc">
                    Kamu telah menyelesaikan seluruh ${totalQuestions} soal kuis dengan tenang.
                </p>

                <div class="st-score-summary-grid">
                    <div class="st-score-summary-box">
                        <span class="st-score-summary-box__label">Total Skor</span>
                        <strong class="st-score-summary-box__val">${this.score} Poin</strong>
                    </div>
                    <div class="st-score-summary-box">
                        <span class="st-score-summary-box__label">Jawaban Tepat</span>
                        <strong class="st-score-summary-box__val">${correctCount} / ${totalQuestions} (${finishData.percentage ?? 0}%)</strong>
                    </div>
                </div>

                <div class="st-celebration-actions">
                    <button type="button" class="btn btn-student btn-secondary st-celebration-btn" onclick="window.location.reload()">
                        Coba Lagi
                    </button>
                    <a href="${this.esc(this.homeUrl)}" class="btn btn-student btn-primary st-celebration-btn">
                        Daftar Kuis
                    </a>
                </div>
            </div>
        `;

        if (this.promptText) this.promptText.textContent = 'Kuis Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        if (this.progressText) this.progressText.style.display = 'none';
        if (this.progressDots) this.progressDots.style.display = 'none';

        this.speak(`${finishData.feedback} Kamu mendapat ${this.score} poin.`);
    }
}

window.QuizEngine = QuizEngine;
