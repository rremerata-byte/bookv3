<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Check if guard user exists
$guardUser = App\Models\User::where('email', 'guard@library.com')->first();

if ($guardUser) {
    echo "Guard user found:\n";
    echo "ID: {$guardUser->id}\n";
    echo "Name: {$guardUser->fullname}\n";
    echo "Email: {$guardUser->email}\n";
    echo "Role: {$guardUser->role}\n";
    echo "Password Hash: " . substr($guardUser->password, 0, 20) . "...\n";
} else {
    echo "Guard user NOT found. Creating one...\n";
    
    $user = App\Models\User::create([
        'fullname' => 'Security Guard',
        'email' => 'guard@library.com',
        'password' => bcrypt('password123'),
        'role' => 'guard',
        'email_verified_at' => now(),
    ]);
    
    echo "Created guard user:\n";
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
}