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

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereNull('returned_at')
            ->whereNotNull('return_date')
            ->get();

        foreach ($borrowings as $b) {
            $due = Carbon::parse($b->return_date)->startOfDay();
            $daysLeft = $today->diffInDays($due, false);

            // helper to avoid duplicates: check if notification exists for this borrowing and type
            $exists = function ($type) use ($b) {
                return Notification::where('type', $type)
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
            }
        }

        $this->info('Due notifications processed.');
        return 0;
    }
}