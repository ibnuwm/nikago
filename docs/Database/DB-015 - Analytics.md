# DB-015 - Analytics

## Tujuan

Mengelola data analitik platform Nikago.

---

## Daftar Tabel

- analytics_events
- analytics_sessions
- analytics_page_views
- analytics_dashboards
- analytics_reports

---

# Table : analytics_events

Menyimpan seluruh event aplikasi.

Contoh

- Login
- Register
- Publish Invitation
- RSVP
- Booking Vendor
- Payment
- AI Usage

---

# Table : analytics_sessions

Session pengguna.

---

# Table : analytics_page_views

Page View.

---

# Table : analytics_dashboards

Konfigurasi Dashboard.

---

# Table : analytics_reports

Laporan yang telah dibuat.

---

## Business Rules

- Event dicatat secara asynchronous.
- Tidak mengganggu transaksi utama.
- Dashboard membaca data agregasi.
- Report dapat diekspor.

---

## Future Improvement

- Realtime Dashboard
- AI Insight
- Funnel Analysis
- Cohort Analysis
- Heatmap
