# Eprocurement Workspace

Aplikasi ini dibangun di atas **CodeIgniter 4.7.4**, AdminLTE 4, dan Bootstrap 5.3. Modul yang tersedia adalah pengelolaan perusahaan, katalog produk beserta datasheet dan media, pembuatan quotation multi-item, pemisahan data berdasarkan perusahaan, pengaturan identitas penerbit, serta unduh quotation ke PDF.

## Instalasi

1. Salin `.env.example` menjadi `.env`, lalu isi `app.baseURL` dan kredensial database.
2. Jalankan `composer install`.
3. Jalankan migration dengan `php spark migrate`.
4. Pastikan `public/uploads` dapat ditulis oleh web server.
5. Arahkan web server ke folder `public/`.
6. Buka `/` untuk dashboard.

Alternatif deployment manual tersedia pada `database/eprocurement.sql`. Migration adalah cara yang direkomendasikan karena dapat dikelola dan di-rollback oleh CodeIgniter.

## Modul utama

- **AdminLTE 4**: layout memakai struktur resmi `app-wrapper`, `app-header`, `app-sidebar`, `app-main`, sidebar treeview, dan tombol collapse/responsive pada header.
- **Perusahaan**: setiap quotation memilih satu perusahaan sehingga data otomatis terkelompok.
- **Katalog Produk**: menyimpan SKU, brand, harga modal, harga jual, deskripsi, datasheet, URL/upload gambar, URL/upload video, toko offline/online, link, telepon, dan PIC.
- **Setting Quotation**: menu `/settings/quotation` menyimpan nama perusahaan, Office 1, Office 2, telepon, email, NPWP, logo, tanda tangan, stempel, nama/telepon penandatangan, default PPN, payment terms, validity, dan delivery terms.
- **Penawaran**: memilih perusahaan dan produk, dengan field To, alamat, Attn, About, tanggal, nomor, quantity, UoM, price, amount, subtotal, PPN, dan grand total. Item menyimpan snapshot nama/deskripsi/harga agar perubahan katalog tidak merusak quotation lama.
- **PDF**: tombol `Unduh PDF` memakai Dompdf, ukuran Letter, dan mengikuti susunan template Excel yang diberikan.

## Catatan keamanan dan kompatibilitas

Perubahan bersifat additive terhadap CodeIgniter starter. Tidak ada tabel atau kode existing yang dihapus. Validasi tipe/ukuran file diterapkan pada upload media: gambar maksimal 5 MB (JPG/PNG/WEBP) dan video maksimal 50 MB (MP4/WEBM/MOV). Untuk production, tambahkan autentikasi/otorisasi, CSRF sesuai kebijakan deployment, backup media, dan audit log sebelum aplikasi dibuka ke publik.
