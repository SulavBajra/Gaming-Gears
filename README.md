# Gaming-Gears

Full‑stack web application with a **Laravel 12** backend (Inertia + Vue, Vite) and a separate **Vue 3 + Vite** frontend.

## Tech Stack
- **Backend:** PHP 8.3+, Laravel 12, Inertia.js, Fortify, Sanctum
- **Frontend (standalone):** Vue 3, Vite, PrimeVue, TailwindCSS
- **Database:** SQLite (simple local) or MySQL (Docker/Sail default)

## Repository Structure
- `backend/` — Laravel application (also includes Vite assets)
- `frontend/` — standalone Vue 3 application

---

## Requirements
### Backend
- **PHP:** 8.3+
- **Composer**
- **Node.js:** (recommended 20+)
- **npm**
- **Database:**
  - **SQLite** (quick local dev), or
  - **MySQL** (via Docker compose / Sail)

Optional:
- **Docker + Docker Compose** (for the `backend/compose.yaml` setup)

### Frontend (standalone)
- **Node.js:** `^20.19.0 || >=22.12.0` (as defined in `frontend/package.json`)
- **npm/pnpm** (project template docs mention pnpm, but `package.json` works with npm too)

---

## Setup & Run (Local)

### 1) Clone
```bash
git clone https://github.com/SulavBajra/Gaming-Gears.git
cd Gaming-Gears
```

### 2) Backend (Laravel) — Local (SQLite)
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

Run the backend:
- Terminal 1:
```bash
php artisan serve
```
- Terminal 2 (Vite assets):
```bash
npm run dev
```

Backend URL (default):
- http://127.0.0.1:8000

### Alternative: Backend one-command dev (Laravel concurrently)
From `backend/`:
```bash
composer run dev
```
This runs Laravel server + queue listener + logs (pail) + Vite together.

---

## Setup & Run (Docker)
The backend includes a Sail-style `compose.yaml` at `backend/compose.yaml`.

From `backend/`:
```bash
docker compose up --build
```
Default ports:
- App: `APP_PORT` (defaults to **80**) → http://localhost
- Vite: `VITE_PORT` (defaults to **5173**)
- phpMyAdmin: http://localhost:8080
- MySQL: `FORWARD_DB_PORT` (defaults to **3306**)

> Note: Make sure your `.env` in `backend/` has `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` set for MySQL.

---

## Frontend (Standalone Vue App)
This is a separate Vue 3 project in `frontend/`.

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
Backend environment template:
- `backend/.env.example`

Minimum values:
- `APP_KEY` (generated via `php artisan key:generate`)
- DB settings (`DB_CONNECTION`, etc.)

---

## License
Add a `LICENSE` file if you plan to distribute this project.
