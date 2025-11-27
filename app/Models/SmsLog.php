<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'formatted_phone_number',
        'message',
        'status',
        'message_id',
        'attempts',
        'error_message',
        'api_response',
        'sent_at',
        'failed_at',
        'context',
        'user_id',
        'sender_id',
    ];

    protected $casts = [
        'api_response' => 'array',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Get the user who received the SMS
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who sent/triggered the SMS
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope: Get failed SMS
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Get sent SMS
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Get pending SMS
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get SMS by phone number
     */
    public function scopeByPhone($query, string $phone)
    {
        return $query->where('phone_number', $phone)
                    ->orWhere('formatted_phone_number', $phone);
    }

    /**
     * Scope: Get recent failures for a phone number
     */
    public function scopeRecentFailures($query, string $phone, int $days = 7)
    {
        return $query->byPhone($phone)
                    ->failed()
                    ->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get SMS for a specific context (FMR, FMD, etc.)
     */
    public function scopeByContext($query, string $context)
    {
        return $query->where('context', $context);
    }

    /**
     * Get SMS success rate as percentage
     */
    public static function getSuccessRate(int $days = 30): float
    {
        $total = self::where('created_at', '>=', now()->subDays($days))->count();

        if ($total === 0) {
            return 0;
        }

        $sent = self::sent()->where('created_at', '>=', now()->subDays($days))->count();

        return round(($sent / $total) * 100, 2);
    }

    /**
     * Get SMS statistics for a date range
     */
    public static function getStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        $query = self::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $total = $query->count();
        $sent = (clone $query)->where('status', 'sent')->count();
        $failed = (clone $query)->where('status', 'failed')->count();
        $pending = (clone $query)->where('status', 'pending')->count();

        return [
            'total' => $total,
            'sent' => $sent,
            'failed' => $failed,
            'pending' => $pending,
            'success_rate' => $total > 0 ? round(($sent / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get most problematic phone numbers (highest failure rate)
     */
    public static function getProblematicNumbers(int $limit = 10, int $days = 30): array
    {
        return self::select('phone_number')
            ->selectRaw('COUNT(*) as total_attempts')
            ->selectRaw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_attempts')
            ->selectRaw('ROUND((SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as failure_rate')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('phone_number')
            ->having('total_attempts', '>=', 3)
            ->orderByDesc('failure_rate')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get SMS count by context (FMR, FMD, SCO, etc.)
     */
    public static function getCountByContext(int $days = 30): array
    {
        return self::select('context')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent')
            ->selectRaw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('context')
            ->groupBy('context')
            ->get()
            ->keyBy('context')
            ->toArray();
    }

    /**
     * Get daily SMS volume for charting
     */
    public static function getDailyVolume(int $days = 30): array
    {
        return self::selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent')
            ->selectRaw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Check if phone number should be blacklisted
     */
    public function shouldBeBlacklisted(): bool
    {
        $threshold = config('services.sms.blacklist_threshold', 10);
        $days = config('services.sms.blacklist_period_days', 30);

        $failures = self::recentFailures($this->phone_number, $days)->count();

        return $failures >= $threshold;
    }
}
