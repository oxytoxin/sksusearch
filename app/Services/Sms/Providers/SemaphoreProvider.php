<?php

namespace App\Services\Sms\Providers;

use App\Helpers\SmsResponse;
use App\Services\Sms\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreProvider implements SmsProviderInterface
{
    protected string $apiKey;
    protected string $senderName;
    protected string $apiUrl = 'https://api.semaphore.co/api/v4/messages';

    public function __construct()
    {
        $this->apiKey = config('services.semaphore.api_key', '');
        $this->senderName = config('services.semaphore.sender_name', '');
    }

    public function send(string $number, string $message): array
    {
        try {
            $formattedNumber = $this->formatPhoneNumber($number);

            $payload = [
                'apikey' => $this->apiKey,
                'number' => $formattedNumber,
                'message' => $message,
            ];

            if (!empty($this->senderName)) {
                $payload['sendername'] = $this->senderName;
            }

            Log::info('Semaphore SMS Request:', [
                'number' => $formattedNumber,
                'message_length' => strlen($message)
            ]);

            $response = Http::timeout(30)
                ->asForm()
                ->post($this->apiUrl, $payload);

            // Check HTTP status code
            if (!$response->successful()) {
                Log::error('Semaphore API HTTP Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return SmsResponse::error(
                    'HTTP ' . $response->status() . ': ' . $response->body(),
                    $formattedNumber,
                    ['status' => $response->status(), 'body' => $response->body()]
                );
            }

            $responseData = $response->json();

            // Validate response structure
            if (!is_array($responseData)) {
                Log::error('Semaphore API Invalid Response Format', [
                    'response' => $response->body()
                ]);

                return SmsResponse::error(
                    'Invalid API response format',
                    $formattedNumber,
                    $responseData
                );
            }

            Log::info('Semaphore SMS Response:', $responseData);

            // Check for API-level errors
            if (isset($responseData['error'])) {
                return SmsResponse::error(
                    $responseData['error'],
                    $formattedNumber,
                    $responseData
                );
            }

            // Check Semaphore response format for failures
            if (isset($responseData[0]['status']) && $responseData[0]['status'] === 'failed') {
                return SmsResponse::error(
                    $responseData[0]['message'] ?? 'Semaphore returned failed status',
                    $formattedNumber,
                    $responseData
                );
            }

            // Success
            return SmsResponse::success(
                $responseData[0]['message_id'] ?? null,
                $formattedNumber,
                $responseData
            );

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Semaphore SMS Connection Failed: ' . $e->getMessage());
            return SmsResponse::error(
                'Connection error: ' . $e->getMessage(),
                $formattedNumber ?? $number
            );
        } catch (\Exception $e) {
            Log::error('Semaphore SMS Failed: ' . $e->getMessage());
            return SmsResponse::error(
                $e->getMessage(),
                $formattedNumber ?? $number
            );
        }
    }

    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters (including +)
        $phone = preg_replace('/\D/', '', $phone);

        // Convert Philippine formats to +63 format
        if (substr($phone, 0, 2) === '09') {
            return '+63' . substr($phone, 1);  // 09123456789 → +639123456789
        }

        if (substr($phone, 0, 1) === '9' && strlen($phone) === 10) {
            return '+63' . $phone;  // 9123456789 → +639123456789
        }

        if (substr($phone, 0, 3) === '639' && strlen($phone) === 12) {
            return '+' . $phone;  // 639123456789 → +639123456789
        }

        if (substr($phone, 0, 2) === '63' && strlen($phone) === 12) {
            return '+' . $phone;  // 63123456789 → +63123456789 (from +63123456789)
        }

        // If already starts with 63 and length is correct
        if (substr($phone, 0, 2) === '63' && strlen($phone) === 12) {
            return '+' . $phone;
        }

        Log::warning('Unusual phone number format for Semaphore: ' . $phone);
        return '+63' . ltrim($phone, '0');  // Fallback: add +63
    }

    public function getName(): string
    {
        return 'Semaphore';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
