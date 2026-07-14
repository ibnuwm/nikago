# ARC-000 - System Overview

## Tujuan

Menjelaskan arsitektur sistem Nikago secara keseluruhan.

---

## Tech Stack

### Frontend

- Next.js 15
- TypeScript
- TailwindCSS
- shadcn/ui
- TanStack Query
- Zustand
- React Hook Form
- Zod

---

### Backend

- Laravel 12
- PHP 8.4
- Laravel Sanctum
- Spatie Permission
- Laravel Horizon
- Laravel Pulse
- Laravel Scout
- Meilisearch
- Laravel Reverb

---

### Database

MySQL 8

Future

PostgreSQL

---

### Cache

Redis

---

### Queue

Redis Queue

Laravel Horizon

---

### Storage

Cloudflare R2

---

### Search

Laravel Scout

Meilisearch

---

### AI

OpenRouter

Abstraction Layer

---

## High Level Architecture

```

Internet

↓

Cloudflare

↓

Nginx

↓

Next.js

↓

Laravel API

↓

Service Layer

↓

Repository

↓

Eloquent

↓

MySQL

↓

Redis

↓

Cloudflare R2

```

---

## Development Principle

- Modular Monolith
- API First
- Multi Tenant
- Domain Driven Module
- Clean Architecture
- SOLID
- Test Driven Development

---

## Future

- Mobile App
- Event Driven
- Microservice
