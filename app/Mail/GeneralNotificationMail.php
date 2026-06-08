<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Reusable notification email.
 *
 * Body is rendered into a branded blade template. Files can be attached by
 * passing $attachmentSpecs as an array of disk+path descriptors, e.g.:
 *   [['disk' => 'public', 'path' => 'fd/demand.pdf', 'as' => 'Formal-Demand.pdf']]
 *
 * Specs are plain serializable arrays (NOT file objects), so this Mailable is
 * safe to queue; the file is streamed from storage at send time. For large
 * files prefer putting a download link in the body instead of attaching.
 */
class GeneralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $title;
    public string $bodyMessage;
    public ?string $actionUrl;
    public array $attachmentSpecs;

    /**
     * @param  array  $attachmentSpecs  list of ['disk' => ..., 'path' => ..., 'as' => ..., 'mime' => ...]
     */
    public function __construct(
        string $subject,
        string $title,
        string $message,
        ?string $actionUrl = null,
        array $attachmentSpecs = []
    ) {
        $this->subjectLine = $subject;
        $this->title = $title;
        $this->bodyMessage = $message;
        $this->actionUrl = $actionUrl;
        $this->attachmentSpecs = $attachmentSpecs;
    }

    public function envelope()
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.general-notification',
            with: [
                'title' => $this->title,
                'bodyMessage' => $this->bodyMessage,
                'actionUrl' => $this->actionUrl,
            ],
        );
    }

    /**
     * Build attachments from the disk+path specs, skipping any file that is
     * missing or larger than the configured cap (so a bad attachment never
     * blocks the email from sending).
     *
     * @return array
     */
    public function attachments()
    {
        $maxBytes = (int) config('services.email.max_attachment_mb', 10) * 1024 * 1024;
        $attachments = [];

        foreach ($this->attachmentSpecs as $spec) {
            $disk = $spec['disk'] ?? config('filesystems.default');
            $path = $spec['path'] ?? null;

            if (! $path) {
                continue;
            }

            try {
                if (! Storage::disk($disk)->exists($path)) {
                    Log::warning('Email attachment skipped: file not found', compact('disk', 'path'));
                    continue;
                }

                if (Storage::disk($disk)->size($path) > $maxBytes) {
                    Log::warning('Email attachment skipped: exceeds size cap', [
                        'disk' => $disk,
                        'path' => $path,
                        'max_mb' => config('services.email.max_attachment_mb', 10),
                    ]);
                    continue;
                }

                $attachment = Attachment::fromStorageDisk($disk, $path)
                    ->as($spec['as'] ?? basename($path));

                if (! empty($spec['mime'])) {
                    $attachment->withMime($spec['mime']);
                }

                $attachments[] = $attachment;
            } catch (\Throwable $e) {
                Log::warning('Email attachment skipped: ' . $e->getMessage(), compact('disk', 'path'));
            }
        }

        return $attachments;
    }
}
