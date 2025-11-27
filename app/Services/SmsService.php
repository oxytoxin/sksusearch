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

            // Validate API key is configured
            if (empty($this->apiKey)) {
                throw new \Exception('Semaphore API key is not configured');
            }

            $payload = [
                'apikey' => $this->apiKey,
                'number' => $formattedNumber,
                'message' => $message,
                // 'sendername' => $this->senderName, // optional
            ];

            Log::info('Semaphore SMS Request:', [
                'number' => $formattedNumber,
                'message_length' => strlen($message)
            ]);

            $response = Http::timeout(30)
                ->asForm()
                ->post('https://api.semaphore.co/api/v4/messages', $payload);

            // Check HTTP status code
            if (!$response->successful()) {
                Log::error('Semaphore API HTTP Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'error' => true,
                    'message' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                ];
            }

            $responseData = $response->json();

            // Validate response structure
            if (!is_array($responseData)) {
                Log::error('Semaphore API Invalid Response Format', [
                    'response' => $response->body()
                ]);

                return [
                    'error' => true,
                    'message' => 'Invalid API response format',
                ];
            }

            Log::info('Semaphore SMS Response:', $responseData);

            // Check for API-level errors
            if (isset($responseData['error'])) {
                Log::error('Semaphore API Error', [
                    'error' => $responseData['error']
                ]);

                return [
                    'error' => true,
                    'message' => $responseData['error'],
                ];
            }

            return $responseData;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Semaphore SMS Connection Failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Semaphore SMS Failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
