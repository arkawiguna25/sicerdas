# SICERDAS

Sistem Informasi Cara Efektif Rehabilitasi Daerah Aliran Sungai.

## Persyaratan
- XAMPP (Apache dan MySQL)
- PHP 8.x
- Browser modern

## Instalasi lokal
1. Pastikan XAMPP berjalan.
2. Copy folder proyek ke `C:\xampp\htdocs\sicerdas`.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih tab **Import**, lalu impor `database/sicerdas_db.sql`.
5. Buka `http://localhost/sicerdas/`.

## Akun awal
- Admin: `admin` / `password`
- Petugas: `petugas` / `password`

Konfigurasi koneksi dapat diubah pada `config/database.php`.
