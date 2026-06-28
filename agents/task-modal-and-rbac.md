# TASK REFACTOR APLIKASI - MODAL CRUD & ROLE BASED DASHBOARD

## Objective

Melakukan refactor aplikasi agar:

1. Seluruh proses CRUD menggunakan Modal (Single Page Experience).
2. Menghilangkan halaman Create, Edit, dan Detail terpisah.
3. Menggunakan AJAX untuk seluruh proses CRUD.
4. Menerapkan redirect dashboard berdasarkan jabatan pengguna.
5. Mengoptimalkan pengalaman pengguna desktop maupun mobile.

---

# BAGIAN 1 - REFACTOR CRUD MENJADI MODAL

## Scope

Terapkan pada seluruh modul yang memiliki fitur:

- Create Data
- Edit Data
- Detail Data
- Delete Data

Contoh modul:

- User Management
- Role Management
- Customer
- Supplier
- Produk
- Kategori
- Inventory
- Produksi
- Penjualan
- Pembelian
- Purchase Order
- Sales Order
- Pengiriman
- Pembayaran
- Keuangan
- Absensi
- Karyawan
- Laporan
- Dan seluruh modul lainnya

---

## 1. Hilangkan Halaman Create

Saat tombol:

```html
Tambah Data Create New
```

diklik:

JANGAN redirect ke:

```php
/module/create
```

Harus membuka modal.

Contoh flow:

```text
Klik Tambah
↓
Modal Create Muncul
↓
Input Data
↓
Simpan AJAX
↓
Success
↓
Refresh Table
```

---

## 2. Hilangkan Halaman Edit

Saat tombol Edit ditekan:

```php
/module/{id}/edit
```

Jangan pindah halaman.

Harus:

```text
Klik Edit
↓
Load Data AJAX
↓
Isi Form Otomatis
↓
Tampilkan Modal Edit
↓
Simpan AJAX
```

---

## 3. Hilangkan Halaman Detail

Semua halaman:

```php
/show
/view
/detail
```

diubah menjadi:

```text
Detail Modal
```

Menampilkan:

- Informasi lengkap
- Relasi data
- Status
- Riwayat aktivitas
- Catatan

Tanpa berpindah halaman.

---

## 4. Gunakan Reusable Modal Component

Buat component global:

```php
resources/views/components/crud-modal.blade.php
```

Parameter:

```php
title
size
formAction
method
content
```

Digunakan oleh seluruh modul.

---

## 5. AJAX CRUD

Seluruh Create dan Edit wajib menggunakan AJAX.

Tidak boleh reload halaman.

Flow:

```text
Open Modal
↓
Input Data
↓
Submit AJAX
↓
Validation
↓
Success Toast
↓
Close Modal
↓
Reload Table
```

---

## 6. Validation Error Dalam Modal

Error harus muncul langsung pada field terkait.

Contoh:

```html
<input />

<div class="text-danger">Nama wajib diisi</div>
```

Tidak boleh redirect kembali ke halaman form.

---

## 7. DataTable Refresh

Setelah Create/Edit/Delete berhasil:

```javascript
table.ajax.reload();
```

atau

```javascript
datatable.refresh();
```

Tanpa reload halaman.

---

## 8. Standardisasi Ukuran Modal

### Small

```text
400px
```

### Medium

```text
600px
```

Untuk:

- Master Data
- User
- Kategori
- Supplier

### Large

```text
800px
```

Untuk:

- Produk
- Karyawan
- Inventory

### XL

```text
1000px+
```

Untuk:

- Sales Order
- Purchase Order
- Produksi
- Pengiriman
- Transaksi Multi Item

---

## 9. Multi Item Form

Untuk transaksi:

- Sales Order
- Purchase Order
- Produksi
- Pengiriman

Gunakan:

```text
Modal XL
```

Fitur:

- Dynamic Row
- Add Item
- Remove Item
- Auto Calculate
- Auto Subtotal
- Auto Grand Total

Tanpa berpindah halaman.

---

## 10. Responsive Mobile

Modal harus mobile friendly.

Gunakan:

```css
width: 95%;
max-height: 90vh;
overflow-y: auto;
```

Support:

- Desktop
- Tablet
- Mobile

---

## 11. Delete Confirmation

Semua delete menggunakan modal konfirmasi.

Bukan halaman terpisah.

Contoh:

```text
Yakin ingin menghapus data ini?
```

Tombol:

```text
Batal
Hapus
```

---

## 12. Cleanup Route

Hilangkan route lama:

```php
/create
/edit
/show
/view
```

Ubah menjadi endpoint AJAX:

```php
GET    /module
GET    /module/{id}
POST   /module
PUT    /module/{id}
DELETE /module/{id}
```

---

# BAGIAN 2 - ROLE BASED DASHBOARD REDIRECT

## Objective

Mengatur dashboard berdasarkan jabatan pengguna.

---

## Rule Dashboard

### Owner

Redirect ke:

```php
/dashboard
```

---

### Super Admin

Redirect ke:

```php
/dashboard
```

---

### Selain Owner dan Super Admin

Contoh:

```text
Admin
Manager
Supervisor
Kasir
Gudang
Produksi
Staff
Karyawan
Operator
Customer Service
```

Redirect ke:

```php
Route::get(
    'employee/dashboard',
    [EmployeeController::class, 'dashboard']
)->name('employee.dashboard');
```

---

## Login Flow

```text
Login
↓
Auth Success
↓
Cek Jabatan
↓
Owner / Super Admin ?
├── Ya
│   └── /dashboard
│
└── Tidak
    └── /employee/dashboard
```

---

## Middleware Dashboard

Proteksi dashboard utama.

Jika user bukan:

```text
Owner
Super Admin
```

dan mengakses:

```php
/dashboard
```

Maka:

```php
redirect()->route('employee.dashboard');
```

---

## Employee Dashboard Access

```php
Route::middleware(['auth'])
    ->group(function () {

        Route::get(
            'employee/dashboard',
            [EmployeeController::class, 'dashboard']
        )->name('employee.dashboard');

    });
```

---

## Navigation Menu Rules

### Owner & Super Admin

Tampilkan:

- Dashboard
- Master Data
- User Management
- Role Management
- Produksi
- Inventory
- Penjualan
- Pembelian
- Keuangan
- Laporan
- Pengaturan Sistem

---

### Karyawan

Tampilkan hanya menu sesuai hak akses.

Sembunyikan:

- User Management
- Role Management
- Pengaturan Sistem
- Konfigurasi
- Menu sensitif lainnya

---

## Employee Dashboard (Mobile First)

Dashboard karyawan wajib dirancang mobile-first.

Prioritas widget:

### Absensi

- Check In
- Check Out

### Tugas Hari Ini

- Daftar tugas
- Status progres

### Produksi Hari Ini

- Target
- Realisasi

### Pengajuan

- Izin
- Cuti
- Lembur

### Aktivitas Terakhir

- Riwayat pekerjaan
- Notifikasi

### Quick Action

- Absen
- Input Produksi
- Pengajuan Izin
- Lihat Jadwal

Gunakan card besar dan tombol yang mudah diakses dari smartphone.

---

# BAGIAN 3 - STANDARISASI IMPLEMENTASI

## Service Redirect

Buat service:

```php
app/Services/RoleRedirectService.php
```

Fungsi:

```php
getDashboardRoute(User $user)
```

Mengembalikan:

```php
/dashboard
```

atau

```php
/employee/dashboard
```

---

## Konfigurasi Role

JANGAN hardcode role di banyak file.

Buat:

```php
config/roles.php
```

Contoh:

```php
return [

    'admin_roles' => [
        'Owner',
        'Super Admin',
    ],

];
```

Penggunaan:

```php
in_array(
    $user->jabatan,
    config('roles.admin_roles')
);
```

---

## Refactor Requirement

Pastikan:

- Tidak ada halaman Create.
- Tidak ada halaman Edit.
- Tidak ada halaman Detail.
- Seluruh CRUD berjalan dalam modal.
- AJAX digunakan untuk seluruh operasi CRUD.
- Dashboard otomatis menyesuaikan jabatan.
- Dashboard karyawan mobile-first.
- Hak akses konsisten melalui middleware, menu, policy, dan service.
- UI/UX lebih cepat tanpa reload halaman.

---

# EXPECTED RESULT

```text
DATA TABLE
     ↓
CREATE MODAL
     ↓
EDIT MODAL
     ↓
DETAIL MODAL
     ↓
DELETE MODAL
```

Tanpa halaman CRUD terpisah.

Serta:

```text
Owner
        → /dashboard

Super Admin
        → /dashboard

Semua Jabatan Lain
        → /employee/dashboard
```

Seluruh aplikasi harus memiliki pengalaman seperti aplikasi ERP modern: cepat, responsif, mobile-friendly, konsisten, dan minim perpindahan halaman.
