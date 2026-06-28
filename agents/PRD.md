# agents/PRD.md

# Product Requirement Document

## Product Name

Eighteen

## Product Type

ERP & Order Management System untuk bisnis Custom Clothing, Sportswear, Jersey, dan Apparel Custom.

---

# Problem Statement

Saat ini proses operasional custom clothing masih banyak dilakukan secara manual menggunakan:

- WhatsApp
- Spreadsheet
- Catatan manual
- Grup Produksi

Dampak:

- Order hilang
- Revisi desain tidak terdokumentasi
- Progress produksi sulit dipantau
- Customer sering menanyakan status pesanan
- Kesalahan ukuran dan nomor punggung
- Monitoring pegawai tidak terintegrasi

Eighteen hadir untuk menjadi pusat operasional yang menghubungkan customer, sales, produksi, HRD, dan owner dalam satu sistem.

---

# Product Goals

## Business Goals

- Meningkatkan efisiensi operasional
- Mengurangi human error
- Mempercepat proses produksi
- Meningkatkan transparansi kepada customer
- Memudahkan monitoring bisnis

---

## User Goals

Admin:

- Mengelola order dengan cepat

Produksi:

- Mengetahui pekerjaan yang harus dikerjakan

HRD:

- Mengelola absensi pegawai

Owner:

- Memantau performa bisnis

Customer:

- Melihat progress pesanan secara real-time

---

# User Roles

## Super Admin

Hak akses penuh.

---

## Owner

Akses seluruh laporan dan monitoring.

---

## Sales

- Customer
- Quotation
- Orders

---

## Production

- Design
- Printing
- Cutting
- Sewing
- QC
- Packing

---

## HRD

- Employee
- Attendance

---

## Finance

- Invoice
- Payment

---

## Customer

- Tracking Order
- Invoice
- Payment History

---

# Core Modules

## CRM

### Features

- Prospects
- Customers
- Customer Notes
- Customer History

---

## Quotation

### Features

- Create Quotation
- Edit Quotation
- Approve Quotation
- Convert To Order

---

## Order Management

### Features

- Create Order
- Update Order
- Cancel Order
- Upload Design
- Deadline Management

---

## Player Management

Digunakan untuk order jersey.

### Features

- Player Name
- Back Number
- Size
- Notes

---

## Production Tracking

Workflow:

Draft

↓

Design

↓

Customer Approval

↓

Printing

↓

Cutting

↓

Sewing

↓

QC

↓

Packing

↓

Shipping

↓

Completed

---

Setiap perubahan status wajib tersimpan dalam activity log.

---

## Attendance Management

### Features

- Check In
- Check Out
- QR Attendance
- GPS Validation
- Selfie Validation
- Attendance Recap

---

## Employee Management

### Features

- Employee Data
- Position
- Department
- Status

---

## Finance

### Features

- Invoice
- Down Payment
- Full Payment
- Payment History

---

## Customer Portal

### Features

- View Orders
- Production Tracking
- Payment History
- Download Invoice

---

# Dashboard Requirements

## Owner Dashboard

Menampilkan:

- Total Revenue
- Total Orders
- Active Orders
- Completed Orders
- Employee Attendance
- Outstanding Payments

---

## Sales Dashboard

Menampilkan:

- Leads
- Quotations
- Orders

---

## Production Dashboard

Menampilkan:

- Today's Tasks
- Active Production
- Deadlines

---

## HR Dashboard

Menampilkan:

- Employee Count
- Attendance Summary

---

# Notifications

## Internal

- New Order
- Status Changed
- Payment Received

---

## Customer

- Order Created
- Design Approved
- Production Started
- Shipping Started
- Order Completed

---

# Reports

## Sales Report

- Revenue
- Orders
- Customer Growth

---

## Production Report

- Production Performance
- Delayed Orders

---

## Attendance Report

- Daily
- Weekly
- Monthly

---

# Non Functional Requirements

## Security

- Role Based Access Control
- Activity Logs
- Password Hashing
- CSRF Protection

---

## Performance

- Page load < 3 seconds
- Pagination mandatory
- Optimized database queries

---

## Scalability

System harus mampu menangani:

- 10.000+ customers
- 50.000+ orders
- 500+ employees

---

# MVP Scope

Versi 1.0 hanya mencakup:

- Authentication
- Role Management
- Customer Management
- Quotation
- Order Management
- Player Management
- Production Tracking
- Attendance
- Finance
- Customer Portal
- Reporting Dashboard

---

# Success Metrics

- Pengurangan order yang terlambat
- Pengurangan kesalahan produksi
- Peningkatan transparansi order
- Pengurangan komunikasi manual terkait status pesanan
- Peningkatan efisiensi operasional
