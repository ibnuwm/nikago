# ARC-004 - Frontend Architecture

## Tujuan

Menjelaskan arsitektur frontend Nikago menggunakan Next.js.

---

## Tech Stack

- Next.js 15 (App Router)
- TypeScript
- Tailwind CSS
- shadcn/ui
- TanStack Query
- Zustand
- React Hook Form
- Zod

---

## Folder Structure

src/

app/

components/

features/

hooks/

layouts/

lib/

providers/

services/

stores/

styles/

types/

utils/

---

## Feature Structure

features/

authentication/

dashboard/

wedding/

invitation/

guest/

planner/

vendor/

payment/

subscription/

cms/

crm/

ai/

analytics/

---

## State Management

Global State

- Zustand

Server State

- TanStack Query

Form

- React Hook Form

Validation

- Zod

---

## Rules

- Semua API melalui services.
- Tidak boleh fetch langsung di component.
- Semua form menggunakan React Hook Form.
- Validasi menggunakan Zod.
- Component harus reusable.

---

## Future Improvement

- Storybook
- Offline Mode
- PWA
