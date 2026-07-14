# DB-006 - RSVP

## Tujuan

Menyimpan konfirmasi kehadiran tamu.

---

## Daftar Tabel

- rsvps
- rsvp_logs

---

# Table : rsvps

| Column       | Type      | Nullable | Index  | Keterangan       |
| ------------ | --------- | -------- | ------ | ---------------- |
| id           | BIGINT    | No       | PK     | Primary Key      |
| uuid         | UUID      | No       | UNIQUE | Public ID        |
| tenant_id    | BIGINT    | No       | INDEX  | Tenant           |
| guest_id     | BIGINT    | No       | FK     | Guest            |
| attendance   | ENUM      | No       | INDEX  | Yes / No / Maybe |
| total_guest  | SMALLINT  | No       |        | Jumlah Hadir     |
| message      | TEXT      | Yes      |        | Ucapan           |
| confirmed_at | TIMESTAMP | Yes      |        | RSVP             |
| created_at   | TIMESTAMP | No       |        |                  |
| updated_at   | TIMESTAMP | No       |        |                  |

---

## Relationships

belongsTo Guest

hasMany RSVP Log

---

## Business Rules

- Satu Guest hanya memiliki satu RSVP aktif.
- RSVP dapat diperbarui sebelum acara.
- Attendance wajib diisi.
- Total Guest minimal 1.

---

# Table : rsvp_logs

Digunakan untuk menyimpan riwayat perubahan RSVP.

| Column     | Type      |
| ---------- | --------- |
| id         | BIGINT    |
| rsvp_id    | BIGINT    |
| old_status | VARCHAR   |
| new_status | VARCHAR   |
| created_at | TIMESTAMP |

---

## Future Improvement

- AI Attendance Prediction
- Reminder RSVP
- WhatsApp Confirmation
