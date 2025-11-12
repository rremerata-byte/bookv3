<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;

class GuardController extends Controller
{
    public function index()
    {
        return Inertia::render('Guard/Dashboard', [
            'flash' => session()->get('flash', [])
        ]);
    }

    public function scanBook($bookId)
    {
        $book = Book::find($bookId);
        
        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found',
                'approved' => false
            ]);
        }

        // Check if book status allows exit (borrowed books can exit)
        $approved = $book->availability === 'Borrowed';
        $status = $approved ? 'approved' : 'not_approved';
        
        $statusMessage = $this->getStatusMessage($book->availability, $approved);

        return response()->json([
            'status' => $status,
            'approved' => $approved,
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'availability' => $book->availability
            ],
            'message' => $statusMessage
        ]);
    }

    public function scanImage(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|max:10240', // Max 10MB
        ]);

        try {
            $image = $request->file('qr_image');
            
            // Use a QR code reader library to extract data
            // For now, we'll simulate QR code extraction
            // In production, you'd use a library like zxing or similar
            
            $qrData = $this->extractQrCodeFromImage($image);
            
            if (!$qrData) {
                return Inertia::render('Guard/Dashboard', [
                    'scanResult' => [
                        'status' => 'error',
                        'approved' => false,
                        'message' => 'No QR code found in the uploaded image',
                        'book' => null
                    ]
                ]);
            }

            // Extract book ID from QR data
            // Assuming QR code contains book ID or a URL with book ID
            $bookId = $this->extractBookIdFromQrData($qrData);
            
            if (!$bookId) {
                return Inertia::render('Guard/Dashboard', [
                    'scanResult' => [
                        'status' => 'error',
                        'approved' => false,
                        'message' => 'Invalid QR code format - no book ID found',
                        'book' => null
                    ]
                ]);
            }

            // Use existing scan logic
            $book = Book::find($bookId);
            
            if (!$book) {
                return Inertia::render('Guard/Dashboard', [
                    'scanResult' => [
                        'status' => 'error',
                        'approved' => false,
                        'message' => 'Book not found',
                        'book' => null
                    ]
                ]);
            }

            // Check if book status allows exit (borrowed books can exit)
            $approved = $book->availability === 'Borrowed';
            $status = $approved ? 'approved' : 'not_approved';
            
            $statusMessage = $this->getStatusMessage($book->availability, $approved);

            return Inertia::render('Guard/Dashboard', [
                'scanResult' => [
                    'status' => $status,
                    'approved' => $approved,
                    'book' => [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'availability' => $book->availability
                    ],
                    'message' => $statusMessage
                ]
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Guard/Dashboard', [
                'scanResult' => [
                    'status' => 'error',
                    'approved' => false,
                    'message' => 'Error processing image: ' . $e->getMessage(),
                    'book' => null
                ]
            ]);
        }
    }

    private function extractQrCodeFromImage($imageFile)
    {
        // This is a simplified simulation
        // In production, you would use a QR code reading library
        
        // For now, we'll extract from filename or return null to simulate failure
        $filename = $imageFile->getClientOriginalName();
        
        // If filename contains a number, use it as book ID for testing
        preg_match('/(\d+)/', $filename, $matches);
        
        if (!empty($matches)) {
            return "book_id:{$matches[1]}";
        }
        
        // Simulate random success/failure for demo
        $simulatedBookIds = [1, 2, 3]; // Use existing book IDs
        if (rand(1, 10) > 3) { // 70% success rate
            $randomId = $simulatedBookIds[array_rand($simulatedBookIds)];
            return "book_id:$randomId";
        }
        
        return null; // Simulate QR not found
    }

    private function extractBookIdFromQrData($qrData)
    {
        // Extract book ID from QR data
        if (preg_match('/book_id:(\d+)/', $qrData, $matches)) {
            return (int)$matches[1];
        }
        
        // Handle URL format like: http://library.com/book/123
        if (preg_match('/\/book\/(\d+)/', $qrData, $matches)) {
            return (int)$matches[1];
        }
        
        // Handle direct numeric format
        if (is_numeric($qrData)) {
            return (int)$qrData;
        }
        
        return null;
    }

    private function getStatusMessage($availability, $approved)
    {
        if ($approved) {
            return "✅ APPROVED - Borrowed book can exit";
        }

        switch ($availability) {
            case 'Available':
                return "❌ NOT APPROVED - Book is available, should not leave library";
            case 'Reserved':
                return "❌ NOT APPROVED - Book is reserved, should not leave library";
            case 'Pending':
                return "❌ NOT APPROVED - Book has pending request, should not leave library";
            default:
                return "❌ NOT APPROVED - Book status: $availability";
        }
    }
}
