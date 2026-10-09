###################################################################
LAWASIA Belt and Road Initiative & Employment Law Conference 2025
###################################################################

Sistem informasi dan portal pendaftaran konferensi internasional **LAWASIA Belt and Road Initiative & Employment Law Conference 2025** yang diselenggarakan oleh PERADI bersama organisasi hukum LAWASIA.

Aplikasi web ini digunakan untuk mengelola pendaftaran peserta/delegasi, penyampaian jadwal acara, materi konferensi, konfirmasi email otomatis, serta informasi pendukung lainnya.

Sistem dikembangkan menggunakan framework **CodeIgniter 3**, antarmuka **Bootstrap**, dan database **MySQL**.

*******************
Informasi Konferensi
*******************

- **Nama Acara**: LAWASIA Belt and Road Initiative & Employment Law Conference 2025
- **Penyelenggara**: PERADI (Perhimpunan Advokat Indonesia) & LAWASIA
- **Tanggal**: 2025
- **Lokasi**: Indonesia

*******************
Fitur Utama Portal
*******************

1. **Beranda & Landing Page**: Tampilan informasi umum konferensi, tema pembahasan, dan daftar pembicara internasional.
2. **Sistem Pendaftaran Peserta**: Formulir pendaftaran delegasi dengan pengisian data personal, instansi/organisasi, dan opsi keikutsertaan.
3. **Pengiriman Email Konfirmasi Otomatis**: Integrasi SMTP PHPMailer untuk pengiriman email konfirmasi pendaftaran secara otomatis kepada peserta.
4. **Materi & Program Konferensi**: Jadwal sesi diskusi, topik pembahasan *Belt & Road Initiative*, serta materi *Employment Law*.
5. **Panel Admin & Manajemen Peserta**:
   - Pengelolaan data registrasi delegasi.
   - Verifikasi status pendaftaran dan pembayaran.
   - Ekspor rekap data peserta ke format Excel/PDF.

*******************
Teknologi yang Digunakan
*******************

- **Framework**: CodeIgniter 3 (PHP)
- **Frontend**: Bootstrap 4 / 5, HTML5, CSS3, JavaScript / jQuery
- **Database**: MySQL / MariaDB
- **Library Tambahan**: PHPMailer (SMTP Email Handler), TCPDF / FPDF

*******************
Persyaratan Server
*******************

- Web Server: Apache (dengan modul ``mod_rewrite`` aktif)
- PHP: Version 7.2 / 7.4 / 8.0
- Database: MySQL 5.7+ / MariaDB
- Ekstensi PHP Wajib: ``mysqli``, ``curl``, ``json``, ``mbstring``, ``gd``, ``openssl``

************
Cara Instalasi
************

1. Kloning repositori ini ke direktori web server lokal (misal: ``C:/xampp/htdocs/lawasia2025``):
   .. code-block:: bash

      git clone https://github.com/rifqi13/LAWASIA-Law-Conference-2025-.git

2. Buat database baru di MySQL (contoh: ``lawasia_2025_db``) dan import skema database (file ``.sql``).
3. Konfigurasikan koneksi database pada berkas ``application/config/database.php``.
4. Atur ``base_url`` dan pengaturan SMTP email pada berkas ``application/config/config.php`` atau file konfigurasi terkait.
5. Buka peramban (browser) Anda dan akses URL projek.

*******
Lisensi
*******

Hak Cipta (c) 2025 PERADI & LAWASIA. All Rights Reserved.
