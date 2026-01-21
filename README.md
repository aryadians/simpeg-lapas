# 🏢 SIMPEG Lapas (Sistem Informasi Kepegawaian)

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-F5788D?style=for-the-badge&logo=chart.js&logoColor=white)

<br>

![Version](https://img.shields.io/badge/version-1.1.0-blue?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)
![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-brightgreen.svg?style=flat-square)
![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square)

</div>

---

**Sistem Informasi Manajemen Kepegawaian (SIMPEG)** yang dirancang khusus untuk kebutuhan Lembaga Pemasyarakatan (Lapas). Aplikasi ini mendigitalisasi proses penjadwalan dinas, absensi real-time, perizinan cuti, hingga pelaporan aplusan regu jaga secara terintegrasi dan efisien.

---

## 🚀 Fitur Unggulan (v1.1.0)

### 1. 📅 Smart Roster System (Penjadwalan Cerdas)
* **Auto-Generate Algorithm:** Membuat jadwal dinas satu bulan penuh secara otomatis hanya dengan satu klik.
* **Fair Randomization:** Menggunakan algoritma pengacak (`Randomize`) untuk memastikan rotasi shift yang adil bagi setiap pegawai.
* **Leave Integration:** Sistem otomatis mendeteksi pegawai yang status cutinya *Approved* dan melewati mereka saat pembuatan jadwal (tidak bentrok).

### 2. 🏃‍♂️ E-Presensi (Absensi Real-time)
* **Shift Detection:** Widget cerdas yang mendeteksi shift pegawai (Pagi/Siang/Malam) berdasarkan jam server.
* **Cross-Day Logic:** Mendukung absensi **Shift Malam** yang melintasi pergantian hari (misal: Masuk 19:00, Pulang 07:00 keesokan harinya).
* **Status Validasi:** Menandai status kehadiran secara visual: ✅ *Hadir Tepat Waktu* atau ⚠️ *Terlambat*.

### 3. 🏖️ E-Cuti (Manajemen Cuti)
* Pengajuan permohonan cuti pegawai secara digital.
* Sistem persetujuan (Approval) berjenjang oleh Admin/Atasan.
* Sinkronisasi otomatis dengan kalender jadwal dinas.

### 4. 📋 E-Logbook (Laporan Aplusan)
* Digitalisasi buku laporan jaga (Astekpam) antar regu.
* Pencatatan inventaris, jumlah WBP, dan kejadian penting secara real-time.

### 5. 🖨️ Reporting & Export PDF
* **Laporan Jadwal:** Cetak jadwal matriks bulanan (Layout Legal/F4 Landscape).
* **Rekap Absensi:** Laporan rekapitulasi kehadiran, keterlambatan, dan alpha per bulan.

### 6. 👤 Manajemen Akun
* CRUD Data Pegawai.
* Akun login otomatis untuk setiap pegawai.
* Fitur ubah profil dan ganti password mandiri.

---

## 🛠️ Teknologi

* **Backend:** [Laravel 10.x](https://laravel.com/)
* **Frontend:** [Blade Templates](https://laravel.com/docs/blade)
* **Styling:** [Tailwind CSS](https://tailwindcss.com/)
* **Interactivity:** [Livewire 3](https://livewire.laravel.com/) (Full-stack reactivity without heavy JS)
* **Database:** MySQL
* **Libraries:**
    * `barryvdh/laravel-dompdf` (PDF Export)
    * `chart.js` (Visualisasi Data Dashboard)
    * `sweetalert2` (Notifikasi Interaktif)

---

## 📸 Tangkapan Layar (Screenshots)

| Dashboard Admin | Jadwal Dinas Otomatis |
| :---: | :---: |
| ![Dashboard](https://placehold.co/600x400/e2e8f0/475569?text=Dashboard+Admin) | ![Jadwal](https://placehold.co/600x400/e2e8f0/475569?text=Jadwal+Otomatis) |

| Widget Absensi (Shift Malam) | Cetak PDF Laporan |
| :---: | :---: |
| ![Absensi](https://placehold.co/600x400/e2e8f0/475569?text=Widget+Absensi) | ![PDF](https://placehold.co/600x400/e2e8f0/475569?text=Laporan+PDF) |

*(Catatan: Gambar di atas adalah placeholder. Silakan ganti URL gambar dengan screenshot asli aplikasi Anda setelah di-upload)*

---

## ⚙️ Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repositori
```bash
git clone [https://github.com/aryadians/simpeg-lapas.git](https://github.com/aryadians/simpeg-lapas.git)
cd simpeg-lapas
