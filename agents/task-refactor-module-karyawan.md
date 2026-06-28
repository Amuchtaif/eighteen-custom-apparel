# Task: Refactor Modul Karyawan - Master Data Dinamis & Auto Generate Kode Karyawan

## Latar Belakang

Saat ini field **Role**, **Jabatan**, dan **Departemen** pada modul karyawan masih menggunakan data statis/hardcode.

Selain itu, **Kode Karyawan** masih diinput manual sehingga berpotensi terjadi duplikasi dan inkonsistensi format.

Perlu dilakukan refactor agar seluruh data master dapat dikelola melalui halaman khusus dan digunakan sebagai dropdown saat menambahkan atau mengubah data karyawan.

---

# Tujuan

1. Membuat master data dinamis untuk:
    - Role
    - Jabatan
    - Departemen

2. Menambahkan halaman manajemen master data.

3. Mengubah form karyawan agar menggunakan dropdown dari master data.

4. Mengubah kode karyawan menjadi otomatis generate berdasarkan nomor terakhir di database.

---

# Scope Pekerjaan

## 1. Master Role

Buat tabel:

### roles

| Field       | Type          |
| ----------- | ------------- |
| id          | bigint        |
| name        | varchar       |
| description | text nullable |
| is_active   | boolean       |
| created_at  | timestamp     |
| updated_at  | timestamp     |

### Halaman

Menu:

```
Pengaturan
 └── Master Role
```

Fitur:

- List Role
- Tambah Role
- Edit Role
- Nonaktifkan Role
- Hapus Role (jika belum digunakan)

Contoh data:

- Owner
- Super Admin
- Admin
- Sales
- Produksi
- Keuangan
- HRD

---

## 2. Master Jabatan

Buat tabel:

### positions

| Field       | Type          |
| ----------- | ------------- |
| id          | bigint        |
| name        | varchar       |
| description | text nullable |
| is_active   | boolean       |
| created_at  | timestamp     |
| updated_at  | timestamp     |

### Halaman

Menu:

```
Pengaturan
 └── Master Jabatan
```

Fitur:

- List
- Tambah
- Edit
- Hapus
- Status Aktif/Nonaktif

Contoh:

- Sales Representative
- Sales Manager
- Operator Produksi
- Supervisor Produksi
- Admin Keuangan

---

## 3. Master Departemen

Buat tabel:

### departments

| Field       | Type          |
| ----------- | ------------- |
| id          | bigint        |
| name        | varchar       |
| description | text nullable |
| is_active   | boolean       |
| created_at  | timestamp     |
| updated_at  | timestamp     |

### Halaman

Menu:

```
Pengaturan
 └── Master Departemen
```

Fitur:

- List
- Tambah
- Edit
- Hapus
- Status Aktif/Nonaktif

Contoh:

- Sales
- Produksi
- Keuangan
- Marketing
- HRD

---

# 4. Refactor Tabel Karyawan

## Tambahan Relasi

Ubah tabel employees:

Tambahkan:

| Field         | Type      |
| ------------- | --------- |
| role_id       | bigint FK |
| position_id   | bigint FK |
| department_id | bigint FK |

Hapus penyimpanan teks langsung apabila saat ini masih menggunakan:

- role
- jabatan
- departemen

Migrasikan ke foreign key.

---

# 5. Refactor Form Tambah/Edit Karyawan

## Sebelum

Field:

- Role (text/manual)
- Jabatan (text/manual)
- Departemen (text/manual)

## Sesudah

Gunakan dropdown:

### Role

Data dari:

```
roles
```

### Jabatan

Data dari:

```
positions
```

### Departemen

Data dari:

```
departments
```

Dropdown hanya menampilkan data aktif.

---

# 6. Auto Generate Kode Karyawan

## Saat Membuat Karyawan Baru

Field:

```
Kode Karyawan
```

Tidak lagi diinput manual.

Field ditampilkan readonly.

---

## Format

```text
EMP-00001
EMP-00002
EMP-00003
...
```

---

## Logic

Ambil data karyawan terakhir berdasarkan nomor terbesar.

Contoh:

Data terakhir:

```text
EMP-00027
```

Maka karyawan baru otomatis:

```text
EMP-00028
```

---

## Ketentuan

- Nomor selalu increment +1
- Tetap unik
- Tidak boleh duplicate
- Tetap berjalan walaupun ada data yang dihapus

Contoh:

Data:

```text
EMP-00001
EMP-00002
EMP-00003
```

Lalu EMP-00003 dihapus.

Karyawan baru:

```text
EMP-00004
```

Bukan mengulang nomor lama.

---

## Implementasi

Disarankan menambahkan:

```sql
employee_number_sequence
```

atau menggunakan query:

```sql
MAX(employee_code)
```

yang kemudian diparsing menjadi angka terakhir.

Pastikan aman terhadap race condition saat beberapa user membuat karyawan bersamaan.

---

# 7. Validasi

- Role wajib dipilih
- Jabatan wajib dipilih
- Departemen wajib dipilih
- Kode karyawan otomatis dan tidak bisa diedit manual
- Tidak boleh ada kode karyawan duplikat

---

# 8. Seed Data Awal

Buat seeder untuk:

### Roles

- Owner
- Super Admin
- Admin
- Sales
- Produksi
- Keuangan
- HRD

### Departments

- Sales
- Produksi
- Marketing
- Keuangan
- HRD

### Positions

- Sales Representative
- Sales Manager
- Operator Produksi
- Supervisor Produksi
- Admin Keuangan
- Staff HRD

---

# Hasil Akhir

- Role menjadi master data dinamis.
- Jabatan menjadi master data dinamis.
- Departemen menjadi master data dinamis.
- Form karyawan menggunakan dropdown relasi.
- Kode karyawan otomatis generate berdasarkan nomor terakhir.
- Tidak ada lagi input manual kode karyawan.
- Struktur data lebih fleksibel dan siap untuk pertumbuhan organisasi.
