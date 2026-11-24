# Panduan Pemeliharaan Sistem (Maintenance Playbook)
## Platform Edukasi Autis SLB B-C Wijayakusumah

Dokumen ini memuat prosedur operasi standar (SOP) bagi administrator dan tim IT sekolah dalam merawat, mencadangkan data (*backup*), serta mengatasi permasalahan umum (*troubleshooting*).

---

### 1. Prosedur Pencadangan Rutin (Backup)

Sangat penting untuk mencadangkan database dan berkas materi visual/audio anak secara berkala:

#### Skrip Backup Otomatis
Buat berkas skrip di `/usr/local/bin/backup-autis.sh`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/slb-autis"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
mkdir -p "$BACKUP_DIR"

# 1. Backup Database SQLite / MySQL
if [ -f "/var/www/autism-elearning/database/database.sqlite" ]; then
    cp /var/www/autism-elearning/database/database.sqlite "$BACKUP_DIR/db_$TIMESTAMP.sqlite"
fi

# 2. Backup Berkas Media (Materi, Video, Audio)
tar -czf "$BACKUP_DIR/media_$TIMESTAMP.tar.gz" -C /var/www/autism-elearning/storage/app/public .

# Hapus backup yang lebih tua dari 30 hari
find "$BACKUP_DIR" -type f -mtime +30 -delete
```
Beri izin eksekusi dan jadwalkan cron setiap hari pukul 02:00 pagi:
```bash
sudo chmod +x /usr/local/bin/backup-autis.sh
# crontab -e
0 2 * * * /usr/local/bin/backup-autis.sh > /dev/null 2>&1
```

---

### 2. Rotasi Log & Pembersihan Sesi Kadaluarsa

Aplikasi telah dikonfigurasi untuk menggunakan rotasi log harian (`daily` driver). Jalankan perintah berikut secara berkala untuk membersihkan cache dan sesi usang:

```bash
cd /var/www/autism-elearning
php artisan cache:clear
php artisan view:clear
php artisan config:cache
```

---

### 3. Panduan Pemecahan Masalah (Troubleshooting)

#### Kasus A: Gambar Materi / Video Tidak Muncul (Error 404 pada Gambar)
- **Penyebab:** Symlink direktori storage public belum terhubung atau terputus setelah migrasi folder.
- **Solusi:**
  ```bash
  cd /var/www/autism-elearning
  php artisan storage:link
  sudo chown -R www-data:www-data storage bootstrap/cache
  ```

#### Kasus B: Suara Narasi / Tombol Dengar Tidak Bersuara di Browser
- **Penyebab:**
  1. Kebijakan browser modern memblokir suara yang tidak diawali interaksi pengguna (kebijakan *autoplay policy*).
  2. Browser belum mengunduh modul suara bahasa Indonesia (id-ID) untuk Web Speech API.
- **Solusi:**
  1. Di aplikasi ini, audio hanya dipicu saat pengguna mengklik kartu atau tombol speaker. Pastikan siswa telah mengklik layar terlebih dahulu.
  2. Pastikan volume perangkat tidak dalam keadaan *mute* (bisu).
  3. Pada sistem operasi Windows / Android, buka *Settings > Time & Language > Speech*, pastikan suara bahasa Indonesia terpasang.

#### Kasus C: Siswa Lupa 4 Digit PIN
- **Penyebab:** Siswa kehilangan kartu cetak atau lupa PIN akunnya.
- **Solusi:**
  1. Guru login ke panel admin (`/admin/siswa`).
  2. Cari baris siswa yang bersangkutan.
  3. Klik tombol **"Reset PIN"**.
  4. Sistem akan mengacak 4 digit PIN baru dan menampilkannya di layar.
  5. Guru mencetak ulang kartu PIN siswa melalui tombol **"Cetak Kartu"**.

#### Kasus D: Ukuran Video MP4 Melebihi Batas Unggah Nginx
- **Penyebab:** Nilai `client_max_body_size` pada Nginx atau `upload_max_filesize` pada `php.ini` lebih kecil dari 50MB.
- **Solusi:**
  Pastikan di `/etc/php/8.2/fpm/php.ini`:
  ```ini
  upload_max_filesize = 60M
  post_max_size = 65M
  memory_limit = 256M
  ```
  Dan di `/etc/nginx/sites-available/autism-elearning`:
  ```nginx
  client_max_body_size 55M;
  ```
  Lalu reload layanan:
  ```bash
  sudo systemctl restart php8.2-fpm && sudo systemctl reload nginx
  ```
