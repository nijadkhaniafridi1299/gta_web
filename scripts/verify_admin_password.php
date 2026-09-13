<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::where('email', 'admin@example.com')->first();
if (! $u) {
    echo "ADMIN NOT FOUND\n";
    exit(1);
}

$ok = Hash::check('password', $u->password);
echo $ok ? "PASSWORD OK\n" : "PASSWORD MISMATCH\n";
