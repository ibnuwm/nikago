# DB-009 - Marketplace

## Tujuan

Mengelola seluruh transaksi marketplace vendor.

---

## Daftar Tabel

- bookings
- booking_items
- booking_histories
- booking_documents
- reviews
- review_images
- wishlists
- compare_lists

---

# Table : bookings

| Column       | Type          | Nullable | Index  | Keterangan     |
| ------------ | ------------- | -------- | ------ | -------------- |
| id           | BIGINT        | No       | PK     |                |
| uuid         | UUID          | No       | UNIQUE | Public ID      |
| tenant_id    | BIGINT        | No       | INDEX  | Tenant         |
| wedding_id   | BIGINT        | No       | FK     | Wedding        |
| vendor_id    | BIGINT        | No       | FK     | Vendor         |
| package_id   | BIGINT        | No       | FK     | Paket          |
| booking_date | DATE          | No       | INDEX  | Tanggal        |
| event_date   | DATE          | No       | INDEX  | Hari Acara     |
| subtotal     | DECIMAL(18,2) | No       |        |                |
| discount     | DECIMAL(18,2) | No       |        |                |
| total        | DECIMAL(18,2) | No       |        |                |
| status       | ENUM          | No       | INDEX  | Booking Status |

---

### Relationships

- belongsTo Vendor
- belongsTo Wedding
- hasMany Booking Items
- hasMany Booking History
- hasMany Payments

---

# Table : booking_items

Detail paket.

---

# Table : booking_histories

Riwayat perubahan booking.

---

# Table : booking_documents

Kontrak.

Invoice.

Lampiran.

---

# Table : reviews

Review pelanggan.

---

# Table : review_images

Foto review.

---

# Table : wishlists

Vendor favorit.

---

# Table : compare_lists

Perbandingan vendor.

---

## Business Rules

- Booking wajib memiliki Vendor.
- Booking wajib memiliki Wedding.
- Review hanya dapat dibuat setelah booking selesai.
- Wishlist hanya milik satu User.

---

## Future Improvement

- Escrow
- AI Recommendation
- Dynamic Pricing
- Auto Booking
