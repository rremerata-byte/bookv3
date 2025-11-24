<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use Carbon\Carbon;

echo "=== RECENT NOTIFICATIONS ===" . PHP_EOL;

$recent = Notification::with('user')->orderBy('created_at', 'desc')->limit(15)->get();

foreach ($recent as $n) {
    echo "ID: {$n->id}" . PHP_EOL;
    echo "  Type: {$n->type}" . PHP_EOL;
    echo "  User: " . ($n->user ? $n->user->fullname : 'Unknown') . " (ID: {$n->user_id})" . PHP_EOL;
    echo "  Created: {$n->created_at}" . PHP_EOL;
    echo "  Read: " . ($n->read ? 'Yes' : 'No') . PHP_EOL;
    if ($n->payload) {
        echo "  Payload: " . json_encode($n->payload) . PHP_EOL;
    }
    echo "-----------------------------" . PHP_EOL;
}