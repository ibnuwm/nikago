# Nikago - Wedding Super App

Plan your perfect wedding with Nikago. A comprehensive wedding management platform.

## Tech Stack

### Backend
- Laravel 12 + PHP 8.4
- MySQL 8
- Redis
- Meilisearch
- Laravel Sanctum (Authentication)
- Spatie Permission (Authorization)
- Laravel Pulse (Monitoring)
- Laravel Scout (Search)

### Frontend
- Next.js 15
- TypeScript
- Tailwind CSS
- shadcn/ui
- TanStack Query
- Zustand
- React Hook Form + Zod

### Infrastructure
- Docker + Docker Compose
- Nginx
- MySQL 8
- Redis 7
- Meilisearch
- GitHub Actions (CI/CD)

## Project Structure

```
nikago/
├── backend/          # Laravel 12 API
├── frontend/         # Next.js 15 App
├── nginx/            # Nginx Configuration
├── .github/          # GitHub Actions
├── docs/             # Documentation
└── docker-compose.yml
```

## Getting Started

### Prerequisites
- PHP 8.4+
- Node.js 20+
- Docker & Docker Compose
- Composer

### Local Development

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd nikago
   ```

2. **Start Docker services**
   ```bash
   docker-compose up -d
   ```

3. **Backend setup**
   ```bash
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   php artisan serve
   ```

4. **Frontend setup**
   ```bash
   cd frontend
   npm install
   npm run dev
   ```

5. **Access the application**
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000/api
   - phpMyAdmin: http://localhost:8080 (if configured)

## Development

### Code Quality
```bash
# Backend
composer pint          # Format code
composer phpstan       # Static analysis
composer test          # Run tests

# Frontend
npm run lint           # Lint code
npm run build          # Build for production
```

### Environment Variables

Backend (.env):
- `DB_CONNECTION=mysql`
- `DB_HOST=mysql`
- `REDIS_HOST=redis`
- `MEILISEARCH_HOST=http://meilisearch:7700`

Frontend (.env.local):
- `NEXT_PUBLIC_API_URL=http://localhost:8000/api`

## Deployment

See [docs/Deployment/](docs/Deployment/) for deployment guides.

## License

Proprietary - All rights reserved.
