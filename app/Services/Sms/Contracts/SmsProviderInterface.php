<?php

namespace App\Services\Sms\Contracts;

interface SmsProviderInterface
{
    /**
     * Send SMS message to a phone number
     *
     * @param string $number The recipient phone number
     * @param string $message The message content
     * @return array Standardized response array with success/error information
     */
    public function send(string $number, string $message): array;

    /**
     * Format phone number according to provider requirements
     *
     * @param string $phone The phone number to format
     * @return string The formatted phone number
     */
    public function formatPhoneNumber(string $phone): string;

    /**
     * Get the provider name
     *
     * @return string The name of the SMS provider
     */
    public function getName(): string;

    /**
     * Check if the provider is properly configured
     *
     * @return bool True if configured, false otherwise
     */
    public function isConfigured(): bool;
}
