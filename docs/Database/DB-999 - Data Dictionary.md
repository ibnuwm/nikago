# DB-999 - Data Dictionary

## Tujuan

Dokumen ini menjadi standar global seluruh database Nikago agar setiap tabel, migration, model, API, dan dokumentasi memiliki struktur yang konsisten.

---

# Database Engine

MySQL 8+

Character Set

utf8mb4

Collation

utf8mb4_unicode_ci

Timezone

UTC

ORM

Laravel Eloquent

---

# Primary Key Standard

Semua tabel menggunakan

BIGINT UNSIGNED AUTO_INCREMENT

Nama kolom

id

Contoh

id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT

---

# Public Identifier

Seluruh entity utama memiliki

uuid

Format

UUID Version 7

Digunakan untuk

- API
- URL
- Public Identifier
- Integrasi

Contoh

users.uuid

vendors.uuid

weddings.uuid

payments.uuid

---

# Multi Tenant

Seluruh tabel bisnis wajib memiliki

tenant_id

Kecuali

- roles
- permissions
- subscription_plans
- countries
- provinces
- integrations
- payment_methods

---

# Foreign Key

Penamaan

table_id

Contoh

user_id

vendor_id

payment_id

Tidak diperbolehkan

userid

vendorID

vendorId

---

# Timestamp

Standar

created_at

updated_at

deleted_at

Menggunakan

TIMESTAMP

UTC

---

# Audit Column

Untuk tabel bisnis penting

created_by

updated_by

deleted_by

Semua berupa BIGINT.

---

# Soft Delete

Menggunakan

deleted_at

Soft Delete digunakan pada hampir seluruh tabel bisnis.

Tidak digunakan untuk

- payment_transactions
- audit_logs
- webhook_logs

---

# Status Enum

Status Umum

ACTIVE

INACTIVE

DRAFT

PUBLISHED

ARCHIVED

Status Pembayaran

PENDING

PAID

FAILED

EXPIRED

REFUNDED

Status Booking

PENDING

CONFIRMED

COMPLETED

CANCELLED

Status RSVP

YES

NO

MAYBE

---

# Boolean

Menggunakan

BOOLEAN

Contoh

is_active

is_verified

is_public

is_primary

---

# Money

Menggunakan

DECIMAL(18,2)

Tidak menggunakan FLOAT.

---

# Percentage

DECIMAL(5,2)

Contoh

87.50

---

# Latitude Longitude

DECIMAL(10,8)

DECIMAL(11,8)

---

# Phone Number

VARCHAR(25)

Format

+628123456789

---

# Email

VARCHAR(255)

Selalu Unique jika digunakan untuk Login.

---

# Slug

VARCHAR(255)

INDEX

Unique per tenant bila diperlukan.

---

# JSON Column

Digunakan untuk

configuration

metadata

settings

preferences

snapshot

---

# File Path

Tidak menyimpan binary.

Hanya menyimpan

Cloudflare R2 URL

atau

Object Key

---

# Image

VARCHAR(255)

Contoh

vendors/logo.png

---

# Enum Recommendation

Laravel Native Enum

PHP Enum

Tidak menggunakan string bebas.

---

# Naming Convention

Table

plural_snake_case

Contoh

users

vendors

payments

Column

snake_case

Pivot Table

alphabetical_order

Contoh

permission_role

role_user

vendor_service

---

# Index Standard

INDEX

tenant_id

created_at

deleted_at

status

UNIQUE

uuid

email

slug

invoice_number

Composite Index

tenant_id + status

tenant_id + created_at

tenant_id + wedding_id

---

# Cache Strategy

Redis

Untuk

settings

subscription

permissions

roles

feature_limits

---

# Queue Strategy

Redis Queue

Digunakan untuk

Email

WhatsApp

Notification

Analytics

AI

Image Processing

Export

Import

---

# Archive Strategy

2 Tahun

Activity Log

Notification

5 Tahun

Payment

Invoice

Audit Log

Unlimited

Subscription

Wedding

User

---

# Security Standard

Password

bcrypt

API Secret

Encrypted

Webhook Secret

Encrypted

Credential

Encrypted

---

# API Standard

Seluruh API menggunakan

UUID

Bukan

ID

Contoh

/api/vendors/{uuid}

---

# Migration Standard

Semua migration menggunakan

foreignId()

constrained()

cascadeOnUpdate()

restrictOnDelete()

SoftDeletes()

timestamps()

---

# Seeder Standard

Master Data

Role

Permission

Subscription Plan

Payment Method

Country

Province

City

---

# Factory Standard

Semua entity utama memiliki Factory.

---

# Testing Standard

Pest

Feature Test

Database Refresh

Factory

---

# Future Improvement

- PostgreSQL Compatibility
- Read Replica
- Partitioning
- Sharding
- Event Sourcing
