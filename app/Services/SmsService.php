<?php

namespace App\Services;

use App\Helpers\SmsResponse;
use App\Services\Sms\Contracts\SmsProviderInterface;
use App\Services\Sms\Providers\SemaphoreProvider;
use App\Services\Sms\Providers\PhilSmsProvider;

use Illuminate\Support\Facades\Log;

class SmsService
{
    protected SmsProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->resolveProvider();
    }

    /**
     * Resolve the SMS provider based on configuration
     */
    protected function resolveProvider(): SmsProviderInterface
    {
        $providerName = config('services.sms.default_provider', 'semaphore');

        $providers = [
            'semaphore' => SemaphoreProvider::class,
            'philsms' => PhilSmsProvider::class,
        ];

        if (!isset($providers[$providerName])) {
            Log::error("Unknown SMS provider: {$providerName}. Falling back to Semaphore.");
            $providerName = 'semaphore';
        }

        $providerClass = $providers[$providerName];
        $provider = new $providerClass();

        if (!$provider->isConfigured()) {
            Log::warning("SMS provider '{$providerName}' is not properly configured.");
        }

        Log::info("Using SMS provider: " . $provider->getName());

        return $provider;
    }

    /**
     * Format phone number using current provider
     */
    public function formatPhoneNumber(string $phone): string
    {
        return $this->provider->formatPhoneNumber($phone);
    }

    /**
     * Get current provider name
     */
    public function getProviderName(): string
    {
        return $this->provider->getName();
    }

    /**
     * Send SMS via configured provider
     */
    public function sendSms(string $number, string $message): array
    {
        // Check rate limiting (only if enabled) - return unified format
        $rateLimitEnabled = config('services.sms.rate_limit_enabled', false);
        if ($rateLimitEnabled && $this->isRateLimited($number)) {
            Log::warning('SMS rate limit exceeded', ['number' => substr($number, 0, 3) . 'XXX']);
            return SmsResponse::error(
                'Too many SMS attempts. Please try again later.',
                $this->formatPhoneNumber($number)
            );
        }

        // Check if number is blacklisted (only if enabled) - return unified format
        $blacklistEnabled = config('services.sms.blacklist_enabled', false);
        if ($blacklistEnabled && $this->isBlacklisted($number)) {
            Log::warning('SMS blocked: Blacklisted number', ['number' => substr($number, 0, 3) . 'XXX']);
            return SmsResponse::error(
                'This phone number is blocked from receiving SMS.',
                $this->formatPhoneNumber($number)
            );
        }

        Log::info('Sending SMS', [
            'provider' => $this->provider->getName(),
            'number' => substr($number, 0, 3) . 'XXX',  // Partially masked for privacy
        ]);

        $result = $this->provider->send($number, $message);

        // Convert provider response to legacy format for backward compatibility
        return $this->convertToLegacyFormat($result);
    }

    /**
     * Check if number has exceeded rate limit
     * Prevents sending too many SMS to same number in short time
     */
    protected function isRateLimited(string $number): bool
    {
        $maxAttemptsPerHour = config('services.sms.rate_limit_per_hour', 5);

        $recentAttempts = \App\Models\SmsLog::where('phone_number', $number)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $recentAttempts >= $maxAttemptsPerHour;
    }

    /**
     * Check if number is blacklisted
     * Numbers with too many failures get blacklisted automatically
     */
    protected function isBlacklisted(string $number): bool
    {
        $failureThreshold = config('services.sms.blacklist_threshold', 10);
        $failurePeriodDays = config('services.sms.blacklist_period_days', 30);

        $recentFailures = \App\Models\SmsLog::where('phone_number', $number)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subDays($failurePeriodDays))
            ->count();

        return $recentFailures >= $failureThreshold;
    }

    /**
     * Convert standardized provider response to legacy format
     * This ensures backward compatibility with existing code
     */
    protected function convertToLegacyFormat(array $result): array
    {
        // New standardized format
        if (isset($result['success'])) {
            // If successful, return the raw response with success indicators
            if ($result['success']) {
                return array_merge($result['raw_response'], [
                    'formatted_number' => $result['formatted_number'],
                    'message_id' => $result['message_id'],
                ]);
            }


            return [
                'error' => true,
                'message' => $result['error'],
                'formatted_number' => $result['formatted_number'],
            ];
        }


        return $result;
    }
}
