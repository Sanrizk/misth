# 🌿 Misth (Hydroponic Farm Management & POS)

Misth adalah sistem manajemen terpadu untuk pertanian hidroponik (hydroponic farm management system) yang dirancang untuk memudahkan pemantauan siklus tanam, perawatan, kualitas air, hingga proses panen dan penjualan dengan Point of Sale (POS) terintegrasi.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)

## About The Project
Sistem ini memfasilitasi proses operasional kebun hidroponik dari hulu ke hilir. Sistem ini diperuntukkan bagi tiga jenis pengguna utama:
- **Admin & Kasir**: Mengelola pengguna, memantau seluruh proses operasional, mengelola produk, dan memproses transaksi pelanggan menggunakan QR Scanner.
- **Petani**: Mencatat siklus tanam, melakukan log perawatan harian, memantau kualitas air, dan mencatat hasil panen beserta satuan ukurnya.
- **Customer**: Menjelajahi katalog toko, memasukkan produk ke keranjang, dan melakukan pemesanan (Checkout).

**Key Features:**
- Manajemen siklus tanam (Planting cycle)
- Pencatatan perawatan harian (Daily maintenance log)
- Monitoring kualitas air (Water quality monitoring)
- Manajemen panen (Harvest management) dengan spesifikasi satuan (kg, ons, ikat)
- Otomasi stok produk dari hasil panen (Auto product stock from harvest)
- **Storefront & Cart System** (Sistem toko dan keranjang untuk Customer)
- **Point of Sale (POS) & QR Scanner** (Memproses invoice pelanggan secara instan via kamera atau upload foto)
- Role-based access control (RBAC)

## Tech Stack
- **Backend:** PHP 8.3 & Laravel 13.x
- **Database:** MySQL
- **Frontend:** Blade Templating Engine
- **Styling:** Tailwind CSS
- **Interactivity:** Alpine.js
- **Icons:** Bootstrap Icons
- **QR Scanner:** html5-qrcode

## Database Structure
Sistem ini menggunakan 10 tabel utama untuk mengelola data operasional:
1. `roles`: Menyimpan peran sistem dan hak akses (Admin, Petani, Customer).
2. `users`: Menyimpan kredensial dan profil.
3. `plant_types`: Katalog tanaman hidroponik.
4. `plantings`: Data setiap siklus tanam (batch).
5. `maintenance_logs`: Log perawatan harian.
6. `water_quality_logs`: Data pemantauan air (pH, nutrisi).
7. `harvests`: Data panen yang terhubung langsung dengan sistem produk (dilengkapi unit/satuan).
8. `products`: Stok produk yang tampil di etalase toko.
9. `transactions`: Data transaksi dan invoice.
10. `transaction_details`: Item spesifik pada transaksi.

## App Guide / Core Workflows

### 1. Farm Operations (Petani Flow)
- **Tanam (Planting):** Petani mencatat siklus tanam baru yang akan otomatis mendapatkan *batch code*.
- **Rawat (Maintenance):** Petani mengisi log perawatan dan kualitas air harian.
- **Panen (Harvest):** Setelah masa tanam selesai, petani mencatat panen beserta satuannya (kg/ons/ikat). **Sistem otomatis mengkonversi hasil panen menjadi stok produk yang siap dijual.**

### 2. Store & Shopping (Customer Flow)
- **Katalog:** Customer masuk ke halaman `Store` untuk melihat produk yang tersedia (Mobile responsive).
- **Keranjang & Checkout:** Customer menambahkan produk ke keranjang, lalu melakukan checkout.
- **Invoice:** Sistem meng-generate Invoice beserta QR Code pembayaran untuk customer. Status transaksi menjadi `Pending`.

### 3. POS & Cashier (Admin/Kasir Flow)
- **Dashboard Transaksi:** Admin membuka menu `Transactions`.
- **QR Scanner:** Admin mengklik tombol "Scan". UI Scanner akan terbuka (bisa menggunakan kamera belakang, upload foto, atau input manual).
- **Konfirmasi:** Admin memindai QR Code milik customer. Data invoice otomatis muncul secara instan. Admin mengkonfirmasi pembayaran, mengubah status menjadi `Paid`, dan stok otomatis terpotong.

## Prerequisites
- PHP >= 8.1 (Disarankan 8.3)
- Composer
- MySQL
- Node.js & NPM
- Git

## Installation Guide

```bash
# 1. Clone the repository
git clone https://github.com/username/misth.git
cd misth

# 2. Install PHP dependencies
composer install

# 3. Install NPM dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# Sesuaikan pengaturan DB Anda

# 7. Run migrations
php artisan migrate

# 8. Run seeders (includes default users & transactions)
php artisan db:seed

# 9. Build frontend assets (Tailwind)
npm run build

# 10. Start local server
php artisan serve
```

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@misth.com | password |
| Petani | petani1@misth.com | password |
| Customer | customer@misth.com | password |

## Module Matrix

| Module | Admin | Petani | Customer |
|--------|-------|--------|----------|
| Plant Types | ✅ | ✅ | ❌ |
| Plantings | ✅ | ✅ | ❌ |
| Maintenance Logs | ✅ | ✅ | ❌ |
| Water Quality Logs | ✅ | ✅ | ❌ |
| Harvests | ✅ | ✅ | ❌ |
| Store & Cart | ✅ | ❌ | ✅ |
| Transactions / POS | ✅ | ✅ | ✅ |

## License
Distributed under the MIT License.
