# DB-016 - Notification

## Tujuan

Mengelola seluruh notifikasi sistem kepada pengguna melalui berbagai channel.

---

## Daftar Tabel

- notifications
- notification_templates
- notification_channels
- notification_logs
- notification_preferences

---

# Table : notifications

## Metadata

Owner Module : Notification

Growth : High

Cache : No

Retention : 2 Tahun

---

| Column       | Type         | Nullable | Index  | Keterangan                  |
| ------------ | ------------ | -------- | ------ | --------------------------- |
| id           | BIGINT       | No       | PK     | Primary Key                 |
| uuid         | UUID         | No       | UNIQUE | Public ID                   |
| tenant_id    | BIGINT       | No       | INDEX  | Tenant                      |
| user_id      | BIGINT       | No       | FK     | User                        |
| template_id  | BIGINT       | Yes      | FK     | Template                    |
| title        | VARCHAR(255) | No       |        | Judul                       |
| message      | TEXT         | No       |        | Isi                         |
| channel      | ENUM         | No       | INDEX  | App, Email, WhatsApp        |
| status       | ENUM         | No       | INDEX  | Pending, Sent, Failed, Read |
| scheduled_at | TIMESTAMP    | Yes      |        | Jadwal                      |
| sent_at      | TIMESTAMP    | Yes      |        | Terkirim                    |
| read_at      | TIMESTAMP    | Yes      |        | Dibaca                      |
| created_at   | TIMESTAMP    | No       |        |                             |
| updated_at   | TIMESTAMP    | No       |        |                             |

---

## Relationships

- belongsTo User
- belongsTo Template

---

## Business Rules

- Mendukung multiple channel.
- Semua pengiriman dicatat.
- Read status disimpan.
- Retry maksimal 3 kali.

---

## Future Improvement

- Push Notification
- FCM
- OneSignal
- AI Notification
