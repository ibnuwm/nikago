# API-000 - Overview

## Base URL

Development

http://localhost:8000/api/v1

Production

https://api.nikago.id/api/v1

---

## Authentication

Laravel Sanctum

Bearer Token

---

## Response Format

Semua endpoint menggunakan format berikut.

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

{
"data": [],
"meta": {
"current_page":1,
"per_page":15,
"total":120,
"last_page":8
}
}

---

## Versioning

/api/v1
