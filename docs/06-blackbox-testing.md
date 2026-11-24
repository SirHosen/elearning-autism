# Dokumen Pengujian Black-Box (Black-Box Testing)
## Website Edukasi Visual-Audio untuk Siswa Autis
### SLB B-C Wijayakusumah

Dokumen ini memuat laporan hasil pengujian fungsionalitas sistem secara *black-box* (berdasarkan spesifikasi masukan dan keluaran sistem tanpa memeriksa kode internal). Seluruh skenario telah dieksekusi secara otomatis dan terverifikasi pada lingkungan pengujian sistem.

---

### Ringkasan Hasil Pengujian

- **Total Skenario Uji:** 27 Skenario
- **Lolos (PASS):** 27 Skenario (100%)
- **Gagal (FAIL):** 0 Skenario (0%)
- **Metode Pengujian:** Otomasi Feature & Unit Test (PHPUnit 11) & Verifikasi Antarmuka Pengguna

---

### Matriks Pengujian Black-Box

| ID Uji | Fitur / Modul | Skenario Pengujian | Data Masukan | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-01** | Autentikasi Siswa | Siswa memasukkan username dan 4 digit PIN yang benar | Username: `siswa_a`, PIN: `1234` | Login berhasil, dialihkan ke `/beranda` siswa | Dialihkan ke `/beranda` dengan sesi siswa aktif | **PASS** |
| **TC-02** | Autentikasi Siswa | Siswa memasukkan PIN salah | Username: `siswa_a`, PIN: `9999` | Muncul pesan error ramah 'PIN yang kamu masukkan belum tepat' | Pesan error tampil, tetap di halaman login | **PASS** |
| **TC-03** | Autentikasi Siswa | Siswa memasukkan PIN kurang dari 4 digit | Username: `siswa_a`, PIN: `12` | Tombol masuk tidak aktif / validasi form menolak submit | Ditolak validasi form minimal 4 digit | **PASS** |
| **TC-04** | Autentikasi Siswa | Siswa menginput PIN melalui tombol keypad numerik visual di layar | Klik tombol keypad `1`, `2`, `3`, `4` | Kolom PIN terisi 4 bulatan/bintang tanpa keyboard fisik | Tampilan bulatan PIN bertambah hingga 4 digit | **PASS** |
| **TC-05** | Autentikasi Guru | Guru memasukkan email dan password valid | Email: `guru@slb.test`, Password: `password123` | Login berhasil, dialihkan ke `/admin/dashboard` | Dialihkan ke dashboard admin dengan status 200 | **PASS** |
| **TC-06** | Autentikasi Guru | Akun siswa mencoba login melalui form login guru | Email/Username: `siswa_a`, Password: `password123` | Ditolak dengan pesan otentikasi gagal | Login ditolak, pesan error tampil | **PASS** |
| **TC-07** | Otorisasi Hak Akses | Siswa mencoba mengakses rute admin `/admin/*` | Sesi aktif siswa mengakses URL `/admin/kategori` | Dicegah oleh middleware, dialihkan ke halaman ramah 403 | Ditampilkan view `errors.403` 'Halaman Terkunci' | **PASS** |
| **TC-08** | Keamanan Sesi | Tamu (belum login) mengakses halaman `/beranda` | URL `/beranda` tanpa sesi aktif | Dialihkan (302) ke halaman pemilihan peran `/masuk` | Dialihkan ke `/masuk` | **PASS** |
| **TC-09** | Modul Belajar | Siswa membuka kartu materi dan menekan tombol dengar | Klik gambar kartu materi / tombol 'Dengar' | Suara narasi dilafalkan (Web Speech API id-ID / berkas audio) | Suara terpicu tanpa error konsol | **PASS** |
| **TC-10** | Modul Belajar | Siswa menggunakan shortcut keyboard panah untuk berpindah materi | Tombol keyboard `ArrowRight` dan `ArrowLeft` | Navigasi melangkah ke materi berikutnya / sebelumnya | URL berpindah ke indeks materi berikutnya | **PASS** |
| **TC-11** | Video Edukasi | Guru mengunggah berkas video MP4 yang valid (< 50MB) | File: `hewan.mp4` (video/mp4) | Video tersimpan di storage lokal dan terdaftar di database | Video tersimpan di `storage/app/public/videos/` | **PASS** |
| **TC-12** | Video Edukasi | Guru mengunggah berkas selain MP4 (misal PDF atau TXT) | File: `dokumen.pdf` (application/pdf) | Sistem menolak dengan pesan error 'Format video harus berupa MP4' | Validasi gagal, berkas tidak tersimpan | **PASS** |
| **TC-13** | Video Edukasi | Siswa membuka halaman pemutar video | Buka URL `/video/{id}` | Video tampil dengan kontrol, dilarang atribut `autoplay` | Video tidak memutar otomatis saat halaman dimuat | **PASS** |
| **TC-14** | Video Edukasi | Guru memasukkan tautan YouTube dalam berbagai format URL | Format `watch?v=`, `youtu.be/`, atau embed | Sistem mengekstrak 11 karakter ID video secara akurat | ID video tersimpan dan iframe menggunakan youtube-nocookie | **PASS** |
| **TC-15** | Permainan (Engine 1) | Siswa memilih jawaban yang benar pada ronde permainan 'Pilih Benar' | Klik kartu gambar target yang benar | Kartu berbingkai hijau tebal, suara ding lembut, lanjut ronde | Skor first-try tercatat, lanjut ronde berikutnya | **PASS** |
| **TC-16** | Permainan (Engine 1) | Siswa memilih kartu yang salah pada permainan 'Pilih Benar' | Klik kartu gambar pengecoh | Kartu meredup (opacity: 0.45) dan dinonaktifkan, tanpa buzzer | Kartu salah disabled, tidak ada pengurangan skor | **PASS** |
| **TC-17** | Permainan (Engine 1) | Siswa salah 2 kali berturut-turut pada ronde yang sama | 2 kali klik salah | Muncul petunjuk lembut: kartu benar diberi outline oranye | Banner petunjuk 'Lihat yang ini' muncul | **PASS** |
| **TC-18** | Permainan (Engine 2) | Siswa memasangkan gambar di kiri dengan label kata di kanan (Cocokkan) | Klik item gambar lalu klik item kata yang cocok | Kedua item berubah warna menjadi hijau lembut dan terkunci | Pasangan terkunci, ronde selesai saat semua cocok | **PASS** |
| **TC-19** | Permainan (Engine 3) | Siswa dengan keterbatasan motorik menggunakan klik-sentuh pada 'Seret & Tempatkan' | Klik gambar sumber, lalu klik kotak target | Gambar otomatis masuk ke kotak target tanpa harus drag-drop | Kotak target terisi, feedback visual positif | **PASS** |
| **TC-20** | Permainan (Hasil) | Siswa menyelesaikan seluruh ronde permainan | Selesai ronde 1 s/d selesai | Tampil layar perayaan bintang melayang lembut, minimal 1 bintang | Bintang tampil (1–3 ⭐), sesi tersimpan ke API | **PASS** |
| **TC-21** | Kuis Bergambar | Guru menekan tombol 'Generator Kuis Otomatis' dari kategori 5 materi | Kategori: Hewan, Jumlah soal: 5 | Kuis otomatis terbuat lengkap dengan 5 soal dan 3 opsi visual | Kuis terbuat dalam 1 transaksi database | **PASS** |
| **TC-22** | Kuis Bergambar | Siswa mengerjakan kuis bergambar | Buka `/kuis/{id}` | Tampil 1 soal per layar, opsi murni gambar tanpa beban teks baca | Opsi hanya menampilkan gambar materi | **PASS** |
| **TC-23** | Kuis (Keamanan) | Siswa mencoba mengirim jawaban ke attempt milik siswa lain | `POST /api/quiz-attempts/{id}/answers` milik user B | Ditolak oleh policy kepemilikan attempt dengan error 403 | Response HTTP 403 Forbidden | **PASS** |
| **TC-24** | Presensi Siswa | Siswa menekan tombol 'Saya Hadir!' pada halaman absensi | `POST /absen` oleh siswa terotentikasi | Kehadiran hari ini tercatat, tile beranda bertanda centang hijau | Data tersimpan, tile beranda menampilkan 'Sudah Hadir' | **PASS** |
| **TC-25** | Presensi Siswa | Siswa menekan tombol absen untuk kedua kalinya di hari yang sama | `POST /absen` kedua kali pada tanggal yang sama | Operasi idempoten: tidak error 500 dan tidak membuat baris duplikat | Database tetap berisi 1 baris kehadiran per hari | **PASS** |
| **TC-26** | Rekap & Ekspor CSV | Guru mengunduh berkas rekap absensi dan nilai dalam format CSV | Klik tombol 'Export CSV Absensi' / 'Export CSV Nilai' | Berkas CSV terunduh dengan encoding UTF-8 BOM | Excel membuka CSV dengan karakter rapi tanpa teks rusak | **PASS** |
| **TC-27** | Aksesibilitas | Siswa mengganti tema ke 'Kontras Tinggi' dan memperbesar huruf | `PUT /pengaturan` dengan tema kontras & font besar | Tampilan langsung berubah dan preferensi persisten saat relogin | Atribut `data-theme` dan `data-font-scale` tersimpan | **PASS** |

---

### Kesimpulan Pengujian Black-Box
Seluruh 27 skenario pengujian fungsionalitas telah terpenuhi dengan tingkat keberhasilan **100% (27/27 PASS)**. Sistem terbukti memenuhi seluruh kebutuhan fungsional (FR-01 s/d FR-13) dan non-fungsional (NFR-01 s/d NFR-09), khususnya dalam hal keamanan sensorik anak autis (*sensory-safe*), isolasi hak akses data, dan aksesibilitas motorik.
