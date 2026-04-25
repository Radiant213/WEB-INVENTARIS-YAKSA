# Panduan Penggunaan Web Inventaris Yaksa

Dokumen ini berisi panduan langkah demi langkah cara menggunakan sistem Inventaris PT Yaksa Ersada Solusindo.

## 1. Registrasi & Login (Autentikasi OTP)
Sistem ini menggunakan keamanan berlapis berupa OTP (One-Time Password) yang dikirim ke email.

**Cara Mendaftar Akun:**
1. Buka halaman utama website.
2. Klik tombol **Daftar & Kirim OTP**.
3. Masukkan Nama Lengkap, Email (wajib `@gmail.com`), dan Password.
4. Klik submit. Sistem akan mengirimkan 6 digit kode OTP ke email Anda.
5. Buka tab baru, cek Inbox/Spam email Anda, salin kode OTP tersebut.
6. Masukkan kode ke halaman verifikasi di website. Jika berhasil, akun Anda langsung aktif.

**Cara Login:**
1. Masukkan email dan password yang sudah terdaftar.
2. Jika Anda lupa password, klik link **Lupa Password**, masukkan email, dan sistem akan mengirimkan OTP untuk mereset password Anda.

---

## 2. Dashboard
Halaman pertama setelah login adalah Dashboard. Halaman ini berfungsi sebagai pusat informasi (Overview).
- **Total Perangkat:** Menampilkan jumlah seluruh barang fisik yang tercatat di sistem.
- **Tersedia (Ready):** Jumlah barang yang saat ini ada di gudang dan siap digunakan.
- **Keluar (Deployed):** Jumlah barang yang sedang dipakai/dipinjam.
- **RMA (Rusak/Service):** Jumlah barang yang sedang dalam perbaikan.

---

## 3. Master Barang
Halaman ini adalah pusat pengelolaan data inventaris. Tergantung pada Role (hak akses) Anda, Anda bisa menambah, mengubah, atau menghapus barang.

**Cara Menambahkan Barang Baru (Admin/Superadmin):**
1. Pergi ke menu **Master Barang**.
2. Klik tombol **+ Tambah Barang Baru**.
3. Isi detail perangkat: Kategori, Nama, Merk, SN (Serial Number), Kondisi, dan Stok awal.
4. Klik **Simpan**.

**Cara Mengekspor Data ke Excel:**
1. Di halaman Master Barang, klik tombol hijau **Export Excel**.
2. File laporan `.xlsx` akan terunduh otomatis dengan format resmi perusahaan (header warna merah, auto-filter).

---

## 4. Log Transaksi (Barang Masuk / Keluar)
Setiap kali ada pergerakan barang (misal: barang baru datang, atau barang dipinjamkan ke staf), Anda **wajib** mencatatnya melalui fitur Log Transaksi.

**Cara Mencatat Transaksi:**
1. Pergi ke menu **Master Barang**.
2. Cari barang yang ingin dicatat transaksinya (bisa gunakan kolom pencarian).
3. Klik tombol biru berlogo keranjang (**Transaksi**) pada baris barang tersebut.
4. Akan muncul *Pop-up Modal*. Pilih **Jenis Transaksi**:
   - `Barang Masuk`: Untuk menambah stok.
   - `Barang Keluar`: Untuk mengurangi stok (misal: dipinjam).
   - `RMA`: Untuk memindahkan barang ke status perbaikan.
5. Masukkan **Jumlah**, tanggal, dan **Keterangan** (sangat penting untuk melacak siapa yang meminjam).
6. Klik **Simpan Transaksi**. Stok di Master Barang akan otomatis terupdate!

---

## 5. Riwayat Log (Audit Trail)
Untuk melihat sejarah pergerakan seluruh barang:
1. Buka menu **Log Transaksi** di sidebar kiri.
2. Anda akan melihat tabel lengkap siapa yang mengeluarkan barang, kapan, berapa jumlahnya, dan apa keterangannya.
3. Ini sangat berguna untuk audit jika ada barang yang hilang.

---

## 6. User Management (Khusus Superadmin)
Jika Anda login sebagai Superadmin, Anda memiliki menu tambahan untuk mengelola pengguna.

**Cara Mengubah Role Pengguna:**
1. Buka menu **Manajemen User**.
2. Cari nama karyawan/user.
3. Anda bisa mengubah hak aksesnya menjadi:
   - `User`: Hanya bisa melihat data (Read-only).
   - `Admin`: Bisa mengelola barang dan transaksi.
   - `Superadmin`: Bisa mengelola user dan memiliki akses penuh.

---
*Dibuat oleh: Galang Ma'ruf Sherinian*
