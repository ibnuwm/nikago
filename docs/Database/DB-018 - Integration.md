# DB-018 - Integration

## Tujuan

Mengelola seluruh integrasi pihak ketiga.

---

## Daftar Tabel

- integrations
- integration_credentials
- webhooks
- webhook_logs
- api_logs

---

# Table : integrations

## Metadata

Owner Module : Integration

Growth : Low

Cache : Redis

Retention : Permanent

---

| Column    | Type         | Nullable | Index  | Keterangan           |
| --------- | ------------ | -------- | ------ | -------------------- |
| id        | BIGINT       | No       | PK     |                      |
| code      | VARCHAR(100) | No       | UNIQUE | MIDTRANS             |
| name      | VARCHAR(255) | No       |        | Nama                 |
| category  | VARCHAR(100) | No       | INDEX  | Payment, AI, Storage |
| is_active | BOOLEAN      | No       | INDEX  | Status               |

---

# Table : integration_credentials

Credential terenkripsi.

Contoh

API Key

Secret

Webhook Secret

Client ID

Client Secret

---

# Table : webhooks

Daftar endpoint webhook.

---

# Table : webhook_logs

Seluruh request webhook.

---

# Table : api_logs

Request API keluar.

Disimpan:

- endpoint
- request
- response
- latency
- status

---

## Business Rules

- Credential wajib dienkripsi.
- API Log hanya untuk debugging.
- Webhook wajib memiliki retry mechanism.
- API timeout dicatat.

---

## Future Improvement

- API Gateway
- MCP
- Zapier
- n8n
- Make.com
