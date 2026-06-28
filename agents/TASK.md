# TASK: Implementasi Modul Pesanan Produksi Apparel

## Latar Belakang

Saat ini monitoring produksi masih menggunakan Google Spreadsheet dengan alur tracking manual menggunakan checkbox.

Target implementasi adalah membuat modul **Pesanan Produksi** yang mampu menggantikan spreadsheet tersebut sehingga seluruh proses produksi dapat dimonitor langsung dari sistem ERP Eighteen.

---

# Tujuan

Membangun halaman **Pesanan Produksi** yang berfungsi sebagai dashboard monitoring seluruh order custom apparel mulai dari order masuk hingga selesai produksi.

---

# Menu

```text
Produksi
└── Pesanan Produksi
```

---

# Struktur Tabel Pesanan

## Kolom Utama

| Kolom            | Tipe        |
| ---------------- | ----------- |
| No               | Auto Number |
| Kode Pesanan     | String      |
| Nama Team        | String      |
| Nama Customer    | String      |
| Bahan            | Relation    |
| Tanggal Masuk    | Date        |
| Estimasi Selesai | Date        |
| Sisa Hari        | Calculated  |
| Deadline         | Date        |
| Paket            | Enum        |
| Qty              | Integer     |
| Print            | Boolean     |
| Press            | Boolean     |
| Pola             | Boolean     |
| Jahit            | Boolean     |
| Finish           | Boolean     |
| Status Produksi  | Enum        |

---

# Workflow Produksi

Alur produksi:

```text
Order Masuk
↓
Print
↓
Press
↓
Pola
↓
Jahit
↓
Finish
↓
Selesai
```

---

# Status Produksi

## Menunggu Produksi

Kondisi:

```text
Print = false
Press = false
Pola = false
Jahit = false
Finish = false
```

Status:

```text
Menunggu Produksi
```

---

## Sedang Produksi

Kondisi:

```text
Minimal satu proses selesai
```

Status:

```text
Proses Produksi
```

---

## Selesai

Kondisi:

```text
Print = true
Press = true
Pola = true
Jahit = true
Finish = true
```

Status:

```text
Selesai
```

---

# Perhitungan Sisa Hari

Rumus:

```php
$sisaHari = deadline - today();
```

Contoh tampilan:

```text
15 Hari
9 Hari
5 Hari
3 Hari
DANGER
OVERDUE
```

---

# Pewarnaan Otomatis

## Deadline Aman

Kondisi:

```text
Sisa Hari > 7
```

Warna:

```text
Biru
```

---

## Mendekati Deadline

Kondisi:

```text
Sisa Hari <= 7
```

Warna:

```text
Kuning
```

---

## Deadline Kritis

Kondisi:

```text
Sisa Hari <= 3
```

Warna:

```text
Orange
```

---

## Terlambat

Kondisi:

```text
Deadline lewat
```

Warna:

```text
Merah
```

---

# Paket Prioritas

Field:

```text
Reguler
Premium
Express
```

## Tampilan

Order Premium harus memiliki badge khusus dan lebih mudah terlihat pada tabel.

---

# Dashboard Summary

Tampilkan widget di atas tabel.

## Total Qty Produksi

```sql
SUM(qty)
```

Contoh:

```text
1129 PCS
```

---

## Total Order Aktif

```sql
COUNT(order)
```

---

## Sedang Produksi

Jumlah order dengan status:

```text
Proses Produksi
```

---

## Selesai

Jumlah order dengan status:

```text
Selesai
```

---

## Terlambat

Jumlah order yang melewati deadline.

---

## Premium

Jumlah order paket premium.

---

# Filter Data

Tambahkan filter berikut:

- Tanggal Masuk
- Deadline
- Paket
- Status Produksi
- Bahan
- Customer
- Lokasi Produksi

---

# Form Tambah Pesanan

## Informasi Pesanan

- Kode Pesanan
- Nama Team
- Nama Customer
- Nomor WhatsApp
- Tanggal Masuk
- Deadline
- Paket
- Qty
- Catatan Produksi

---

## Informasi Produksi

- Bahan
- Lokasi Produksi
- Designer
- Operator Print
- Penjahit
- QC

---

# Multi Lokasi Produksi

Karena Eighteen memiliki dua lokasi kerja:

```text
Kantor Produksi
Studio Desain
```

Tambahkan field:

| Field           | Tipe |
| --------------- | ---- |
| Lokasi Produksi | Enum |
| Designer        | User |
| Operator Print  | User |
| Penjahit        | User |
| QC              | User |

---

# Database

## Table orders

```sql
id
kode_order
customer_id
nama_team
tanggal_masuk
deadline
estimasi_selesai
paket
qty
status
catatan
lokasi_produksi
designer_id
operator_print_id
penjahit_id
qc_id
created_at
updated_at
```

---

## Table production_progress

```sql
id
order_id

print_status
print_at

press_status
press_at

pola_status
pola_at

jahit_status
jahit_at

finish_status
finish_at

created_at
updated_at
```

---

# Hak Akses

## Owner

- Full Access

## Super Admin

- Full Access

## Admin Produksi

- Create
- Edit
- Update Progress

## Operator

- Update Progress Tahapan Produksi

## Customer Service

- Input Order Baru
- View Status Produksi

---

# Fitur Tambahan

## Progress Produksi

Tampilkan progress bar otomatis:

```text
0/5
1/5
2/5
3/5
4/5
5/5
```

atau

```text
20%
40%
60%
80%
100%
```

---

## Riwayat Progress

Simpan histori setiap perubahan status:

```text
Print selesai oleh Fulan
Tanggal 20-06-2026

Press selesai oleh Ahmad
Tanggal 21-06-2026
```

---

# Target Hasil

Modul ini harus mampu menggantikan penggunaan spreadsheet monitoring produksi dan menjadi pusat kontrol produksi apparel Eighteen secara realtime mulai dari order masuk hingga order selesai.
