# Gaming Gears

Full‑stack project with two apps:

- **Admin Panel:** `backend/` (Laravel 12 + Inertia.js + Vue, Vite)
- **E-commerce Storefront:** `frontend/` (Vue 3 + Vite)

## Tech Stack
- **Backend (Admin):** PHP 8.3+, Laravel 12, Inertia.js, Fortify, Sanctum
- **Frontend (Storefront):** Vue 3, Vite, PrimeVue, TailwindCSS
- **Database:** SQLite (local) or MySQL (Docker/Sail)

## Repository Structure
- `backend/` — Laravel application (Admin panel) + Vite assets
- `frontend/` — standalone Vue 3 storefront

---

## Requirements
### Admin (backend)
- PHP **8.3+**
- Composer
- Node.js (recommended **20+**)
- npm
- Database: SQLite or MySQL

Optional:
- Docker + Docker Compose (for `backend/compose.yaml`)

### Storefront (frontend)
- Node.js: `^20.19.0 || >=22.12.0` (from `frontend/package.json`)
- npm (or pnpm)

---

## Setup & Run

### 1) Clone
```bash
git clone https://github.com/SulavBajra/Gaming-Gears.git
cd Gaming-Gears
```

---

## Admin Panel (Laravel + Inertia) — `backend/`

### Local (SQLite)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

# SQLite database file
mkdir -p database
touch database/database.sqlite

php artisan migrate
npm install
```

Run (two terminals):
- Terminal 1:
```bash
php artisan serve
```
- Terminal 2:
```bash
npm run dev
```

Admin URL (default):
- http://127.0.0.1:8000

### One-command dev (recommended)
From `backend/`:
```bash
composer run dev
```
This runs Laravel server + queue + logs (pail) + Vite together.

### Docker (Sail-style)
From `backend/`:
```bash
docker compose up --build
```
Default ports:
- App: `APP_PORT` (defaults to **80**) → http://localhost
- Vite: `VITE_PORT` (defaults to **5173**)
- phpMyAdmin: http://localhost:8080
- MySQL: `FORWARD_DB_PORT` (defaults to **3306**)

> Ensure `backend/.env` has `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` set when using MySQL.

---

## Storefront (Vue 3) — `frontend/`

```bash
cd frontend
npm install
npm run dev
```

Build & preview:
```bash
npm run build
npm run preview
```

---

## Useful Commands
### Backend (from `backend/`)
```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan tinker
```

### Backend Vite (from `backend/`)
```bash
npm run dev
npm run build
npm run lint
npm run format
npm run types:check
```

### Frontend (from `frontend/`)
```bash
npm run dev
npm run build
npm run preview
npm run lint
```

---

## Environment Variables
Backend template:
- `backend/.env.example`

Minimum:
- `APP_KEY`
- DB settings (`DB_CONNECTION`, etc.)

---

## License
Add a `LICENSE` file if you plan to distribute this project.
