# DB-008 - Vendor

## Tujuan

Mengelola seluruh data vendor yang bergabung di Nikago.

---

## Daftar Tabel

- vendors
- vendor_services
- vendor_packages
- vendor_portfolios
- vendor_galleries
- vendor_calendars
- vendor_teams
- vendor_documents
- vendor_verifications

---

# Table : vendors

| Column        | Type         | Nullable | Index  | Keterangan    |
| ------------- | ------------ | -------- | ------ | ------------- |
| id            | BIGINT       | No       | PK     |               |
| uuid          | UUID         | No       | UNIQUE | Public ID     |
| tenant_id     | BIGINT       | No       | INDEX  | Tenant        |
| user_id       | BIGINT       | No       | FK     | Owner         |
| business_name | VARCHAR(255) | No       | INDEX  | Nama Vendor   |
| slug          | VARCHAR(255) | No       | UNIQUE | URL           |
| description   | TEXT         | Yes      |        |               |
| phone         | VARCHAR(30)  | Yes      |        |               |
| email         | VARCHAR(255) | Yes      |        |               |
| address       | TEXT         | Yes      |        |               |
| city          | VARCHAR(100) | Yes      | INDEX  | Kota          |
| province      | VARCHAR(100) | Yes      | INDEX  | Provinsi      |
| rating        | DECIMAL(3,2) | No       |        | Rating        |
| total_review  | INT          | No       |        | Jumlah Review |
| verified_at   | TIMESTAMP    | Yes      |        |               |

### Relationships

- hasMany Services
- hasMany Packages
- hasMany Gallery
- hasMany Portfolio
- hasMany Booking

---

# Table : vendor_services

Contoh

- Dekorasi
- Catering
- Venue
- Fotografer
- Videografer
- MC
- Entertainment

---

# Table : vendor_packages

Daftar paket layanan.

---

# Table : vendor_portfolios

Portfolio pekerjaan.

---

# Table : vendor_galleries

Galeri foto.

---

# Table : vendor_calendars

Jadwal vendor.

---

# Table : vendor_verifications

Status verifikasi vendor.

---

## Business Rules

- Vendor memiliki banyak layanan.
- Vendor dapat memiliki banyak paket.
- Jadwal booking tidak boleh bentrok.
- Vendor dapat diverifikasi Admin.

---

## Future Improvement

- AI Portfolio
- AI Pricing
- Vendor Badge
