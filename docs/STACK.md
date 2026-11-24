# STACK TEKNOLOGI & DAFTAR DEPENDENSI (STACK.md)
**Proyek:** Website Edukasi Interaktif Visual-Audio untuk Anak Autis  
**Lokasi Studi:** SLB B-C Wijayakusumah  
**Terakhir Diperbarui:** 24 November 2026

Dokumen ini mencatat seluruh lapisan teknologi yang diverifikasi langsung dari lingkungan eksekusi, serta daftar pustaka pihak ketiga dengan lisensi dan alasannya.

---

## 1. Arsitektur Inti

| Lapisan | Pilihan | Versi Nyata Terverifikasi | Alasan Pemilihan |
|---|---|---|---|
| **Bahasa Backend** | PHP | 8.5.12 | Kompatibilitas tinggi dengan server lokal sekolah (Laragon/XAMPP) & VPS. |
| **Framework** | Laravel Framework | v13.43.0 | Struktur MVC konsisten, validasi FormRequest, Session Auth, dan migrasi terstruktur. |
| **Basis Data** | SQLite (Dev/Test) / MySQL 8 (Prod) | SQLite 3.46.1 / MySQL 8.0+ | SQLite instan tanpa server terpisah saat pengujian; MySQL untuk server sekolah. |
| **Template Engine** | Laravel Blade | Bawaan Laravel | Server-rendered HTML ringan, rendering cepat pada perangkat tablet sekolah. |
| **Gaya Visual (CSS)** | CSS Murni + CSS Design Tokens | W3C Standard CSS | Kontrol penuh atas prinsip *sensory-safe* tanpa bloated framework, mendukung tema kontras & skala font langsung. |
| **Frontend Scripting** | Vanilla ES Modules | ES2024+ Native | Tanpa framework runtime berat (React/Vue/jQuery), *progressive enhancement*. |
| **Aset Tipografi** | Lexend Font | Self-hosted (woff2) | Font yang dirancang khusus untuk kemudahan membaca & keterbacaan anak, tanpa CDN luar. |
| **Sistem Audio** | HTML5 Audio + Web Speech API | Native Browser | Audio rekaman guru sebagai prioritas; Text-to-Speech bahasa Indonesia (`id-ID`) sebagai cadangan otomatis. |
| **Bundler & Build Tool**| Vite | 8.3.8 | Build aset statis lokal & cache-busting. |

---

## 2. Dependensi PHP (Composer)

Diverifikasi dari `composer.json` dan `composer.lock`:

| Paket | Versi | Alasan | Lisensi |
|---|---|---|---|
| `laravel/framework` | 13.43.0 | Framework utama MVC, routing, Eloquent ORM | MIT |
| `laravel/tinker` | 3.0.1 | Console REPL untuk debugging Laravel | MIT |
| `laravel-lang/lang` | 15.x | Terjemahan pesan validasi ke Bahasa Indonesia | MIT |
| `pestphp/pest` | 3.x / 4.x | Framework pengujian Black Box & Unit test | MIT |
| `laravel/pint` | 1.27.1 | Linter & formatter kode standar PSR-12 / Laravel | MIT |
| `mockery/mockery` | 1.6.12 | Mocking objek pada unit testing | BSD-3-Clause |
| `fakerphp/faker` | 1.24.1 | Pembangkit data uji pada seeder & factory | MIT |

---

## 3. Dependensi Frontend (NPM)

Diverifikasi dari `package.json` dan `package-lock.json`:

| Paket | Versi | Alasan | Lisensi |
|---|---|---|---|
| `vite` | 8.3.8 | Tool bundling aset CSS dan JS | MIT |
| `laravel-vite-plugin` | 3.2.1 | Integrasi Blade dengan Vite | MIT |
| `@fontsource/lexend` | 5.x | Self-hosting font Lexend lokal tanpa CDN eksternal | MIT |
| `@playwright/test` | 1.50+ | Pengujian E2E, responsif tablet/mobile, sensory-safe check | Apache-2.0 |
| `@axe-core/playwright` | 4.10+ | Audit otomatis aksesibilitas WCAG AA | MPL-2.0 |

---

## 4. Batasan & Larangan (Strict Policy)

Sesuai aturan keras pada Master Prompt:
1. **Dilarang CDN runtime**: Tidak boleh memuat skrip/font/CSS dari `cdn.jsdelivr.net`, `fonts.googleapis.com`, `cdnjs`, dsb.
2. **Dilarang framework JS berat**: Tidak menggunakan React, Vue, jQuery, atau Angular.
3. **Dilarang CSS library instan**: Tidak menggunakan Bootstrap atau Tailwind runtime di halaman siswa demi memastikan kepatuhan 100% pada token *sensory-safe*.
4. **Dilarang animasi berlebih**: Tidak menggunakan GSAP, Lottie, atau library confetti.
