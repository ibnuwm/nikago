# ARC-015 - Deployment

## Tujuan

Menjelaskan proses deployment aplikasi Nikago.

---

## Server

Ubuntu 24.04 LTS

---

## Web Server

Nginx

---

## Container

Docker

Coolify

---

## CI/CD

GitHub Actions

---

## Branch

main

develop

feature/\*

hotfix/\*

---

## Deployment Flow

Developer

↓

GitHub

↓

GitHub Actions

↓

Coolify

↓

Production

---

## Environment

Development

Staging

Production

---

## Rules

- Deploy hanya dari branch main.
- Semua secret menggunakan Environment Variable.
- Database backup harian.
- Rollback tersedia.

---

## Future

- Blue Green Deployment
- Canary Release
