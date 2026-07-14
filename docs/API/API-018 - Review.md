# API-018 - Review

## Module

Review & Rating

---

## Endpoint

GET /reviews

POST /reviews

PUT /reviews/{uuid}

DELETE /reviews/{uuid}

POST /reviews/{uuid}/reply

POST /reviews/{uuid}/report

GET /vendors/{uuid}/reviews

---

## Request

{
"rating":5,
"review":"Pelayanan sangat baik."
}

---

## Rules

- Review hanya setelah booking selesai.
- Satu booking hanya satu review.
- Vendor dapat membalas review.

---

## Permission

Bride

Groom

Vendor
