<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Database connection: " . config('database.default') . PHP_EOL;
echo "Database name: " . config('database.connections.' . config('database.default') . '.database') . PHP_EOL;
echo "Products count: " . \App\Models\Product::count() . PHP_EOL;
foreach (\App\Models\Product::all() as $p) {
    echo "- ID: {$p->id} | Name: {$p->name} | Active: " . ($p->active ? 'yes' : 'no') . " | Category: " . ($p->category->name ?? 'None') . PHP_EOL;
}
