# NEWSLara
Portal berita/edukasi berbasis Laravel 12 + Filament 3.

## Quick start (5 langkah)
1) `git clone https://github.com/mbuzzz/newslaravel.git && cd newslaravel`  
2) `composer install`  
3) `cp .env.example .env` lalu set DB dan `php artisan key:generate`  
4) `php artisan migrate --seed`  
5) `npm install && npm run dev` kemudian `php artisan serve` (akses `http://localhost:8000`)

## Stack
- Laravel 12, PHP 8.2+
- Filament 3, Livewire 3
- Vite + Tailwind (via `npm run dev`/`npm run build`)
- SQLite atau MySQL/MariaDB

## Fitur
- Panel admin Filament untuk konten.
- Seeder bawaan untuk user admin, artikel, halaman statis.
- Script otomatis `composer run setup` untuk sekali jalan.

## Setup lokal (detail)
- Prasyarat: PHP 8.2+, Composer, Node 18+, npm, Git.
- Salin env: `cp .env.example .env`.
- Pilih database:
  - SQLite (paling cepat): `DB_CONNECTION=sqlite` lalu `touch database/database.sqlite`.
  - MySQL contoh:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=newslaravel
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```
- Generate key: `php artisan key:generate`.
- Migrasi + seeder: `php artisan migrate --seed`.
- Link storage: `php artisan storage:link`.
- Frontend: `npm install && npm run dev` (pakai `npm run build` untuk produksi).
- Jalankan: `php artisan serve`.

## Akun default (seeder)
- Email: `admin@pelajarmu.com`
- Password: `password`
- Admin Filament: `/admin`.

## Skrip siap pakai
- `composer run setup` : install composer, copy env (jika belum ada), key:generate, migrate, npm install, build.
- `composer run dev`   : jalankan server, queue listener, pail log, dan Vite bersamaan (lihat `composer.json`).

## Perintah berguna
- Bersihkan cache config: `php artisan config:clear`
- Tes: `php artisan test`
- Build aset produksi: `npm run build`

## Troubleshooting singkat
- Port 8000 dipakai: `php artisan serve --port=8001`
- Storage belum linked: `php artisan storage:link`
- Node versi lama: pakai Node 18+ (cek `node -v`)
