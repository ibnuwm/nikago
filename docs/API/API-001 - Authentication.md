# API-001 - Authentication

## Module

Authentication

---

## Endpoint

POST /auth/register

POST /auth/login

POST /auth/logout

GET /auth/me

PUT /auth/profile

PUT /auth/password

POST /auth/forgot-password

POST /auth/reset-password

POST /auth/verify-email

POST /auth/resend-verification

POST /auth/google

---

# POST /auth/register

## Tujuan

Registrasi akun baru.

Permission

Guest

Request

{
"name":"",
"email":"",
"password":"",
"password_confirmation":""
}

Response

201 Created

{
"success":true,
"message":"Register success.",
"data":{
"user":{}
}
}

Validation

email unique

password min 8

---

# POST /auth/login

Permission

Guest

Request

{
"email":"",
"password":""
}

Response

200 OK

{
"success":true,
"data":{
"user":{},
"token":""
}
}

---

# POST /auth/logout

Permission

Authenticated

204 No Content

---

# GET /auth/me

Permission

Authenticated

Return

Current User

---

# PUT /auth/profile

Update Profile

---

# PUT /auth/password

Update Password

---

# POST /auth/google

Google OAuth Login
