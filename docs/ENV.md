# LINGKUNGAN PENGEMBANGAN (ENV.md)
**Proyek:** Website Edukasi Interaktif Visual-Audio untuk Anak Autis  
**Lokasi Studi:** SLB B-C Wijayakusumah  
**Tanggal Verifikasi:** 24 November 2026

Dokumen ini mencatat hasil verifikasi lingkungan eksekusi aktual pada server/mesin pengembangan.

---

## 1. Perangkat Lunak Inti & Tooling

| Perangkat Lunak | Versi Terpasang | Status | Keterangan |
|---|---|---|---|
| **PHP** | 8.5.12 (cli) | PASS | CLI NTS dengan Zend OPcache |
| **Composer** | 2.10.3 | PASS | Manajemen dependensi PHP |
| **Node.js** | v26.13.1 | PASS | Runtime JavaScript & bundling Vite |
| **npm** | 11.20.0 | PASS | Paket manajer JavaScript |
| **Git** | 2.56.0 | PASS | Version control system |
| **MySQL / MariaDB CLI** | /usr/bin/mariadb (15.1) | PASS | Tersedia client library |
| **SQLite3 CLI** | 3.46.1 | PASS | Terpasang, siap untuk DB lokal/in-memory test |

---

## 2. Ekstensi PHP

Verifikasi modul ekstensi PHP yang dipersyaratkan:

| Ekstensi | Status | Keterangan |
|---|---|---|
| `gd` | PASS | Pengolahan gambar materi (resize, konversi WebP) |
| `fileinfo` | PASS | Deteksi MIME asli untuk upload aman |
| `mbstring` | PASS | Manipulasi string multibyte |
| `pdo_mysql` | PASS | Driver relasional MySQL/MariaDB |
| `pdo_sqlite` | PASS | Driver SQLite untuk pengujian & dev lokal |
| `intl` | PASS | Lokalisasi & format tanggal/angka Indonesia |
| `openssl` | PASS | Keamanan & enkripsi |
| `tokenizer` | PASS | Parsing kode PHP bawaan Laravel |
| `xml` / `ctype` | PASS | Modul standar Laravel |

---

## 3. Koneksi Basis Data

- **Lokal Dev & Testing:** SQLite (`database/database.sqlite` dan `:memory:`) aktif dan terintegrasi penuh.
- **Produksi / Server Sekolah:** MySQL 8 / MariaDB 10.6+ didukung via `pdo_mysql` (konfigurasi di `.env` untuk instalasi pada Laragon/XAMPP/VPS sesuai panduan `DEPLOYMENT.md`).

---

## 4. Konfigurasi Lingkungan Awal

- Direktori proyek: `/home/hosea/Projects/001/SLB_BC_Wijayakusumah/autism-elearning`
- Zona Waktu Aplikasi: `Asia/Jakarta`
- Bahasa Default: `id` (Bahasa Indonesia)
- Mode Eksekusi: `AUTO_LANJUT`
