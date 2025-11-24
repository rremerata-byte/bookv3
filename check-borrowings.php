<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Borrowing;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "=== CHECKING BORROWING SYSTEM ===" . PHP_EOL;
echo "Date: " . Carbon::today()->toDateString() . PHP_EOL;
echo "Time: " . Carbon::now()->format('H:i:s') . PHP_EOL;
echo "=======================================\n" . PHP_EOL;

// Check if Borrowing table exists and has data
$totalBorrowings = Borrowing::count();
echo "Total borrowings in database: " . $totalBorrowings . PHP_EOL;

// Check active borrowings
$activeBorrowings = Borrowing::with(['user', 'book'])
    ->whereNull('returned_at')
    ->whereNotNull('return_date')
    ->get();

echo "Active borrowings (not returned): " . $activeBorrowings->count() . PHP_EOL;
echo "-----------------------------" . PHP_EOL;

if ($activeBorrowings->count() > 0) {
    foreach ($activeBorrowings as $b) {
        $dueDate = Carbon::parse($b->return_date);
        $today = Carbon::today();
        $daysLeft = $today->diffInDays($dueDate, false);
        
        echo "Borrowing #{$b->id}:" . PHP_EOL;
        echo "  User: " . ($b->user ? $b->user->fullname : 'Unknown') . " (ID: {$b->user_id})" . PHP_EOL;
        echo "  Book: " . ($b->book ? $b->book->title : 'Unknown') . " (ID: {$b->book_id})" . PHP_EOL;
        echo "  Due Date: " . $dueDate->format('Y-m-d') . PHP_EOL;
        echo "  Days Left: " . $daysLeft . PHP_EOL;
        
        // Check if this should trigger notifications
        if ($daysLeft === 2) {
            echo "  >>> SHOULD CREATE: due_in_2_days notification" . PHP_EOL;
        }
        if ($daysLeft === 1) {
            echo "  >>> SHOULD CREATE: due_tomorrow notification" . PHP_EOL;
        }
        if ($daysLeft === 0) {
            echo "  >>> SHOULD CREATE: due_today notification" . PHP_EOL;
        }
        if ($daysLeft < 0) {
            echo "  >>> SHOULD CREATE: overdue notification (" . abs($daysLeft) . " days)" . PHP_EOL;
        }
        
        echo "-----------------------------" . PHP_EOL;
    }
} else {
    echo "No active borrowings found!" . PHP_EOL;
    echo "-----------------------------" . PHP_EOL;
}

// Check total notifications in database
$totalNotifications = Notification::count();
echo "Total notifications in database: " . $totalNotifications . PHP_EOL;

// Check recent notifications
$recentNotifications = Notification::with('user')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "Recent notifications (last 5):" . PHP_EOL;
if ($recentNotifications->count() > 0) {
    foreach ($recentNotifications as $n) {
        echo "  ID: {$n->id}, Type: {$n->type}, User: " . ($n->user ? $n->user->fullname : 'Unknown') . ", Created: " . $n->created_at . PHP_EOL;
    }
} else {
    echo "  No notifications found!" . PHP_EOL;
}

echo "\n=== SYSTEM STATUS ===" . PHP_EOL;
echo "Users count: " . User::count() . PHP_EOL;
echo "Borrowings count: " . $totalBorrowings . PHP_EOL;
echo "Notifications count: " . $totalNotifications . PHP_EOL;