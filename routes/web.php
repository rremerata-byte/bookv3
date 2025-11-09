<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Inertia\Inertia;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\BookRequestController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// Dashboard route: redirect user role to homepage, allow admin to dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'user') {
            return redirect()->route('student.home');
        }
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');
});

// Admin-only GET routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/available-books', [BookController::class, 'index'])->name('books.index');
    Route::get('/student-list', [DashboardController::class, 'studentList'])->name('students.index');
    Route::get('/add-book', [BookController::class, 'create'])->name('books.create');
});

// User-only GET routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/student/home', [StudentController::class, 'index'])->name('student.home');
});

// Both admin and user can access profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/basic', [ProfileController::class, 'updateBasicProfile'])->name('profile.update.basic');
});

// Admin-only actions
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/students', [DashboardController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{user}', [DashboardController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{user}', [DashboardController::class, 'deleteStudent'])->name('students.delete');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::patch('/book-requests/{id}/approve', [BookRequestController::class, 'approve'])->name('book-requests.approve');
    Route::patch('/book-requests/{id}/reject', [BookRequestController::class, 'reject'])->name('book-requests.reject');
    Route::delete('/books/{book}/reserve', [BookController::class, 'cancelReservation'])->name('reserve.destroy');
    Route::post('/books/{book}/return', [BookController::class, 'return'])->name('receipts.return');
    Route::post('/books/{book}/return-reservation', [BookController::class, 'returnReservation'])->name('reserve.return');
});


Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/student/home', [StudentController::class, 'index'])->name('student.home');
    Route::post('/student/reserve-book', [StudentController::class, 'reserveBook'])->name('student.reserve-book');
    Route::post('/student/request-book', [BookRequestController::class, 'store'])->name('book-requests.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/basic', [ProfileController::class, 'updateBasicProfile'])->name('profile.update.basic');
    // QR code endpoint for both admin and user
    Route::get('/books/{id}/qrcode', [BookController::class, 'getQrCode'])->name('books.qrcode');
    // Notification actions for users
    Route::post('/notifications/{notification}/toggle-read', [\App\Http\Controllers\NotificationController::class, 'toggleRead'])->name('notifications.toggle-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});



require __DIR__.'/auth.php';
