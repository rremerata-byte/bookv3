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

    public function handle()
    {
        $today = Carbon::today();

        $borrowings = Borrowing::whereNull('returned_at')->whereNotNull('return_date')->get();

        foreach ($borrowings as $b) {
            $due = Carbon::parse($b->return_date)->startOfDay();
            $daysLeft = $today->diffInDays($due, false);

            // helper to avoid duplicates: check if notification exists for this borrowing and type
            $exists = function ($type) use ($b) {
                return Notification::where('type', $type)
                    ->whereJsonContains('payload->borrowing_id', $b->id)
                    ->exists();
            };

            if ($daysLeft === 3 && ! $exists('due_soon')) {
                Notification::create([
                    'user_id' => $b->user_id,
                    'type' => 'due_soon',
                    'message' => null,
                    'payload' => [
                        'borrowing_id' => $b->id,
                        'book_title' => optional($b->book)->title,
                        'due_date' => $b->return_date,
                        'days_left' => 3,
                    ],
                ]);
            }

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
