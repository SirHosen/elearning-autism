/**
 * Quiz Engine for EduAutis (Sensory-Safe)
 * Compliant with strict accessibility & sensory safety rules:
 * - 1 question per screen
 * - Pure visual options (NO text label inside option cards)
 * - NO visual or audible timer
 * - Soft highlight on select, explicit 'Lanjut' confirmation
 * - Always positive reinforcement on finish (1-3 stars)
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
                this.arena.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 2rem;">Soal kuis belum tersedia.</p>';
            }
        } catch (e) {
            console.error('Gagal memulai kuis:', e);
            this.arena.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 2rem;">Gagal memuat kuis. Silakan muat ulang halaman.</p>';
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
            <span class="quiz-dot" data-idx="${i}" style="width: 14px; height: 14px; border-radius: 50%; background: ${i === 0 ? 'var(--primary, #5A8DEE)' : 'var(--border)'}; display: inline-block; transition: all 0.2s ease;"></span>
        `).join('');
    }

    updateProgress() {
        if (this.progressText) {
            this.progressText.textContent = `Soal ${this.currentIndex + 1} dari ${this.questions.length}`;
        }
        if (this.progressDots) {
            const dots = this.progressDots.querySelectorAll('.quiz-dot');
            dots.forEach((dot, i) => {
                if (i === this.currentIndex) {
                    dot.style.background = 'var(--primary, #5A8DEE)';
                    dot.style.transform = 'scale(1.25)';
                } else if (i < this.currentIndex) {
                    dot.style.background = 'var(--success, #246B4D)';
                    dot.style.transform = 'scale(1)';
                } else {
                    dot.style.background = 'var(--border)';
                    dot.style.transform = 'scale(1)';
                }
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
            <div style="display: flex; flex-direction: column; gap: var(--space-8);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-6); justify-items: center;">
                    ${q.options.map(opt => `
                        <button type="button" class="quiz-opt-btn card" data-id="${opt.material_id}" style="width: 100%; max-width: 250px; aspect-ratio: 1/1; padding: var(--space-4); border: 4px solid var(--border); border-radius: var(--radius-lg); background: var(--surface); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                            <img src="${opt.image_url}" alt="Pilihan Jawaban" style="max-width: 85%; max-height: 85%; object-fit: contain; pointer-events: none;">
                        </button>
                    `).join('')}
                </div>

                <div style="display: flex; justify-content: center;">
                    <button id="btn-next-question" type="button" class="btn btn-primary" disabled style="font-size: 1.25rem; font-weight: 700; padding: var(--space-4) var(--space-10); border-radius: var(--radius-full); opacity: 0.5; cursor: not-allowed; transition: all 0.2s ease;">
                        Lanjut &rarr;
                    </button>
                </div>
            </div>
        `;

        const optBtns = this.arena.querySelectorAll('.quiz-opt-btn');
        const nextBtn = document.getElementById('btn-next-question');

        optBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                optBtns.forEach(b => {
                    b.style.borderColor = 'var(--border)';
                    b.style.boxShadow = 'var(--shadow-sm)';
                });

                this.selectedMaterialId = btn.dataset.id;
                btn.style.borderColor = 'var(--primary, #5A8DEE)';
                btn.style.boxShadow = '0 0 0 5px rgba(90, 141, 238, 0.35)';

                if (nextBtn) {
                    nextBtn.disabled = false;
                    nextBtn.style.opacity = '1';
                    nextBtn.style.cursor = 'pointer';
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

        const starEmojis = '⭐'.repeat(finishData.stars || 1);

        this.arena.innerHTML = `
            <div class="card" style="text-align: center; padding: var(--space-8); max-width: 480px; margin: 0 auto; border-radius: var(--radius-lg); background: var(--surface);">
                <div style="font-size: 3.5rem; letter-spacing: 6px; margin-bottom: var(--space-2); animation: floatSlow 3s ease-in-out infinite;">
                    ${starEmojis}
                </div>
                <h2 style="font-size: 1.75rem; margin-bottom: var(--space-2); color: var(--text);">${finishData.feedback}</h2>
                <p style="color: var(--text-muted); font-size: 1.125rem; margin-bottom: var(--space-6);">
                    Kamu telah menyelesaikan semua soal kuis dengan baik.
                </p>
                <div style="display: flex; justify-content: center; gap: var(--space-4); flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary" onclick="window.location.reload()" style="font-size: 1.125rem; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full);">
                        Coba Lagi
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

        if (this.promptText) this.promptText.textContent = 'Kuis Selesai!';
        if (this.speakerBtn) this.speakerBtn.style.display = 'none';
        if (this.progressText) this.progressText.style.display = 'none';
        if (this.progressDots) this.progressDots.style.display = 'none';

        this.speak(finishData.feedback);
    }
}

window.QuizEngine = QuizEngine;
