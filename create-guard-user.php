<?php

require_once __DIR__.'/vendor/autoload.php';

// Initialize Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Guard User ===\n";

try {
    // Check if guard user already exists
    $existingGuard = User::where('email', 'guard@library.com')->first();
    
    if ($existingGuard) {
        echo "Guard user already exists!\n";
        echo "Email: guard@library.com\n";
        echo "Current Role: {$existingGuard->role}\n";
        
        // Update to guard role if not already
        if ($existingGuard->role !== 'guard') {
            $existingGuard->update(['role' => 'guard']);
            echo "✅ Updated user role to 'guard'\n";
        }
    } else {
        // Create new guard user
        $guard = User::create([
            'fullname' => 'Library Guard',
            'email' => 'guard@library.com',
            'password' => Hash::make('password123'),
            'studentid' => 'GUARD001',
            'courseSection' => 'Security Department',
            'gender' => 'Other',
            'phone_number' => '09632879598',
            'role' => 'guard'
        ]);
        
        echo "✅ Created new guard user!\n";
        echo "Email: guard@library.com\n";
        echo "Password: password123\n";
        echo "Role: guard\n";
    }
    
    echo "\n=== Guard User Details ===\n";
    $guardUser = User::where('email', 'guard@library.com')->first();
    echo "ID: {$guardUser->id}\n";
    echo "Name: {$guardUser->fullname}\n";
    echo "Email: {$guardUser->email}\n";
    echo "Role: {$guardUser->role}\n";
    echo "Phone: {$guardUser->phone_number}\n";
    
    echo "\n✅ Guard user ready for testing!\n";
    echo "Login at /login with:\n";
    echo "Email: guard@library.com\n";
    echo "Password: password123\n";
    
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}