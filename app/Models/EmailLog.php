<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_email',
        'subject',
        'body',
        'status',
        'message_id',
        'attempts',
        'error_message',
        'api_response',
        'attachments',
        'sent_at',
        'failed_at',
        'context',
        'user_id',
        'sender_id',
    ];

    protected $casts = [
        'api_response' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Get the user who received the email
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who sent/triggered the email
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope: Get failed emails
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Get sent emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Get pending emails
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get emails by recipient address
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('recipient_email', $email);
    }

    /**
     * Scope: Get emails for a specific context
     */
    public function scopeByContext($query, string $context)
    {
        return $query->where('context', $context);
    }

    /**
     * Get email success rate as percentage
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
     * Get email statistics for a date range
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
     * Get email count by context
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
}
