<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Jobs\SendSmsJob;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class SmsTestController extends Controller
{
    /**
     * Send test SMS
     *
     * POST /api/sms/send
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'message' => 'required|string|max:500',
            'context' => 'nullable|string|max:50',
            'user_id' => 'nullable|integer',
            'sender_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                'Validation failed',
                422,
                null,
                $validator->errors()
            );
        }

        try {
            $phone = $request->input('phone');
            $message = $request->input('message');
            $context = $request->input('context', 'TEST');
            $userId = $request->input('user_id');
            $senderId = $request->input('sender_id');

            // Dispatch SMS job
            SendSmsJob::dispatch(
                $phone,
                $message,
                $context,
                $userId,
                $senderId
            );

            return ApiResponse::success([
                'phone' => $phone,
                'message' => $message,
                'context' => $context,
                'user_id' => $userId,
                'sender_id' => $senderId,
                'status' => 'queued',
                'note' => 'SMS has been queued for sending. Check sms_logs table for results.'
            ], 'SMS queued successfully');

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to queue SMS: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get SMS log by ID
     *
     * GET /api/sms/log/{id}
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLog($id)
    {
        try {
            $log = SmsLog::find($id);

            if (!$log) {
                return ApiResponse::error('SMS log not found', 404);
            }

            return ApiResponse::success([
                'id' => $log->id,
                'phone_number' => $log->phone_number,
                'formatted_phone_number' => $log->formatted_phone_number,
                'message' => $log->message,
                'status' => $log->status,
                'message_id' => $log->message_id,
                'attempts' => $log->attempts,
                'error_message' => $log->error_message,
                'context' => $log->context,
                'user_id' => $log->user_id,
                'sender_id' => $log->sender_id,
                'sent_at' => $log->sent_at,
                'failed_at' => $log->failed_at,
                'created_at' => $log->created_at,
                'updated_at' => $log->updated_at,
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve log: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get recent SMS logs
     *
     * GET /api/sms/logs?limit=10&status=sent
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $status = $request->input('status'); // sent, failed, pending
            $phone = $request->input('phone');
            $context = $request->input('context');

            $query = SmsLog::query()->latest();

            if ($status) {
                $query->where('status', $status);
            }

            if ($phone) {
                $query->where('phone_number', $phone);
            }

            if ($context) {
                $query->where('context', $context);
            }

            $logs = $query->limit($limit)->get();

            return ApiResponse::success([
                'total' => $logs->count(),
                'logs' => $logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'phone_number' => $log->phone_number,
                        'message' => $log->message,
                        'status' => $log->status,
                        'context' => $log->context,
                        'attempts' => $log->attempts,
                        'error_message' => $log->error_message,
                        'created_at' => $log->created_at,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve logs: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get SMS statistics
     *
     * GET /api/sms/stats?days=30
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        try {
            $days = $request->input('days', 30);

            $stats = [
                'success_rate' => SmsLog::getSuccessRate($days),
                'statistics' => SmsLog::getStatistics(
                    now()->subDays($days),
                    now()
                ),
                'by_context' => SmsLog::getCountByContext($days),
            ];

            return ApiResponse::success($stats);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve stats: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Test SMS provider (direct send, bypass queue)
     *
     * POST /api/sms/test-direct
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testDirect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'message' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                'Validation failed',
                422,
                null,
                $validator->errors()
            );
        }

        try {
            $phone = $request->input('phone');
            $message = $request->input('message');

            $smsService = app(SmsService::class);

            $result = $smsService->sendSms($phone, $message);

            // Check if error
            if (!empty($result['error'])) {
                return ApiResponse::error(
                    'SMS failed: ' . $result['error'],
                    400,
                    null,
                    $result
                );
            }

            return ApiResponse::success([
                'phone' => $phone,
                'message' => $message,
                'provider' => $smsService->getProviderName(),
                'result' => $result,
                'status' => 'sent',
                'message_id' => $result['message_id'] ?? null,
                'formatted_number' => $result['formatted_number'] ?? null,
            ], 'SMS sent successfully');

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to send SMS: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get current provider info
     *
     * GET /api/sms/provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvider()
    {
        try {
            $smsService = app(SmsService::class);

            return ApiResponse::success([
                'provider' => $smsService->getProviderName(),
                'config' => config('services.sms'),
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to get provider info: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Format phone number for testing
     *
     * POST /api/sms/format-phone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function formatPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                'Validation failed',
                422,
                null,
                $validator->errors()
            );
        }

        try {
            $phone = $request->input('phone');
            $smsService = app(SmsService::class);

            $formatted = $smsService->formatPhoneNumber($phone);

            return ApiResponse::success([
                'original' => $phone,
                'formatted' => $formatted,
                'provider' => $smsService->getProviderName(),
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to format phone: ' . $e->getMessage(),
                500
            );
        }
    }
}
