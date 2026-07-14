# DB-000 - Database Overview

## Tujuan

Mendefinisikan standar desain database Nikago agar seluruh modul memiliki struktur yang konsisten, scalable, dan mudah dikembangkan.

---

## Database Information

Database Engine : MySQL 8+

Character Set : utf8mb4

Collation : utf8mb4_unicode_ci

Timezone : UTC

ORM : Laravel Eloquent

---

## Database Standards

### Primary Key

Semua tabel menggunakan

BIGINT UNSIGNED AUTO INCREMENT

Contoh

id

---

### Public Identifier

Semua entity utama memiliki

uuid

Format

UUID v7

Digunakan untuk

- Public API
- URL
- Integrasi

---

### Multi Tenant

Seluruh tabel bisnis memiliki

tenant_id

kecuali

- roles
- permissions
- countries
- provinces
- districts
- subscriptions_plan

---

### Soft Delete

Semua data bisnis menggunakan

deleted_at

---

### Audit Column

Semua tabel bisnis memiliki

created_at

updated_at

deleted_at

created_by

updated_by

deleted_by

---

### Index Standard

INDEX

tenant_id

created_at

deleted_at

UNIQUE

uuid

---

### Naming Convention

Table

plural_snake_case

Column

snake_case

Foreign Key

table_id

Pivot Table

alphabetical_order

contoh

role_user

permission_role

---

## Folder Database

DB-001 Authentication

DB-002 Tenant

DB-003 Wedding

DB-004 Invitation

DB-005 Guest

DB-006 RSVP

DB-007 Planner

DB-008 Vendor

DB-009 Marketplace

DB-010 Payment

DB-011 Subscription

DB-012 CMS

DB-013 CRM

DB-014 AI

DB-015 Analytics

DB-016 Notification

DB-017 System

DB-018 Integration

---

## Future Improvement

- PostgreSQL
- Read Replica
- Sharding
- Database Partition
