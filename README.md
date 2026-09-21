# Eprocurement Workspace

Aplikasi ini dibangun di atas **CodeIgniter 4.7.4**, AdminLTE 4, dan Bootstrap 5.3. Modul MVP yang tersedia adalah pengelolaan perusahaan, katalog produk beserta datasheet dan informasi toko, pembuatan quotation multi-item, pemisahan data berdasarkan perusahaan, serta unduh quotation ke PDF.

## Instalasi

1. Salin `.env.example` menjadi `.env`, lalu isi `app.baseURL` dan kredensial database.
2. Jalankan `composer install`.
3. Jalankan migration dengan `php spark migrate`.
4. Arahkan web server ke folder `public/`.
5. Buka `/` untuk dashboard.

Alternatif deployment manual tersedia pada `database/eprocurement.sql`. Migration adalah cara yang direkomendasikan karena dapat dikelola dan di-rollback oleh CodeIgniter.

## Modul utama

- **Perusahaan**: setiap quotation memilih satu perusahaan sehingga data otomatis terkelompok.
- **Katalog Produk**: menyimpan SKU, brand, harga modal, harga jual, deskripsi, datasheet, gambar, toko offline/online, link, telepon, dan PIC.
- **Penawaran**: memilih perusahaan dan produk, menghitung subtotal, diskon, pajak, dan grand total. Item menyimpan snapshot nama/deskripsi/harga agar perubahan katalog tidak merusak quotation lama.
- **PDF**: tombol `Unduh PDF` memakai Dompdf.

## Catatan keamanan dan kompatibilitas

Perubahan bersifat additive terhadap CodeIgniter starter. Tidak ada tabel atau kode existing yang dihapus. Validasi dasar diterapkan pada input perusahaan, produk, dan quotation. Untuk production, tambahkan autentikasi/otorisasi, CSRF sesuai kebijakan deployment, upload file terkelola untuk gambar/datasheet, dan audit log sebelum aplikasi dibuka ke publik.
