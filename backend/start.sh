#!/bin/sh
set -e

echo "==> Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo "==> Caching config and routes..."
php artisan config:cache
php artisan route:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding..."
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=UserSeeder --force
php artisan db:seed --class=StatusSeeder --force

echo "==> Starting SSR..."
php artisan inertia:start-ssr &

echo "==> Starting server..."
exec php artisan serve --host=0.0.0.0 --port=8080
