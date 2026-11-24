# EduAutis — Website Pembelajaran Visual-Audio Ramah Sensorik
### SLB B-C Wijayakusumah, Sumedang

EduAutis adalah platform pembelajaran digital berbasis web yang dirancang khusus untuk anak dengan spektrum autisme (*Autism Spectrum Disorder* / ASD) di **SLB B-C Wijayakusumah**. Sistem ini mengutamakan keamanan sensorik (*sensory-safe design*), komunikasi berbasis visual (PECS / TEACCH), umpan balik positif tanpa vonis gagal, serta kemudahan guru dalam mengelola materi dan rapor belajar.

---

### Fitur Utama

1. **Keamanan Sensorik & Antarmuka Tenang (*Sensory-Safe UI*):**
   - Palet warna lembut bebas silau (*Calm Theme*) dan opsi *Kontras Tinggi* (WCAG AAA >= 7:1).
   - Tipografi menggunakan font **Lexend** (self-hosted lokal 4 bobot) yang dirancang secara ilmiah untuk meningkatkan keterbacaan.
   - Bebas suara bising mendadak (*zero buzzer*, *no harsh alarm*), umpan balik ramah melalui suara chime lembut Web Audio API.
   - Peniadaan animasi kilat/cepat untuk mencegah kejang fotosensitif (*no flashing animations*).
   - Dukungan viewport responsif dari smartphone 360px hingga layar desktop tanpa geser horizontal.

2. **Dua Peran Pengguna Sesuai Karakteristik:**
   - **Siswa:** Masuk menggunakan 4 digit PIN sederhana tanpa kata sandi rumit; antarmuka visual besar dengan tombol target sentuh minimal 48x48px.
   - **Guru / Admin:** Dashboard lengkap untuk mengelola kategori, kartu materi gambar, generator kuis otomatis, rekap absensi, dan pencetakan rapor ringkas.

3. **Modul Belajar Visual-Audio (Segmented Learning):**
   - Penampilan 1 objek per layar untuk menjaga fokus anak autis.
   - Narasi audio bahasa Indonesia otomatis via Web Speech API atau rekaman suara guru asli.
   - Navigasi tombol besar dan shortcut keyboard panah kiri/kanan.

4. **Tiga Engine Permainan Edukasi Interaktif:**
   - **Pilih yang Benar (Receptive Identification):** Mencocokkan instruksi dengan gambar; kartu salah meredup lembut (opacity 0.45), petunjuk otomatis muncul jika salah 2x berturut-turut.
   - **Cocokkan Gambar (Matching Pairs):** Menghubungkan gambar di kiri dengan teks/gambar di kanan.
   - **Seret & Tempatkan (Drag & Drop):** Penempatan gambar ke slot target berlabel, dilengkapi alternatif klik-sentuh bagi siswa dengan keterbatasan motorik halus.
   - Layar perayaan bintang melayang lambat (selalu minimal 1 bintang, tidak ada kondisi kalah).

5. **Kuis Bergambar & Generator Otomatis 1-Klik:**
   - Guru dapat membuat kuis otomatis dari kategori materi hanya dalam 1 klik.
   - Opsi jawaban murni visual (gambar tanpa teks) untuk meminimalkan beban baca anak autis.
   - Kebijakan keamanan isolasi sesi (siswa hanya dapat mengirim jawaban ke sesi miliknya sendiri).

6. **Presensi Kehadiran Siswa Idempoten:**
   - Siswa mencatat kehadiran secara mandiri melalui tombol konfirmasi ramah "Saya Hadir!".
   - Sistem menjamin integritas data (tidak ada entri duplikat pada tanggal yang sama).

7. **Laporan & Ekspor Data:**
   - Rekap absensi dan nilai siswa dapat diekspor ke CSV dengan encoding **UTF-8 BOM** agar terbaca rapi di Microsoft Excel.
   - Halaman cetak rapor belajar ringkas siap cetak (*print-friendly CSS*).

---

### Tech Stack & Arsitektur

- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** SQLite (lingkungan pengujian & demo lokal) / MySQL (lingkungan produksi VPS)
- **Frontend:** Pure CSS Design System berbasis Design Tokens (Zero CDN dependency, self-hosted Lexend WOFF2)
- **Audio & Suara:** Web Audio API (sintesis chime lembut) & Web Speech API (sintesis vokal Bahasa Indonesia `id-ID`)
- **Testing:** PHPUnit 11 (68+ automated feature & unit tests, 100% PASS)
- **Code Quality:** Laravel Pint (PSR-12 strict formatting)

---

### Panduan Instalasi Langkah Demi Langkah

#### 1. Kloning Repositori
```bash
git clone https://github.com/username/autism-elearning.git
cd autism-elearning
```

#### 2. Pasang Dependensi Composer & Node
```bash
composer install
npm install && npm run build
```

#### 3. Konfigurasi Lingkungan
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Migrasi Database & Seeder Data Awal
```bash
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
```

#### 5. Jalankan Server Pengembangan
```bash
php artisan serve
```
Buka browser pada alamat `http://localhost:8000`.

---

### Akun Demo Bawaan

| Peran | Username / Email | Kata Sandi / PIN | Keterangan |
| :--- | :--- | :--- | :--- |
| **Guru / Admin** | `guru@slb.test` | `password123` | Akses penuh panel admin & rekap |
| **Siswa A** | `siswa_a` | `1234` | Profil siswa mandiri |
| **Siswa B** | `siswa_b` | `1234` | Profil siswa pendamping |
| **Siswa C** | `siswa_c` | `1234` | Profil siswa terapi |

---

### Verifikasi Kualitas & Test Suite

```bash
# Menjalankan seluruh test suite otomatis (73 tests)
php artisan test

# Menjalankan linter standar kode
vendor/bin/pint --test

# Memeriksa rasio kontras warna WCAG AA / AAA
node scripts/check-contrast.mjs
```

---

### Dokumentasi Teknis Tambahan

Dokumentasi akademik dan teknis lengkap tersedia di folder `docs/`:
- [`01-analisis-kebutuhan.md`](docs/01-analisis-kebutuhan.md) — Analisis kebutuhan fungsional & non-fungsional
- [`02-uml/`](docs/02-uml/) — Diagram Use Case, Activity, Sequence, dan Class Diagram
- [`03-erd-database.md`](docs/03-erd-database.md) — Desain Entity-Relationship Diagram & Kamus Data
- [`04-wireframe.md`](docs/04-wireframe.md) — Rancangan Wireframe antarmuka siswa dan guru
- [`05-design-system.md`](docs/05-design-system.md) — Spesifikasi Design Tokens & Sensory Safe Design
- [`06-blackbox-testing.md`](docs/06-blackbox-testing.md) — Laporan lengkap 27 skenario uji blackbox
- [`07-usability-sus.md`](docs/07-usability-sus.md) — Instrumen System Usability Scale (SUS) siap pakai
- [`DEPLOYMENT.md`](docs/DEPLOYMENT.md) — Panduan deployment VPS Ubuntu & Offline Lab Sekolah
- [`PANDUAN_GURU.md`](docs/PANDUAN_GURU.md) — Panduan operasional ramah pengguna bagi guru SLB
- [`MAINTENANCE.md`](docs/MAINTENANCE.md) — Prosedur backup, pemeliharaan rutin, dan troubleshooting

---

### Lisensi & Hak Cipta
Hak cipta proyek ini didekasikan untuk pengembangan pendidikan inklusif di **SLB B-C Wijayakusumah**. Dilisensikan di bawah lisensi MIT.
