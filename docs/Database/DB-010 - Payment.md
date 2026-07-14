# DB-010 - Payment

## Tujuan

Mengelola seluruh transaksi pembayaran di platform Nikago, baik untuk Subscription, Vendor Marketplace, maupun layanan lainnya.

---

## Daftar Tabel

- payment_methods
- payments
- payment_items
- payment_transactions
- payment_callbacks
- refunds

---

# Table : payment_methods

## Deskripsi

Master metode pembayaran.

| Column        | Type         | Nullable | Index  | Keterangan               |
| ------------- | ------------ | -------- | ------ | ------------------------ |
| id            | BIGINT       | No       | PK     | Primary Key              |
| code          | VARCHAR(50)  | No       | UNIQUE | MIDTRANS, XENDIT, MANUAL |
| name          | VARCHAR(100) | No       |        | Nama Metode              |
| provider      | VARCHAR(100) | No       | INDEX  | Penyedia                 |
| is_active     | BOOLEAN      | No       | INDEX  | Status                   |
| configuration | JSON         | Yes      |        | Konfigurasi              |

---

# Table : payments

## Deskripsi

Header transaksi pembayaran.

| Column            | Type          | Nullable | Index  | Keterangan                           |
| ----------------- | ------------- | -------- | ------ | ------------------------------------ |
| id                | BIGINT        | No       | PK     |                                      |
| uuid              | UUID          | No       | UNIQUE | Public ID                            |
| tenant_id         | BIGINT        | No       | INDEX  | Tenant                               |
| user_id           | BIGINT        | No       | FK     | Pembayar                             |
| invoice_number    | VARCHAR(100)  | No       | UNIQUE | Nomor Invoice                        |
| payment_method_id | BIGINT        | No       | FK     | Payment Method                       |
| amount            | DECIMAL(18,2) | No       |        | Total                                |
| status            | ENUM          | No       | INDEX  | Pending/Paid/Expired/Failed/Refunded |
| paid_at           | TIMESTAMP     | Yes      |        | Waktu Bayar                          |
| expired_at        | TIMESTAMP     | Yes      |        | Kadaluarsa                           |
| created_at        | TIMESTAMP     | No       |        |                                      |
| updated_at        | TIMESTAMP     | No       |        |                                      |

### Relationships

- belongsTo User
- belongsTo Payment Method
- hasMany Payment Items
- hasMany Payment Transactions

---

# Table : payment_items

Detail item pembayaran.

Contoh:

- Subscription Pro
- Vendor Booking
- Premium Template
- AI Credits

---

# Table : payment_transactions

Menyimpan seluruh request dan response dari payment gateway.

---

# Table : payment_callbacks

Webhook dari Midtrans, Xendit, dll.

---

# Table : refunds

Data refund transaksi.

---

## Business Rules

- Invoice Number harus unik.
- Payment tidak boleh dihapus.
- Callback wajib disimpan sebagai audit.
- Refund hanya dapat dilakukan pada transaksi Paid.

---

## Future Improvement

- Split Payment
- Escrow
- Multi Currency
- Installment
