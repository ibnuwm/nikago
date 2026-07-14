# DB-017 - System

## Tujuan

Menyimpan konfigurasi global aplikasi serta aktivitas sistem.

---

## Daftar Tabel

- settings
- activity_logs
- audit_logs
- jobs
- failed_jobs
- cache_locks

---

# Table : settings

## Metadata

Owner Module : System

Growth : Low

Cache : Redis

Retention : Permanent

---

| Column     | Type         | Nullable | Index  | Keterangan    |
| ---------- | ------------ | -------- | ------ | ------------- |
| id         | BIGINT       | No       | PK     |               |
| key        | VARCHAR(150) | No       | UNIQUE | Konfigurasi   |
| value      | JSON         | Yes      |        | Nilai         |
| group      | VARCHAR(100) | No       | INDEX  | Group         |
| is_public  | BOOLEAN      | No       |        | Public Config |
| updated_at | TIMESTAMP    | No       |        |               |

---

# Table : activity_logs

Aktivitas pengguna.

Contoh

- Login
- Logout
- Update Profile
- Publish Invitation
- Upload Gallery

---

# Table : audit_logs

Audit perubahan data.

Disimpan:

- tabel
- record_id
- action
- before
- after
- user_id

---

# Table : jobs

Laravel Queue.

---

# Table : failed_jobs

Laravel Failed Queue.

---

# Table : cache_locks

Distributed Lock.

---

## Business Rules

- Audit log tidak boleh dihapus.
- Activity Log dapat diarsipkan.
- Setting dibaca melalui cache Redis.

---

## Future Improvement

- Event Sourcing
- System Health
- Error Monitoring
