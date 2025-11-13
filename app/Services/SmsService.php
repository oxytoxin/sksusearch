<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiKey;
    protected string $senderName;

    public function __construct()
    {
        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
    }

    /**
     * Format phone number to +63 format
     */
    public function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (substr($phone, 0, 2) === '09') {
            return '+63' . substr($phone, 1);
        }

        if (substr($phone, 0, 1) === '9') {
            return '+63' . $phone;
        }

        if (substr($phone, 0, 3) === '639') {
            return '+'.$phone;
        }

        if (substr($phone, 0, 3) === '+63') {
            return $phone;
        }

        Log::error('Invalid phone number format (Semaphore): ' . $phone);
        return $phone;
    }

    /**
     * Send SMS via Semaphore API
     */
    public function sendSms(string $number, string $message): array
    {
        try {
            $formattedNumber = $this->formatPhoneNumber($number);

            $payload = [
                'apikey' => $this->apiKey,
                'number' => $formattedNumber,
                'message' => $message,
                // 'sendername' => $this->senderName, // optional
            ];

            Log::info('Semaphore SMS Request:', $payload);

            $response = Http::asForm()
                ->post('https://api.semaphore.co/api/v4/messages', $payload);

            $responseData = $response->json();

            Log::info('Semaphore SMS Response:', $responseData);

            return $responseData;
        } catch (\Exception $e) {
            Log::error('Semaphore SMS Failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
