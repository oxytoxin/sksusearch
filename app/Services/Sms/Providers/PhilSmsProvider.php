<?php

namespace App\Services\Sms\Providers;

use App\Helpers\SmsResponse;
use App\Services\Sms\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhilSmsProvider implements SmsProviderInterface
{
    protected string $apiToken;
    protected string $senderId;
    protected string $apiUrl = 'https://dashboard.philsms.com/api/v3/sms/send';

    public function __construct()
    {
        $this->apiToken = config('services.philsms.api_token', '');
        $this->senderId = config('services.philsms.sender_id', '');
    }

    public function send(string $number, string $message): array
    {
        try {
            $formattedNumber = $this->formatPhoneNumber($number);

            $payload = [
                'recipient' => $formattedNumber,
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => $message,
            ];

            Log::info('PhilSMS Request:', [
                'number' => $formattedNumber,
                'message_length' => strlen($message)
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->apiUrl, $payload);

            // Check HTTP status code
            if (!$response->successful()) {
                Log::error('PhilSMS API HTTP Error', [
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
                Log::error('PhilSMS API Invalid Response Format', [
                    'response' => $response->body()
                ]);

                return SmsResponse::error(
                    'Invalid API response format',
                    $formattedNumber,
                    $responseData
                );
            }

            Log::info('PhilSMS Response:', $responseData);

            // Check for API-level errors based on PhilSMS response format
            if (isset($responseData['status']) && $responseData['status'] === 'error') {
                return SmsResponse::error(
                    $responseData['message'] ?? 'Unknown error from PhilSMS',
                    $formattedNumber,
                    $responseData
                );
            }

            // Check for success status
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                // Extract message ID if available in the data
                $messageId = null;
                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    // PhilSMS may return the SMS report details in 'data'
                    // Try to extract a unique identifier if available
                    $messageId = $responseData['data']['uid'] ??
                                $responseData['data']['id'] ??
                                $responseData['data']['message_id'] ??
                                null;
                }

                return SmsResponse::success(
                    $messageId,
                    $formattedNumber,
                    $responseData
                );
            }

            // Unexpected response format
            return SmsResponse::error(
                'Unexpected response format from PhilSMS',
                $formattedNumber,
                $responseData
            );

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('PhilSMS Connection Failed: ' . $e->getMessage());
            return SmsResponse::error(
                'Connection error: ' . $e->getMessage(),
                $formattedNumber ?? $number
            );
        } catch (\Exception $e) {
            Log::error('PhilSMS Failed: ' . $e->getMessage());
            return SmsResponse::error(
                $e->getMessage(),
                $formattedNumber ?? $number
            );
        }
    }

    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // PhilSMS expects format: 639171234567 (country code 63 + 10-digit number)
        // Convert various Philippine formats to 639XXXXXXXXX format

        // If starts with 09, replace with 639
        if (substr($phone, 0, 2) === '09') {
            return '63' . substr($phone, 1);  // 09123456789 → 639123456789
        }

        // If starts with 9 and is 10 digits, add 63
        if (substr($phone, 0, 1) === '9' && strlen($phone) === 10) {
            return '63' . $phone;  // 9123456789 → 639123456789
        }

        // If starts with +63, remove the +
        if (substr($phone, 0, 3) === '+63') {
            return substr($phone, 1);  // +639123456789 → 639123456789
        }

        // If already in correct format (639XXXXXXXXX)
        if (substr($phone, 0, 2) === '63' && strlen($phone) === 12) {
            return $phone;
        }

        Log::warning('Unusual phone number format for PhilSMS: ' . $phone);
        return '63' . ltrim($phone, '0');  // Fallback: add 63
    }

    public function getName(): string
    {
        return 'PhilSMS';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiToken) && !empty($this->senderId);
    }
}
