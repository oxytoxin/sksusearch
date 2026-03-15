<?php

namespace App\Helpers;

class SmsResponse
{
    /**
     * Create a standardized success response
     */
    public static function success(
        string $messageId,
        string $formattedNumber,
        array $rawResponse = []
    ): array {
        return [
            'success' => true,
            'message_id' => $messageId,
            'formatted_number' => $formattedNumber,
            'error' => null,
            'raw_response' => $rawResponse,
        ];
    }

    /**
     * Create a standardized error response
     */
    public static function error(
        string $error,
        string $formattedNumber = '',
        array $rawResponse = []
    ): array {
        return [
            'success' => false,
            'message_id' => null,
            'formatted_number' => $formattedNumber,
            'error' => $error,
            'raw_response' => $rawResponse,
        ];
    }

    /**
     * Check if response indicates success
     */
    public static function isSuccess(array $response): bool
    {
        return isset($response['success']) && $response['success'] === true;
    }

    /**
     * Check if response indicates failure
     */
    public static function isFailure(array $response): bool
    {
        return !self::isSuccess($response);
    }

    /**
     * Get error message from response
     */
    public static function getError(array $response): ?string
    {
        return $response['error'] ?? null;
    }

    /**
     * Get message ID from response
     */
    public static function getMessageId(array $response): ?string
    {
        return $response['message_id'] ?? null;
    }
}
