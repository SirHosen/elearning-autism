/**
 * Quiz Engine for Sahabat Belajar (Sensory-Safe)
 * Compliant with strict accessibility & sensory safety rules:
 * - 1 question per screen
 * - Pure visual options (NO text label inside option cards)
 * - NO visual or audible timer
 * - Soft highlight on select, explicit 'Lanjut' confirmation
 * - Always positive vector star reinforcement on finish (1-3 stars)
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

        this.arena = document.getElementById('quiz-arena');
        this.progressText = document.getElementById('quiz-progress-text');
        this.progressDots = document.getElementById('quiz-progress-dots');
        this.promptText = document.getElementById('quiz-prompt-text');
        this.speakerBtn = document.getElementById('btn-speak-prompt');

        this.init();
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
                this.arena.innerHTML = '<div class="st-empty-card"><p style="color: var(--ink-soft); margin: 0;">Soal kuis belum tersedia.</p></div>';
            }
        } catch (e) {
            console.error('Gagal memulai kuis:', e);
            this.arena.innerHTML = '<div class="st-empty-card"><p style="color: var(--ink-soft); margin: 0;">Gagal memuat kuis. Silakan muat ulang halaman.</p></div>';
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

        const q = this.questions[this.currentIndex];
        if (this.promptText) {
            this.promptText.textContent = q.prompt_text;
        }

        this.arena.innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 28px;">
                <div class="st-choice-grid">
                    ${q.options.map(opt => `
                        <button type="button" class="quiz-opt-btn st-quiz-opt-card" data-id="${opt.material_id}" aria-label="Pilihan gambar">
                            <img src="${opt.image_url}" alt="Pilihan Jawaban" style="max-width: 86%; max-height: 86%; object-fit: contain; pointer-events: none;">
                        </button>
                    `).join('')}
                </div>

                <div style="display: flex; justify-content: center;">
                    <button id="btn-next-question" type="button" class="btn btn-student btn-primary" disabled style="min-width: 220px; border-radius: 999px;">
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
        if (nextBtn) nextBtn.disabled = true;

        try {
            await fetch(this.answerUrl.replace(':attempt', this.attemptId), {
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
        } catch (e) {
            console.error('Gagal mengirim jawaban:', e);
        }

        this.currentIndex++;
        if (this.currentIndex < this.questions.length) {
            this.renderQuestion();
        } else {
            this.finishQuiz();
        }
    }

    async finishQuiz() {
        let finishData = {
            stars: 1,
            percentage: 100,
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

        this.arena.innerHTML = `
            <div class="st-celebration-card">
                <div class="st-stars-row" aria-label="Mendapat ${finishData.stars || 1} bintang">
                    ${this.renderStarsSvg(finishData.stars || 1)}
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 8px; color: var(--ink);">${finishData.feedback}</h2>
                <p style="color: var(--ink-soft); font-size: 1.0625rem; margin: 0 0 28px; line-height: 1.5;">
                    Kamu telah menyelesaikan semua soal kuis dengan baik.
                </p>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-student btn-secondary" onclick="window.location.reload()" style="border-radius: 999px;">
                        Coba Lagi
                    </button>
                    <a href="${this.homeUrl}" class="btn btn-student btn-primary" style="border-radius: 999px;">
                        Daftar Kuis
                    </a>
                </div>
            </div>
        `;

        if (this.promptText) this.promptText.textContent = 'Kuis Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        if (this.progressText) this.progressText.style.display = 'none';
        if (this.progressDots) this.progressDots.style.display = 'none';

        this.speak(finishData.feedback);
    }
}

window.QuizEngine = QuizEngine;
