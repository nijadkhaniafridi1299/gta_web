#!/usr/bin/env bash
set -e
cp -n .env.example .env || true
mkdir -p database storage/framework/{cache,sessions,views} storage/logs
touch database/database.sqlite
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link || true
npm install
npm run build
printf '\nInstalled. Run: php artisan serve\nAdmin: http://localhost:8000/admin\nEmail: admin@example.com\nPassword: password\n'
