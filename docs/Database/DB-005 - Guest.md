# DB-005 - Guest

## Tujuan

Mengelola seluruh data tamu undangan.

---

## Daftar Tabel

- guests
- guest_groups
- guest_categories
- guest_tags

---

# Table : guests

| Column             | Type         | Nullable | Index  | Keterangan  |
| ------------------ | ------------ | -------- | ------ | ----------- |
| id                 | BIGINT       | No       | PK     | Primary Key |
| uuid               | UUID         | No       | UNIQUE | Public ID   |
| tenant_id          | BIGINT       | No       | INDEX  | Tenant      |
| wedding_id         | BIGINT       | No       | FK     | Wedding     |
| group_id           | BIGINT       | Yes      | FK     | Guest Group |
| category_id        | BIGINT       | Yes      | FK     | Category    |
| name               | VARCHAR(255) | No       |        | Nama        |
| phone              | VARCHAR(25)  | Yes      | INDEX  | WhatsApp    |
| email              | VARCHAR(255) | Yes      | INDEX  | Email       |
| address            | TEXT         | Yes      |        | Alamat      |
| pax                | SMALLINT     | No       |        | Jumlah Tamu |
| qr_code            | VARCHAR(255) | Yes      | UNIQUE | QR Checkin  |
| invitation_sent_at | TIMESTAMP    | Yes      |        |             |
| status             | ENUM         | No       | INDEX  | Active      |
| created_at         | TIMESTAMP    | No       |        |             |
| updated_at         | TIMESTAMP    | No       |        |             |
| deleted_at         | TIMESTAMP    | Yes      | INDEX  | Soft Delete |

---

## Relationships

belongsTo Wedding

belongsTo Group

belongsTo Category

hasOne RSVP

---

## Business Rules

- Nomor WhatsApp boleh kosong.
- QR Code harus unik.
- Pax minimal 1.
- Guest tidak boleh lintas tenant.

---

## Future Improvement

- Import Google Contact
- AI Duplicate Detection
- Auto Grouping
