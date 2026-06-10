<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Services\EmailService;
use Illuminate\Console\Command;

/**
 * Safe testing entry point for the email channel.
 *
 * Examples:
 *   php artisan email:test you@example.com
 *   php artisan email:test you@example.com --direct
 *   php artisan email:test you@example.com --attach=public:fd/sample.pdf --attach=public:travel_order_attachments/x.pdf
 *
 * Use --direct to send synchronously through EmailService (bypasses the queue);
 * omit it to dispatch SendEmailJob (the real path, writes an email_logs row).
 */
class EmailTest extends Command
{
    protected $signature = 'email:test
                            {email : Recipient email address}
                            {--direct : Send synchronously via EmailService instead of dispatching the job}
                            {--attach=* : Attachment as disk:path (e.g. public:fd/sample.pdf), repeatable}';

    protected $description = 'Send a test notification email (queued by default, or --direct)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $subject = 'S.E.A.R.C.H Email Test';
        $title = 'Email channel is working';
        $body = "This is a test email from the S.E.A.R.C.H system sent via the '"
            . config('mail.default') . "' mailer.\n\nIf you received this, the email channel is configured correctly.";

        $attachmentSpecs = $this->parseAttachments();

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return self::FAILURE;
        }

        if ($this->option('direct')) {
            $this->info("Sending directly via '" . config('mail.default') . "' mailer...");
            $result = app(EmailService::class)->sendEmail($email, $subject, $title, $body, null, $attachmentSpecs);

            if ($result['success']) {
                $this->info("Sent to {$email}.");
                return self::SUCCESS;
            }

            $this->error('Failed: ' . $result['error']);
            return self::FAILURE;
        }

        SendEmailJob::dispatch($email, $subject, $title, $body, 'test', null, null, $attachmentSpecs);
        $this->info("Queued email to {$email} (check email_logs and your mailbox/MailHog).");

        return self::SUCCESS;
    }

    /**
     * Parse --attach=disk:path options into attachment specs.
     */
    private function parseAttachments(): array
    {
        $specs = [];

        foreach ((array) $this->option('attach') as $raw) {
            if (! str_contains($raw, ':')) {
                $this->warn("Ignoring malformed --attach value (expected disk:path): {$raw}");
                continue;
            }

            [$disk, $path] = explode(':', $raw, 2);
            $specs[] = [
                'disk' => $disk,
                'path' => $path,
                'as' => basename($path),
            ];
        }

        return $specs;
    }
}
