# API-004 - Wedding

## Module

Wedding

---

## Endpoint

GET /weddings
POST /weddings
GET /weddings/{uuid}
PUT /weddings/{uuid}
DELETE /weddings/{uuid}

PATCH /weddings/{uuid}/publish
PATCH /weddings/{uuid}/archive

---

## GET /weddings

### Tujuan

Menampilkan daftar wedding milik user.

Authentication

Required

Permission

Bride

Groom

Admin

Query

page

per_page

search

status

sort

Response

200 OK

---

## POST /weddings

### Tujuan

Membuat data wedding baru.

Authentication

Required

Request

{
"title":"",
"bride_name":"",
"groom_name":"",
"wedding_date":"",
"venue":""
}

Response

201 Created

---

## GET /weddings/{uuid}

Detail wedding.

---

## PUT /weddings/{uuid}

Update wedding.

---

## DELETE /weddings/{uuid}

Soft Delete.

---

## PATCH /weddings/{uuid}/publish

Publish wedding.

---

## PATCH /weddings/{uuid}/archive

Archive wedding.
