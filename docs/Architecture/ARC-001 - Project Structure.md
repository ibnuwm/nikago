# ARC-001 - Project Structure

## Backend

```

app/

Modules/

Shared/

Core/

Infrastructure/

Support/

routes/

database/

tests/

```

---

## Modules

```

Authentication

Dashboard

Wedding

Invitation

Guest

RSVP

Planner

Vendor

Marketplace

Payment

Subscription

CMS

CRM

AI

Analytics

Notification

System

Integration

```

---

## Frontend

```

src/

app/

components/

features/

hooks/

services/

stores/

types/

utils/

providers/

```

---

## Shared

```

Button

Input

Modal

Card

Table

Pagination

Toast

```

---

## Rules

- Setiap module berdiri sendiri.
- Tidak boleh saling mengakses Model secara langsung.
- Komunikasi melalui Service.
