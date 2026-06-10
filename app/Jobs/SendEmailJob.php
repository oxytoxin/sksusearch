<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Queued email send (mirrors App\Jobs\SendSmsJob).
 *
 * Independent and safe: a failure here logs and retries, then marks the
 * email_logs row failed — it never crashes the action that dispatched it and
 * never affects the SMS or realtime channels.
 *
 * Attachments are passed as serializable disk+path specs, e.g.:
 *   [['disk' => 'public', 'path' => 'fd/x.pdf', 'as' => 'Formal-Demand.pdf']]
 */
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $subject;
    public $title;
    public $body;
    public $context;
    public $userId;
    public $senderId;
    public $attachmentSpecs;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    public function __construct(
        string $email,
        string $subject,
        string $title,
        string $body,
        ?string $context = null,
        ?int $userId = null,
        ?int $senderId = null,
        array $attachmentSpecs = []
    ) {
        $this->email = $email;
        $this->subject = $subject;
        $this->title = $title;
        $this->body = $body;
        $this->context = $context;
        $this->userId = $userId;
        $this->senderId = $senderId;
        $this->attachmentSpecs = $attachmentSpecs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailService $emailService)
    {
        Log::info('Processing email job', [
            'to' => $this->email,
            'attempt' => $this->attempts(),
        ]);

        // Create or find existing email log
        $emailLog = EmailLog::firstOrCreate(
            [
                'recipient_email' => $this->email,
                'subject' => $this->subject,
                'status' => 'pending',
            ],
            [
                'body' => $this->body,
                'context' => $this->context,
                'user_id' => $this->userId,
                'sender_id' => $this->senderId,
                'attachments' => $this->attachmentSpecs ?: null,
            ]
        );

        $emailLog->increment('attempts');

        $result = $emailService->sendEmail(
            $this->email,
            $this->subject,
            $this->title,
            $this->body,
            null,
            $this->attachmentSpecs
        );

        // Failure (standardized result: 'error' is a string message or null)
        if (! empty($result['error'])) {
            $emailLog->update([
                'error_message' => $result['error'],
                'api_response' => $result,
            ]);

            throw new \Exception('Email sending failed: ' . $result['error']);
        }

        // Success
        $emailLog->update([
            'status' => 'sent',
            'message_id' => $result['message_id'] ?? null,
            'api_response' => $result,
            'sent_at' => now(),
            'error_message' => null,
        ]);

        Log::info('Email sent successfully', [
            'to' => $this->email,
            'log_id' => $emailLog->id,
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Email job failed permanently', [
            'to' => $this->email,
            'subject' => $this->subject,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        $emailLog = EmailLog::where('recipient_email', $this->email)
            ->where('subject', $this->subject)
            ->where('status', 'pending')
            ->first();

        if ($emailLog) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'failed_at' => now(),
            ]);
        }
    }
}
