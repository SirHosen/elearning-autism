import assert from 'node:assert/strict';

function createMockElement(initial = {}) {
  const listeners = new Map();
  const classes = new Set(initial.classes || []);
  const attrs = new Map(Object.entries(initial.attrs || {}));
  let innerHTMLValue = initial.innerHTML || '';
  const el = {
    id: initial.id || '',
    dataset: { ...(initial.dataset || {}) },
    disabled: Boolean(initial.disabled),
    style: {},
    textContent: initial.textContent || '',
    get innerHTML() {
      return innerHTMLValue;
    },
    set innerHTML(val) {
      innerHTMLValue = val;
    },
    classList: {
      add: (...c) => c.forEach((x) => classes.add(x)),
      remove: (...c) => c.forEach((x) => classes.delete(x)),
      toggle: (c, force) => {
        if (force === undefined) {
          if (classes.has(c)) classes.delete(c);
          else classes.add(c);
        } else if (force) {
          classes.add(c);
        } else {
          classes.delete(c);
        }
      },
      contains: (c) => classes.has(c),
    },
    setAttribute: (k, v) => attrs.set(k, String(v)),
    getAttribute: (k) => attrs.get(k),
    addEventListener: (type, fn) => {
      if (!listeners.has(type)) listeners.set(type, []);
      listeners.get(type).push(fn);
    },
    click: () => el.dispatch('click'),
    dispatch: (type, event = {}) => {
      const e = { preventDefault: () => {}, dataTransfer: { setData: () => {} }, ...event };
      (listeners.get(type) || []).forEach((fn) => fn(e));
    },
    querySelector: () => createMockElement(),
    querySelectorAll: () => [],
    insertAdjacentHTML: () => {},
  };
  return el;
}

// 1. Setup minimal DOM & Web Speech / Audio mocks BEFORE importing modules
const elements = new Map();
const settingsEl = { textContent: JSON.stringify({ volume: 0, sfx_enabled: true }) };
elements.set('user-settings-data', settingsEl);

globalThis.document = {
  getElementById: (id) => elements.get(id) || null,
};

let canceledCalls = 0;
let lastSpoken = null;
globalThis.window = {
  speechSynthesis: {
    cancel: () => {
      canceledCalls++;
    },
    speak: (utter) => {
      lastSpoken = utter;
    },
  },
};
globalThis.SpeechSynthesisUtterance = class {
  constructor(text) {
    this.text = text;
  }
};

// Load AudioManager & ArenaHelpers
const { audioManager } = await import('../public/js/core/audio-manager.js');

// --- TEST CLICK-PATH-001: volume 0 -> setVolume(80) unmutes immediately ---
assert.equal(audioManager.sfxMuted, true, 'Initially muted at 0%');
assert.equal(audioManager.getEffectiveVolume(), 0);
audioManager.setVolume(80);
assert.equal(audioManager.sfxMuted, false, 'Raising volume > 0 must unmute sfxMuted');
assert.equal(audioManager.getEffectiveVolume(), 0.8);
audioManager.setVolume(0);
assert.equal(audioManager.sfxMuted, true, 'Lowering volume to 0 must mute sfxMuted');

// --- TEST CLICK-PATH-003: interrupting active play() runs previous onEnd and ignores stale callbacks ---
audioManager.setVolume(70);
let endedA = 0;
let endedB = 0;
audioManager.play({ text: 'Kata Pertama', onEnd: () => { endedA++; } });
const staleUtterA = lastSpoken;
audioManager.play({ text: 'Kalimat Kedua', onEnd: () => { endedB++; } });
assert.equal(endedA, 1, 'Previous onEnd must fire when interrupted by new playback');
assert.equal(endedB, 0, 'New playback onEnd has not fired yet');
// Simulate stale canceled onerror/onend arriving late from utter A
staleUtterA.onerror();
assert.equal(endedA, 1, 'Stale utterA callback must not re-fire onEnd');
assert.equal(endedB, 0, 'Stale utterA callback must not prematurely finish utterB');
lastSpoken.onend();
assert.equal(endedB, 1, 'Active utterB finishes cleanly');

// --- TEST CLICK-PATH-002: GameEngine seret_tempatkan double-tap / double-drop guard ---
const dragSource = createMockElement({ id: 'drag-source' });
const dropCorrect = createMockElement({ dataset: { id: '1', label: 'Kucing', correct: '1' } });
const dropSlot = createMockElement();
dropCorrect.querySelector = () => dropSlot;

const arenaEl = createMockElement({ id: 'game-arena' });
arenaEl.querySelectorAll = (sel) => (sel === '.drop-target' ? [dropCorrect] : []);
const scoreEl = createMockElement({ id: 'game-score-val', textContent: '0' });
const feedbackEl = createMockElement({ id: 'game-feedback' });
const feedbackTextEl = createMockElement({ id: 'game-feedback-text' });

elements.set('drag-source', dragSource);
elements.set('game-arena', arenaEl);
elements.set('game-score-val', scoreEl);
elements.set('game-feedback', feedbackEl);
elements.set('game-feedback-text', feedbackTextEl);

globalThis.fetch = async () => ({
  ok: true,
  json: async () => ({
    success: true,
    rounds: [
      {
        round_number: 1,
        target: { id: 1, label: 'Kucing', image_url: '/storage/materials/hewan-1.svg' },
        instruction: 'Tempatkan gambar pada kotak Kucing',
        targets: [{ id: 1, label: 'Kucing', is_correct: true }],
      },
      {
        round_number: 2,
        target: { id: 2, label: 'Anjing', image_url: '/storage/materials/hewan-2.svg' },
        instruction: 'Tempatkan gambar pada kotak Anjing',
        targets: [{ id: 2, label: 'Anjing', is_correct: true }],
      },
    ],
  }),
});

await import('../public/js/games/game-engine.js');
const engine = new globalThis.window.GameEngine({
  gameId: 3,
  gameType: 'seret_tempatkan',
  roundsUrl: '/api/games/3/rounds',
  sessionUrl: '/api/games/3/sessions',
  csrfToken: 'tok',
  homeUrl: '/bermain',
});
await new Promise((r) => setTimeout(r, 20));

// Tap source -> tap correct box TWICE rapidly + drop ONCE rapidly (including keyboard Enter)
dragSource.dispatch('keydown', { key: 'Enter' });
dropCorrect.dispatch('keydown', { key: 'Enter' });
dropCorrect.dispatch('click');
dropCorrect.dispatch('drop');

assert.equal(engine.score, 10, 'Score must only increment once per completed round');
assert.equal(engine.correctFirstTryCount, 1, 'correctFirstTryCount must not exceed 1 for 1 round');

// --- TEST CLICK-PATH-006: QuizEngine does not advance on network failure ---
const quizOpt = createMockElement({ dataset: { id: '11' } });
const nextBtn = createMockElement({ id: 'btn-next-question', disabled: true });
const quizArenaEl = createMockElement({ id: 'quiz-arena' });
quizArenaEl.querySelectorAll = (sel) => (sel === '.quiz-opt-btn' ? [quizOpt] : []);
quizArenaEl.querySelector = () => quizOpt;
elements.set('quiz-arena', quizArenaEl);
elements.set('btn-next-question', nextBtn);
elements.set('quiz-feedback', createMockElement({ id: 'quiz-feedback' }));
elements.set('quiz-feedback-text', createMockElement({ id: 'quiz-feedback-text' }));

globalThis.fetch = async (url) => {
  if (url.includes('/attempts')) {
    return {
      ok: true,
      json: async () => ({
        success: true,
        attempt_id: 99,
        questions: [{ id: 1, number: 1, prompt_text: 'Mana Kucing?', options: [{ material_id: 11, image_url: '/x.svg' }] }],
      }),
    };
  }
  throw new Error('Offline');
};

await import('../public/js/quiz/quiz-engine.js');
const quizEngine = new globalThis.window.QuizEngine({
  quizId: 1,
  startUrl: '/api/quizzes/1/attempts',
  answerUrl: '/api/quiz-attempts/:attempt/answers',
  finishUrl: '/api/quiz-attempts/:attempt/finish',
  csrfToken: 'tok',
  homeUrl: '/kuis',
});
await new Promise((r) => setTimeout(r, 20));
quizOpt.dispatch('click');
await quizEngine.submitCurrentAnswer();
assert.equal(quizEngine.currentIndex, 0, 'Failed answer POST must NOT skip to next question');
assert.equal(nextBtn.disabled, false, 'Next button must re-enable for retry after failure');

console.log('[SUKSES] Seluruh pengujian click-path JS (AudioManager, GameEngine, QuizEngine) lulus!');
