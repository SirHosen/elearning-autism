# ANALISIS KEBUTUHAN SISTEM & MATRIKS KETERLACAKAN (01-analisis-kebutuhan.md)
**Proyek:** Website Edukasi Interaktif Visual-Audio untuk Anak Autis  
**Lokasi Studi:** SLB B-C Wijayakusumah  
**Metodologi:** Research and Development (R&D) + Waterfall  

---

## 1. Kebutuhan Fungsional (Functional Requirements)

| ID | Deskripsi Kebutuhan | Prioritas | Aktor |
|---|---|---|---|
| **FR-01** | Halaman login terpisah untuk siswa (numpad PIN ramah anak) dan admin/guru (email & password). | Must Have | Siswa, Admin |
| **FR-02** | Halaman beranda siswa menampilkan navigasi visual utama (Belajar, Bermain, Kuis, Video, Absen). | Must Have | Siswa |
| **FR-03** | Materi pembelajaran berbasis gambar visual dan teks kata (kategori Hewan, Warna, Benda). | Must Have | Siswa |
| **FR-04** | Fitur audio pengucapan nama materi/objek (audio rekaman guru + cadangan Web Speech TTS `id-ID`). | Must Have | Siswa |
| **FR-05** | Modul video pembelajaran (pemutar lokal ramah sensory-safe tanpa autoplay, dan opsi YouTube nocookie). | Must Have | Siswa |
| **FR-06** | Permainan edukasi interaktif (Pilih Benar, Cocokkan Gambar-Kata, Seret & Tempatkan / Tap-Tap). | Must Have | Siswa |
| **FR-07** | Kuis sederhana pengenalan materi dengan pilihan jawaban berbentuk gambar dan evaluasi skor bintang. | Must Have | Siswa |
| **FR-08** | Absensi mandiri kehadiran siswa harian ("Saya Hadir") dengan proteksi idempoten. | Must Have | Siswa |
| **FR-09** | Admin dapat mengelola materi pembelajaran (tambah, lihat, ubah label/gambar/audio, hapus aman). | Must Have | Admin |
| **FR-10** | Admin dapat mengelola kategori, video edukasi, konfigurasi permainan, dan bank soal kuis. | Must Have | Admin |
| **FR-11** | Admin dapat mengelola data akun siswa (tambah akun, ubah kelas, reset PIN dengan cetak kartu, nonaktifkan). | Must Have | Admin |
| **FR-12** | Admin dapat melihat rekap kehadiran absensi, hasil kuis/game siswa, serta mengekspor laporan ke format CSV. | Must Have | Admin |
| **FR-13** | Pengaturan preferensi aksesibilitas siswa (pengatur volume, skala font, tingkat animasi, tema tenang/kontras). | Must Have | Siswa |

---

## 2. Kebutuhan Non-Fungsional (Non-Functional Requirements)

| ID | Deskripsi Kebutuhan | Kriteria Keberhasilan |
|---|---|---|
| **NFR-01** | Tampilan sederhana dan mudah dipahami (Segmentasi satu hal per layar). | Layout bersih, instruksi teks maksimal 7 kata. |
| **NFR-02** | Kombinasi warna nyaman & ramah sensori (*sensory-safe*). | Lolos WCAG 2.1 AA (rasio kontras $\ge$ 4.5:1 untuk teks normal). |
| **NFR-03** | Tombol navigasi konsisten dan mudah digunakan. | Target sentuh siswa $\ge$ 64px, target admin $\ge$ 48px, posisi tombol konsisten. |
| **NFR-04** | Ukuran gambar dan tipografi mudah dikenali. | Font Lexend khusus disleksia/keterbacaan, ukuran kata 48–80px pada viewer. |
| **NFR-05** | Kendali audio dan animasi berada penuh di tangan pengguna. | Bebas autoplay suara/video, opsi mematikan atau meminimalkan animasi. |
| **NFR-06** | Dapat diakses melalui browser komputer desktop maupun perangkat seluler/tablet. | Kompatibel pada viewport 360×640 (ponsel), 768×1024 (tablet), dan 1366×768 (desktop). |
| **NFR-07** | Tampilan responsif tanpa pergeseran horizontal. | Tidak ada horizontal scrollbar pada resolusi minimum 360px (`scrollWidth <= clientWidth`). |
| **NFR-08** | Keamanan aplikasi (Otentikasi berbasis peran, proteksi CSRF, sanitasi upload, rate limiting login). | Pencegahan IDOR via Policy, sanitasi MIME type asli (`finfo`), hashing bcrypt PIN/password. |
| **NFR-09** | Nol permintaan runtime ke host eksternal (*Zero external network dependency*). | Font, ikon SVG sprite, CSS, dan JS disajikan 100% lokal dari server lokal sekolah. |

---

## 3. Matriks Keterlacakan (Traceability Matrix)

| ID Kebutuhan | Fitur Sistem | Rute (Route) | File Kode Terkait | ID Test Case |
|---|---|---|---|---|
| **FR-01** | Autentikasi Siswa & Admin | `/masuk`, `/masuk/siswa`, `/masuk/guru`, `/keluar` | `AuthController.php`, `EnsureRole.php`, `auth/login-*.blade.php` | BB-AUTH-01 s.d. BB-AUTH-08 |
| **FR-02** | Beranda Siswa | `/beranda` | `Student/HomeController.php`, `student/home.blade.php` | BB-AUTH-01 |
| **FR-03** | Pembelajaran Visual | `/belajar`, `/belajar/{category:slug}`, `/belajar/{category:slug}/{material}` | `Student/LearnController.php`, `student/learn/*.blade.php` | BB-LRN-01, BB-LRN-02 |
| **FR-04** | Audio Pengucapan | Viewer & Game | `resources/js/core/audio-manager.js`, `SpeakerButton` | BB-LRN-03, BB-LRN-04, BB-LRN-06 |
| **FR-05** | Video Edukasi | `/video`, `/video/{video}` | `Student/VideoController.php`, `student/video/*.blade.php` | BB-VID-01 |
| **FR-06** | Permainan Edukasi | `/bermain`, `/bermain/{game}`, `/api/games/*` | `Student/GameController.php`, `GameRoundBuilder.php`, `resources/js/games/*.js` | BB-GAM-01 s.d. BB-GAM-06 |
| **FR-07** | Kuis Interaktif | `/kuis`, `/kuis/{quiz}`, `/api/quizzes/*` | `Student/QuizController.php`, `QuizBuilder.php`, `resources/js/pages/quiz.js` | BB-QUZ-01 s.d. BB-QUZ-03 |
| **FR-08** | Presensi Siswa | `/absen` | `Student/AttendanceController.php`, `AttendanceService.php` | BB-ABS-01, BB-ABS-02 |
| **FR-09** | CRUD Materi Belajar | `/admin/materi` | `Admin/MaterialController.php`, `ImageProcessor.php`, `MediaStorage.php` | BB-ADM-01 s.d. BB-ADM-06 |
| **FR-10** | CRUD Konten & Bank Soal | `/admin/kategori`, `/admin/video`, `/admin/permainan`, `/admin/kuis` | `Admin/{Category,Video,Game,Quiz}Controller.php` | BB-VID-02 s.d. 04, BB-GAM-07, BB-QUZ-04, 05 |
| **FR-11** | Manajemen Siswa & PIN | `/admin/siswa`, `/admin/siswa/{user}/reset-pin` | `Admin/StudentController.php`, `admin/students/*.blade.php` | BB-ADM-07, BB-AUTH-08 |
| **FR-12** | Rekap & Ekspor Laporan | `/admin/absensi`, `/admin/absensi/ekspor`, `/admin/hasil`, `/admin/hasil/ekspor` | `Admin/{Attendance,Report}Controller.php` | BB-ABS-03, BB-ABS-04 |
| **FR-13** | Pengaturan Aksesibilitas | `/pengaturan` | `Student/SettingController.php`, `resources/js/core/settings.js` | BB-UX-01 |
| **NFR-01–07**| Sensory-Safe Design & Responsif | Seluruh antarmuka | `resources/css/{tokens,themes,base,components}.css`, `check-contrast.mjs` | BB-UX-02, BB-UX-03 |
| **NFR-08** | Keamanan & Otorisasi | Global Middleware | `SecurityHeaders.php`, `EnsureRole.php`, Policies | BB-AUTH-05, BB-ADM-05, 06 |
| **NFR-09** | Isolasi Runtime Lokal | Global Asset | Vite build, self-hosted fonts `@fontsource/lexend` | BB-UX-04 |
