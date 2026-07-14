# ARC-005 - Database Architecture

## Tujuan

Menjelaskan arsitektur database Nikago.

---

## Database

Current

MySQL 8

Future

PostgreSQL

---

## Multi Tenant

Single Database

tenant_id

---

## Primary Key

BIGINT

Public ID

UUID v7

---

## Soft Delete

Seluruh tabel bisnis menggunakan

deleted_at

---

## Audit

created_by

updated_by

deleted_by

---

## Relationships

One To One

One To Many

Many To Many

Morph

---

## Index

- tenant_id
- status
- created_at
- uuid

---

## Future

Read Replica

Partition

Sharding
