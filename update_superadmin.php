<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Handle potential conflict with existing user
$existingUser = User::where('email', 'roy.kamto@yaksaersadasolusindo.com')->where('role', '!=', 'superadmin')->first();
if ($existingUser) {
    $existingUser->email = 'old_' . $existingUser->email;
    $existingUser->save();
}

// Update the superadmin
$u = User::where('role', 'superadmin')->first();
if ($u) {
    $u->email = 'roy.kamto@yaksaersadasolusindo.com';
    $u->name = 'Super Admin Roy Kamto';
    $u->password = Hash::make('Yaksaersadasolusindo2023$');
    $u->save();
    echo "Superadmin updated successfully.\n";
} else {
    echo "Superadmin not found.\n";
}
