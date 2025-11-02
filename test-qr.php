<?php
// Simple test script to verify QR code generation
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Book;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

echo "Testing QR Code Generation...\n\n";

// Get first book
$book = Book::first();

if (!$book) {
    echo "❌ No books found in database!\n";
    exit(1);
}

echo "✓ Book found: {$book->title}\n";
echo "✓ Book ID (database): {$book->id}\n";
echo "✓ BookId field: {$book->bookId}\n\n";

try {
    // Generate QR code
    $qrCodeSvg = QrCode::format('svg')
        ->size(200)
        ->margin(1)
        ->errorCorrection('H')
        ->generate($book->bookId);
    
    $qrString = (string) $qrCodeSvg;
    
    echo "✓ QR code generated successfully!\n";
    echo "✓ QR code length: " . strlen($qrString) . " characters\n";
    echo "✓ Starts with: " . substr($qrString, 0, 50) . "...\n\n";
    
    if (str_starts_with($qrString, '<?xml') || str_starts_with($qrString, '<svg')) {
        echo "✅ QR code is valid SVG format!\n";
        echo "\n🎉 Everything is working correctly!\n";
    } else {
        echo "❌ QR code is NOT valid SVG format!\n";
        echo "Content preview: " . substr($qrString, 0, 200) . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error generating QR code: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
