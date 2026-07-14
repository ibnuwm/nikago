# API-999 - Response Standard

## Success Response

200 OK

{
"success": true,
"message": "Success",
"data": {},
"meta": null,
"request_id": "req_01J...",
"timestamp": "2026-07-14T10:00:00Z"
}

---

## Validation Error

422 Unprocessable Entity

{
"success": false,
"message": "Validation Error",
"errors": {
"email": [
"The email field is required."
]
},
"request_id": "req_01J...",
"timestamp": "2026-07-14T10:00:00Z"
}

---

## Unauthorized

401 Unauthorized

{
"success": false,
"message": "Unauthorized"
}

---

## Forbidden

403 Forbidden

{
"success": false,
"message": "Forbidden"
}

---

## Not Found

404 Not Found

{
"success": false,
"message": "Resource not found"
}

---

## Server Error

500 Internal Server Error

{
"success": false,
"message": "Internal Server Error"
}

---

## Pagination

{
"success": true,
"message": "Success",
"data": [],
"meta": {
"current_page": 1,
"per_page": 15,
"total": 120,
"last_page": 8
},
"request_id": "req_01J...",
"timestamp": "2026-07-14T10:00:00Z"
}

---

## Error Code Convention

AUTH_001

AUTH_002

PAYMENT_001

VENDOR_001

AI_001

SUBSCRIPTION_001

SYSTEM_001
