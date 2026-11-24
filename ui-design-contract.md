# EduAutis UI Design Direction Contract

## 1. Purpose
Platform e-learning inklusif untuk siswa autis SLB B-C Wijayakusumah.
Dua persona: **Siswa** (anak berkebutuhan khusus, butuh UI besar, tenang, visual) dan **Guru** (admin CRUD data, butuh dense scannable tables).

## 2. Audience
- **Siswa**: Anak autis usia 7-15 tahun. Butuh: target sentuh besar (min 64px), warna tenang, nol emoji, nol animasi berkedip, tipografi besar jelas.
- **Guru**: Pengajar SLB. Butuh: dashboard padat, tabel data, form CRUD, navigasi sidebar cepat.

## 3. Tone
**Calm institutional** — profesional, hangat tapi tidak childish. Tidak ada emoji/emoticon di manapun.
Warna: menggunakan token CSS yang sudah ada di `tokens.css` (--primary #2F6F9F, --success #246B4D, --bg #FBF8F3, dll).

## 4. Memorable Detail
- **Monogram letters** (S, G, E) untuk identitas role — bukan ikon SVG, bukan emoji.
- **CSS-only decorative dots** untuk bullet lists — bukan checkmark SVG, bukan emoji.
- **Clean accent top-bars** (4px color bar) di kartu sebagai pembeda visual.

## 5. Constraints
- Harus mempertahankan semua string yang di-assert di test (`Saya Siswa`, `Saya Guru`, `Masuk Siswa`, `Nama Pengguna Kamu`, `BELAJAR`, `BERMAIN`, `KUIS`, `VIDEO`, `ABSEN`, `Sudah Hadir`, `Saya Hadir!`, `Kehadiranmu Tercatat!`, `Panel Pengelolaan Guru`, `Kekerasan Suara`, `Warna Tampilan Layar`, `Ukuran Huruf`, `Animasi Layar`, dll).
- Zero emoji di seluruh project — replace semua 🎒🧩🎬🎮⭐👥📅📊⚙🏷🖨📥🔊🎨✨🧸🔤🔢🌟🐾🎈🙋‍♂️🎯🤖🎙⚠⌂↪ dengan teks/CSS murni.
- PSR-12 (Pint clean).
- Mempertahankan semua fungsionalitas JavaScript (game-engine, quiz-engine, audio-manager).
- Responsive: 360px mobile, 768px tablet, 1440px desktop.
- Sensory-safe: `--dur: 150ms` max, no flashing, no autoplay.

## 6. File Scope (semua .blade.php yang perlu diubah)

### Auth (3 files + 1 layout)
- `layouts/guest.blade.php` ✅ already clean
- `auth/role-select.blade.php` ✅ already clean
- `auth/login-student.blade.php` ✅ already clean
- `auth/login-teacher.blade.php` ✅ already clean

### Student (12 files + 1 layout)
- `layouts/student.blade.php` — hapus emoji ⌂ ⚙ ↪, replace dgn teks
- `student/home.blade.php` — hapus inline SVG icons dari tiles, ganti text-only
- `student/learn/index.blade.php` — hapus emoji 🎈🐾🎨🧸🔤🔢🌟
- `student/learn/viewer.blade.php` — hapus emoji 🔊 dari tombol dengar
- `student/learn/empty.blade.php` — cek emoji
- `student/attendance/index.blade.php` — hapus emoji 🙋‍♂️
- `student/game/index.blade.php` — hapus emoji 🎯🧩✋🎮
- `student/game/play.blade.php` — sudah clean (SVG speaker OK utk fungsional)
- `student/quiz/index.blade.php` — hapus emoji ⭐
- `student/quiz/play.blade.php` — sudah clean
- `student/video/index.blade.php` — hapus emoji 🎬
- `student/video/show.blade.php` — cek emoji
- `student/settings.blade.php` — hapus emoji ✨🔊🎨
- `student/students.blade.php` — cek emoji

### Admin (19 files + 1 layout)
- `layouts/admin.blade.php` — hapus semua emoji nav (🏷🧩🎬🎮⭐👥📅📊⚙⚠✓↪)
- `admin/dashboard.blade.php` — cek emoji
- Semua CRUD views (categories, materials, games, quizzes, students, videos, attendances, reports) — scan & replace emoji

## 7. Target Viewports
- 390x844 (Mobile)
- 1440x900 (Desktop)
