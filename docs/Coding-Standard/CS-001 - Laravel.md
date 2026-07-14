# CS-001 - Laravel

## Architecture

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

## Controller

Hanya

- Request Validation
- Authorization
- Return Response

Tidak boleh Business Logic.

---

## Action

Satu Action

=

Satu Use Case

Example

CreateWeddingAction

PublishInvitationAction

GenerateTimelineAction

---

## Service

Berisi Business Logic.

---

## Repository

Hanya Query Database.

---

## Model

Relationship

Accessor

Mutator

Scope

Tidak boleh Business Logic.

---

## Validation

Semua Request

Menggunakan Form Request.

---

## Response

Semua Response

Menggunakan API Resource.
