<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;

class CheckBorrowings extends Command
{
    protected $signature = 'check:borrowings';
    protected $description = 'Check borrowings return dates';

    public function handle()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->whereNull('returned_at')
            ->get();

        $this->info('Current Borrowings:');
        $this->info('==================');
        
        foreach ($borrowings as $borrowing) {
            $this->info('ID: ' . $borrowing->id);
            $this->info('Book: ' . $borrowing->book->title);
            $this->info('User: ' . $borrowing->user->fullname);
            $this->info('Borrowed Date: ' . $borrowing->borrowed_date);
            $this->info('Return Date: ' . $borrowing->return_date);
            $this->info('Return Date Type: ' . gettype($borrowing->return_date));
            $this->info('---');
        }

        return 0;
    }
}
