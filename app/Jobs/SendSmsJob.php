<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $number;
    public $message;
    public $context;
    public $userId;
    public $senderId;

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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $number,
        string $message,
        ?string $context = null,
        ?int $userId = null,
        ?int $senderId = null
    ) {
        $this->number = $number;
        $this->message = $message;
        $this->context = $context;
        $this->userId = $userId;
        $this->senderId = $senderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SmsService $smsService)
    {
        Log::info('Processing SMS job', [
            'number' => $this->number,
            'attempt' => $this->attempts()
        ]);

        // Create or find existing SMS log
        $smsLog = SmsLog::firstOrCreate(
            [
                'phone_number' => $this->number,
                'message' => $this->message,
                'status' => 'pending',
            ],
            [
                'context' => $this->context,
                'user_id' => $this->userId,
                'sender_id' => $this->senderId,
            ]
        );

        // Update attempt count
        $smsLog->increment('attempts');

        $result = $smsService->sendSms(
            $this->number,
            $this->message
        );

        // Store formatted phone number from service
        if (isset($result['formatted_number'])) {
            $smsLog->formatted_phone_number = $result['formatted_number'];
            $smsLog->save();
        }

        // Check if there's an error (new standardized format)
        // $result['error'] is now a STRING (error message), not boolean
        if (!empty($result['error'])) {
            $smsLog->update([
                'error_message' => $result['error'],
                'api_response' => $result,
            ]);

            throw new \Exception('SMS sending failed: ' . $result['error']);
        }

        // Success - update log
        $smsLog->update([
            'status' => 'sent',
            'message_id' => $result['message_id'] ?? null,
            'api_response' => $result,
            'sent_at' => now(),
            'error_message' => null,
        ]);

        Log::info('SMS sent successfully', [
            'number' => $this->number,
            'message_id' => $result['message_id'] ?? null,
            'log_id' => $smsLog->id
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
        Log::error('SMS job failed permanently', [
            'number' => $this->number,
            'message' => $this->message,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Update SMS log to failed status
        $smsLog = SmsLog::where('phone_number', $this->number)
            ->where('message', $this->message)
            ->where('status', 'pending')
            ->first();

        if ($smsLog) {
            $smsLog->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'failed_at' => now(),
            ]);
        }

        // You can add additional failure handling here:
        // - Send notification to admin
        // - Trigger alert system
    }
}
