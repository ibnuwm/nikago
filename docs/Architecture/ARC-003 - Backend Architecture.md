# ARC-003 - Backend Architecture

## Request Flow

```

Client

↓

Route

↓

Middleware

↓

Controller

↓

Action

↓

Service

↓

Repository

↓

Model

↓

Database

```

---

## Layer

Presentation

Business

Persistence

Infrastructure

---

## Pattern

Controller

↓

Action

↓

Service

↓

Repository

↓

Model

---

## Rules

Controller

- Validasi Request
- Return Response

Action

- Satu use case

Service

- Business Logic

Repository

- Query Database

Model

- Relationship

---

## Queue

Email

Notification

AI

Export

Import

Image Processing

menggunakan Queue.
