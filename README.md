<div align="center">
    <img src="public/images/logo.png" alt="Yaksa Logo" width="120" style="margin-bottom: 20px;">
    <h1>Inventaris Yaksa 📦</h1>
    <p>Sistem Manajemen Inventaris Modern & Real-Time untuk PT Yaksa Ersada Solusindo</p>
    <p><strong>Didevelop oleh: Galang Ma'ruf Sherinian</strong></p>
</div>

---

## 🌟 Tentang Proyek
**Inventaris Yaksa** adalah aplikasi berbasis web berskala *enterprise* yang saya kembangkan khusus untuk **PT Yaksa Ersada Solusindo**. Tujuan utama dari proyek ini adalah digitalisasi pemantauan, pencatatan, dan manajemen sirkulasi barang/hardware (IT Asset Management). Sistem ini dirancang dengan antarmuka bergaya *glassmorphism* modern, sistem notifikasi *real-time*, keamanan autentikasi via OTP (One-Time Password) ke email, dan pencatatan riwayat transaksi secara komprehensif.

## 🎯 Pencapaian Proyek (Portfolio Highlights)
- 🚀 Berhasil mendeploy sistem dari tahap perancangan lokal (development) hingga *Live Production* di cPanel Hosting.
- 🔐 Mengintegrasikan sistem Autentikasi canggih dengan OTP ke Email Gmail (menggunakan SMTP dan Mailtrap) untuk keamanan ekstra saat registrasi dan lupa password.
- 📊 Membangun sistem Export Laporan Excel terotomatisasi dengan kustomisasi gaya (styling) sesuai format operasional perusahaan.
- ⚡ Mengoptimalkan User Experience (UX) dengan *single-page feel* menggunakan Alpine.js tanpa mengorbankan performa Laravel.

## ✨ Fitur Utama
- **Autentikasi Aman:** Login, Register dengan OTP Email, dan Forgot Password.
- **Dashboard Interaktif**: Ringkasan data perangkat (Total, Tersedia, Keluar, RMA) secara visual.
- **Master Barang**: Manajemen (CRUD) data inventory secara lengkap berserta pencarian instan dan filter multi-status.
- **Log Transaksi Terintegrasi**: Catat barang masuk dan keluar langsung dari *Master Barang* melalui fitur *Pop-up Modal* dinamis.
- **Role-Based Access Control (RBAC)**: Batasan hak akses terstruktur (`Superadmin`, `Admin`, dan `User`).
- **Sistem Notifikasi**: Pemberitahuan *real-time* setiap ada pergerakan barang atau penambahan item baru.
- **Export Excel Otomatis**: Format laporan rapi, di-styling khusus.

## 🛠️ Stack Teknologi
- **Backend:** Laravel 11.x, PHP 8.3
- **Frontend:** HTML5, Alpine.js, Tailwind CSS v4
- **Database:** MySQL
- **Email Delivery:** SMTP Gmail & Mailtrap API
- **Deployment:** Shared Hosting (cPanel)

---

## 🚀 Panduan Instalasi (Development Lokal)

1. **Clone repositori ini / Ekstrak ZIP:**
   Arahkan ke direktori server lokal Anda (misal: `C:\laragon\www\inventaris-yaksa`).

2. **Install dependensi:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   Duplikat file `.env.example` menjadi `.env`. Sesuaikan kredensial Database dan SMTP (Mailtrap/Gmail).

4. **Setup Database & Kunci Aplikasi:**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi:**
   ```bash
   npm run build
   php artisan serve
   ```
   Akses di: `http://localhost:8000`

---

## 🔐 Akun Dummy (Hasil Seeder)

| Akses/Role | Alamat Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@gmail.com` | `password` | Kendali Penuh (Master Data & User) |
| **Admin** | `admin@gmail.com` | `password` | Manajemen Barang & Log |
| **User** | `user1@gmail.com` | `password` | View-only |

---

<div align="center">
    <strong>Portofolio Web Development © 2026</strong>
</div>
