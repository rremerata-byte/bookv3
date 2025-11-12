<?php

require_once __DIR__.'/vendor/autoload.php';

// Initialize Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;

echo "=== Creating Test Borrowed Book ===\n\n";

try {
    // Find an available book
    $book = Book::where('availability', 'Available')->first();
    
    if (!$book) {
        echo "❌ No available books found\n";
        exit;
    }
    
    // Find a regular user (not admin/guard)
    $user = User::where('role', 'user')->first();
    
    if (!$user) {
        echo "❌ No regular user found\n";
        exit;
    }
    
    echo "Creating test borrowing:\n";
    echo "Book: {$book->title} (ID: {$book->id})\n";
    echo "User: {$user->fullname} (ID: {$user->id})\n\n";
    
    // Create a borrowing record
    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrowed_date' => now(),
        'return_date' => now()->addDays(7), // Due in 7 days
    ]);
    
    // Update book status
    $book->update(['availability' => 'Borrowed']);
    
    echo "✅ Test borrowing created!\n";
    echo "Borrowing ID: {$borrowing->id}\n";
    echo "Due Date: {$borrowing->return_date}\n";
    echo "Book status updated to: Borrowed\n\n";
    
    // Test the scanner now
    $guardController = new \App\Http\Controllers\GuardController();
    $response = $guardController->scanBook($book->id);
    $result = json_decode($response->getContent(), true);
    
    echo "=== Scanner Test ===\n";
    echo "Book ID: {$book->id}\n";
    echo "Title: {$book->title}\n";
    echo "Status: {$book->availability}\n";
    echo "Scanner Result: ";
    if ($result['approved']) {
        echo "✅ APPROVED - {$result['message']}\n";
    } else {
        echo "❌ NOT APPROVED - {$result['message']}\n";
    }
    
    echo "\n✅ Test borrowed book created and scanner tested!\n";
    echo "You can now test the guard scanner with book ID: {$book->id}\n";
    
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}