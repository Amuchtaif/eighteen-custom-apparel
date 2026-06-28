# Task: Hapus Sistem Login Customer dan Seluruh Modul Autentikasi Customer

## Latar Belakang

Sistem saat ini masih memiliki skema customer account yang memungkinkan pelanggan melakukan login ke aplikasi.

Setelah evaluasi kebutuhan bisnis, fitur login customer tidak diperlukan karena pelanggan hanya menggunakan website untuk melakukan pemesanan dan tracking pesanan. Fitur tracking pesanan sudah tersedia dan tetap dipertahankan.

Tujuan task ini adalah menghapus seluruh fitur autentikasi customer beserta halaman yang terkait, tanpa mengubah fitur tracking pesanan yang sudah berjalan.

---

# Tujuan

- Menghapus login customer.
- Menghapus registrasi customer.
- Menghapus dashboard customer.
- Menghapus role customer dari sistem.
- Menghapus seluruh route dan halaman customer authentication.
- Memastikan fitur tracking pesanan tetap berjalan normal.

---

# Scope Pekerjaan

## 1. Hapus Halaman Customer Authentication

Hapus seluruh halaman berikut:

```text
/customer/login
/customer/register
/customer/forgot-password
/customer/reset-password
```

Hapus seluruh view, controller, middleware, dan route yang terkait.

---

## 2. Hapus Dashboard Customer

Jika terdapat dashboard customer:

```text
/customer/dashboard
```

hapus seluruh fitur berikut:

- Dashboard Customer
- Profil Customer
- Riwayat Pesanan Customer
- Pengaturan Akun Customer

Beserta seluruh menu navigasinya.

---

## 3. Hapus Menu Login Customer dari Landing Page

Cari seluruh tombol/menu berikut:

```text
Login
Masuk
Register
Daftar
Akun Saya
Dashboard Customer
```

dan hapus dari:

- Header
- Mobile Menu
- Footer
- CTA Section
- Landing Page

---

## 4. Hapus Role Customer

Jika menggunakan sistem role & permission:

hapus role:

```text
Customer
```

Pastikan hanya role internal yang tersisa:

- Owner
- Super Admin
- Admin
- Sales
- Produksi
- Keuangan
- HRD

---

## 5. Refactor Middleware dan Guard

Hapus:

- customer guard
- customer middleware
- customer auth session

Pastikan hanya user internal yang dapat login ke sistem admin.

---

## 6. Evaluasi Database

Jika terdapat tabel autentikasi customer yang tidak digunakan lagi:

Contoh:

```sql
customers
customer_users
customer_accounts
```

hapus tabel dan relasinya.

Jika data customer masih digunakan sebagai data pesanan, pertahankan data tersebut namun lepaskan dari mekanisme login.

---

## 7. Pertahankan Fitur Tracking Pesanan

JANGAN mengubah:

- Halaman tracking pesanan
- Form tracking pesanan
- Detail tracking pesanan
- Status produksi
- Link tracking pesanan

Tracking tetap berjalan seperti implementasi saat ini.

---

## 8. Restriksi Login Sistem

Setelah refactor:

Yang dapat login hanya user internal perusahaan.

Contoh:

```text
Owner
Super Admin
Admin
Sales
Produksi
Keuangan
HRD
```

Pelanggan tidak memiliki akun dan tidak memiliki akses ke panel sistem.

---

# Testing

## Validasi

- Halaman login customer sudah tidak dapat diakses.
- Halaman register customer sudah tidak dapat diakses.
- Dashboard customer sudah tidak dapat diakses.
- Menu login/register sudah hilang dari landing page.
- Tracking pesanan tetap berfungsi normal.
- Login admin/internal tetap berjalan normal.
- Tidak ada role customer di sistem.

---

# Hasil Akhir

Alur sistem berubah dari:

```text
Customer
→ Register
→ Login
→ Dashboard
→ Tracking Pesanan
```

Menjadi:

```text
Customer
→ Pesan Produk
→ Dapat Nomor Pesanan
→ Tracking Pesanan
```

Tanpa akun customer dan tanpa dashboard customer.
