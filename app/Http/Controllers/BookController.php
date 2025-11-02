<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\BookAvailabilityService;
use Illuminate\Support\Facades\Storage;
use App\Models\BookAction;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class BookController extends Controller
{
    // Shared logic for top 5 most request users (borrow/reserve)
    private function getTopRequestUsers($limit = 5)
    {
        return \App\Models\User::select('id', 'fullname')
            ->withCount(['bookRequests as total_requests' => function($query) {
                $query->whereIn('request_type', ['borrow', 'reserve']);
            }])
            ->orderByDesc('total_requests')
            ->limit($limit)
            ->get()
            ->map(fn($user) => [
                'fullname' => $user->fullname,
                'total_requests' => $user->total_requests
            ]);
    }
    protected $availabilityService;

    public function __construct(BookAvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    const STATUS_AVAILABLE = 'Available';
    const STATUS_BORROWED = 'Borrowed';
    const STATUS_RESERVED = 'Reserved';

    public function index()
    {
        // Fetch all books with necessary relationships
        $books = Book::with([
            'bookRequests.user',  // Eager load book requests with user details
            'borrowings.user',    // Eager load borrowings with user details
            'reservations.user',  // Eager load reservations with user details
            'currentBorrower',    // Eager load current borrower relationship
            'currentReserver'     // Eager load current reserver relationship
        ])->get()->map(function ($book) {
            $currentBorrowing = $book->borrowings
                ->where('returned_at', null)
                ->first();  // Get the current borrowing where the book hasn't been returned

            // Prefer an approved BookRequest's return_date if present (admin approved request)
            $approvedBorrowRequest = null;
            if ($book->relationLoaded('bookRequests')) {
                $approvedBorrowRequest = $book->bookRequests
                    ->filter(function ($r) {
                        try {
                            return $r->status === 'approved' && $r->request_type === 'borrow' && Carbon::parse($r->return_date)->greaterThanOrEqualTo(Carbon::today());
                        } catch (\Exception $e) {
                            return false;
                        }
                    })
                    ->sortByDesc(function ($r) {
                        return Carbon::parse($r->return_date)->timestamp;
                    })
                    ->first();
            }

            $currentReserver = $book->reservations
                ->where('returned_date', null)
                ->first();  // Get the current borrowing where the book hasn't been returned
    
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'bookId' => $book->bookId,
                'publicationDate' => $book->publicationDate,
                'description' => $book->description,
                'course' => $book->course,
                'availability' => $book->availability,
                'image_url' => $book->image_path ? Storage::url($book->image_path) : null,
                'current_borrower' => ($currentBorrowing || $approvedBorrowRequest) ? [
                    'fullname' => $currentBorrowing?->user?->fullname ?? $approvedBorrowRequest?->user?->fullname,
                    'borrowed_date' => $currentBorrowing?->borrowed_date ?? null,
                    // prefer approved request return_date when available, else use borrowing return_date
                    'due_date' => $approvedBorrowRequest ? ($approvedBorrowRequest->return_date instanceof \Carbon\Carbon ? $approvedBorrowRequest->return_date->toDateString() : $approvedBorrowRequest->return_date) : ($currentBorrowing?->return_date ?? null),
                    'phone_number' => $currentBorrowing?->user?->phone_number ?? $approvedBorrowRequest?->user?->phone_number,
                ] : null,
                'current_reserver' => $currentReserver ? [
                    'fullname' => $currentReserver->user?->fullname,
                    'until_date' => $currentReserver?->return_date,
                    'phone_number' => $currentReserver->user?->phone_number,
                ] : null,
            ];
        });
    
        // Fetch book requests with necessary relationships
        $bookRequests = BookRequest::with(['user', 'book'])
            ->latest('created_at')  // Use 'latest' for sorting
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'book' => [
                        'title' => $request->book?->title  // Use null-safe operator
                    ],
                    'user' => [
                        'fullname' => $request->user?->fullname  // Updated to safely fetch user field
                    ],
                    'request_type' => $request->request_type,
                    'request_date' => $request->request_date,
                    'return_date' => $request->return_date,
                    'status' => $request->status
                ];
            });
    
        // Top 5 users with most borrow/reserve requests
        $topRequestUsers = $this->getTopRequestUsers(5);

        // Return data to the Inertia view
        return Inertia::render('Books/AvailableBooks', [
            'books' => $books,
            'bookRequests' => $bookRequests,
            'topRequestUsers' => $topRequestUsers
        ]);
    }
    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'bookId' => 'required|string|unique:books',
            'publicationDate' => 'required|date',
            'description' => 'nullable|string',
            'course' => 'nullable|string|max:255',
            'availability' => 'required|in:Available,Borrowed,Reserved',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $path;
        }

        $book = Book::create($validated);

        // Generate QR code for the book using simplesoftwareio/simple-qrcode
        // The QR code can encode the bookId, or a URL to the book details
        $qrData = $book->bookId; // You can change this to a URL if needed
    $qrCodeSvg = (string) QrCode::format('svg')->size(200)->generate($qrData);

        // Return QR code SVG to the frontend via Inertia
        return Inertia::render('Books/AddBooks', [
            'message' => 'Book added successfully',
            'qrCodeSvg' => $qrCodeSvg
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'course' => 'nullable|string|max:255',
            'availability' => 'required|in:Available,Borrowed,Reserved',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            
            $path = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $path;
        }

        $book->update($validated);

        return back()->with('message', 'Book updated successfully');
    }

    public function destroy(Book $book)
    {
        if ($this->availabilityService->canDelete($book)) {
            // Delete the image if exists
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            
            $book->delete();
            return back()->with('message', 'Book deleted successfully');
        }

        return back()->withErrors(['message' => 'Cannot delete book that is currently borrowed or reserved']);
    }
    public function create()
    {
        return Inertia::render('Books/AddBooks');
    }

    public function return(Book $book)
    {
        $user = Auth::user();
        
        // Create book action record
        BookAction::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'type' => 'return',
            'action_date' => now(),
        ]);

        // Mark the current borrowing as returned (if any) and notify the borrower
        try {
            $borrowing = Borrowing::where('book_id', $book->id)
                ->whereNull('returned_at')
                ->latest('borrowed_date')
                ->first();

            if ($borrowing) {
                $borrowing->update(['returned_at' => now()]);

                // send notification to the borrower
                try {
                    Notification::create([
                        'user_id' => $borrowing->user_id,
                        'type' => 'book_returned',
                        'message' => null,
                        'payload' => [
                            'book_title' => $book->title,
                            'returned_at' => now()->toDateTimeString(),
                            'borrowing_id' => $borrowing->id,
                        ],
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed creating return notification: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating borrowing on return: ' . $e->getMessage());
        }

        // Update book status
        $book->update(['availability' => 'Available']);
        
        return redirect()->back()->with('success', 'Book returned successfully');
    }

    // Add this method to handle reservation cancellations
    public function cancelReservation(Book $book)
    {
        $user = Auth::user();
        
        // Create book action record
        BookAction::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'type' => 'cancellation',
            'action_date' => now(),
        ]);

        // Update book status
        $book->update(['availability' => 'Available']);
        
        return redirect()->back()->with('success', 'Reservation cancelled successfully');
    }

    // Generate QR code for a specific book
    public function getQrCode($id)
    {
        try {
            $book = Book::findOrFail($id);
            
            if (!$book->bookId) {
                return response()->json([
                    'error' => 'Book ID is missing for this book'
                ], 400);
            }
            
            // Ensure bookId is a string
            $qrData = (string) $book->bookId;
            
            // Generate QR code with explicit settings
            $qrCodeSvg = QrCode::format('svg')
                ->size(200)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($qrData);
            
            // Convert to string - this should work with SimpleSoftwareIO QR code
            $qrCodeString = $qrCodeSvg instanceof \Illuminate\Support\HtmlString 
                ? $qrCodeSvg->toHtml() 
                : (string) $qrCodeSvg;
            
            // Trim whitespace
            $qrCodeString = trim($qrCodeString);
            
            // Verify we actually have SVG content
            if (empty($qrCodeString)) {
                \Log::error('QR code generation returned empty', [
                    'book_id' => $id,
                    'bookId' => $qrData
                ]);
                return response()->json([
                    'error' => 'QR code generation failed - empty result'
                ], 500);
            }
            
            // Check if it's valid SVG
            if (!str_starts_with($qrCodeString, '<?xml') && !str_starts_with($qrCodeString, '<svg')) {
                \Log::error('Invalid SVG format generated', [
                    'book_id' => $id,
                    'preview' => substr($qrCodeString, 0, 100)
                ]);
                return response()->json([
                    'error' => 'Invalid QR code format generated'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'qrCodeSvg' => $qrCodeString,
                'bookId' => $book->bookId,
                'bookTitle' => $book->title
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Book not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('QR code generation exception', [
                'book_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

}