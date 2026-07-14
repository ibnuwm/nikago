# ARC-009 - Caching

## Tujuan

Mengoptimalkan performa aplikasi menggunakan Redis.

---

## Technology

Redis

Laravel Cache

---

## Cache Strategy

### Configuration

Cache Forever

- Settings
- Feature Limits
- Subscription Plans
- Roles
- Permissions

---

### Short Cache (5 - 30 menit)

- Vendor Directory
- Landing Page
- Homepage
- FAQ
- Blog

---

### Dynamic Cache (1 - 5 menit)

- Dashboard
- Analytics
- Wedding Statistics

---

## Cache Invalidation

Update Data

↓

Forget Cache

↓

Rebuild Cache

---

## Rules

- Jangan cache data transaksi aktif.
- Cache menggunakan key yang konsisten.
- Semua cache memiliki prefix.

---

## Cache Key

tenant:{tenant_id}:settings

tenant:{tenant_id}:dashboard

vendor:{uuid}

blog:{slug}

---

## Future

- Redis Cluster
- Cache Tag
- Edge Cache
