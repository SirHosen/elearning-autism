# PANDUAN DESIGN SYSTEM RAMAH SENSORI (05-design-system.md)
**Proyek:** Website Edukasi Interaktif Visual-Audio untuk Anak Autis  
**Lokasi Studi:** SLB B-C Wijayakusumah  

---

## 1. Filosofi & Prinsip Sensory-Safe

Anak dengan *Autism Spectrum Disorder* (ASD) sering mengalami hipersensitivitas sensorik terhadap warna mencolok (neon/flashing), suara mendadak/keras, dan tata letak yang padat/berubah secara tak terduga. Sistem desain EduAutis dibangun atas 7 pilar:

1. **Segmentasi Informasi (One Thing at a Time)**: Satu objek materi, satu pertanyaan permainan, atau satu tugas per tampilan.
2. **Konsistensi Navigasi**: Tombol *Beranda*, *Kembali*, *Dengar*, dan *Berikutnya* berada pada koordinat dan bentuk yang identik di semua halaman siswa.
3. **Warna Menenangkan (Calm Palette)**: Menghindari warna jenuh tinggi. Warna merah ditiadakan di sisi siswa; pesan coba lagi menggunakan warna hangat kuning lembut (`--hint-bg` dan `--hint-line`).
4. **Ketiadaan Animasi Kejutan**: Tidak ada auto-scroll, efek goyang (*shake*), kilatan (*flashing*), atau hamburan *confetti*.
5. **Dua Saluran Sensorik (Dual Channel)**: Setiap suara selalu disertai representasi visual (teks/ikon), dan setiap status warna memiliki padanan ikon dan teks.
6. **Kendali Penuh di Tangan Pengguna**: Tidak ada *autoplay* audio atau video. Suara hanya berbunyi setelah interaksi sadar pengguna.
7. **Ukuran Area Sentuh Luas**: Tombol siswa memiliki tinggi minimum 64px dengan margin aman 12px guna memfasilitasi keterbatasan motorik halus.

---

## 2. Token Desain (Design Tokens)

Didefinisikan dalam `resources/css/tokens.css` dan `resources/css/themes.css`:

### 2.1 Tema "Tenang" (Calm Theme - Default)
- `--bg`: `#FBF8F3` (Latar krem lembut ramah mata)
- `--surface`: `#FFFFFF` (Permukaan kartu bersih)
- `--surface-alt`: `#F3EEE5` (Aksen permukaan sekunder)
- `--line`: `#D9D2C5` (Garis batas kontras terukur)
- `--ink`: `#1F2933` (Teks utama pekat, rasio > 10:1 terhadap latar)
- `--ink-soft`: `#4B5563` (Teks keterangan sekunder)
- `--primary`: `#2F6F9F` (Biru tenang, fokus terarah)
- `--on-primary`: `#FFFFFF`
- `--success`: `#2E7D5B` (Hijau daun alami untuk umpan balik benar)
- `--success-bg`: `#E3F2EA`
- `--hint-bg`: `#FFF4DB` (Kuning pastel ramah untuk coba lagi)
- `--hint-line`: `#C99A2E`
- `--focus`: `#1D4ED8` (Indikator fokus keyboard tebal 3px)

### 2.2 Tema "Kontras Tinggi" (High Contrast Theme)
Diterapkan via `<html data-theme="contrast">` bagi peserta didik dengan gangguan penglihatan tambahan (low vision):
- Latar hitam pekat (`#000000`), teks putih bersih (`#FFFFFF`), warna fokus kuning terang (`#FFD400`), rasio kontras $\ge$ 7:1 (AAA).

---

## 3. Tipografi & Skala Keterbacaan

- **Font Utama**: **Lexend** (self-hosted via woff2). Lexend dirancang khusus oleh Bonnie Shaver-Troup untuk mempercepat pembacaan dan mengurangi kesalahan fonologis pada anak berkebutuhan khusus.
- **Skala Font Siswa**:
  - Teks umum: `20px`
  - Tombol aksi: `24px`
  - Judul section: `32px`
  - Kata materi viewer: `48px – 80px`
- **Preferensi Pengguna**: Mendukung 3 tingkatan via atribut `data-font-scale`: `normal` (1.0x), `large` (1.15x), `xlarge` (1.3x).

---

## 4. Spesifikasi Komponen Siswa

| Komponen | Spesifikasi & Aksesibilitas |
|---|---|
| **Button** | Min tinggi 64px, padding horizontal 32px, radius 20px, font 24px + ikon SVG. State: focus ring 3px, active translateY 1px. |
| **Tile Navigasi** | Kartu 180×180px min, ikon 96px, teks 28px, background `--tint-*`, border 2px `--line`. |
| **SpeakerButton** | Ikon speaker + teks "Dengar". Saat memutar teks berganti menjadi "Sedang berbunyi" (`aria-pressed="true"`). |
| **ImageChoice** | Kartu gambar pilihan 180×240px, border 3px. Pilihan salah menjadi redup (`opacity: 0.45`), bukan disilang merah. |
| **FeedbackBar** | Bilah status tetap di atas pilihan jawaban (`role="status"`, `aria-live="polite"`). |
| **Numpad PIN** | Grid 3×4, tombol 72px, indikator digit terisi (● dan ○), tombol hapus & masuk terpisah. |
