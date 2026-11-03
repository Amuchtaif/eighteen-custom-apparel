# Dokumentasi Database Eighteen Custom Apparel

Database aplikasi ini berjalan di server **MySQL** lokal dengan konfigurasi sebagai berikut:

### 1. Informasi Koneksi
- **Host**: `127.0.0.1`
- **Port**: `3306`
- **Database**: `eighteen`
- **Username**: `amuchtaif`
- **Password**: `qurmutun`
- **Driver**: `mysql2/promise` (Connection pool di [`lib/db.ts`](../lib/db.ts))

### 2. Berkas SQL di Folder Ini
- **[`eighteen.sql`](./eighteen.sql)**: Salinan dump lengkap struktur tabel beserta data awal (users, roles, master packages, materials, kantor, shift, orders).
- **[`schema.sql`](./schema.sql)**: Definisi struktur DDL skema tabel saja.

### 3. Cara Mengakses via Terminal
```bash
mysql -u amuchtaif -pqurmutun eighteen
```

### 4. Cara Restore / Import Ulang Database
Jika ingin mengimpor ulang database ke MySQL baru:
```bash
mysql -u amuchtaif -pqurmutun -e "CREATE DATABASE IF NOT EXISTS eighteen;"
mysql -u amuchtaif -pqurmutun eighteen < database/eighteen.sql
```

### 5. Konfigurasi Environment
Konfigurasi database disimpan di berkas:
- `.env`
- `.env.local`

