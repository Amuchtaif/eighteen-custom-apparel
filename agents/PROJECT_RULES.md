# agents/PROJECT_RULES.md

# Eighteen Development Rules

## Core Principle

Setiap perubahan kode harus:

- Konsisten
- Aman
- Dapat dipelihara
- Mudah dipahami

Jangan membuat solusi yang hanya bekerja untuk kasus tertentu.

---

# Tech Stack

Framework:
Laravel 12

Language:
PHP 8.4+

Admin Panel:
Filament v4

Frontend:
Livewire
AlpineJS
TailwindCSS

Database:
MySQL 8+

---

# Folder Rules

Business logic tidak boleh berada di:

- Controller
- Filament Resource

Business logic harus ditempatkan pada:

- Services
- Actions
- Domain Layer

---

# Controller Rules

Controller hanya bertugas:

- menerima request
- validasi request
- memanggil service
- mengembalikan response

---

# Model Rules

Model hanya berisi:

- relationship
- accessor
- mutator
- scope

Jangan menaruh business logic kompleks pada model.

---

# Service Rules

Seluruh proses bisnis wajib menggunakan Service Class.

Contoh:

OrderService

QuotationService

ProductionService

AttendanceService

PaymentService

---

# Database Rules

Gunakan:

- Foreign Key
- Index
- Unique Constraint

Hindari query mentah jika Eloquent sudah memadai.

---

# UI Rules

Ikuti DESIGN.md.

Tidak boleh membuat warna baru tanpa alasan bisnis.

---

# Security Rules

- Authorization wajib menggunakan Policy
- Validation wajib menggunakan FormRequest
- Password wajib hash
- Hindari mass assignment

---

# Logging Rules

Aktivitas penting wajib tercatat.

- Login
- Order
- Payment
- Production Status
- Attendance

---

# Performance Rules

- Gunakan eager loading
- Hindari N+1 query
- Gunakan pagination
- Cache data berat

---

# Documentation Rules

Setiap fitur baru wajib memperbarui:

- PRD
- ERD (jika ada perubahan struktur)
- API Documentation
- Testing Documentation
