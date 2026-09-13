<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

$u = User::where('email','admin@example.com')->first();
if (! $u) { echo "NO USER\n"; exit(1); }

$ok = Hash::check('password', $u->password);
echo "Hash check: ".($ok ? 'OK' : 'FAIL')."\n";

// Try to authenticate using the default guard
$attempt = Auth::attempt(['email'=>'admin@example.com','password'=>'password']);
echo "Auth::attempt result: ".($attempt ? 'OK' : 'FAIL')."\n";
