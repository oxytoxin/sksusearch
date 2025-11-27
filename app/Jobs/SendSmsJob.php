<?php

namespace App\Jobs;

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
    public function __construct(string $number, string $message)
    {
        $this->number = $number;
        $this->message = $message;
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

        $result = $smsService->sendSms(
            $this->number,
            $this->message
        );

        // Check if the result indicates an error
        if (isset($result['error']) && $result['error'] === true) {
            throw new \Exception('SMS sending failed: ' . ($result['message'] ?? 'Unknown error'));
        }

        // Check Semaphore API response format for failures
        if (isset($result[0]['status']) && $result[0]['status'] === 'failed') {
            throw new \Exception('Semaphore API returned failed status: ' . ($result[0]['message'] ?? 'Unknown error'));
        }

        // Check for HTTP error status
        if (isset($result['status']) && $result['status'] === 'error') {
            throw new \Exception('Semaphore API error: ' . ($result['message'] ?? 'Unknown error'));
        }

        Log::info('SMS sent successfully', [
            'number' => $this->number,
            'message_id' => $result[0]['message_id'] ?? null
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

        // You can add additional failure handling here:
        // - Send notification to admin
        // - Store failed SMS in database
        // - Trigger alert system
    }
}
