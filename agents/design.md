# agents/DESIGN.md

# Eighteen Design System

## Product Name

Eighteen

ERP & Order Management System untuk bisnis Custom Clothing, Jersey Sportswear, Apparel Komunitas, dan Konveksi Custom.

---

# Product Vision

Eighteen dibangun sebagai pusat operasional bisnis custom apparel yang mengintegrasikan:

- CRM Customer
- Quotation
- Order Management
- Production Tracking
- Employee Attendance
- Finance Tracking
- Customer Portal
- Reporting System

Tujuan utama sistem adalah menghilangkan proses manual, mengurangi human error, dan meningkatkan efisiensi operasional.

---

# Design Philosophy

Prinsip desain Eighteen:

- Modern
- Professional
- Fast
- Clean
- Mobile Friendly
- Easy To Learn
- Data Driven

Desain harus mendukung aktivitas operasional harian tanpa mengganggu produktivitas pengguna.

---

# Brand Identity

Eighteen merupakan brand apparel modern yang menggabungkan:

- Performance
- Speed
- Precision
- Professionalism

Visual harus merepresentasikan:

- Sportswear Industry
- Manufacturing Process
- Technology Platform
- Team Collaboration

Design Direction:

Industrial Sports Tech

---

# Brand Assets

Asset resmi yang digunakan:

- Main Logo
- Favicon

Seluruh desain wajib mengikuti identitas visual logo resmi Eighteen.

Jangan membuat identitas visual baru yang bertentangan dengan logo.

---

# Logo Usage

## Landing Page

Logo tampil pada:

- Navbar
- Hero Section
- Footer

---

## Dashboard

Logo tampil pada:

- Sidebar
- Login Page
- Loading Screen

---

## Customer Portal

Logo tampil pada:

- Header
- Login Page
- Invoice

---

## Authentication Pages

- Login
- Register
- Forgot Password
- Reset Password

Wajib menampilkan logo resmi.

---

# Logo Variants

## Primary Logo

Logo biru dengan wordmark putih.

Digunakan untuk:

- Hero Section
- Landing Page
- Marketing Material

---

## Dark Background Version

Background:

#081223

---

## Light Background Version

Background:

#FFFFFF

---

# Logo Clear Space

Minimum clear space:

1x tinggi huruf "E"

Tidak boleh ada elemen lain yang memasuki area tersebut.

---

# Minimum Logo Size

Desktop:

200px

Mobile:

120px

Favicon:

32x32

48x48

64x64

---

# Favicon Rules

Gunakan favicon resmi pada:

- Landing Page
- Dashboard
- Customer Portal

Jangan menggunakan favicon bawaan framework.

---

# Color Palette

## Primary

Eighteen Blue

#1E90FF

---

## Primary Dark

Deep Navy

#0A1A3A

---

## Secondary

Bright Blue

#007BFF

---

## Accent

Sky Blue

#36A3FF

---

## Success

#22C55E

---

## Warning

#F59E0B

---

## Danger

#EF4444

---

## Background Dark

#081223

---

## Background Light

#F8FAFC

---

## Text Primary

#0F172A

---

## Text Secondary

#64748B

---

## Border

#E2E8F0

---

# Typography

Primary Font:

Inter

Fallback:

sans-serif

---

# Border Radius

Card:
12px

Input:
10px

Button:
10px

Badge:
999px

Modal:
16px

---

# Shadow System

Gunakan shadow ringan.

Hindari:

- Neumorphism
- Glassmorphism berlebihan
- Glow effect
- Neon effect

Fokus pada tampilan profesional dan mudah dibaca.

---

# Layout Structure

## Landing Page

Menu:

- Home
- Products
- Portfolio
- Pricing
- Tracking Order
- Contact

CTA:

- Order Sekarang
- Konsultasi Desain

---

# Hero Section

Komposisi:

Kiri:

- Headline
- Subheadline
- CTA Primary
- CTA Secondary
- Statistics

Kanan:

- Jersey Mockup
- Dashboard Preview
- Production Tracking Preview

---

Contoh Headline:

CUSTOM APPAREL WITHOUT THE CHAOS

Kelola Order, Produksi, dan Absensi Pegawai Dalam Satu Sistem.

---

# Dashboard Structure

Sidebar:

Dashboard

CRM

- Prospects
- Customers

Sales

- Quotations
- Orders

Production

- Design Approval
- Production Tracking

Inventory

- Materials
- Stock Movement

HRD

- Employees
- Attendance

Finance

- Invoices
- Payments

Reports

Settings

---

# Sidebar Theme

Background:

#081223

Active Menu:

#1E90FF

Text:

#FFFFFF

Hover:

rgba(255,255,255,0.08)

---

# Dashboard Widgets

Dashboard menampilkan:

- Total Orders
- Revenue This Month
- Active Productions
- Completed Orders
- Employee Attendance
- Pending Payments
- Upcoming Deadlines

---

# Order Detail Page

Header:

- Order Number
- Customer Name
- Deadline
- Status

Tabs:

- Overview
- Player List
- Design Files
- Production Progress
- Payments
- Activity Logs

---

# Production Tracking

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

Quality Control

↓

Packing

↓

Shipping

↓

Completed

---

Setiap perubahan status wajib menyimpan:

- User
- Timestamp
- Notes
- Attachments

---

# Customer Portal

Menu:

Dashboard

My Orders

Production Tracking

Invoices

Payments

Profile

---

Customer dapat melihat progress order secara real-time.

---

# Attendance System

Attendance Method:

- Dynamic QR Code
- GPS Validation
- Selfie Validation

Features:

- Check In
- Check Out
- Attendance History
- Monthly Recap

---

# Form Design Rules

- Mobile First
- Label berada di atas field
- Validation message wajib tampil
- Maximum 2 column layout pada desktop

---

# Table Design Rules

Desktop:

- Search
- Filter
- Sort
- Pagination

Mobile:

- Card View
- Expandable Detail

---

# Empty States

Setiap halaman wajib memiliki:

- Illustration
- Short Description
- CTA Button

Contoh:

Belum ada data order.

[Buat Order Baru]

---

# Loading States

Gunakan:

- Skeleton Loading
- Progress Indicator
- Toast Notification

---

# Accessibility

Minimal memenuhi WCAG AA.

Ketentuan:

- Kontras warna memadai
- Font minimum 14px
- Keyboard friendly
- Focus state jelas

---

# Responsive Breakpoints

Mobile:
< 768px

Tablet:
768px - 1024px

Desktop:

> 1024px

---

# UX Goals

Mengurangi:

- Human Error
- Order Hilang
- Salah Produksi
- Keterlambatan Produksi
- Komunikasi Berulang

Meningkatkan:

- Transparansi
- Efisiensi Operasional
- Monitoring Produksi
- Customer Experience

---

# Design Goal

Membangun sistem ERP Custom Clothing modern yang menjadi pusat operasional bisnis Eighteen, mulai dari order masuk, produksi berjalan, hingga produk diterima customer dengan pengalaman yang cepat, profesional, dan konsisten di seluruh platform.
