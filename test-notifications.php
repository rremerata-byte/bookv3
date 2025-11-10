<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;

$today = Carbon::today();
echo "Today: " . $today->toDateString() . PHP_EOL;
echo "--------------------" . PHP_EOL;

$borrowings = Borrowing::with(['user', 'book'])
    ->whereNull('returned_at')
    ->whereNotNull('return_date')
    ->get();

echo "Found " . $borrowings->count() . " active borrowings" . PHP_EOL;
echo "--------------------" . PHP_EOL;

foreach ($borrowings as $b) {
    $due = Carbon::parse($b->return_date)->startOfDay();
    $daysLeft = $today->diffInDays($due, false);
    
    echo "Borrowing ID: " . $b->id . PHP_EOL;
    echo "  User ID: " . $b->user_id . PHP_EOL;
    echo "  Book: " . ($b->book ? $b->book->title : 'N/A') . PHP_EOL;
    echo "  Due Date: " . $due->toDateString() . PHP_EOL;
    echo "  Days Left: " . $daysLeft . PHP_EOL;
    
    // Check if notification already exists
    $exists = Notification::where('type', 'due_tomorrow')
        ->whereJsonContains('payload->borrowing_id', $b->id)
        ->exists();
    echo "  Notification exists: " . ($exists ? 'YES' : 'NO') . PHP_EOL;
    
    // Check conditions
    if ($daysLeft === 2) {
        echo "  >>> Should create 'due_in_2_days' notification" . PHP_EOL;
    }
    if ($daysLeft === 1) {
        echo "  >>> Should create 'due_tomorrow' notification" . PHP_EOL;
    }
    if ($daysLeft === 0) {
        echo "  >>> Should create 'due_today' notification" . PHP_EOL;
    }
    if ($daysLeft < 0) {
        echo "  >>> Should create 'overdue' notification (days overdue: " . abs($daysLeft) . ")" . PHP_EOL;
    }
    
    echo "--------------------" . PHP_EOL;
}
