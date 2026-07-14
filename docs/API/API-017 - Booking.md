# API-017 - Booking

## Module

Vendor Booking

---

## Endpoint

GET /bookings

POST /bookings

GET /bookings/{uuid}

PUT /bookings/{uuid}

DELETE /bookings/{uuid}

PATCH /bookings/{uuid}/confirm

PATCH /bookings/{uuid}/cancel

PATCH /bookings/{uuid}/complete

POST /bookings/{uuid}/contract

GET /bookings/calendar

GET /bookings/history

---

## Request

{
"vendor_uuid":"",
"package_uuid":"",
"event_date":"",
"notes":""
}

---

## Status

Pending

Confirmed

Completed

Cancelled

---

## Permission

Bride

Groom

Vendor

Wedding Organizer
