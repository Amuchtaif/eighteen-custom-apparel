# agents/ERD.md

# Eighteen Database Design

## Architecture

CRM
↓
Quotation
↓
Order
↓
Production
↓
Invoice
↓
Payment
↓
Completed

---

# AUTHENTICATION

## roles

| Field       |
| ----------- |
| id          |
| name        |
| description |
| created_at  |
| updated_at  |

---

## users

| Field         |
| ------------- |
| id            |
| role_id       |
| name          |
| email         |
| phone         |
| password      |
| is_active     |
| last_login_at |
| created_at    |
| updated_at    |

---

# EMPLOYEE

## employees

| Field         |
| ------------- |
| id            |
| user_id       |
| employee_code |
| position      |
| department    |
| join_date     |
| status        |
| created_at    |
| updated_at    |

---

## attendances

| Field        |
| ------------ |
| id           |
| employee_id  |
| check_in     |
| check_out    |
| latitude     |
| longitude    |
| selfie_photo |
| notes        |
| created_at   |
| updated_at   |

---

# CRM

## customers

| Field          |
| -------------- |
| id             |
| company_name   |
| contact_person |
| phone          |
| email          |
| address        |
| city           |
| notes          |
| created_at     |
| updated_at     |

---

## customer_histories

| Field       |
| ----------- |
| id          |
| customer_id |
| user_id     |
| activity    |
| notes       |
| created_at  |

---

# QUOTATION

## quotations

| Field            |
| ---------------- |
| id               |
| quotation_number |
| customer_id      |
| quotation_date   |
| valid_until      |
| subtotal         |
| discount         |
| grand_total      |
| status           |
| notes            |
| created_at       |
| updated_at       |

---

## quotation_items

| Field        |
| ------------ |
| id           |
| quotation_id |
| product_id   |
| description  |
| qty          |
| unit_price   |
| subtotal     |
| created_at   |
| updated_at   |

---

# PRODUCT

## products

| Field       |
| ----------- |
| id          |
| category_id |
| name        |
| sku         |
| description |
| base_price  |
| is_active   |
| created_at  |
| updated_at  |

---

## product_categories

| Field      |
| ---------- |
| id         |
| name       |
| created_at |
| updated_at |

---

# ORDER

## orders

| Field        |
| ------------ |
| id           |
| order_number |
| customer_id  |
| quotation_id |
| order_date   |
| deadline     |
| subtotal     |
| discount     |
| grand_total  |
| status       |
| notes        |
| created_at   |
| updated_at   |

---

## order_items

| Field        |
| ------------ |
| id           |
| order_id     |
| product_id   |
| product_name |
| qty          |
| unit_price   |
| subtotal     |
| notes        |
| created_at   |
| updated_at   |

---

# PLAYER LIST

## order_players

| Field         |
| ------------- |
| id            |
| order_item_id |
| player_name   |
| player_number |
| size          |
| notes         |
| created_at    |
| updated_at    |

---

# DESIGN FILES

## design_files

| Field       |
| ----------- |
| id          |
| order_id    |
| file_name   |
| file_path   |
| version     |
| notes       |
| uploaded_by |
| created_at  |

---

# DESIGN APPROVAL

## design_approvals

| Field          |
| -------------- |
| id             |
| order_id       |
| design_file_id |
| approved_by    |
| approved_at    |
| notes          |
| status         |
| created_at     |
| updated_at     |

---

# PRODUCTION

## production_stages

| Field      |
| ---------- |
| id         |
| name       |
| sequence   |
| created_at |
| updated_at |

Data Default:

1 Draft

2 Design

3 Customer Approval

4 Printing

5 Cutting

6 Sewing

7 QC

8 Packing

9 Shipping

10 Completed

---

## production_logs

| Field        |
| ------------ |
| id           |
| order_id     |
| stage_id     |
| updated_by   |
| notes        |
| started_at   |
| completed_at |
| created_at   |

---

# FINANCE

## invoices

| Field          |
| -------------- |
| id             |
| order_id       |
| invoice_number |
| invoice_date   |
| due_date       |
| total_amount   |
| status         |
| created_at     |
| updated_at     |

---

## payments

| Field            |
| ---------------- |
| id               |
| invoice_id       |
| payment_date     |
| amount           |
| payment_method   |
| reference_number |
| proof_file       |
| notes            |
| created_at       |
| updated_at       |

---

# NOTIFICATIONS

## notifications

| Field      |
| ---------- |
| id         |
| user_id    |
| title      |
| message    |
| is_read    |
| created_at |

---

# ACTIVITY LOGS

## activity_logs

| Field       |
| ----------- |
| id          |
| user_id     |
| module      |
| action      |
| description |
| ip_address  |
| created_at  |

---

# RELATIONSHIPS

roles
└── users

users
└── employees

employees
└── attendances

customers
├── quotations
├── orders
└── customer_histories

quotations
└── quotation_items

orders
├── order_items
├── design_files
├── production_logs
└── invoices

order_items
└── order_players

invoices
└── payments

production_stages
└── production_logs
