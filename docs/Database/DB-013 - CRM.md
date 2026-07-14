# DB-013 - CRM

## Tujuan

Mengelola seluruh proses Customer Relationship Management (CRM) untuk Vendor, Wedding Organizer, dan Event Organizer.

---

## Daftar Tabel

- leads
- lead_sources
- lead_statuses
- lead_activities
- pipelines
- pipeline_stages
- follow_ups
- customer_notes

---

# Table : leads

## Deskripsi

Menyimpan data calon pelanggan.

| Column            | Type          | Nullable | Index  | Keterangan     |
| ----------------- | ------------- | -------- | ------ | -------------- |
| id                | BIGINT        | No       | PK     | Primary Key    |
| uuid              | UUID          | No       | UNIQUE | Public ID      |
| tenant_id         | BIGINT        | No       | INDEX  | Tenant         |
| vendor_id         | BIGINT        | Yes      | FK     | Vendor         |
| name              | VARCHAR(255)  | No       |        | Nama           |
| phone             | VARCHAR(25)   | Yes      | INDEX  | WhatsApp       |
| email             | VARCHAR(255)  | Yes      | INDEX  | Email          |
| source_id         | BIGINT        | Yes      | FK     | Lead Source    |
| pipeline_stage_id | BIGINT        | Yes      | FK     | Pipeline Stage |
| estimated_budget  | DECIMAL(18,2) | Yes      |        | Budget         |
| wedding_date      | DATE          | Yes      | INDEX  | Hari Acara     |
| status_id         | BIGINT        | No       | FK     | Status         |
| assigned_to       | BIGINT        | Yes      | FK     | User           |
| created_at        | TIMESTAMP     | No       |        |                |
| updated_at        | TIMESTAMP     | No       |        |                |
| deleted_at        | TIMESTAMP     | Yes      | INDEX  | Soft Delete    |

---

## Relationships

- belongsTo Vendor
- belongsTo Pipeline Stage
- belongsTo Status
- hasMany Follow Ups
- hasMany Activities
- hasMany Notes

---

# Table : lead_sources

Contoh

- Website
- Instagram
- TikTok
- Facebook
- WhatsApp
- Referral
- Marketplace

---

# Table : lead_statuses

Contoh

- New
- Contacted
- Negotiation
- Won
- Lost

---

# Table : pipelines

Pipeline CRM.

---

# Table : pipeline_stages

Tahapan Pipeline.

---

# Table : follow_ups

Riwayat Follow Up.

---

# Table : lead_activities

Timeline aktivitas.

---

# Table : customer_notes

Catatan internal.

---

## Business Rules

- Lead wajib memiliki status.
- Lead dapat dipindahkan antar pipeline.
- Semua aktivitas dicatat.
- Follow up memiliki reminder.

---

## Future Improvement

- AI Lead Scoring
- WhatsApp Automation
- Sales Forecast
- Workflow Automation
