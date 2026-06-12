@php
    $statusColors = [
        'sent' => 'text-green-700 bg-green-50 border-green-200',
        'failed' => 'text-red-700 bg-red-50 border-red-200',
        'pending' => 'text-yellow-700 bg-yellow-50 border-yellow-200',
    ];
    $statusClass = $statusColors[$record->status] ?? 'text-gray-700 bg-gray-50 border-gray-200';
@endphp

<div class="space-y-4 text-sm">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Recipient</span>
            <p class="font-semibold text-gray-800 break-words">{{ $record->recipient_email }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Status</span>
            <p>
                <span class="inline-block rounded border px-2 py-0.5 text-xs font-semibold {{ $statusClass }}">
                    {{ ucfirst($record->status) }}
                </span>
            </p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Context</span>
            <p class="text-gray-800">{{ $record->context ? ucwords(str_replace('_', ' ', $record->context)) : '—' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Sent By</span>
            <p class="text-gray-800">{{ $record->sender?->employee_information?->full_name ?? 'System' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Created</span>
            <p class="text-gray-800">{{ $record->created_at?->format('M d, Y g:i A') ?? '—' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Sent At</span>
            <p class="text-gray-800">{{ $record->sent_at?->format('M d, Y g:i A') ?? '—' }}</p>
        </div>
    </div>

    <div>
        <span class="text-xs font-medium text-gray-500 uppercase">Subject</span>
        <p class="font-semibold text-gray-800">{{ $record->subject }}</p>
    </div>

    <div>
        <span class="text-xs font-medium text-gray-500 uppercase">Message</span>
        <div class="mt-1 rounded border border-gray-200 bg-gray-50 p-3 whitespace-pre-wrap text-gray-700">{{ $record->body }}</div>
    </div>

    @if ($record->status === 'failed' && $record->error_message)
        <div>
            <span class="text-xs font-medium text-red-500 uppercase">Error</span>
            <div class="mt-1 rounded border border-red-200 bg-red-50 p-3 whitespace-pre-wrap text-red-700">{{ $record->error_message }}</div>
        </div>
    @endif

    @if (!empty($record->attachments))
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Attachments</span>
            <ul class="mt-1 list-disc list-inside text-gray-700">
                @foreach ($record->attachments as $attachment)
                    <li>{{ $attachment['as'] ?? ($attachment['path'] ?? 'Attachment') }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
