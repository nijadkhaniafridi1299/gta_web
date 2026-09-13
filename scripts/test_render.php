<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/', 'GET');
$response = $app->handle($request);
echo "Home Status: " . $response->getStatusCode() . PHP_EOL;
$content = $response->getContent();
if (strpos($content, 'Grand Theft Auto VI — Standard Edition') !== false) {
    echo "✓ Home page displays GTA VI Standard Edition" . PHP_EOL;
} else {
    echo "✗ Home page did NOT contain GTA VI Standard Edition" . PHP_EOL;
}

$request2 = Illuminate\Http\Request::create('/products', 'GET');
$response2 = $app->handle($request2);
echo "Products Page Status: " . $response2->getStatusCode() . PHP_EOL;
$content2 = $response2->getContent();
if (strpos($content2, 'Grand Theft Auto VI — Standard Edition') !== false) {
    echo "✓ Products page displays GTA VI Standard Edition" . PHP_EOL;
} else {
    echo "✗ Products page did NOT contain GTA VI Standard Edition" . PHP_EOL;
}
