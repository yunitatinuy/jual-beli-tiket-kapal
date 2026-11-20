# Panduan Deploy (Produksi)

Aplikasi: Laravel 10, PHP >= 8.1 (ekstensi `gd`, `mbstring`, `openssl`, `pdo_mysql`, `curl`, `xml`, `zip`), MySQL/MariaDB, Composer, Node.js 18+ (hanya untuk build CSS/JS).

## 1. Kode & dependency

```bash
git clone <repo> && cd jual-beli-tiket-kapal
composer install --no-dev --optimize-autoloader
npm ci && npm run build     # WAJIB: membuat public/build (Tailwind). Tanpa ini halaman tampil tanpa style
cp .env.example .env
php artisan key:generate
```

`public/build` tidak ikut di git. Jika server tidak punya Node.js, jalankan `npm run build` di komputer lokal
lalu upload folder `public/build` ke server. Jangan upload file `public/hot` (hanya untuk `npm run dev`).

Arahkan document root web server ke folder `public/`, bukan ke root project.
Folder `storage/` dan `bootstrap/cache/` harus bisa ditulis oleh user web server.

## 2. Isi `.env` produksi

```dotenv
APP_ENV=production
APP_DEBUG=false            # WAJIB false: debug page membocorkan .env & query
APP_URL=https://domain-anda.com
LOG_LEVEL=warning

FORCE_HTTPS=true
SESSION_SECURE_COOKIE=true
TRUSTED_PROXIES=*          # isi jika di belakang Cloudflare / load balancer; kosongkan jika tidak

DB_DATABASE=...
DB_USERNAME=...            # jangan pakai root
DB_PASSWORD=...

QUEUE_CONNECTION=database  # email dikirim di background oleh queue worker

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com   # atau SMTP penyedia lain (Brevo, Mailgun, SES, ...)
MAIL_PORT=587
MAIL_USERNAME=akun@gmail.com
MAIL_PASSWORD=app-password # Gmail: buat "App Password" (butuh 2FA), bukan password akun
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=akun@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

MIDTRANS_SERVER_KEY=Mid-server-...
MIDTRANS_CLIENT_KEY=Mid-client-...
MIDTRANS_IS_PRODUCTION=true   # false selama masih memakai key sandbox
MIDTRANS_BATAS_BAYAR_MENIT=60

ADMIN_NAME="Administrator"
ADMIN_EMAIL=admin@domain-anda.com
ADMIN_PASSWORD=            # kosong = dibuat acak, ditampilkan sekali saat seeding
```

## 3. Database

```bash
php artisan migrate --force
php artisan db:seed --force          # hanya membuat akun admin
```

Migration aman dijalankan di database lama hasil import dump SQL: tabel yang sudah ada dilewati,
kolom baru ditambahkan. Tetap **backup dulu** (`mysqldump`) sebelum migrate di server.

`DemoDataSeeder` (jadwal contoh 14 hari) hanya untuk lokal/staging dan menolak jalan di produksi.

## 4. Optimasi

Jalankan setiap kali deploy ulang:

```bash
php artisan optimize        # config:cache + route:cache
php artisan view:cache
php artisan event:cache
```

Setelah `config:cache`, perubahan `.env` baru terbaca setelah perintah itu dijalankan lagi.
Untuk kembali ke mode pengembangan: `php artisan optimize:clear`.

**Server:**
- Aktifkan **OPcache** di `php.ini` (`opcache.enable=1`, `opcache.validate_timestamps=0` di produksi,
  lalu restart PHP-FPM setiap deploy). Ini biasanya mempercepat PHP 2–3 kali.
- `public/.htaccess` sudah berisi gzip dan cache browser untuk gambar dan `public/build` (Apache).
  Untuk Nginx, tambahkan `gzip on;` dan `location /build/ { expires 1y; add_header Cache-Control "immutable"; }`.
- Gambar di `public/img` sudah dikompres (maks lebar 1920 px). Gambar baru sebaiknya juga dikecilkan
  sebelum diunggah (target < 400 KB per foto).

## 5. Proses background (wajib)

**Scheduler** – membatalkan pesanan yang lewat batas bayar dan mengembalikan kursi. Tambahkan ke crontab:

```cron
* * * * * cd /path/ke/jual-beli-tiket-kapal && php artisan schedule:run >> /dev/null 2>&1
```

**Queue worker** – mengirim email verifikasi & konfirmasi tiket. Jalankan dengan Supervisor (atau systemd):

```ini
[program:tiket-kapal-worker]
command=php /path/ke/jual-beli-tiket-kapal/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
stdout_logfile=/path/ke/jual-beli-tiket-kapal/storage/logs/worker.log
```

Setelah deploy ulang: `php artisan queue:restart`.
Shared hosting tanpa Supervisor: tambahkan cron
`* * * * * php /path/artisan queue:work --stop-when-empty` atau pakai `QUEUE_CONNECTION=sync` (email dikirim langsung, halaman sedikit lebih lambat).

## 6. Midtrans

1. Dashboard Midtrans > Settings > Access Keys: salin Server Key & Client Key ke `.env`.
2. Settings > Payment > Notification URL: `https://domain-anda.com/midtrans/notification`
3. Uji dulu dengan key **sandbox** (`MIDTRANS_IS_PRODUCTION=false`), lalu ganti ke key produksi
   setelah akun Midtrans disetujui.
4. Pantau log untuk `Midtrans: pembayaran diterima untuk pesanan yang sudah dibatalkan` (level critical):
   pembayaran yang masuk setelah pesanan dibatalkan perlu di-refund manual.

## 7. Checklist sebelum go-live

- [ ] `APP_DEBUG=false`, `APP_ENV=production`, HTTPS aktif (sertifikat Let's Encrypt / Cloudflare)
- [ ] Bisa register dan email verifikasi sampai ke inbox (cek folder spam)
- [ ] Pesan tiket → bayar di sandbox → status Lunas dan email konfirmasi terkirim
- [ ] Pesanan yang tidak dibayar dibatalkan otomatis setelah batas waktu (cek cron)
- [ ] Ganti password admin bawaan; hapus akun uji
- [ ] Backup database terjadwal (mis. `mysqldump` harian)
- [ ] `composer audit` bersih dari advisory high/critical yang punya perbaikan

## Catatan keamanan yang tersisa

Laravel 10 sudah tidak menerima patch keamanan (EOL). `composer audit` masih melaporkan
5 advisory di `laravel/framework` yang hanya diperbaiki di Laravel 12/13:

- CRLF injection pada rule `email`: sudah dimitigasi di project ini dengan `email:rfc,filter`.
- XSS di halaman debug: tidak berlaku jika `APP_DEBUG=false`.
- Concurrency process output injection: fitur `Concurrency` baru ada di Laravel 11, tidak dipakai di sini.
- Temporary signed URL path confusion (GHSA-crmm-hgp2-wgrp): link verifikasi email **memakai**
  temporary signed URL. Dampaknya terhadap project ini belum dianalisis; ini alasan utama untuk upgrade.

Rencanakan upgrade ke Laravel 12 sebagai pekerjaan terpisah.
