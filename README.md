# 👮‍♂️ SIMPEG Lapas (Sistem Kepegawaian & Jadwal Dinas)

Aplikasi manajemen kepegawaian modern untuk Lembaga Pemasyarakatan (Lapas). Dibangun untuk mempermudah distribusi jadwal jaga (Rupam), manajemen personel, dan pelaporan otomatis.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![Livewire](https://img.shields.io/badge/Livewire-3-pink)
![Tailwind](https://img.shields.io/badge/Tailwind-3-cyan)

## ✨ Fitur Unggulan

- **🚀 Dashboard Interaktif**: Visualisasi data pegawai dan jadwal dalam tampilan kartu 3D & Glassmorphism.
- **⚡ Auto-Generate Schedule**: Algoritma cerdas untuk membuat jadwal dinas 1 bulan penuh secara otomatis (Pagi/Siang/Malam/Libur).
- **🖨️ Export PDF**: Cetak laporan jadwal resmi siap tanda tangan (Format A4 Landscape).
- **👥 Manajemen Pegawai**: CRUD data pegawai dengan foto inisial otomatis.
- **🔐 Keamanan Bertingkat**: Sistem login, proteksi rute, dan manajemen profil (Ganti Password).
- **📊 Statistik Real-time**: Grafik tren shift dan rekapitulasi personil dinas malam.

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Blade, Livewire 3, Alpine.js
- **Styling**: Tailwind CSS (via CDN)
- **Database**: MySQL / MariaDB
- **Libraries**: 
  - `barryvdh/laravel-dompdf` (Cetak PDF)
  - `chart.js` (Grafik Statistik)
  - `sweetalert2` (Notifikasi Cantik)

## 💻 Cara Instalasi (Localhost)

Ikuti langkah ini untuk menjalankan proyek di komputer Anda:

1. **Clone Repository**
   ```bash
   git clone [https://github.com/username-anda/simpeg-lapas.git](https://github.com/username-anda/simpeg-lapas.git)
   cd simpeg-lapas