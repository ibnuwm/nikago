# DB-001 - Authentication

## Tujuan

Mengelola autentikasi, otorisasi, dan identitas pengguna.

---

## Daftar Tabel

users

password_reset_tokens

sessions

roles

permissions

model_has_roles

model_has_permissions

role_has_permissions

social_accounts

email_verifications

---

# Table : users

## Deskripsi

Menyimpan seluruh akun pengguna.

### Columns

id

uuid

tenant_id

name

email

email_verified_at

password

avatar

phone

status

last_login_at

remember_token

created_at

updated_at

deleted_at

created_by

updated_by

deleted_by

### Relationships

belongsTo Tenant

hasMany Social Account

hasMany Session

hasMany Wedding

hasMany Notification

---

Business Rules

Email Unique

Password Hash

Soft Delete

Avatar Nullable

---

# Table : social_accounts

Digunakan untuk Login Google.

Columns

id

user_id

provider

provider_id

access_token

refresh_token

expired_at

---

Future

Apple Login

Facebook Login

Magic Link

WhatsApp Login
