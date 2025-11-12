<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Get books
$books = App\Models\Book::all(['id', 'title', 'availability']);

echo "Available Books:\n";
foreach ($books as $book) {
    echo "ID: {$book->id}, Title: {$book->title}, Status: {$book->availability}\n";
}

// Create a test borrowed book for testing
$borrowedBook = App\Models\Book::where('availability', 'Borrowed')->first();

if (!$borrowedBook) {
    echo "\nNo borrowed books found. Creating one for testing...\n";
    
    $book = App\Models\Book::first();
    if ($book) {
        $book->update(['availability' => 'Borrowed']);
        echo "Updated book ID {$book->id} to 'Borrowed' status\n";
    } else {
        echo "No books found in database\n";
    }
} else {
    echo "\nFound borrowed book: ID {$borrowedBook->id}, Title: {$borrowedBook->title}\n";
}