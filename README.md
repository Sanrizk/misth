# 🌿 HydroFarm

HydroFarm adalah sistem manajemen terpadu untuk pertanian hidroponik (hydroponic farm management system) yang dirancang untuk memudahkan pemantauan siklus tanam, perawatan, kualitas air, hingga proses panen dan penjualan.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)

## About The Project
Sistem ini memfasilitasi proses operasional kebun hidroponik dari hulu ke hilir. Sistem ini diperuntukkan bagi tiga jenis pengguna utama:
- **Admin**: Mengelola pengguna, memantau seluruh proses operasional, dan melihat laporan transaksi.
- **Petani**: Mencatat siklus tanam, melakukan log perawatan harian, memantau kualitas air, dan mencatat hasil panen.
- **Customer**: Membeli produk hasil panen melalui sistem transaksi.

**Key Features:**
- Manajemen siklus tanam (planting cycle)
- Pencatatan perawatan harian (daily maintenance log)
- Monitoring kualitas air (water quality monitoring)
- Manajemen panen (harvest management)
- Otomasi stok produk dari hasil panen (auto product stock from harvest)
- Sistem transaksi dan penjualan (transaction & sales system)
- Role-based access control

## Tech Stack
- **Backend:** PHP 8.3 & Laravel 13.x
- **Database:** MySQL
- **Frontend:** Blade Templating Engine
- **Styling:** Bootstrap 5
- **Icons:** Bootstrap Icons

## Database Structure
Sistem ini menggunakan 10 tabel utama untuk mengelola data operasional:

1. `roles`: Menyimpan peran sistem dan hak akses (Admin, Petani, Customer).
2. `users`: Menyimpan kredensial dan informasi profil pengguna.
3. `plant_types`: Katalog daftar jenis tanaman hidroponik yang tersedia.
4. `plantings`: Mencatat data setiap siklus tanam (batch).
5. `maintenance_logs`: Log aktivitas harian untuk perawatan tanaman.
6. `water_quality_logs`: Data hasil pemantauan metrik kualitas air (pH, nutrisi).
7. `harvests`: Menyimpan data panen dari setiap siklus tanam yang selesai.
8. `products`: Mengelola data stok produk hasil panen yang siap dijual.
9. `transactions`: Mencatat data utama untuk transaksi penjualan.
10. `transaction_details`: Menyimpan detail spesifik (item dan kuantitas) dari setiap transaksi.

*(Lihat diagram relasi entitas secara lengkap di `/docs/erd.png`)*

## Prerequisites
Pastikan sistem Anda telah menginstal dependensi berikut sebelum memulai instalasi:
- PHP >= 8.1 (Disarankan 8.3)
- Composer
- MySQL
- Node.js & NPM
- Git

## Installation Guide

```bash
# 1. Clone the repository
git clone https://github.com/username/hydrofarm.git
cd hydrofarm

# 2. Install PHP dependencies
composer install

# 3. Install NPM dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hydrofarm
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. Run seeders (includes default users)
php artisan db:seed

# 9. Build frontend assets
npm run build

# 10. Start local server
php artisan serve
```

## Default Login Credentials
Setelah melakukan migrasi dan seeding, Anda dapat masuk menggunakan akun default berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hydrofarm.com | password |
| Petani | petani1@hydrofarm.com | password |
| Customer | customer@hydrofarm.com | password |

## Module List & Routes
- Plant Types → `/plant-types`
- Plantings → `/plantings`
- Maintenance Logs → `/maintenance-logs`
- Water Quality Logs → `/water-quality-logs`
- Harvests → `/harvests`
- Products → `/products`
- Transactions → `/transactions`

## Role Access Matrix
| Module | Admin | Petani | Customer |
|--------|-------|--------|----------|
| Plant Types | ✅ | ✅ | ❌ |
| Plantings | ✅ | ✅ | ❌ |
| Maintenance Logs | ✅ | ✅ | ❌ |
| Water Quality Logs | ✅ | ✅ | ❌ |
| Harvests | ✅ | ✅ | ❌ |
| Products | ✅ | ✅ | ✅ |
| Transactions | ✅ | ✅ | ✅ |

## Business Logic Flow
1. Petani mencatat siklus tanam baru → *batch code* auto-generated.
2. Petani mencatat perawatan & kualitas air harian.
3. Petani mengeksekusi panen → stok produk terbuat otomatis.
4. Customer membeli produk → stok berkurang otomatis.
5. Admin memantau seluruh proses.

## Contributing
Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License
Distributed under the MIT License.
