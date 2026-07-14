# ARC-010 - Queue

## Tujuan

Menjalankan proses berat secara asynchronous.

---

## Technology

Redis Queue

Laravel Horizon

---

## Queue List

High Priority

- Payment Callback
- Webhook
- Notification

Medium Priority

- Email
- WhatsApp
- AI

Low Priority

- Export
- Import
- Analytics
- Image Processing

---

## Retry

3 Kali

---

## Failed Job

Disimpan pada failed_jobs.

---

## Monitoring

Laravel Horizon

---

## Rules

- Semua proses berat menggunakan Queue.
- Hindari proses sinkron lebih dari 2 detik.
- Job harus idempotent.

---

## Future

- Queue Partition
- Multiple Worker
