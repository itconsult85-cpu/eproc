# Production Deployment Checklist

Dokumen ini adalah prosedur minimum untuk menjalankan EPROC di production. Hardening kode sudah berada di repository; konfigurasi domain, PHP, web server, database, dan backup tetap harus dilakukan pada server deployment.

## 1. Prasyarat server

- PHP 8.2+ dengan ekstensi `intl`, `mbstring`, `mysqli`, `xml`, `curl`, `zip`, dan `fileinfo`.
- MySQL/MariaDB dengan user database khusus aplikasi; jangan memakai `root`.
- Apache dengan `mod_rewrite`, `mod_headers`, dan dukungan `.htaccess`, atau konfigurasi Nginx ekuivalen.
- HTTPS aktif dengan sertifikat valid.
- Document root diarahkan ke folder `public/`, bukan root repository.
- PHP `display_errors=Off` dan `log_errors=On`.

## 2. Environment

```bash
cp env.production.example .env
chmod 640 .env
```

Edit `.env` di server dan isi hanya secret yang sebenarnya. Jangan commit `.env` atau menaruh password di issue, log, atau chat.

Endpoint setup administrator pertama **dinonaktifkan secara default** ketika `CI_ENVIRONMENT=production`. Untuk database yang benar-benar kosong, aktifkan `app.allowInitialSetup = true` hanya sementara di `.env` server, buat administrator pertama, lalu hapus setting tersebut dan restart aplikasi.

Wajib dipastikan:

```ini
CI_ENVIRONMENT = production
app.baseURL = 'https://domain-anda.example/'
database.default.DBDebug = false
```

Jika memakai reverse proxy, isi `app.proxyIPs` hanya dengan alamat proxy yang dipercaya.

## 3. Release aplikasi

Gunakan release directory dan symlink agar rollback mudah:

```bash
mkdir -p /var/www/eproc/releases
# clone/copy repository ke /var/www/eproc/releases/<commit>
cd /var/www/eproc/releases/<commit>
composer install --no-dev --classmap-authoritative --prefer-dist --no-interaction
```

Jangan menjalankan `composer update` di production. Gunakan `composer.lock` dari commit yang sudah diuji.

## 4. Backup sebelum migration

Backup database sebelum setiap deployment yang menjalankan migration:

```bash
mkdir -p /var/backups/eproc
mysqldump --single-transaction --routines --triggers \
  -u eprocurement_backup -p eprocurement \
  | gzip > /var/backups/eproc/eprocurement-$(date +%F-%H%M%S).sql.gz
```

Uji restore backup secara berkala ke database staging. Backup yang tidak pernah diuji restore belum dianggap backup yang dapat diandalkan.

## 5. Migration dan cache

Jalankan dari release directory setelah backup:

```bash
php spark migrate:status
php spark migrate
php spark cache:clear
```

Periksa migration baru pada staging dengan salinan database production sebelum dijalankan live. Perubahan schema modul BAST, PO IN, PO OUT, dan Surat Jalan harus sudah tersedia melalui migration atau SQL manual yang sesuai.

## 6. Permission filesystem

Web server hanya perlu menulis ke `writable/` dan storage upload privat:

```bash
chown -R www-data:www-data writable/
chmod -R u=rwX,g=rX,o= writable/
chmod 750 writable/
```

Storage privat berada di `writable/uploads-private` dan tidak boleh menjadi document root. Jangan memberi `777` pada project atau folder upload.

## 7. Upload dan file sensitif

- `public/uploads/.htaccess` memblokir eksekusi PHP/CGI/script upload.
- File tender dan bukti pembayaran baru disimpan di storage privat dan dikirim melalui controller berpermission.
- File lama di `public/uploads/tenders` dan `public/uploads/payment-proofs` tetap ditolak akses langsung oleh `.htaccess`.
- Periksa file lama yang pernah ter-upload dan pindahkan ke storage privat bila masih berisi dokumen sensitif.

## 8. Smoke test setelah release

Dengan akun role yang berbeda, uji:

- Login berhasil, password salah, lockout/rate limit, dan logout.
- User tanpa permission tidak dapat membuka atau mengirim POST ke modul lain.
- CSRF menolak request POST tanpa token.
- CRUD Quotation, PO IN, PO OUT, Tagihan, BAST, dan Surat Jalan.
- Perhitungan PPN/PPh dan PDF.
- Download dokumen hanya untuk user yang berhak.
- Upload gambar/PDF valid, ekstensi palsu ditolak, dan script upload tidak dapat dieksekusi.
- DataTables server-side, pagination, search, dan sorting.
- Response production tidak menampilkan stack trace, SQL, path server, atau Debug Toolbar.

## 9. Rollback

Jika smoke test gagal:

1. Hentikan traffic atau arahkan traffic ke release sebelumnya.
2. Kembalikan symlink release ke commit sebelumnya.
3. Jangan menghapus database secara manual.
4. Restore database hanya bila migration sudah mengubah data/schema dan prosedur rollback migration tidak cukup.
5. Simpan log kegagalan dan backup sebelum mencoba ulang.

Migration yang sudah berjalan harus memiliki rencana rollback yang diuji di staging. Jangan melakukan rollback kode tanpa menilai kompatibilitas schema.

## 10. Monitoring minimum

- Pantau log PHP/web server dan `writable/logs` tanpa menampilkan log ke publik.
- Pantau error 4xx/5xx, percobaan login gagal, disk usage, backup, dan expiry TLS.
- Jalankan `composer audit --no-interaction` pada pipeline atau jadwal maintenance.
- Jadwalkan backup database dan uji restore.

## Status hardening repository

Perubahan repository mencakup CSP production, database debug off pada production, HTTPS redirect untuk `www`, security headers untuk asset statis, dan proteksi eksekusi file upload. Konfigurasi server dan smoke test di atas tetap wajib karena tidak dapat dijalankan dari repository secara otomatis.
