<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class CheckNotifications extends Command
{
    protected $signature = 'check:notifications';
    protected $description = 'Check recent notifications';

    public function handle()
    {
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $this->info('Recent Notifications:');
        $this->info('===================');
        
        foreach ($notifications as $notification) {
            $this->info('Type: ' . $notification->type);
            $this->info('User: ' . optional($notification->user)->fullname);
            $this->info('Created: ' . $notification->created_at);
            $this->info('Payload: ' . json_encode($notification->payload));
            $this->info('---');
        }

        return 0;
    }
}
