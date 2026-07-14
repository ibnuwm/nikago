# API-009 - Planner

## Module

Wedding Planner

---

## Endpoint

GET /planner

GET /planner/summary

GET /planner/progress

POST /planner/generate-ai

GET /planner/export

---

## GET /planner

### Tujuan

Menampilkan dashboard Wedding Planner.

Permission

Bride

Groom

Wedding Organizer

---

## GET /planner/summary

Return

- Progress
- Checklist
- Budget
- Timeline
- Reminder

---

## GET /planner/progress

Return

{
"progress":75.5,
"completed_task":58,
"total_task":77
}

---

## POST /planner/generate-ai

Menghasilkan planner otomatis menggunakan AI.

---

## GET /planner/export

Export planner ke PDF.
