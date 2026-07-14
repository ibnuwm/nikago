# DB-002 - Tenant

## Tujuan

Mengelola Multi Tenant pada Nikago.

---

## Daftar Tabel

tenants

tenant_domains

tenant_settings

tenant_members

---

# Table : tenants

Columns

id

uuid

name

slug

subscription_id

owner_id

status

created_at

updated_at

deleted_at

---

Relationships

belongsTo User

hasMany Wedding

hasMany Invitation

hasMany Guest

hasMany Vendor

---

Business Rules

Slug Unique

Owner wajib User

Satu tenant memiliki banyak user

---

# Table : tenant_domains

Columns

id

tenant_id

domain

is_primary

ssl_status

verified_at

---

# Table : tenant_members

Columns

id

tenant_id

user_id

role

joined_at

---

Future

White Label

Custom Email

Custom SMTP
