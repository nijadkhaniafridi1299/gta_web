# Digital Game Store — Laravel + Filament

A starter digital game-key marketplace. It supports products, categories, encrypted game keys, inventory, cart, checkout, orders, and a Filament admin panel.

> **Important:** only import and sell game keys that you are legally authorized to resell. This project does not generate or validate unauthorized keys.

## Requirements
- PHP 8.2+
- Composer
- Node.js 20+
- SQLite (default) or MySQL

Laravel's current installation docs recommend PHP/Composer plus Node/NPM for frontend assets. Filament 5 is used by this project. See the official docs linked below.

## Install

```bash
composer install
cp .env.example .env
php artisan key:generate
mkdir -p database
# Windows PowerShell: New-Item database/database.sqlite -ItemType File
# Linux/macOS: touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Open http://localhost:8000

Admin: http://localhost:8000/admin

Seeded admin:
- Email: admin@example.com
- Password: password

Change the password immediately in a real deployment.

## Features
- Public storefront and product pages
- Session cart
- Checkout demo payment flow
- Automatic assignment of one available key after a successful demo payment
- Customer order page with delivered key
- Filament resources for categories, products, game keys and orders
- Bulk key import action in Filament
- Game keys are encrypted at rest using Laravel's encrypted cast
- Inventory counts and low-stock indicator

## Production work still required
Before accepting real money, integrate a supported payment provider, add payment webhooks/signature verification, configure email/queue workers, add fraud/rate-limit controls, HTTPS, backups, taxes/refunds, legal pages, and verify your right to resell each product.
