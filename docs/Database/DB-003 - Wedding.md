# DB-003 - Wedding

## Tujuan

Mengelola data utama pernikahan.

---

## Daftar Tabel

weddings

wedding_events

couples

love_stories

wedding_galleries

wedding_schedules

---

# Table : weddings

Columns

id

uuid

tenant_id

title

slug

status

theme

cover_image

published_at

created_at

updated_at

deleted_at

---

Relationships

belongsTo Tenant

hasOne Couple

hasMany Event

hasMany Gallery

hasMany Invitation

---

Business Rules

Satu tenant dapat memiliki banyak wedding

Slug Unique per Tenant

Status

Draft

Published

Archived

---

# Table : couples

Columns

id

wedding_id

groom_name

bride_name

groom_parent

bride_parent

groom_photo

bride_photo

---

# Table : wedding_events

Columns

id

wedding_id

event_type

title

venue

address

latitude

longitude

start_at

end_at

---

# Table : love_stories

Columns

id

wedding_id

title

description

event_date

sort_order

---

# Table : wedding_galleries

Columns

id

wedding_id

image

caption

sort_order

---

Future

Multiple Event

Engagement

Reception

Ngunduh Mantu

Live Streaming

Multi Language
