<?php

namespace App\Services;

use App\Mail\GeneralNotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Thin wrapper around Laravel's native mail layer.
 *
 * Provider swapping is handled natively by Laravel via the MAIL_MAILER env
 * value (smtp / resend / ses / mailgun / log / ...), so this service does NOT
 * implement a custom provider abstraction the way SmsService does — it simply
 * sends through whichever mailer is configured and returns a standardized
 * result array (mirrors App\Helpers\SmsResponse).
 */
class EmailService
{
    /**
     * Send a notification email.
     *
     * @param  string  $to               recipient email address
     * @param  string  $subject          email subject line
     * @param  string  $title            heading shown in the email body
     * @param  string  $body             message body (plain text; rendered into the template)
     * @param  string|null  $actionUrl   optional "View Details" link
     * @param  array  $attachmentSpecs   list of ['disk' => ..., 'path' => ..., 'as' => ..., 'mime' => ...]
     * @return array{success: bool, error: string|null, raw_response: mixed}
     */
    public function sendEmail(
        string $to,
        string $subject,
        string $title,
        string $body,
        ?string $actionUrl = null,
        array $attachmentSpecs = []
    ): array {
        try {
            $mailable = new GeneralNotificationMail($subject, $title, $body, $actionUrl, $attachmentSpecs);

            Mail::to($to)->send($mailable);

            return [
                'success' => true,
                'error' => null,
                'raw_response' => [
                    'mailer' => $this->getMailerName(),
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('Email send failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'raw_response' => null,
            ];
        }
    }

    /**
     * Get the active mailer name (the current "provider").
     */
    public function getMailerName(): string
    {
        return config('mail.default', 'smtp');
    }
}
