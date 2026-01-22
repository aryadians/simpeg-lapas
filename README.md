<div align="center">

# 🏢 SIMPEG Lapas
### (Sistem Informasi Manajemen Kepegawaian)

**Aplikasi manajemen kepegawaian modern yang dirancang untuk digitalisasi operasional di Lembaga Pemasyarakatan.**

<p>
    <img alt="PHP" src="https://img.shields.io/badge/PHP-^8.2-777BB4.svg?style=for-the-badge&logo=php"/>
    <img alt="Laravel" src="https://img.shields.io/badge/Laravel-^12.0-FF2D20.svg?style=for-the-badge&logo=laravel"/>
    <img alt="Livewire" src="https://img.shields.io/badge/Livewire-^4.0-4E56A6.svg?style=for-the-badge&logo=livewire"/>
    <img alt="Alpine.js" src="https://img.shields.io/badge/Alpine.js-8BC0D0.svg?style=for-the-badge&logo=alpine.js"/>
    <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind_CSS-38B2AC.svg?style=for-the-badge&logo=tailwind-css"/>
</p>

<p>
    <img alt="License" src="https://img.shields.io/github/license/laravel/laravel?style=flat-square&color=green"/>
    <img alt="Status" src="https://img.shields.io/badge/Status-Development-blue.svg?style=flat-square"/>
    <img alt="Maintenance" src="https://img.shields.io/badge/Maintained%3F-yes-brightgreen.svg?style=flat-square"/>
</p>

</div>

---

**SIMPEG Lapas** adalah solusi terintegrasi untuk mengelola seluruh aspek administrasi kepegawaian di lingkungan lapas, mulai dari penjadwalan, absensi, hingga pelaporan, dengan fokus pada kemudahan penggunaan dan keamanan data.

## ✨ Fitur Utama

- **👤 Manajemen Pegawai:** Pengelolaan data master pegawai, termasuk informasi pribadi, jabatan, dan grade tukin.
- **🗓️ Penjadwalan Dinas:** Pembuatan jadwal dinas (roster) bulanan secara otomatis dengan algoritma yang adil.
- **📍 Absensi Cerdas (Geofencing & Selfie):**
  - **Verifikasi Lokasi:** Mengunci tombol absen agar hanya aktif jika pegawai berada dalam radius yang ditentukan (contoh: 100m) dari titik koordinat lapas.
  - **Bukti Kehadiran:** Mewajibkan pegawai untuk mengambil foto selfie saat melakukan absen masuk sebagai bukti kehadiran fisik.
  - **Deteksi Shift:** Secara otomatis mengenali jadwal shift pegawai (pagi, siang, malam) dan menangani absensi yang melintasi hari.
- **🌴 Manajemen Cuti:** Proses pengajuan dan persetujuan cuti secara digital dan terintegrasi dengan sistem penjadwalan.
- **📓 Buku Laporan Jaga:** Digitalisasi laporan aplusan regu jaga, mencatat inventaris, jumlah penghuni, dan kejadian penting.
- **💥 Laporan Insiden:** Fitur khusus untuk mencatat dan mengelola laporan kejadian tak terduga selama jam dinas.
- **💰 Laporan Tukin:** Kalkulasi otomatis potongan tunjangan kinerja (tukin) berdasarkan data keterlambatan dan absensi.
- **🖨️ Ekspor PDF:** Mencetak berbagai laporan penting seperti jadwal dinas dan rekap tukin dalam format PDF yang rapi.
- **🔐 Sistem Autentikasi:** Sistem login yang aman dengan manajemen peran (Admin & Pegawai).

---

## 🛠️ Tumpukan Teknologi

- **Backend:** [Laravel](https://laravel.com/) - PHP Framework
- **Frontend:** [Blade](https://laravel.com/docs/blade) + [Livewire](https://livewire.laravel.com/)
- **UI Interactivity:** [Alpine.js](https://alpinejs.dev/)
- **Styling:** [Tailwind CSS](https://tailwindcss.com/)
- **Database:** Dapat dikonfigurasi (MySQL, PostgreSQL, SQLite)
- **PDF Generation:** `barryvdh/laravel-dompdf`

---

## ⚙️ Panduan Instalasi dan Setup

Pastikan lingkungan lokal Anda memenuhi prasyarat berikut:
- **PHP >= 8.2**
- **Composer**
- **Node.js & NPM**
- **Database** (e.g., MySQL, MariaDB)

### 1. Clone Repositori
```bash
git clone <URL_REPOSITORI_ANDA>
cd simpeg-lapas
```

### 2. Konfigurasi Awal
```bash
# Salin file environment. File ini bersifat sensitif dan tidak boleh masuk ke git.
cp .env.example .env

# Install dependensi PHP (backend)
composer install

# Install dependensi JavaScript (frontend)
npm install
```

### 3. Setup Aplikasi
```bash
# Generate kunci enkripsi unik untuk aplikasi
php artisan key:generate

# Konfigurasi file .env Anda
# Atur `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dll.
# Atur `OFFICE_LATITUDE` dan `OFFICE_LONGITUDE` sesuai lokasi Anda.

# Jalankan migrasi database untuk membuat tabel-tabel yang diperlukan
php artisan migrate

# (Opsional) Seed database dengan data awal jika tersedia
php artisan db:seed
```

### 4. Menjalankan Aplikasi
Aplikasi ini membutuhkan dua proses yang berjalan bersamaan.

- **Terminal 1: Jalankan Server Backend Laravel**
  ```bash
  php artisan serve
  ```

- **Terminal 2: Jalankan Server Frontend Vite**
  ```bash
  npm run dev
  ```

Buka browser Anda dan akses `http://127.0.0.1:8000`.

---

## 📸 Tangkapan Layar (Placeholder)

| Dashboard | Absensi dengan Selfie |
| :---: | :---: |
| ![Dashboard](https://placehold.co/600x400/e2e8f0/475569?text=Dashboard) | ![Absensi](https://placehold.co/600x400/e2e8f0/475569?text=Absensi+Selfie) |

| Laporan Tukin | Jadwal Dinas |
| :---: | :---: |
| ![Tukin](https://placehold.co/600x400/e2e8f0/475569?text=Laporan+Tukin) | ![Jadwal](https://placehold.co/600x400/e2e8f0/475569?text=Jadwal+Dinas) |