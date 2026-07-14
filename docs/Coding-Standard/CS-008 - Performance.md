# CS-008 - Performance

## Tujuan

Menjaga performa aplikasi tetap optimal.

---

## Database

- Gunakan Eager Loading.
- Hindari N+1 Query.
- Gunakan Index pada kolom yang sering digunakan.
- Gunakan Pagination untuk data list.
- Hindari SELECT \*.

---

## Cache

Gunakan Redis untuk:

- Settings
- Subscription
- Permission
- Dashboard
- Analytics

---

## Queue

Gunakan Queue untuk:

- Email
- WhatsApp
- AI
- Export
- Import
- Image Processing

---

## Storage

Gunakan Cloudflare R2.

Jangan menyimpan file di local storage production.

---

## API

- Gunakan Pagination.
- Gunakan Filtering.
- Gunakan Sorting.
- Hindari Nested Response yang terlalu dalam.

---

## Frontend

- Gunakan Server Component sebagai default.
- Gunakan Dynamic Import bila diperlukan.
- Optimalkan Image dengan Next.js Image.
- Lazy Load halaman yang berat.

---

## Monitoring

- Laravel Pulse
- Laravel Horizon

---

## Rules

- Response API target < 500 ms untuk operasi normal.
- Hindari query di dalam loop.
- Gunakan cache bila data jarang berubah.
