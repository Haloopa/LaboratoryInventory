# Laboratory Inventory Management System

## Deskripsi
Laboratory Inventory Management System adalah aplikasi berbasis web yang dikembangkan menggunakan framework Laravel. Aplikasi ini digunakan untuk mengelola inventaris laboratorium Teknologi Informasi dan Komunikasi (TIK), termasuk manajemen barang dengan gambar, peminjaman, persetujuan (approval), serta pengelolaan pengguna dengan sistem role dan hak akses.

---

## 🚀 Fitur Utama

### 🔐 Manajemen User & Role
- Manajemen pengguna (CRUD user)
- Sistem role & permission menggunakan Spatie Laravel Permission
- Role utama:
  - Super Admin
  - Admin
- Role user dengan dua profil:
  - Dosen (dengan profil NIP)
  - Mahasiswa (dengan profil NIM)

### 🖼️ Manajemen Inventory dengan Gambar
- Pengelolaan data inventaris barang laboratorium
- Upload gambar untuk setiap barang inventaris
- Gambar disimpan dalam storage dengan optimasi
- Preview gambar sebelum upload
- Format gambar yang didukung: JPG, PNG, GIF (max 2MB)
- Monitoring ketersediaan barang dengan visual yang jelas

### 🔄 Peminjaman & Approval System
- Pengajuan peminjaman barang oleh user (Dosen/Mahasiswa)
- Keranjang peminjaman dengan sistem session
- Proses approval peminjaman oleh Admin/Super Admin
- Monitoring status peminjaman barang secara real-time
- Validasi stok sebelum peminjaman

### 📊 Dashboard Multi-role
- Dashboard overview untuk Admin & Super Admin
- Dashboard user (Dosen & Mahasiswa)
- Ringkasan data inventory dan peminjaman
- Aktivitas terbaru user

### 📦 Keranjang Peminjaman
- Sistem keranjang dengan session
- Update jumlah barang di keranjang
- Validasi stok saat penambahan ke keranjang
- Submit peminjaman dalam satu klik

### ✅ Approval Management
- Daftar pengajuan peminjaman yang pending
- Approve/Reject peminjaman dengan validasi stok
- Update status peminjaman secara otomatis
- Notifikasi perubahan status

### 📆 Pengembalian Barang
- Konfirmasi pengembalian barang
- Update tanggal kembali otomatis/manual
- Pengembalian stok ke inventory
- Riwayat pengembalian lengkap

### 📄 Surat Peminjaman
- Upload surat pendukung peminjaman
- Download template surat
- Manajemen surat yang sudah diupload
- Penyimpanan file menggunakan storage Laravel

### 👤 Profil Pengguna
- Halaman profil pengguna
- Update data profil sesuai role
- Update password dengan validasi

### 🔍 Pencarian & Filter
- Pencarian barang berdasarkan nama
- Filter riwayat peminjaman
- Filter berdasarkan role dan tanggal
- Pagination untuk data yang banyak

---

## Teknologi & Versi
- Laravel Framework: 12.38.1
- PHP: 8.3.25
- Database: MySQL (MariaDB 10.4.25)

---

## Requirement Sistem
- PHP >= 8.3
- Composer
- Node.js & NPM
- Database MySQL
- Web Server (Apache / Nginx / Laravel Built-in Server)

---

## Instalasi & Setup

1. Clone repository
```bash
git clone https://github.com/Haloopa/LaboratoryInventory.git
cd LaboratoryInventory
````

2. Install dependensi backend

```bash
composer install
```

3. Install dependensi frontend

```bash
npm install
```

4. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

5. Konfigurasi database
   Sesuaikan pengaturan database pada file `.env`:

```env
DB_DATABASE=laboratory_db
DB_USERNAME=root
DB_PASSWORD=
```

6. Migrasi database & seeder

```bash
php artisan migrate --seed
```

7. Storage Symbolic link

```bash
php artisan storage:link
```

8. Jalankan asset frontend

```bash
npm run dev
```

9. Jalankan server aplikasi

```bash
php artisan serve
```

10. Akses aplikasi melalui:

```
http://127.0.0.1:8000
```

---

## Akun Default (Seeder)

Akun berikut tersedia secara default melalui database seeder:

| Role        | Email                                                   | Password |
| ----------- | ------------------------------------------------------- | -------- |
| Super Admin | [superadmin@example.com](mailto:superadmin@example.com) | password |
| Admin       | [admin@example.com](mailto:admin@example.com)           | password |
| Dosen       | [dosen@example.com](mailto:dosen@example.com)           | password |
| Mahasiswa   | [mahasiswa@example.com](mailto:mahasiswa@example.com)   | password |

> ⚠️ Segera ganti password setelah login pertama!

---

## Struktur Folder

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller aplikasi
│   │   ├── Middleware/      # Middleware (auth, role, dll)
│   │   └── Requests/        # Form request validation
│   ├── Models/              # Model Eloquent
│   └── Providers/           # Service providers
│
├── bootstrap/               # File bootstrap framework
│
├── config/                  # File konfigurasi aplikasi
│
├── database/
│   ├── migrations/          # File migrasi database
│   ├── seeders/             # Seeder akun default & role
│   └── factories/           # Factory model
│
├── public/
│   ├── css/                 # File CSS aplikasi
│   ├── js/                  # File JavaScript aplikasi
│   └── storage/             # Storage publik (symlink)
│
├── resources/
│   └── views/               # Blade templates
│
├── routes/
│   ├── web.php              # Route aplikasi web
│   └── api.php              # Route API (jika digunakan)
│
├── storage/
│   ├── app/                 # File upload (surat peminjaman) dan gambar alat
│   ├── framework/           # Cache & session
│   └── logs/                # Log aplikasi
│
├── tests/                   # Unit & feature test
│
├── vendor/                  # Dependensi composer
│
├── .env                     # Konfigurasi environment
├── composer.json            # Konfigurasi Composer
├── package.json             # Konfigurasi NPM
├── vite.config.js           # Konfigurasi Vite
└── README.md                # Dokumentasi project
```

