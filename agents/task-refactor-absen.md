# TASK: Refactor Sistem Absensi Pegawai Berbasis Lokasi, Shift Kerja, dan Geofencing

## Latar Belakang

Sistem absensi saat ini perlu direfactor agar mampu melakukan validasi kehadiran berdasarkan lokasi kantor (GPS Geofencing), jadwal kerja pegawai, serta mendukung pengembangan fitur HRIS di masa mendatang.

Sistem harus mendukung multi lokasi kantor karena Eighteen memiliki lebih dari satu lokasi operasional, yaitu:

1. Kantor Produksi
2. Kantor Studio

Seluruh konfigurasi lokasi kantor dan jadwal kerja harus dapat dikelola melalui Admin Panel tanpa perlu perubahan kode aplikasi.

---

# Tujuan

Membangun sistem absensi yang:

- Mendukung multi lokasi kantor.
- Mendukung multi shift kerja.
- Memvalidasi absensi menggunakan GPS.
- Menghitung keterlambatan secara otomatis.
- Menyimpan lokasi absensi masuk dan pulang.
- Menyimpan foto selfie absensi masuk dan pulang.
- Menjadi fondasi untuk fitur izin, cuti, lembur, payroll, dan HRIS.

---

# Scope Pekerjaan

## Modul Baru

### 1. Master Lokasi Kantor

Buat menu:

```text
Master Data
└── Lokasi Kantor
```

### Fitur

- List Lokasi
- Tambah Lokasi
- Edit Lokasi
- Hapus Lokasi
- Aktif / Nonaktif Lokasi

### Field

| Field        | Type          |
| ------------ | ------------- |
| id           | bigint        |
| name         | string        |
| address      | text          |
| latitude     | decimal(10,8) |
| longitude    | decimal(11,8) |
| radius_meter | integer       |
| is_active    | boolean       |
| created_at   | timestamp     |
| updated_at   | timestamp     |

### Ketentuan

- Admin dapat menentukan radius absensi per lokasi.
- Latitude dan Longitude dapat diinput manual.
- Siapkan dukungan integrasi Google Maps pada tahap berikutnya.

---

## 2. Master Shift Kerja

Buat menu:

```text
Master Data
└── Shift Kerja
```

### Fitur

- List Shift
- Tambah Shift
- Edit Shift
- Hapus Shift

### Field

| Field                  | Type      |
| ---------------------- | --------- |
| id                     | bigint    |
| name                   | string    |
| start_time             | time      |
| end_time               | time      |
| late_tolerance_minutes | integer   |
| is_active              | boolean   |
| created_at             | timestamp |
| updated_at             | timestamp |

### Contoh

#### Shift Reguler

```text
Masuk : 08:00
Pulang : 17:00
Toleransi : 15 Menit
```

#### Shift Produksi

```text
Masuk : 07:00
Pulang : 16:00
Toleransi : 15 Menit
```

---

## 3. Relasi Pegawai

Tambahkan relasi pada tabel pegawai:

```sql
office_id
work_shift_id
```

### Relasi

```text
Pegawai
├── Lokasi Kantor
└── Shift Kerja
```

Setiap pegawai wajib memiliki:

- Lokasi Kantor
- Shift Kerja

---

# Refactor Modul Absensi

## Halaman Absensi

Saat pegawai membuka halaman absensi:

### Sistem wajib:

1. Meminta izin lokasi GPS perangkat.
2. Mengambil koordinat pegawai.
3. Mengambil data lokasi kantor pegawai.
4. Menghitung jarak ke kantor.
5. Memvalidasi radius.
6. Menentukan status absensi.

---

## Informasi Yang Ditampilkan

```text
Lokasi Kantor
Nama Lokasi

Koordinat Kantor
Latitude
Longitude

Radius Absensi

Lokasi Anda
Latitude
Longitude

Jarak Ke Kantor

Status Lokasi
✓ Dalam Area Kantor
atau
✕ Di Luar Area Kantor

Shift Kerja
08:00 - 17:00
```

---

# Validasi Geofencing

Gunakan Haversine Formula untuk menghitung jarak.

### Kondisi

Jika:

```text
jarak <= radius_meter
```

Maka:

```text
Status:
Dalam Area Kantor
```

Tombol absensi aktif.

Jika:

```text
jarak > radius_meter
```

Maka:

```text
Status:
Di Luar Area Kantor
```

Tombol absensi dinonaktifkan.

---

# Validasi Kehadiran

Contoh:

```text
Jam Masuk Shift
08:00

Toleransi
15 Menit
```

Maka:

| Jam Absen | Status    |
| --------- | --------- |
| 07:55     | Hadir     |
| 08:05     | Hadir     |
| 08:15     | Hadir     |
| 08:16     | Terlambat |

---

# Selfie Absensi

## Absen Masuk

Wajib:

- GPS aktif
- Foto selfie

## Absen Pulang

Wajib:

- GPS aktif
- Foto selfie

---

# Refactor Database Absensi

Tambahkan field:

```sql
office_id
work_shift_id

check_in_time
check_in_latitude
check_in_longitude
check_in_distance
check_in_photo

check_out_time
check_out_latitude
check_out_longitude
check_out_distance
check_out_photo

attendance_status

ip_address
user_agent
device_info
```

---

# Status Absensi

Minimal mendukung:

```text
Hadir
Terlambat
Izin
Sakit
Alpha
WFH
Dinas Luar
```

---

# Dashboard Admin

Buat menu:

```text
Absensi
├── Absensi Hari Ini
├── Riwayat Absensi
├── Rekap Absensi
└── Monitoring Kehadiran
```

---

# Detail Absensi

Tampilkan:

### Data Masuk

- Tanggal
- Jam Masuk
- Foto Masuk
- Latitude
- Longitude
- Jarak Ke Kantor

### Data Pulang

- Jam Pulang
- Foto Pulang
- Latitude
- Longitude
- Jarak Ke Kantor

---

# Rekap Kehadiran

Tampilkan statistik:

- Hadir
- Terlambat
- Izin
- Sakit
- Alpha
- WFH
- Dinas Luar

Filter:

- Harian
- Mingguan
- Bulanan
- Rentang Tanggal

---

# Seeder Data Awal

Sistem wajib membuat data awal agar fitur dapat langsung diuji setelah deployment.

## Lokasi Kantor

### Kantor Produksi

```text
Nama : Kantor Produksi
Radius : 100 Meter
```

### Kantor Studio

```text
Nama : Kantor Studio
Radius : 100 Meter
```

Latitude dan Longitude dapat diisi kemudian melalui Admin Panel.

---

## Shift Kerja

### Shift Reguler

```text
08:00 - 17:00
```

### Shift Produksi

```text
07:00 - 16:00
```

---

# Standar UI

Seluruh halaman Create dan Edit wajib menggunakan Modal sesuai standar refactor aplikasi.

Tidak diperbolehkan menggunakan halaman create/edit terpisah.

Standarisasi:

- Modal Create
- Modal Edit
- Modal Detail
- Konfirmasi Delete

---

# Kriteria Selesai

- Modul Lokasi Kantor selesai.
- Modul Shift Kerja selesai.
- Relasi Pegawai selesai.
- Geofencing berjalan normal.
- Validasi radius berjalan normal.
- Validasi keterlambatan berjalan normal.
- Foto selfie absensi tersimpan.
- Lokasi absensi masuk dan pulang tersimpan.
- Rekap absensi tersedia.
- Seeder data awal tersedia.
- Seluruh form menggunakan modal.
- Siap dikembangkan ke modul HRIS berikutnya.
