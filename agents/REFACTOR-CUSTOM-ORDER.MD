# TASK: Refactor Modul Pesanan Menjadi Pesanan Produksi Custom Apparel

## Latar Belakang

Struktur sistem saat ini masih menggunakan konsep:

- Kategori Produk
- Produk
- Penawaran
- Pesanan Produksi

Setelah evaluasi proses bisnis Custom Apparel Eighteen, ditemukan bahwa mayoritas pesanan bersifat custom dan tidak menggunakan katalog produk tetap.

Contoh pesanan:

- Team Futsal
- Team Basket
- Komunitas Motor
- Event Organizer
- Sekolah
- Instansi

Karena itu pendekatan berbasis produk dan penawaran tidak lagi relevan untuk kebutuhan operasional saat ini dan justru memperumit proses input data.

Sistem perlu direfactor menjadi berpusat pada **Pesanan Produksi Custom Apparel**.

---

# Tujuan

Menyederhanakan proses input pesanan agar lebih sesuai dengan operasional workshop produksi.

Fokus sistem berubah dari:

```text
Kategori Produk
    ↓
Produk
    ↓
Penawaran
    ↓
Pesanan Produksi
```

Menjadi:

```text
Pesanan
    ↓
Tracking Produksi
    ↓
Pembayaran
```

---

# Scope Pekerjaan

## 1. Hapus Seluruh Modul Lama

### Hapus Menu

- Kategori Produk
- Produk
- Penawaran
- Pesanan Produksi

### Hapus Halaman

- List Kategori Produk

- Create Kategori Produk

- Edit Kategori Produk

- List Produk

- Create Produk

- Edit Produk

- List Penawaran

- Create Penawaran

- Edit Penawaran

- List Pesanan Produksi Lama

- Create Pesanan Produksi Lama

- Edit Pesanan Produksi Lama

### Hapus Controller

- ProductCategoryController
- ProductController
- QuotationController
- ProductionOrderController

atau controller lain yang berkaitan.

### Hapus Model

- ProductCategory
- Product
- Quotation
- ProductionOrder

atau model lain yang sudah tidak digunakan.

### Hapus Migration dan Relasi Lama

Pastikan seluruh foreign key dan relasi berikut dibersihkan:

```text
product_category_id
product_id
quotation_id
production_order_id
```

Hapus seluruh tabel yang tidak lagi digunakan:

```text
product_categories
products
quotations
production_orders
```

beserta tabel detail turunannya apabila ada.

### Hapus Route

Pastikan seluruh route lama dibersihkan agar tidak menghasilkan error 404 atau menu mati.

### Hapus Menu Sidebar

Pastikan menu berikut sudah tidak muncul:

- Kategori Produk
- Produk
- Penawaran
- Pesanan Produksi

---

# Struktur Baru Sistem

## Menu Yang Dipertahankan

### Dashboard

### Master Paket

### Pesanan

---

# 2. Buat Modul Master Paket

## Menu

Master Data → Paket

### Field

| Field        | Tipe    |
| ------------ | ------- |
| Nama Paket   | String  |
| Deskripsi    | Text    |
| Status Aktif | Boolean |

### Contoh Data

```text
Starter
Premium
Exclusive
```

### Fitur

- Tambah Paket
- Edit Paket
- Hapus Paket
- Aktif / Nonaktif Paket

---

# 3. Buat Modul Pesanan Produksi Custom Apparel

## Menu

Pesanan

### Field

| Field             | Tipe             |
| ----------------- | ---------------- |
| Nomor Pesanan     | Auto Generate    |
| Nama Team         | String           |
| Bahan             | String           |
| Tanggal Masuk     | Date             |
| Qty Esti          | Integer          |
| Qty Tink          | Integer          |
| Deadline          | Date             |
| Paket             | Relasi Paket     |
| Total Tagihan     | Decimal          |
| DP                | Decimal          |
| Sisa Tagihan      | Decimal Otomatis |
| Status Pembayaran | Otomatis         |
| Status Produksi   | Enum             |
| Catatan           | Text             |

---

# 4. Nomor Pesanan Otomatis

Format:

```text
ORD-YYYYMM-XXXX
```

Contoh:

```text
ORD-202606-0001
ORD-202606-0002
```

Nomor urut reset setiap bulan.

---

# 5. Status Pembayaran

## Enum

```text
Belum DP
DP
Lunas
```

## Rules

### Belum DP

Jika:

```text
DP = 0
```

### DP

Jika:

```text
DP > 0
dan
DP < Total Tagihan
```

### Lunas

Jika:

```text
Sisa Tagihan = 0
```

Status dihitung otomatis oleh sistem.

---

# 6. Perhitungan Otomatis

## Sisa Tagihan

Rumus:

```text
Total Tagihan - DP
```

Field readonly dan tidak dapat diedit manual.

---

# 7. Status Produksi

## Enum

```text
Antrian
Print
Press
Pola
Jahit
Finish
Siap Kirim
Selesai
```

Default:

```text
Antrian
```

---

# 8. Tampilan Tabel Pesanan

Kolom yang ditampilkan:

| Kolom              |
| ------------------ |
| Nomor Pesanan      |
| Nama Team          |
| Bahan              |
| Qty Esti           |
| Qty Tink           |
| Paket              |
| Total Tagihan      |
| Status Pembayaran  |
| Status Produksi    |
| Deadline           |
| Sisa Hari Deadline |

---

# 9. Badge Status Produksi

| Status     | Warna   |
| ---------- | ------- |
| Antrian    | Gray    |
| Print      | Blue    |
| Press      | Purple  |
| Pola       | Orange  |
| Jahit      | Yellow  |
| Finish     | Green   |
| Siap Kirim | Emerald |
| Selesai    | Teal    |

---

# 10. Deadline Monitoring

Tambahkan kolom:

```text
Sisa Hari
```

Contoh tampilan:

```text
5 Hari Lagi
Besok
Hari Ini
Terlambat 2 Hari
```

### Warna

Hijau:

- > 3 Hari

Kuning:

- ≤ 3 Hari

Merah:

- Deadline lewat

---

# 11. Dashboard

## Widget Ringkasan

Tampilkan:

- Total Pesanan Aktif
- Total Pesanan Selesai
- Total Omset
- Total Piutang

---

## Widget Status Produksi

Tampilkan jumlah pesanan berdasarkan status:

```text
Antrian
Print
Press
Pola
Jahit
Finish
Siap Kirim
Selesai
```

---

## Widget Deadline Terdekat

Menampilkan maksimal 10 pesanan dengan deadline paling dekat.

Urut berdasarkan deadline ascending.

---

# Ketentuan UI

- Seluruh form Create dan Edit menggunakan Modal.
- Gunakan format Rupiah untuk nominal.
- Gunakan datepicker untuk tanggal.
- Gunakan badge warna untuk status pembayaran dan status produksi.
- Responsive desktop dan mobile.
- Tabel mendukung pencarian dan filter status.

---

# Acceptance Criteria

## Cleanup Sistem Lama

- Modul Kategori Produk berhasil dihapus.
- Modul Produk berhasil dihapus.
- Modul Penawaran berhasil dihapus.
- Modul Pesanan Produksi Lama berhasil dihapus.
- Seluruh tabel lama berhasil dibersihkan.
- Seluruh relasi lama berhasil dibersihkan.
- Tidak ada menu mati atau route rusak.

## Sistem Baru

- Master Paket berfungsi normal.
- Pesanan dapat dibuat tanpa produk.
- Nomor pesanan otomatis.
- Status pembayaran otomatis.
- Sisa tagihan otomatis.
- Status produksi dapat diperbarui.
- Dashboard menampilkan data produksi dan keuangan.
- Input pesanan dapat dilakukan dalam satu form sederhana.
- Sistem lebih sederhana dan berfokus pada alur bisnis custom apparel.
