Copy-Item .env.example .env -ErrorAction SilentlyContinue
New-Item database/database.sqlite -ItemType File -Force | Out-Null
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
Write-Host "Installed. Run: php artisan serve"
Write-Host "Admin: http://localhost:8000/admin"
Write-Host "Email: admin@example.com"
Write-Host "Password: password"
