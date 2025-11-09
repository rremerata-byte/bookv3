<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $senderName;
    protected $enabled;
    protected $apiUrl = 'https://api.semaphore.co/api/v4/messages';

    public function __construct()
    {
        $this->apiKey = config('services.sms.api_key');
        $this->senderName = config('services.sms.sender_name');
        $this->enabled = config('services.sms.enabled', false);
    }

    /**
     * Send SMS message
     *
     * @param string $phoneNumber
     * @param string $message
     * @return bool
     */
    public function send($phoneNumber, $message)
    {
        if (!$this->enabled) {
            Log::info('SMS sending is disabled', [
                'phone' => $phoneNumber,
                'message' => $message
            ]);
            return false;
        }

        if (!$this->apiKey) {
            Log::error('SMS API key not configured');
            return false;
        }

        // Clean phone number (remove spaces, dashes, etc.)
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);

        try {
            $response = Http::post($this->apiUrl, [
                'apikey' => $this->apiKey,
                'number' => $phoneNumber,
                'message' => $message,
                'sendername' => $this->senderName,
            ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('SMS sending failed', [
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number for Philippines
     * Converts to format: 639XXXXXXXXX
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If starts with 0, replace with 63
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '63' . substr($phoneNumber, 1);
        }

        // If doesn't start with 63, add it
        if (substr($phoneNumber, 0, 2) !== '63') {
            $phoneNumber = '63' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Send due date reminder SMS
     *
     * @param string $phoneNumber
     * @param string $bookTitle
     * @param string $dueDate
     * @param int $daysLeft
     * @return bool
     */
    public function sendDueReminder($phoneNumber, $bookTitle, $dueDate, $daysLeft)
    {
        $message = match ($daysLeft) {
            2 => "Library Reminder: Your borrowed book '{$bookTitle}' is due in 2 days ({$dueDate}). Please return on time. Thank you!",
            1 => "Library Reminder: Your borrowed book '{$bookTitle}' is due TOMORROW ({$dueDate}). Please return on time. Thank you!",
            0 => "Library Reminder: Your borrowed book '{$bookTitle}' is due TODAY ({$dueDate}). Please return to avoid late fees. Thank you!",
            default => "Library Reminder: Your borrowed book '{$bookTitle}' is due on {$dueDate}. Please return on time. Thank you!"
        };

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send overdue notification SMS
     *
     * @param string $phoneNumber
     * @param string $bookTitle
     * @param int $daysOverdue
     * @return bool
     */
    public function sendOverdueNotification($phoneNumber, $bookTitle, $daysOverdue)
    {
        $message = "Library Notice: Your borrowed book '{$bookTitle}' is {$daysOverdue} day(s) OVERDUE. Please return it immediately to avoid additional fines. Thank you!";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Send request approved SMS
     *
     * @param string $phoneNumber
     * @param string $bookTitle
     * @param string $requestType
     * @param string $dueDate
     * @return bool
     */
    public function sendRequestApproved($phoneNumber, $bookTitle, $requestType, $dueDate)
    {
        $message = "Good news! Your {$requestType} request for '{$bookTitle}' has been approved. Due date: {$dueDate}. Please collect from the library. Thank you!";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Send request rejected SMS
     *
     * @param string $phoneNumber
     * @param string $bookTitle
     * @param string $requestType
     * @return bool
     */
    public function sendRequestRejected($phoneNumber, $bookTitle, $requestType)
    {
        $message = "Library Notice: Your {$requestType} request for '{$bookTitle}' has been declined. Please contact the library for more information. Thank you!";
        return $this->send($phoneNumber, $message);
    }
}
