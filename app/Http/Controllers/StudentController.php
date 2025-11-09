<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StudentController extends Controller
{
    public function index()
    {
        $books = Book::all()->map(function ($book) {
            return [
                'id' => $book->id,
                'bookId' => $book->bookId,  // ✅ ADDED THIS - Required for QR scanner!
                'title' => $book->title,
                'description' => $book->description,
                'course' => $book->course,
                'image_url' => $book->image_path ? Storage::url($book->image_path) : null,
                'author' => $book->author,
                'availability' => $book->availability,
                'publicationDate' => $book->publicationDate ? $book->publicationDate->format('Y-m-d') : null
            ];
        });

        // Fetch all borrow/reserve requests for the authenticated user
        $myRequests = \App\Models\BookRequest::with('book')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'book_title' => $request->book ? $request->book->title : '',
                    'request_type' => $request->request_type,
                    'request_date' => $request->request_date,
                    'return_date' => $request->return_date,
                    'status' => $request->status
                ];
            });

        // fetch notifications for the authenticated user
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'message' => $n->message,
                    'payload' => $n->payload,
                    'read' => (bool) $n->read,
                    'created_at' => $n->created_at,
                ];
            });

        return Inertia::render('Student/HomePage', [
            'books' => $books,
            'myRequests' => $myRequests,
            'notifications' => $notifications,
        ]);
    }

    public function reserveBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'reservation_date' => 'required|date',
            'return_date' => 'required|date|after:reservation_date',
        ]);

        // Check if book is available
        $book = Book::find($request->book_id);
        if ($book->availability !== 'Available') {
            return response()->json([
                'message' => 'Book is not available for reservation'
            ], 400);
        }
        // Create a BookRequest instead of immediately creating a reservation
        // so the admin can approve it. Do not update book availability here.
        $bookRequest = BookRequest::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'request_type' => 'reserve',
            'request_date' => $request->reservation_date,
            'return_date' => $request->return_date,
            'status' => 'pending',
        ]);

        // Create notification for the user (non-blocking)
        try {
            \App\Models\Notification::create([
                'user_id' => Auth::id(),
                'type' => 'request_submitted',
                'message' => null,
                'payload' => [
                    'book_title' => optional($book)->title,
                    'request_type' => 'reserve',
                    'request_date' => $request->reservation_date,
                    'request_id' => $bookRequest->id,
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Notification create failed (reserve): ' . $e->getMessage());
        }

        // Return an Inertia-friendly redirect so the frontend router handles it gracefully
        return redirect()->back()->with('success', 'Reservation request submitted successfully');
    }
}