<div align="center">

<!-- Project Logo -->
<div style="background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%); width: 120px; height: 120px; border-radius: 40px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; shadow: 0 20px 50px rgba(79, 70, 229, 0.3);">
    <span style="font-size: 60px; font-weight: 900; color: white; font-family: 'Inter', sans-serif;">S</span>
</div>

# 🏢 SIMPEG LAPAS v2.0
### **Institutional Personnel Intelligence & Operational Resource Planning**

**Sistem informasi manajemen kepegawaian modern yang dirancang khusus untuk digitalisasi, keamanan, dan efisiensi operasional di Lembaga Pemasyarakatan.**

<p align="center">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
    <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
    <img src="https://img.shields.io/badge/Livewire-4.0-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" />
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Security-MFA_Enabled-success?style=flat-square" />
    <img src="https://img.shields.io/badge/Status-Production_Ready-blue?style=flat-square" />
    <img src="https://img.shields.io/badge/Architecture-TALL_Stack-indigo?style=flat-square" />
    <img src="https://img.shields.io/badge/UI-High_End_Enterprise-black?style=flat-square" />
</p>

</div>

---

## 📖 Deskripsi Proyek

**SIMPEG Lapas** bukan sekadar aplikasi absensi. Ini adalah solusi **Enterprise Resource Planning (ERP)** terintegrasi yang menangani seluruh spektrum manajemen SDM dan keamanan di Lapas. Mulai dari algoritma penjadwalan dinas otomatis, sistem verifikasi biometrik dengan *geofencing*, hingga protokol keamanan tingkat tinggi seperti **Panic Button** dan **Audit Trail Intel**.

Aplikasi ini dibangun menggunakan **TALL Stack** (Tailwind, Alpine.js, Laravel, Livewire) untuk memastikan performa yang ringan, responsif, dan interaktif secara real-time.

---

## 🚀 Fitur Utama & Kapabilitas

Berikut adalah rincian fitur yang telah diimplementasikan dari tahap awal hingga optimasi final:

| Kategori | Fitur | Deskripsi | Status |
| :--- | :--- | :--- | :---: |
| **Security** | 🔐 **MFA (OTP Email)** | Proteksi login Admin menggunakan verifikasi kode 6-digit via email. | ✅ |
| | 🛡️ **Audit Trail Intel** | Pencatatan setiap perubahan data (JSON payload) untuk transparansi mutlak. | ✅ |
| | 🧱 **IP Restriction** | Pembatasan akses dashboard admin berdasarkan daftar IP yang diizinkan. | ✅ |
| | 🚨 **Panic Button** | Sinyal darurat real-time yang memicu alarm visual di seluruh sistem. | ✅ |
| **Operations** | 🗓️ **Roster Engine** | Algoritma otomatisasi jadwal dinas bulanan bagi seluruh regu jaga. | ✅ |
| | 📍 **Biometric Presence** | Absensi dengan verifikasi GPS (Geofencing) dan Foto Selfie AI-Scan. | ✅ |
| | 🛡️ **QR Checkpoint** | Verifikasi patroli petugas di titik rawan menggunakan kode lokasi. | ✅ |
| | 🔄 **Shift Exchange** | Sistem pertukaran jadwal dinas antar pegawai dengan alur approval. | ✅ |
| **Personnel** | 👥 **Asset Database** | Manajemen master data pegawai lengkap dengan grade Tukin & jabatan. | ✅ |
| | 🏖️ **E-Leave Portal** | Pengajuan dan persetujuan cuti digital secara paperless. | ✅ |
| | 🗄️ **Digital Vault** | Brankas dokumen digital terenkripsi untuk menyimpan SK/Sertifikat. | ✅ |
| | 📊 **Activity Journal** | Kalender riwayat kehadiran dan performa harian yang interaktif. | ✅ |
| **Intelligence** | 📈 **Real-time Analytics** | Visualisasi tren kehadiran dan kedisiplinan via Chart.js. | ✅ |
| | 💰 **Payroll Analytics** | Kalkulasi otomatis potongan Tukin berdasarkan keterlambatan/alpha. | ✅ |
| | 🏆 **Officer Index** | Leaderboard reputasi petugas berdasarkan performa operasional. | ✅ |
| | 📄 **PDF Reporting** | Ekspor laporan jadwal, Tukin, dan Patroli ke format dokumen resmi. | ✅ |
| **System** | 🌓 **Night Shift Mode** | Antarmuka Dark Mode premium untuk kenyamanan petugas jaga malam. | ✅ |
| | 📱 **PWA Ready** | Aplikasi dapat diinstall langsung di perangkat Android/iOS (Native feel). | ✅ |
| | ⚙️ **Dynamic Config** | Pengaturan parameter sistem (GPS, Radius, Nama) langsung dari UI. | ✅ |
| | 💾 **System Backup** | Fitur kompresi data dan storage menjadi arsip ZIP dalam satu klik. | ✅ |

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

- **Framework:** Laravel 12 (The latest & most secure)
- **Frontend:** Livewire 4 + Alpine.js (Reactive UI)
- **Styling:** Tailwind CSS (Enterprise Design System)
- **Database:** SQLite (Default) / MySQL / PostgreSQL
- **Reporting:** Barryvdh DOMPDF
- **Visualization:** Chart.js

---

## ⚙️ Panduan Instalasi (Development)

Pastikan lingkungan Anda memenuhi syarat: **PHP 8.2+**, **Composer**, **Node.js & NPM**.

### 1. Kloning Repositori
```bash
git clone https://github.com/aryadians/simpeg-lapas.git
cd simpeg-lapas
```

### 2. Instalasi Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database & Seeding
```bash
# Membuat file database (jika menggunakan SQLite)
touch database/database.sqlite

# Menjalankan migrasi dan data awal (Master Shift & Admin)
php artisan migrate --seed
```

### 5. Compile Assets & Run
```bash
npm run dev
php artisan serve
```

---

## 🚢 Panduan Deploy (Production)

Untuk performa maksimal di server produksi, jalankan perintah optimasi:

1.  **Build Frontend:** `npm run build`
2.  **Optimize Laravel:** `php artisan optimize`
3.  **Link Storage:** `php artisan storage:link`
4.  **Security:** Atur `ALLOWED_IPS` di menu **Settings** untuk membatasi akses Admin.

---

## 🔐 Kredensial Default

| Akun | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@lapas.com` | `password` |
| **Petugas (Staff)** | `staff@lapas.com` | `password` |

*Catatan: OTP untuk Admin dapat dilihat sementara di `storage/logs/laravel.log` selama tahap development.*

---

<div align="center">
    <p>Dikembangkan dengan ❤️ untuk kemajuan birokrasi digital Indonesia.</p>
    <p><b>&copy; 2026 Institutional Perimeter Control System</b></p>
</div>
