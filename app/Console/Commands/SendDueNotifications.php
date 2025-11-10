<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;

class SendDueNotifications extends Command
{
    protected $signature = 'notifications:send-due';
    protected $description = 'Create due reminders and overdue notifications for borrowings.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        $this->info("Processing notifications for: " . $today->toDateString());

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereNull('returned_at')
            ->whereNotNull('return_date')
            ->get();

        $this->info("Found {$borrowings->count()} active borrowings");
        $created = 0;

        foreach ($borrowings as $b) {
            $due = Carbon::parse($b->return_date)->startOfDay();
            $daysLeft = (int) $today->diffInDays($due, false); // Cast to integer

            $this->info("Borrowing #{$b->id}: {$daysLeft} days left (Due: {$due->toDateString()})");

            // helper to avoid duplicates: check if notification exists for this borrowing and type
            $exists = function ($type) use ($b) {
                return Notification::where('type', $type)
                    ->where('user_id', $b->user_id)
                    ->whereJsonContains('payload->borrowing_id', $b->id)
                    ->exists();
            };

            // 2 days before due date
            if ($daysLeft === 2 && ! $exists('due_in_2_days')) {
                Notification::create([
                    'user_id' => $b->user_id,
                    'type' => 'due_in_2_days',
                    'message' => null,
                    'payload' => [
                        'borrowing_id' => $b->id,
                        'book_title' => optional($b->book)->title,
                        'due_date' => $b->return_date,
                        'days_left' => 2,
                    ],
                ]);
                $this->info("  ✓ Created due_in_2_days notification");
                $created++;
            }

            // 1 day before due date
            if ($daysLeft === 1 && ! $exists('due_tomorrow')) {
                Notification::create([
                    'user_id' => $b->user_id,
                    'type' => 'due_tomorrow',
                    'message' => null,
                    'payload' => [
                        'borrowing_id' => $b->id,
                        'book_title' => optional($b->book)->title,
                        'due_date' => $b->return_date,
                        'days_left' => 1,
                    ],
                ]);
                $this->info("  ✓ Created due_tomorrow notification");
                $created++;
            }

            // On the due date
            if ($daysLeft === 0 && ! $exists('due_today')) {
                Notification::create([
                    'user_id' => $b->user_id,
                    'type' => 'due_today',
                    'message' => null,
                    'payload' => [
                        'borrowing_id' => $b->id,
                        'book_title' => optional($b->book)->title,
                        'due_date' => $b->return_date,
                        'days_left' => 0,
                    ],
                ]);
                $this->info("  ✓ Created due_today notification");
                $created++;
            }

            // Overdue
            if ($daysLeft < 0 && ! $exists('overdue')) {
                // fine calculation can be implemented elsewhere; leave fine blank for now
                Notification::create([
                    'user_id' => $b->user_id,
                    'type' => 'overdue',
                    'message' => null,
                    'payload' => [
                        'borrowing_id' => $b->id,
                        'book_title' => optional($b->book)->title,
                        'due_date' => $b->return_date,
                        'days_overdue' => abs($daysLeft),
                    ],
                ]);
                $this->info("  ✓ Created overdue notification");
                $created++;
            }
        }

        $this->info("Done! Created {$created} notifications.");
        return 0;
    }
}