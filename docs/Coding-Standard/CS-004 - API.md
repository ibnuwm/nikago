# CS-004 - API

## API Standard

Base URL

/api/v1

---

## Method

GET

POST

PUT

PATCH

DELETE

---

## Response

Semua API wajib menggunakan format yang sama.

Success

{
"success": true,
"message": "Success",
"data": {}
}

Error

{
"success": false,
"message": "Validation Error",
"errors": {}
}

---

## HTTP Status

200 OK

201 Created

204 No Content

400 Bad Request

401 Unauthorized

403 Forbidden

404 Not Found

409 Conflict

422 Validation Error

429 Too Many Requests

500 Internal Server Error

---

## Pagination

Semua endpoint list wajib menggunakan pagination.

---

## Versioning

Semua endpoint menggunakan

/api/v1

---

## Resource

Seluruh response menggunakan Laravel API Resource.

---

## Validation

Seluruh request menggunakan Form Request.

---

## Error

Gunakan format error yang konsisten pada seluruh endpoint.
