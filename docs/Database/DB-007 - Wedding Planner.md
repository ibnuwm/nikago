# DB-007 - Wedding Planner

## Tujuan

Mengelola seluruh aktivitas persiapan pernikahan dalam satu modul Wedding Planner.

---

## Daftar Tabel

- checklists
- checklist_items
- timelines
- timeline_tasks
- budgets
- budget_categories
- budget_transactions
- notes
- reminders

---

# Table : checklists

## Deskripsi

Master checklist per wedding.

| Column      | Type         | Nullable | Index  | Keterangan     |
| ----------- | ------------ | -------- | ------ | -------------- |
| id          | BIGINT       | No       | PK     | Primary Key    |
| uuid        | UUID         | No       | UNIQUE | Public ID      |
| tenant_id   | BIGINT       | No       | INDEX  | Tenant         |
| wedding_id  | BIGINT       | No       | FK     | Wedding        |
| title       | VARCHAR(255) | No       |        | Nama Checklist |
| description | TEXT         | Yes      |        | Deskripsi      |
| progress    | DECIMAL(5,2) | No       |        | Progress (%)   |
| created_at  | TIMESTAMP    | No       |        |                |
| updated_at  | TIMESTAMP    | No       |        |                |
| deleted_at  | TIMESTAMP    | Yes      | INDEX  | Soft Delete    |

### Relationships

- belongsTo Wedding
- hasMany Checklist Items

---

# Table : checklist_items

| Column       | Type         | Nullable | Index | Keterangan      |
| ------------ | ------------ | -------- | ----- | --------------- |
| id           | BIGINT       | No       | PK    |                 |
| checklist_id | BIGINT       | No       | FK    | Checklist       |
| title        | VARCHAR(255) | No       |       | Nama Task       |
| priority     | ENUM         | No       | INDEX | Low/Medium/High |
| due_date     | DATE         | Yes      | INDEX | Deadline        |
| completed_at | TIMESTAMP    | Yes      |       |                 |
| sort_order   | INT          | No       |       |                 |

---

# Table : timelines

Digunakan untuk timeline utama wedding.

---

# Table : timeline_tasks

Digunakan untuk daftar aktivitas pada timeline.

---

# Table : budgets

Master budget wedding.

---

# Table : budget_categories

Kategori budget.

Contoh

- Venue
- Catering
- Dekorasi
- MUA
- Dokumentasi

---

# Table : budget_transactions

Seluruh pemasukan dan pengeluaran.

---

# Table : notes

Catatan Wedding Planner.

---

# Table : reminders

Reminder aktivitas.

---

## Business Rules

- Semua planner dimiliki Wedding.
- Progress dihitung otomatis.
- Budget dihitung realtime.
- Reminder dapat terhubung ke Notification.

---

## Future Improvement

- AI Planner
- Google Calendar
- Smart Reminder
- Recurring Task
