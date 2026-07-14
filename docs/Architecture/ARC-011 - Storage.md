# ARC-011 - Storage

## Tujuan

Mengelola penyimpanan file aplikasi.

---

## Technology

Cloudflare R2

---

## File Type

- Avatar
- Gallery
- Invitation Assets
- Vendor Portfolio
- Blog Image
- Invoice PDF
- Contract
- AI Generated Image

---

## Folder Structure

avatars/

vendors/

weddings/

invitations/

blogs/

contracts/

invoices/

ai/

---

## Rules

- Tidak menyimpan file di server lokal.
- Gunakan object key.
- URL dibuat melalui Storage Service.
- Semua upload divalidasi.

---

## Future

- CDN
- Image Optimization
- Versioning
