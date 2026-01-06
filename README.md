<p align="center">
    <a href="#" target="_blank">
        <img src="https://i.ibb.co.com/chWmj9Bf/Logo-1.png" width="150" alt="e-Report Logo">
    </a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-Framework-red" alt="Laravel">
    <img src="https://img.shields.io/badge/Status-Development-yellow" alt="Status">
    <img src="https://img.shields.io/badge/License-MIT-blue" alt="License">
</p>

## Tentang e-Report

**e-Report** (Sistem Informasi Laporan Digital) adalah platform berbasis web yang dikembangkan untuk mengatasi isu di sekolah. Sistem ini bertujuan untuk memodernisasi dan menyederhanakan proses pelaporan di lingkungan sekolah, menggantikan metode konvensional dengan solusi digital yang cepat, transparan, dan akurat.

Dengan e-Report, siswa, guru, maupun staf dapat mengirimkan laporan (akademik, sarana prasarana, atau kedisiplinan) secara real-time dan memantau status tindak lanjutnya.

## Fitur Utama

- **📝 Pelaporan Mudah & Cepat:** Antarmuka yang ramah pengguna (user-friendly) untuk membuat laporan baru.
- **🔒 Keamanan & Privasi:** Data pelapor dan isi laporan dijaga kerahasiaannya.
- **📱 Responsif:** Akses mudah melalui Desktop, Tablet, maupun Smartphone.
- **📊 Dashboard Admin:** Panel kontrol terpusat untuk memverifikasi dan menindaklanjuti laporan masuk.
- **🔔 Notifikasi Status:** Pelacakan status laporan (Terkirim, Diproses, Selesai).
- **📂 Manajemen Arsip:** Penyimpanan riwayat laporan digital yang terorganisir.

## Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan teknologi web modern untuk menjamin performa dan skalabilitas:

- **Backend:** [Laravel](https://laravel.com) (PHP Framework)
- **Frontend:** Tailwind CSS & Blade Templating
- **Database:** MySQL
- **Web Server:** Apache/Nginx

## Cara Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username-anda/e-report.git](https://github.com/username-anda/e-report.git)
    cd e-report
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Duplikasi file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  **Generate Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database**
    Pastikan database sudah dibuat di phpMyAdmin/MySQL, lalu jalankan:
    ```bash
    php artisan migrate
    ```

6.  **Jalankan Server**
    ```bash
    npm run dev
    php artisan serve
    ```
    Buka browser dan akses `http://localhost:8000`.

## Kontribusi
<p align="center">
    <a href="#" target="_blank">
        <img src="https://i.ibb.co.com/VcT1YMcT/Logo-Utama.png" width="150" alt="turi logo">
         <img src="https://i.ibb.co.com/JRjQhmqm/ADW-300x149.png" width="150" alt="Smart school">
    </a>
</p>
Proyek ini dikembangkan dengan kerjasama bersama SMAN 1 TURI. Jika Anda menemukan bug atau memiliki saran pengembangan fitur, silakan buat issue baru atau hubungi tim pengembang.

## Keamanan

Jika Anda menemukan celah keamanan pada sistem ini, harap segera laporkan kepada tim IT sekolah atau pengembang melalui email, jangan mempublikasikannya secara umum.

## Lisensi

e-Report adalah perangkat lunak open-source dikembangkan oleh PT. KREASI DIGITAL CREATIVE MINDS INDONESIA
