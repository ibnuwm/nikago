# DB-004 - Invitation

## Tujuan

Mengelola seluruh data website undangan digital.

---

## Daftar Tabel

- invitations
- invitation_templates
- invitation_themes
- invitation_settings
- invitation_music
- invitation_domains

---

# Table : invitations

## Deskripsi

Menyimpan data website undangan.

| Column       | Type         | Nullable | Index  | Keterangan      |
| ------------ | ------------ | -------- | ------ | --------------- |
| id           | BIGINT       | No       | PK     | Primary Key     |
| uuid         | UUID         | No       | UNIQUE | Public ID       |
| tenant_id    | BIGINT       | No       | INDEX  | Tenant          |
| wedding_id   | BIGINT       | No       | FK     | Wedding         |
| template_id  | BIGINT       | No       | FK     | Template        |
| theme_id     | BIGINT       | Yes      | FK     | Theme           |
| title        | VARCHAR(255) | No       |        | Judul           |
| slug         | VARCHAR(255) | No       | INDEX  | URL             |
| cover_image  | VARCHAR(255) | Yes      |        | Cover           |
| description  | TEXT         | Yes      |        | Deskripsi       |
| status       | ENUM         | No       | INDEX  | Draft / Publish |
| published_at | TIMESTAMP    | Yes      |        | Publish         |
| created_at   | TIMESTAMP    | No       |        |                 |
| updated_at   | TIMESTAMP    | No       |        |                 |
| deleted_at   | TIMESTAMP    | Yes      | INDEX  | Soft Delete     |

---

## Relationships

belongsTo Wedding

belongsTo Template

belongsTo Theme

hasOne Setting

hasMany Gallery

---

## Business Rules

- Satu Wedding memiliki satu Invitation aktif.
- Slug unik dalam tenant.
- Draft dapat diedit.
- Publish mengunci slug kecuali diubah manual.

---

## Future Improvement

- Multi Invitation
- White Label
- Versioning
