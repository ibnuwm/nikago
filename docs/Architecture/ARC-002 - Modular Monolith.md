# ARC-002 - Modular Monolith

## Tujuan

Menjelaskan bagaimana setiap module bekerja.

---

## Struktur

```

Modules/

Authentication/

Wedding/

Invitation/

Vendor/

Payment/

AI/

```

---

Setiap Module terdiri dari

```

Controllers/

Services/

Repositories/

Models/

DTO/

Actions/

Policies/

Events/

Listeners/

Jobs/

Requests/

Resources/

Enums/

Exceptions/

Routes/

Tests/

```

---

## Dependency Rule

Module A

↓

Shared

↓

Core

Tidak boleh

Module A

↓

Module B

langsung

Gunakan

Service

Event

Interface

---

## Benefit

- Mudah dipisah menjadi Microservice.
- Mudah Testing.
- Mudah Maintenance.
