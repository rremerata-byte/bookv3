<?php

require_once __DIR__.'/vendor/autoload.php';

// Initialize Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Book;
use App\Http\Controllers\GuardController;

echo "=== Testing Guard Scanner Functionality ===\n\n";

try {
    // Get some sample books to test
    $books = Book::take(3)->get();
    
    if ($books->count() === 0) {
        echo "❌ No books found in database for testing\n";
        exit;
    }
    
    echo "Found {$books->count()} books for testing:\n";
    foreach ($books as $book) {
        echo "- Book ID: {$book->id} | Title: {$book->title} | Status: {$book->availability}\n";
    }
    echo "\n";
    
    // Test the guard controller scanner method
    $guardController = new GuardController();
    
    echo "=== Scanner Test Results ===\n\n";
    
    foreach ($books as $book) {
        echo "Testing Book ID: {$book->id}\n";
        echo "Title: {$book->title}\n";
        echo "Current Status: {$book->availability}\n";
        
        // Call the scanner method
        $response = $guardController->scanBook($book->id);
        $result = json_decode($response->getContent(), true);
        
        echo "Scanner Result: ";
        if ($result['approved']) {
            echo "✅ APPROVED - {$result['message']}\n";
        } else {
            echo "❌ NOT APPROVED - {$result['message']}\n";
        }
        echo "Status: {$result['status']}\n";
        echo "---\n\n";
    }
    
    // Test with invalid book ID
    echo "Testing Invalid Book ID (999999):\n";
    $response = $guardController->scanBook(999999);
    $result = json_decode($response->getContent(), true);
    echo "Result: {$result['status']} - {$result['message']}\n\n";
    
    echo "✅ Guard scanner functionality test completed!\n";
    echo "\nExpected behavior:\n";
    echo "- Books with 'Borrowed' status should be APPROVED ✅\n";
    echo "- Books with 'Available', 'Reserved', 'Pending' status should be NOT APPROVED ❌\n";
    echo "- Invalid book IDs should show error message\n";
    
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}
