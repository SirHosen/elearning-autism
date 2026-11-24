# RANCANGAN WIREFRAME & SITEMAP (04-wireframe.md)
**Proyek:** Website Edukasi Interaktif Visual-Audio untuk Anak Autis  
**Lokasi Studi:** SLB B-C Wijayakusumah  

---

## 1. Peta Situs (Sitemap & Navigasi)

```
[ Beranda Publik / ]
   ├── [ Pilih Masuk /masuk ]
   │      ├── [ Masuk Siswa /masuk/siswa ] (Numpad PIN Ramah Anak)
   │      └── [ Masuk Guru /masuk/guru ] (Email & Password)
   │
   ├── [ Area Siswa (Role: Siswa) ]
   │      ├── /beranda (5 Kartu Navigasi Utama: Belajar, Bermain, Kuis, Video, Absen)
   │      ├── /belajar (Pilih Kategori: Hewan, Warna, Benda)
   │      │      └── /belajar/{category}/{material} (Viewer Segmentasi: Gambar, Audio, StepNav)
   │      ├── /bermain (Pilih Game Edukasi)
   │      │      └── /bermain/{game} (Panggung Permainan Interaktif + Bintang)
   │      ├── /kuis (Pilih Kuis)
   │      │      └── /kuis/{quiz} (Kuis Gambar, Dengar, Umpan Balik)
   │      ├── /video (Katalog Video)
   │      │      └── /video/{video} (Pemutar Video Ramah Sensori)
   │      ├── /absen (Tombol Mandiri "Saya Hadir" + Riwayat Kehadiran)
   │      └── /pengaturan (Volume, Animasi, Ukuran Huruf, Tema Kontras)
   │
   └── [ Area Guru / Admin (Role: Admin) ]
          ├── /admin (Dashboard Statistik: Kehadiran Hari Ini, Aktivitas Kuis)
          ├── /admin/kategori (CRUD Kategori Materi & Warna)
          ├── /admin/materi (CRUD Materi, Upload Gambar WebP, Audio Guru)
          ├── /admin/video (CRUD Video Edukasi & Validasi YouTube)
          ├── /admin/permainan (Konfigurasi Tipe Game & Coba Mainkan)
          ├── /admin/kuis (Bank Soal Kuis & Generator Kuis Otomatis)
          ├── /admin/siswa (Manajemen Akun Siswa & Cetak Kartu PIN)
          ├── /admin/absensi (Rekap Absensi, Koreksi Manual, Ekspor CSV)
          ├── /admin/hasil (Rekap Skor Kuis/Game & Ekspor CSV)
          └── /admin/profil (Pengaturan Akun & Sandi Guru)
```

---

## 2. Tata Letak Wireframe Detail

### 2.1 Halaman Masuk Siswa (`/masuk/siswa`)
Navigasi login dengan tombol PIN digital berukuran ekstra besar (min 72px) agar mudah diakses oleh anak dengan gangguan motorik halus.
```
+-------------------------------------------------------+
|  [Logo EduAutis]  SLB B-C Wijayakusumah               |
|                                                       |
|              Halo! Siapa namamu?                      |
|  +-------------------------------------------------+  |
|  |  Nama Pengguna (mis. budi)                      |  |
|  +-------------------------------------------------+  |
|                                                       |
|  Masukkan PIN Kamu:                                   |
|              [ ● ]   [ ● ]   [ ● ]   [ ○ ]            |
|                                                       |
|       +-----------+  +-----------+  +-----------+     |
|       |     1     |  |     2     |  |     3     |     |
|       +-----------+  +-----------+  +-----------+     |
|       |     4     |  |     5     |  |     6     |     |
|       +-----------+  +-----------+  +-----------+     |
|       |     7     |  |     8     |  |     9     |     |
|       +-----------+  +-----------+  +-----------+     |
|       |  < Hapus  |  |     0     |  |  Masuk >  |     |
|       +-----------+  +-----------+  +-----------+     |
|                                                       |
|  [ Saya Guru / Pengelola -> ]                         |
+-------------------------------------------------------+
```

### 2.2 Beranda Siswa (`/beranda`)
Header konsisten di seluruh halaman siswa (Tinggi 72px): Kiri tombol `Beranda`, tengah sapaan hangat, kanan tombol `Pengaturan` & `Keluar`.
```
+-----------------------------------------------------------------------+
| [⌂ Beranda]               Halo, Budi!            [⚙ Atur]  [↪ Keluar] |
+-----------------------------------------------------------------------+
|                                                                       |
|  +--------------------+  +--------------------+  +-----------------+  |
|  |       [ 🐾 ]       |  |       [ 🎮 ]       |  |      [ ⭐ ]     |  |
|  |      BELAJAR       |  |      BERMAIN       |  |      KUIS       |  |
|  | (Kenali Hewan/Dll) |  | (Tebak Nama Objek) |  | (Jawab Soal)    |  |
|  +--------------------+  +--------------------+  +-----------------+  |
|                                                                       |
|  +--------------------+  +--------------------+                       |
|  |       [ 🎬 ]       |  |       [ 📅 ]       |   (Tile Absen punya   |
|  |       VIDEO        |  |       ABSEN        |    lencana centang ✓  |
|  |  (Nonton Belajar)  |  | [✓ Sudah Hadir]    |    jika sudah hadir)  |
|  +--------------------+  +--------------------+                       |
+-----------------------------------------------------------------------+
```

### 2.3 Viewer Materi (`/belajar/{kategori}/{id}`)
Mengedepankan prinsip *segmentasi*: satu objek per layar untuk mencegah beban kognitif berlebih (*cognitive overload*).
```
+-----------------------------------------------------------------------+
| [← Kembali]                    HEWAN                    3 dari 10     |
+-----------------------------------------------------------------------+
|                                                                       |
|               +---------------------------------------+               |
|               |                                       |               |
|               |              GAMBAR BESAR             |               |
|               |          (Tap gambar = Dengar)        |               |
|               |                                       |               |
|               +---------------------------------------+               |
|                                                                       |
|                                KUCING                                 |
|                                                                       |
|                      [ 🔊 Dengarkan Suara ]                           |
|                      "Ini adalah kucing lucu."                        |
|                                                                       |
|      [ < Sebelumnya ]                            [ Berikutnya > ]     |
|                     ○   ○   ●   ○   ○   ○   ○   ○                     |
+-----------------------------------------------------------------------+
```

### 2.4 Panggung Permainan & Kuis Bergambar
```
+-----------------------------------------------------------------------+
| [✕ Keluar]                                               Soal 2 dari 5|
+-----------------------------------------------------------------------+
|                                                                       |
|                     Mana yang bernama "Kucing"?                       |
|                      [ 🔊 Dengarkan Kata ]                            |
|                                                                       |
|  +------------------+  +------------------+  +---------------------+  |
|  |     GAMBAR 1     |  |     GAMBAR 2     |  |      GAMBAR 3       |  |
|  |     (Anjing)     |  |     (Kucing)     |  |       (Sapi)        |  |
|  +------------------+  +------------------+  +---------------------+  |
|                                                                       |
|  +-----------------------------------------------------------------+  |
|  | [✓ Ikon] Benar! Hebat sekali!                  [ Lanjutkan > ]  |  |
|  +-----------------------------------------------------------------+  |
+-----------------------------------------------------------------------+
```
