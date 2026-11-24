# Instrumen Pengujian Kegunaan: System Usability Scale (SUS)
## Platform Edukasi Visual-Audio Ramah Autis
### SLB B-C Wijayakusumah

Dokumen ini memuat instrumen pengujian kegunaan (*usability testing*) berbasis standar internasional **System Usability Scale (SUS)** yang telah diadaptasi untuk mengevaluasi antarmuka guru dan pendamping siswa autis di SLB B-C Wijayakusumah.

> [!IMPORTANT]
> **Pemberitahuan Kepatuhan Akademis:**
> Sesuai etika penelitian skripsi, tabel skor responden pada dokumen ini **dikosongkan** sebagai instrumen siap pakai untuk diisi data nyata hasil kuesioner lapangan bersama guru SLB B-C Wijayakusumah. Data empiris dilarang dimanipulasi atau dikarang.

---

### 1. Kuisioner 10 Pernyataan SUS (Bahasa Indonesia)

Responden memberikan penilaian terhadap 10 butir pernyataan berikut menggunakan skala Likert 1 sampai 5:
- **1:** Sangat Tidak Setuju (STS)
- **2:** Tidak Setuju (TS)
- **3:** Netral / Ragu-ragu (N)
- **4:** Setuju (S)
- **5:** Sangat Setuju (SS)

| No | Butir Pernyataan SUS | Tipe Butir |
| :---: | :--- | :---: |
| **Q1** | Saya merasa ingin sering menggunakan website EduAutis ini untuk mendampingi pembelajaran anak autis. | Positif (Ganjil) |
| **Q2** | Saya merasa website ini rumit dan sulit dipahami oleh guru maupun anak. | Negatif (Genap) |
| **Q3** | Saya merasa website ini mudah digunakan, baik modul belajar, video, maupun permainannya. | Positif (Ganjil) |
| **Q4** | Saya rasa saya memerlukan bantuan tenaga ahli teknis untuk dapat mengoperasikan website ini. | Negatif (Genap) |
| **Q5** | Saya merasa berbagai fitur dalam website ini (materi, kuis, game, absensi) terintegrasi dengan sangat baik. | Positif (Ganjil) |
| **Q6** | Saya merasa terdapat terlalu banyak ketidakkonsistenan pada tata letak atau tombol dalam sistem ini. | Negatif (Genap) |
| **Q7** | Saya yakin sebagian besar guru dan orang tua dapat belajar menggunakan website ini dengan sangat cepat. | Positif (Ganjil) |
| **Q8** | Saya merasa website ini sangat membingungkan saat digunakan. | Negatif (Genap) |
| **Q9** | Saya merasa sangat percaya diri saat mengoperasikan website ini di dalam kelas. | Positif (Ganjil) |
| **Q10**| Saya perlu mempelajari banyak hal terlebih dahulu sebelum dapat mengoperasikan website ini secara lancar. | Negatif (Genap) |

---

### 2. Rumus dan Algoritma Perhitungan Skor SUS

Skor akhir SUS dihitung dengan rentang nilai **0 hingga 100** menggunakan algoritma standar Brooke (1996):

1. **Untuk Pertanyaan Bernomor Ganjil (Q1, Q3, Q5, Q7, Q9):**
   $$S_i = R_i - 1$$
   *(di mana $R_i$ adalah skor respons responden dari 1 sampai 5, sehingga $S_i$ bernilai 0 sampai 4).*

2. **Untuk Pertanyaan Bernomor Genap (Q2, Q4, Q6, Q8, Q10):**
   $$S_i = 5 - R_i$$
   *(di mana $R_i$ adalah skor respons responden dari 1 sampai 5, sehingga $S_i$ bernilai 0 sampai 4).*

3. **Total Skor Akhir per Responden:**
   $$\text{Skor SUS} = \left( \sum_{i=1}^{10} S_i \right) \times 2.5$$

4. **Rata-rata Skor SUS Keseluruhan:**
   $$\bar{X}_{\text{SUS}} = \frac{1}{N} \sum_{k=1}^{N} \text{Skor SUS}_k$$

---

### 3. Skala Interpretasi Skor SUS (Bangor et al., 2008)

Hasil rata-rata skor SUS diinterpretasikan ke dalam tiga tingkatan evaluasi:

```
 Skor SUS        Grade Scale      Adjective Rating         Acceptability
┌───────────────┬────────────────┬────────────────────────┬───────────────┐
│ >= 80.3       │ A              │ Excellent (Luar Biasa) │ Acceptable    │
│ 68.0 - 80.2   │ B              │ Good (Bagus)           │ Acceptable    │
│ 51.0 - 67.9   │ C / D          │ OK (Cukup)             │ Marginal      │
│ < 51.0        │ F              │ Poor (Kurang/Buruk)    │ Not Acceptable│
└───────────────┴────────────────┴────────────────────────┴───────────────┘
```
*Catatan: Rata-rata benchmark kegunaan sistem industri secara global adalah **68.0**.*

---

### 4. Lembar Pengambilan Data Empiris Responden (Template Siap Pakai)

Tabel berikut disiapkan untuk diisi oleh peneliti pada saat sesi pengujian lapangan bersama para guru pendamping di SLB B-C Wijayakusumah:

| No | Kode Responden | Inisial / Jabatan | Q1 | Q2 | Q3 | Q4 | Q5 | Q6 | Q7 | Q8 | Q9 | Q10 | Jumlah (0-40) | Skor SUS (0-100) |
| :-: | :--- | :--- | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :---: | :---: |
| 1 | R-01 | [Guru Kelas Autis 1] | | | | | | | | | | | | |
| 2 | R-02 | [Guru Kelas Autis 2] | | | | | | | | | | | | |
| 3 | R-03 | [Guru Terapi Wicara] | | | | | | | | | | | | |
| 4 | R-04 | [Guru Keterampilan] | | | | | | | | | | | | |
| 5 | R-05 | [Kepala Sekolah / Kurikulum] | | | | | | | | | | | | |
| 6 | R-06 | [Orang Tua Siswa A] | | | | | | | | | | | | |
| 7 | R-07 | [Orang Tua Siswa B] | | | | | | | | | | | | |
| 8 | R-08 | [Orang Tua Siswa C] | | | | | | | | | | | | |
| **Rata-rata** | | | | | | | | | | | | | | **[Diisi di Lapangan]** |

---

### 5. Panduan Pelaksanaan Sesi Pengujian Lapangan

1. **Persiapan Perangkat:** Siapkan laptop/tablet sekolah yang terhubung ke server lokal sekolah (Laragon/XAMPP) atau VPS.
2. **Skenario Tugas (Task Scenario):**
   - Guru diminta login menggunakan akun pendamping.
   - Guru diminta menambahkan 1 kartu materi dan membuat kuis otomatis 5 soal.
   - Guru mendampingi siswa login dengan PIN bergambar dan menyelesaikan 1 modul belajar.
   - Siswa mencoba permainan 'Pilih yang Benar' dan melakukan absensi mandiri.
   - Guru memeriksa rekap kehadiran dan mencetak laporan hasil belajar.
3. **Pengisian Kuesioner:** Berikan lembar kuesioner fisik atau Google Form kepada responden tanpa intervensi opini dari peneliti.
4. **Kalkulasi:** Masukkan skor mentah ke dalam tabel di atas dan hitung rata-rata skor SUS sesuai rumus resmi.
