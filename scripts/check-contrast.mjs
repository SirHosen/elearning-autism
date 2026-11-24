// scripts/check-contrast.mjs
// Membaca token warna dan menghitung rasio kontras WCAG untuk kedua tema (Calm & Contrast)

function hexToRgb(hex) {
  hex = hex.replace(/^#/, '');
  if (hex.length === 3) {
    hex = hex.split('').map(c => c + c).join('');
  }
  const num = parseInt(hex, 16);
  return {
    r: (num >> 16) & 255,
    g: (num >> 8) & 255,
    b: num & 255
  };
}

function relativeLuminance({ r, g, b }) {
  const [rs, gs, bs] = [r, g, b].map(c => {
    c = c / 255;
    return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
  });
  return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
}

function contrastRatio(hex1, hex2) {
  const l1 = relativeLuminance(hexToRgb(hex1));
  const l2 = relativeLuminance(hexToRgb(hex2));
  const lighter = Math.max(l1, l2);
  const darker = Math.min(l1, l2);
  return (lighter + 0.05) / (darker + 0.05);
}

// 1. Token Tema Tenang (Calm - Default)
const calmTokens = {
  bg: '#FBF8F3',
  surface: '#FFFFFF',
  surfaceAlt: '#F3EEE5',
  ink: '#1F2933',
  inkSoft: '#4B5563',
  primary: '#2F6F9F',
  onPrimary: '#FFFFFF',
  success: '#246B4D',
  onSuccess: '#FFFFFF',
  successBg: '#E3F2EA',
  hintBg: '#FFF4DB',
  tintBlue: '#DCEBF7',
  tintGreen: '#DDF0E8',
  tintYellow: '#FCEFC7',
  tintLavender: '#E8E4F8',
  tintPeach: '#FBE3D6'
};

// 2. Token Tema Kontras Tinggi (High Contrast)
const contrastTokens = {
  bg: '#000000',
  surface: '#0B0B0B',
  ink: '#FFFFFF',
  inkSoft: '#E5E5E5',
  primary: '#FFD400',
  onPrimary: '#000000',
  success: '#4ADE80',
  onSuccess: '#000000',
  successBg: '#0F2A1A',
  hintBg: '#2A2200',
  tintAll: '#0B0B0B'
};

console.log('=== VERIFIKASI RASIO KONTRAS WCAG (scripts/check-contrast.mjs) ===\n');

let allPassed = true;

console.log('--- 1. TEMA TENANG (CALM) - Target: Normal >= 4.5:1 (AA), Large/UI >= 3:1 ---');
const calmPairs = [
  { name: 'Teks Utama / Latar (ink / bg)', fg: calmTokens.ink, bg: calmTokens.bg, target: 4.5 },
  { name: 'Teks Utama / Permukaan (ink / surface)', fg: calmTokens.ink, bg: calmTokens.surface, target: 4.5 },
  { name: 'Teks Sekunder / Permukaan (ink-soft / surface)', fg: calmTokens.inkSoft, bg: calmTokens.surface, target: 4.5 },
  { name: 'Teks Tombol / Primer (on-primary / primary)', fg: calmTokens.onPrimary, bg: calmTokens.primary, target: 4.5 },
  { name: 'Teks Tombol / Sukses (on-success / success)', fg: calmTokens.onSuccess, bg: calmTokens.success, target: 4.5 },
  { name: 'Teks / Sukses Background (success / success-bg)', fg: calmTokens.success, bg: calmTokens.successBg, target: 4.5 },
  { name: 'Teks / Tint Biru (ink / tint-blue)', fg: calmTokens.ink, bg: calmTokens.tintBlue, target: 4.5 },
  { name: 'Teks / Tint Hijau (ink / tint-green)', fg: calmTokens.ink, bg: calmTokens.tintGreen, target: 4.5 },
  { name: 'Teks / Tint Kuning (ink / tint-yellow)', fg: calmTokens.ink, bg: calmTokens.tintYellow, target: 4.5 },
  { name: 'Teks / Tint Lavender (ink / tint-lavender)', fg: calmTokens.ink, bg: calmTokens.tintLavender, target: 4.5 },
  { name: 'Teks / Tint Peach (ink / tint-peach)', fg: calmTokens.ink, bg: calmTokens.tintPeach, target: 4.5 },
  { name: 'Teks / Hint Background (ink / hint-bg)', fg: calmTokens.ink, bg: calmTokens.hintBg, target: 4.5 }
];

console.table(calmPairs.map(p => {
  const ratio = contrastRatio(p.fg, p.bg);
  const pass = ratio >= p.target;
  if (!pass) allPassed = false;
  return {
    'Pasangan Token': p.name,
    'Foreground': p.fg,
    'Background': p.bg,
    'Rasio': ratio.toFixed(2) + ':1',
    'Target': p.target + ':1',
    'Status': pass ? 'PASS (AA)' : 'FAIL'
  };
}));

console.log('\n--- 2. TEMA KONTRAS TINGGI (CONTRAST) - Target: AAA >= 7:1 ---');
const contrastPairs = [
  { name: 'Teks Utama / Latar Hitam (ink / bg)', fg: contrastTokens.ink, bg: contrastTokens.bg, target: 7.0 },
  { name: 'Teks Utama / Permukaan (ink / surface)', fg: contrastTokens.ink, bg: contrastTokens.surface, target: 7.0 },
  { name: 'Teks Sekunder / Permukaan (ink-soft / surface)', fg: contrastTokens.inkSoft, bg: contrastTokens.surface, target: 7.0 },
  { name: 'Teks Tombol / Primer Kuning (on-primary / primary)', fg: contrastTokens.onPrimary, bg: contrastTokens.primary, target: 7.0 },
  { name: 'Teks Tombol / Sukses Hijau (on-success / success)', fg: contrastTokens.onSuccess, bg: contrastTokens.success, target: 7.0 },
  { name: 'Teks / Tint Card (ink / tint-all)', fg: contrastTokens.ink, bg: contrastTokens.tintAll, target: 7.0 }
];

console.table(contrastPairs.map(p => {
  const ratio = contrastRatio(p.fg, p.bg);
  const pass = ratio >= p.target;
  if (!pass) allPassed = false;
  return {
    'Pasangan Token': p.name,
    'Foreground': p.fg,
    'Background': p.bg,
    'Rasio': ratio.toFixed(2) + ':1',
    'Target': p.target + ':1',
    'Status': pass ? 'PASS (AAA)' : 'FAIL'
  };
}));

if (!allPassed) {
  console.error('\n[GAGAL] Ada pasangan token warna yang tidak memenuhi kriteria WCAG!');
  process.exit(1);
} else {
  console.log('\n[SUKSES] Seluruh pasangan token warna LOLOS kriteria kontras WCAG AA/AAA!');
  process.exit(0);
}
