# Panduan Deployment & Instalasi Sistem
## Website Edukasi Autis SLB B-C Wijayakusumah

Dokumen ini menyediakan panduan lengkap untuk melakukan *deployment* aplikasi pada dua lingkungan:
1. **Server Produksi VPS (Ubuntu 24.04 LTS + Nginx + PHP 8.2+ + MySQL)**
2. **Server Lokal Sekolah / Laboratorium (Windows + Laragon / XAMPP)**

---

### OPSI 1: Deployment VPS (Ubuntu Linux)

#### 1. Persyaratan Server
- VPS dengan RAM minimal 1 GB, Disk 20 GB SSD.
- Sistem Operasi: Ubuntu 22.04 LTS atau Ubuntu 24.04 LTS.
- Domain aktif yang mengarah ke IP publik VPS.

#### 2. Instalasi Paket Dependensi
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git curl unzip sqlite3 \
    php8.2-fpm php8.2-cli php8.2-common php8.2-sqlite3 php8.2-mysql \
    php8.2-mbstring php8.2-xml php8.2-curl php8.2-gd php8.2-intl php8.2-zip
```

#### 3. Instalasi Composer & Node.js
```bash
# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

#### 4. Kloning Repositori & Konfigurasi Lingkungan
```bash
sudo mkdir -p /var/www/autism-elearning
sudo chown -R $USER:$USER /var/www/autism-elearning
cd /var/www/autism-elearning

git clone https://github.com/username/autism-elearning.git .
composer install --no-dev --optimize-autoloader
npm ci && npm run build

cp .env.example .env
php artisan key:generate
```

#### 5. Pengaturan Berkas `.env` Produksi
Edit berkas `.env`:
```ini
APP_NAME=EduAutis
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://edukasi.slb-wijayakusumah.sch.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slb_autis
DB_USERNAME=slb_user
DB_PASSWORD=PasswordKuatMySQL123!

# Kredensial Administrator Awal (Wajib Diisi untuk Seeder Produksi)
ADMIN_EMAIL=guru@slb.test
ADMIN_PASSWORD=KatasandiKuatGuru2026!
```

#### 6. Migrasi Database & Symlink Storage
```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Izin Akses Direktori untuk Nginx
sudo chown -R www-data:www-data /var/www/autism-elearning/storage /var/www/autism-elearning/bootstrap/cache
sudo chmod -R 775 /var/www/autism-elearning/storage /var/www/autism-elearning/bootstrap/cache
```

#### 7. Konfigurasi Nginx Server Block
Buat berkas konfigurasi di `/etc/nginx/sites-available/autism-elearning`:
```nginx
server {
    listen 80;
    server_name edukasi.slb-wijayakusumah.sch.id;
    root /var/www/autism-elearning/public;

    index index.php index.html;
    client_max_body_size 55M;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache untuk Font & Aset Statis Lokal
    location ~* \.(woff|woff2|svg|png|jpg|webp)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```
Aktifkan konfigurasi dan pasang SSL:
```bash
sudo ln -s /etc/nginx/sites-available/autism-elearning /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# Pasang SSL Gratis Let's Encrypt
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d edukasi.slb-wijayakusumah.sch.id
```

---

### OPSI 2: Deployment Lokal Sekolah (Laragon / XAMPP)

Jika sekolah memiliki keterbatasan jaringan internet dan ingin menjalankan web edukasi secara *offline* di kelas menggunakan laptop guru atau lab komputer:

#### 1. Menggunakan Laragon (Direkomendasikan)
1. Unduh dan pasang **Laragon Full** (PHP 8.2+, MySQL, Apache/Nginx).
2. Buka folder `C:\laragon\www\` dan buat folder `autism-elearning` atau salin repositori ini ke dalamnya.
3. Buka Terminal Laragon:
   ```bash
   cd C:\laragon\www\autism-elearning
   composer install
   npm install && npm run build
   cp .env.example .env
   php artisan key:generate
   ```
4. Di file `.env`, gunakan SQLite untuk kepraktisan lokal tanpa konfigurasi server:
   ```ini
   DB_CONNECTION=sqlite
   ```
   Lalu buat database:
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Akses melalui browser di laptop guru: `http://autism-elearning.test` atau jalankan `php artisan serve --host=0.0.0.0 --port=8000`. Tablet siswa dapat terhubung ke WiFi lokal yang sama dan membuka `http://[IP-Laptop-Guru]:8000`.
