# ARC-008 - Multi Tenant

## Tujuan

Menjelaskan implementasi Multi Tenant.

---

## Strategy

Single Database

Shared Schema

tenant_id

---

## Tenant Resolution

Login

↓

Tenant

↓

Middleware

↓

Request

---

## Data Isolation

Semua query wajib menggunakan

tenant_id

---

## Global Scope

TenantScope

---

## Super Admin

Bypass Tenant Scope

---

## Rules

- Tidak boleh query tanpa tenant.
- Semua Service menggunakan Tenant Context.
- Semua Repository otomatis menerapkan Tenant Scope.

---

## Future

Multi Database

Regional Database

Tenant Migration
