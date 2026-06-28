# TASK: Halaman Formulir Pemesanan Custom Apparel (Landing Page)

## Latar Belakang

Saat ini landing page belum memiliki halaman khusus untuk menerima pemesanan custom apparel dari calon pelanggan.

Sistem perlu menyediakan halaman formulir pemesanan yang terhubung langsung dari landing page agar calon pelanggan dapat mengirim kebutuhan custom apparel secara lengkap tanpa harus menghubungi admin terlebih dahulu.

Form ini mengikuti revisi skema pesanan terbaru yang telah disederhanakan, yaitu menggunakan **Pesanan Custom** dan tidak lagi menggunakan konsep kategori produk, produk, pesanan produksi, maupun penawaran.

---

# Tujuan

Membuat halaman baru pada landing page untuk menerima permintaan pemesanan custom apparel dari calon pelanggan dan menyimpan data langsung ke tabel `orders`.

---

# Scope Pekerjaan

## 1. Tambah Menu Landing Page

Tambahkan tombol CTA pada landing page:

- Pesan Sekarang
- Konsultasi Desain
- Buat Pesanan

Arahkan ke:

```text
/custom-order
```

---

## 2. Buat Halaman Form Pemesanan

Route:

```php
/custom-order
```

Halaman berisi form pemesanan custom apparel.

---

## 3. Field Form Pemesanan

### Data Pemesan

| Field                     | Tipe             |
| ------------------------- | ---------------- |
| Nama Pemesan              | Text             |
| Nama Instansi / Komunitas | Text             |
| Nomor WhatsApp            | Text             |
| Email                     | Email (optional) |
| Kota / Kabupaten          | Text             |

---

### Informasi Pesanan

| Field             | Tipe     |
| ----------------- | -------- |
| Nama Pesanan      | Text     |
| Jenis Apparel     | Select   |
| Jumlah Pesanan    | Number   |
| Target Penggunaan | Date     |
| Budget Perkiraan  | Currency |
| Catatan Kebutuhan | Textarea |

Pilihan Jenis Apparel:

- Kaos
- Polo Shirt
- Kemeja
- Jersey
- Hoodie
- Jaket
- Rompi
- Topi
- Lainnya

Jika memilih **Lainnya**, tampilkan field tambahan:

```text
Jenis Apparel Lainnya
```

---

### Upload Referensi

Field upload multiple:

- Logo
- Referensi Desain
- Referensi Warna
- File Pendukung

Format:

- JPG
- JPEG
- PNG
- PDF
- AI
- CDR

Maksimal:

```text
10 MB / file
```

---

## 4. Validasi Form

Field wajib:

- Nama Pemesan
- Nomor WhatsApp
- Nama Pesanan
- Jenis Apparel
- Jumlah Pesanan

Validasi:

- Nomor WA harus valid
- Jumlah pesanan minimal 1 pcs
- Tanggal penggunaan tidak boleh tanggal lampau

---

## 5. Penyimpanan Data

Saat submit:

Buat data baru pada tabel:

```text
orders
```

Status otomatis:

```text
Menunggu Review
```

Source:

```text
Landing Page
```

Nomor pesanan otomatis:

```text
ORD-YYYYMM-XXXX
```

Contoh:

```text
ORD-202606-0001
```

---

## 6. Halaman Sukses

Setelah submit berhasil:

Redirect ke halaman:

```text
/custom-order/success
```

Isi halaman:

### Terima Kasih

Pesanan Anda telah kami terima dan sedang menunggu review tim kami.

Nomor Pesanan:

```text
ORD-202606-0001
```

Tampilkan tombol:

- Kembali ke Beranda
- Hubungi Admin WhatsApp

---

## 7. Integrasi WhatsApp

Setelah submit berhasil:

Generate tombol WhatsApp dengan format:

```text
Halo Admin,

Saya telah mengirim formulir pemesanan custom apparel.

Nomor Pesanan:
{nomor_pesanan}

Nama:
{nama_pemesan}

Mohon informasi tahap selanjutnya.
```

---

## 8. Dashboard Admin

Pastikan seluruh pesanan yang masuk dari landing page otomatis muncul pada:

```text
Pesanan → Semua Pesanan
```

Dengan informasi:

- Nomor Pesanan
- Nama Pemesan
- Nama Pesanan
- Jenis Apparel
- Qty
- Status
- Source

Source harus tampil:

```text
Landing Page
```

---

## 9. Upload File Pesanan

Tambahkan relasi file pesanan agar seluruh file referensi yang diupload customer dapat dilihat pada halaman detail pesanan.

File yang tersimpan:

- Nama File
- Tipe File
- Ukuran
- Tanggal Upload

Admin dapat:

- Preview
- Download

---

## 10. Penghapusan Fitur Lama

Pastikan form pemesanan baru menggunakan skema refactor terbaru.

Hapus seluruh dependensi terhadap:

- Kategori Produk
- Produk
- Pesanan Produksi
- Penawaran

Seluruh pesanan baru harus langsung tersimpan sebagai:

```text
Pesanan Custom
```

tanpa melalui modul-modul lama tersebut.

---

# Acceptance Criteria

- Halaman `/custom-order` dapat diakses dari landing page.
- Customer dapat mengirim pesanan custom apparel tanpa login.
- Data tersimpan ke tabel `orders`.
- File referensi tersimpan dengan baik.
- Nomor pesanan otomatis tergenerate.
- Source pesanan tercatat sebagai `Landing Page`.
- Admin dapat melihat detail pesanan dan file lampiran.
- Halaman sukses dan tombol WhatsApp berfungsi.
- Tidak ada ketergantungan dengan modul produk, kategori produk, pesanan produksi, maupun penawaran.
