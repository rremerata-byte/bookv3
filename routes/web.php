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
    Route::get('/course-subject', [\App\Http\Controllers\CourseSubjectController::class, 'index'])->name('course-subject.index');
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
    // Course & Subject routes
    Route::post('/course-subject/add-course', [\App\Http\Controllers\CourseSubjectController::class, 'addCourse'])->name('course-subject.add-course');
    Route::post('/course-subject/add-subject', [\App\Http\Controllers\CourseSubjectController::class, 'addSubject'])->name('course-subject.add-subject');
    Route::delete('/course-subject/delete-course/{id}', [\App\Http\Controllers\CourseSubjectController::class, 'deleteCourse'])->name('course-subject.delete-course');
    Route::delete('/course-subject/delete-subject/{id}', [\App\Http\Controllers\CourseSubjectController::class, 'deleteSubject'])->name('course-subject.delete-subject');
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

// Guard-only routes
Route::middleware(['auth', 'role:guard'])->group(function () {
    Route::get('/guard/dashboard', [\App\Http\Controllers\GuardController::class, 'index'])->name('guard.dashboard');
    Route::get('/guard/scan/{bookId}', [\App\Http\Controllers\GuardController::class, 'scanBook'])->name('guard.scan');
    Route::post('/guard/scan-image', [\App\Http\Controllers\GuardController::class, 'scanImage'])->name('guard.scan-image');
});


require __DIR__.'/auth.php';
