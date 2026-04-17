<div align="center">
    <img src="public/images/logo.png" alt="Yaksa Logo" width="120" style="margin-bottom: 20px;">
    <h1>Inventaris Yaksa</h1>
    <p>Sistem Manajemen Inventaris Modern & Real-Time untuk PT Yaksa Ersada Solusindo</p>
</div>

---

## 🌟 Tentang Proyek
**Inventaris Yaksa** adalah aplikasi berbasis web yang dirancang khusus untuk mempermudah pemantauan, pencatatan, dan manajemen sirkulasi barang/hardware. Sistem ini dilengkapi dengan antarmuka bergaya *glassmorphism* modern, sistem notifikasi *real-time*, dan pencatatan riwayat transaksi (*log*) barang secara detail.

## ✨ Fitur Utama
- 📊 **Dashboard Interaktif**: Ringkasan data perangkat (Total, Tersedia, Keluar, RMA) secara *real-time*.
- 📦 **Master Barang**: CRUD data inventory secara lengkap berserta pencarian instan dan filter multi-status.
- 🔄 **Log Transaksi Terintegrasi**: Catat barang masuk dan keluar langsung dari *Master Barang* melalui fitur *Pop-up Modal* yang dinamis.
- 👨‍💻 **Role-Based Access Control (RBAC)**: Batasan hak akses terstruktur (`Superadmin`, `Admin`, dan `User`).
- 🔔 **Sistem Notifikasi**: Pemberitahuan setiap ada pergerakan barang atau penambahan item baru.
- 📄 **Export Excel Otomatis**: Format Excel yang sudah di-styling (Auto-filter, warna baris, *freeze pane*) mengikuti template *Google Sheets* standar perusahaan.
- 🎨 **Modern UI/UX**: Didukung oleh Tailwind CSS v4, Alpine.js, dan SweetAlert2 untuk pengalaman pengguna setara aplikasi *Enterprise Native*.

## 🛠️ Teknologi yang Digunakan
- **Backend:** Laravel 11.x, PHP 8.2+
- **Frontend:** HTML5, Alpine.js (Reaktivitas), Tailwind CSS v4 (Styling)
- **Database:** MySQL
- **Tooling/Library:** Vite, Laravel Excel (Maatwebsite), SweetAlert2

---

## ⚙️ Persyaratan Sistem (System Requirements)
Sebelum melakukan instalasi, pastikan sistem Anda telah memiliki:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB Database Server

---

## 🚀 Panduan Instalasi (Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di *local machine* (misal: Laragon, XAMPP, Valet):

1. **Clone repositori ini** (jika menggunakan Git) atau Ekstrak file ZIP proyek ke direktori server lokal Anda (misal: `C:\laragon\www\inventaris-yaksa`).

2. **Buka terminal** di dalam folder proyek tersebut, arahkan path-nya (contoh: `cd C:\laragon\www\inventaris-yaksa`).

3. **Install dependensi PHP dan Node.js:**
   ```bash
   composer install
   npm install
   ```

4. **Siapkan Environment Variables:**
   Duplikat file `.env.example` lalu ubah menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding (Penting):**
   Ini akan membuat struktur tabel dan mengisi data awal (akun login).
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Compile Assets Frontend:**
   ```bash
   npm run build
   # Atau jika sedang mendevelop: npm run dev
   ```

8. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi pada browser melalui url: `http://localhost:8000`

---

## 🔐 Akun Akses Default (Role)

Setelah menjalankan *seeder*, Anda bisa masuk menggunakan akun berikut:

| Akses/Role | Alamat Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@gmail.com` | `password` | Kendali Penuh (Termasuk Management User) |
| **Admin** | `admin@gmail.com` | `password` | Kendali Operasional Inventaris |
| **User Biasa** | `user1@gmail.com` | `password` | Hanya *Read-Only* |

> *Pastikan segera mengubah password default setelah sistem di-*deploy* ke ranah produksi.*

---

## 📄 Ringkasan Penyesuaian Kustom (Custom Log)
- **Fitur SweetAlert2** telah ditambahkan khusus di `Master Barang`, `Log Transaksi`, dan `User Management` untuk memblokir metode penghapusan tidak sengaja.
- **Logika Expandable Row** menggunakan tag `<tbody>` spesifik untuk scope per-item, mengatasi isu rendering dari Alpine.js di HTML struktur Table.
- **Laporan Excel** menggunakan Styling Custom (Header merah '#DC2626', teks putih tebal) yang didefinisikan pada `App\Exports\ItemsExport`.

---

<div align="center">
    <strong>Copyright ©2026</strong>
</div>
