# DB-011 - Subscription

## Tujuan

Mengelola paket langganan pengguna dan vendor.

---

## Daftar Tabel

- subscription_plans
- subscriptions
- subscription_histories
- subscription_features
- feature_limits

---

# Table : subscription_plans

| Column        | Type          | Nullable | Index  | Keterangan       |
| ------------- | ------------- | -------- | ------ | ---------------- |
| id            | BIGINT        | No       | PK     |                  |
| code          | VARCHAR(50)   | No       | UNIQUE | FREE, BASIC, PRO |
| name          | VARCHAR(100)  | No       |        | Nama Paket       |
| monthly_price | DECIMAL(18,2) | No       |        | Harga Bulanan    |
| yearly_price  | DECIMAL(18,2) | Yes      |        | Harga Tahunan    |
| is_active     | BOOLEAN       | No       | INDEX  | Status           |

---

# Table : subscriptions

Langganan aktif tenant.

Kolom utama

- uuid
- tenant_id
- plan_id
- started_at
- expired_at
- auto_renew
- status

---

# Table : subscription_features

Daftar fitur tiap paket.

Contoh

- Unlimited Guest
- Gallery
- AI
- Vendor Marketplace
- QR Check-in

---

# Table : feature_limits

Contoh

Website = 1

Guest = 100

AI Credits = 1000

Storage = 5 GB

---

## Business Rules

- Satu tenant hanya memiliki satu subscription aktif.
- Upgrade dapat dilakukan kapan saja.
- Downgrade berlaku setelah masa aktif habis.
- Feature dibaca berdasarkan paket aktif.

---

## Future Improvement

- Coupon
- Referral
- Lifetime Plan
- Enterprise Contract
