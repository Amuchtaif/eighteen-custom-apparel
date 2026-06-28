# agents/DATABASE.md

# Eighteen Database Standards

## Database Engine

MySQL 8+

Character Set:

utf8mb4

Collation:

utf8mb4_unicode_ci

---

# Naming Convention

## Table

Gunakan:

snake_case

Contoh:

users
customers
order_items

---

## Column

Gunakan:

snake_case

Contoh:

customer_name
order_number
created_at

---

# Primary Key

Semua tabel:

id BIGINT UNSIGNED AUTO_INCREMENT

---

# Foreign Key

Format:

table_name_id

Contoh:

customer_id
order_id
invoice_id

---

# Timestamp Rules

Seluruh tabel wajib memiliki:

created_at
updated_at

Kecuali tabel pivot sederhana.

---

# Soft Delete

Gunakan:

deleted_at

Pada tabel:

users
customers
products
orders
employees

---

# Status Convention

Gunakan ENUM atau Constant Class.

Jangan menggunakan status bebas.

Contoh:

quotation_status

draft
sent
approved
rejected

---

# Order Number Format

ORD-YYYYMM-00001

Contoh:

ORD-202606-00001

---

# Quotation Number Format

QUO-YYYYMM-00001

---

# Invoice Number Format

INV-YYYYMM-00001

---

# Employee Code Format

EMP-00001

---

# Data Integrity Rules

Tidak boleh:

- orphan data
- duplicate order number
- duplicate invoice number

Harus menggunakan:

- foreign key
- unique constraint
- index

---

# Required Indexes

users

- email
- phone

customers

- company_name
- phone

orders

- order_number
- customer_id
- status

invoices

- invoice_number
- order_id

payments

- invoice_id

---

# File Storage Rules

Design Files:

storage/app/private/designs

Payment Proof:

storage/app/private/payments

Attendance Selfie:

storage/app/private/attendance

---

# Activity Log Rules

Semua perubahan penting wajib dicatat.

Minimal menyimpan:

user_id
module
action
description
created_at

---

# Security Rules

Password:

bcrypt atau argon2id

Tidak boleh menyimpan password plaintext.

---

# Migration Rules

Satu migration untuk satu tabel.

Contoh:

create_users_table
create_customers_table
create_orders_table

---

# Seeder Rules

Wajib tersedia:

RoleSeeder
UserSeeder
ProductionStageSeeder

---

# Default Roles

Super Admin

Owner

Sales

Production

HRD

Finance

Customer

---

# Production Stage Seeder

Draft

Design

Customer Approval

Printing

Cutting

Sewing

QC

Packing

Shipping

Completed
