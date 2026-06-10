# Email Integration — Setup & Provider Guide

> **Status: SCAFFOLDING IMPLEMENTED — not yet wired to any feature.**
> The reusable email plumbing (service, queued job, log table, Mailable, test command) is built and tested on branch `feature/email-channel-setup`. **Nothing dispatches it yet**, so there is zero impact on existing flows. To go live: pick a provider, set the `.env` block below, then call `SendEmailJob::dispatch(...)` from the desired feature(s). See **§11 Usage** for the API.

Email is being added as a **third notification channel** for **high-importance transactions** — events where the client wants a durable, official record in the recipient's inbox, beyond the realtime bell pop-up and the (paid) SMS.

The three channels are **independent**: realtime in-app, SMS, and (future) email. Any one failing must never crash an action or affect the others.

---

## 1. Compatibility — validated ✅

| Item | Result |
|---|---|
| Laravel | `^9.19` (9.52 installed) |
| PHP | 8.3 (Resend needs 8.1+) ✅ |
| Filament | `^2.0` — uses Laravel mail underneath, inherits any mailer automatically |
| `config/mail.php` | Already defines `smtp`, `ses`, `mailgun`, `postmark`, `sendmail`, `log`, `array`, `failover` |
| Resend via **SMTP** | ✅ Works today, **no package needed** |
| Resend via **API driver** | ⚠️ Needs version pin `resend/resend-laravel:^0.23` (v1.x requires Laravel 10+) |
| Recipient email | `User.email` — **real institutional addresses** (`@sksu.edu.ph`), unique + required, `Notifiable` trait present |

### Corrections to the original integration guide
- The project uses **beyondcode/laravel-websockets** (Pusher protocol), **not** Reverb/Echo.
- SMS in this project is **not** a notification `via()` channel — it is dispatched via `SendSmsJob::dispatch()` at each call site with its own `sms_logs` audit table. **Email will mirror this SMS job-dispatch pattern** (not `via()`), so we get the same per-message logging and transparency.

---

## 2. Architecture — mirror SMS, reuse Laravel's native mail abstraction

The client wants: *"change provider by editing `.env` only, just like SMS; logged; transparent about what wasn't sent; safe; maintainable."*

Key decision: **SMS needed a custom provider layer** (`SmsProviderInterface` + Semaphore/PhilSMS classes) only because Laravel has no built-in SMS abstraction. **Laravel already abstracts email providers natively** — so we do **not** rebuild that. We get provider-swapping for free and only build the logging layer.

| Concern | SMS (existing) | Email (planned) |
|---|---|---|
| Swap provider via `.env` | `SMS_PROVIDER` + custom provider classes | **`MAIL_MAILER`** (native Laravel) — no custom classes |
| Queued send + retries | `SendSmsJob` (`$tries=3`, `$backoff=[60,300,900]`) | `SendEmailJob` (same) |
| Per-message audit log | `sms_logs` + `SmsLog` model | `email_logs` + `EmailLog` model (same shape) |
| Service wrapper | `SmsService` (provider + rate limit + blacklist) | `EmailService` (thin wrapper over `Mail::mailer()->send()` + log) |
| Toggles in `config/services.php` | `sms` section | new `email` section |
| Dispatch at call sites | `SendSmsJob::dispatch(...)`, guarded, try/catch | `SendEmailJob::dispatch(...)`, guarded, try/catch |

**Result:** switching Resend ↔ SES ↔ Mailgun ↔ SMTP ↔ MailHog is a **one-line `.env` change** (`MAIL_MAILER`) — exactly the SMS-like flexibility requested, with less code to maintain.

---

## 3. `.env` setup — step by step

Current `.env` (local) uses MailHog:
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3a. Local testing — MailHog (catches mail, never sends out)
Keep the block above. All "sent" mail appears in the MailHog UI (`http://localhost:8025`). Zero risk, no real emails leave.

### 3b. Staging testing — free personal SMTP (300–500/day)
Use any free SMTP (e.g. a personal Gmail App Password, Brevo/Sendinblue free tier ~300/day, Mailtrap). Example (Gmail App Password):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=youraccount@gmail.com
MAIL_PASSWORD=your_16_char_app_password   # NOT your real password — an App Password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="youraccount@gmail.com"
MAIL_FROM_NAME="S.E.A.R.C.H"
```

### 3c. Production Option A — Resend over SMTP (recommended first; no package)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_your_api_key_here        # Resend API key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.edu.ph"   # must be on a verified domain
MAIL_FROM_NAME="S.E.A.R.C.H"
```

### 3d. Production Option B — Resend API driver (later; richer tracking)
Adds delivery/bounce/open events via the Resend dashboard & webhooks.
```bash
composer require resend/resend-laravel:^0.23   # pin: required for Laravel 9
```
Add a mailer in `config/mail.php` under `'mailers'`:
```php
'resend' => [
    'transport' => 'resend',
],
```
Then `.env`:
```env
MAIL_MAILER=resend
RESEND_API_KEY=re_your_api_key_here
MAIL_FROM_ADDRESS="noreply@yourdomain.edu.ph"
MAIL_FROM_NAME="S.E.A.R.C.H"
```
> If package auto-discovery is disabled, register `Resend\Laravel\ResendServiceProvider` in `config/app.php`.

### 3e. ⚠️ Required before sending from your own address — verify the domain
In the Resend dashboard, verify the sending domain (add the SPF/DKIM DNS records). Until then you can only send from the test address `onboarding@resend.dev`. Sending *to* any address works during testing; sending *from* your domain needs verification.

### 3f. Proposed custom toggles (mirror the `sms` section) — for the next phase
To be added to `config/services.php` and `.env` when the code lands:
```env
EMAIL_LOG_ENABLED=true                 # write every attempt to email_logs
EMAIL_RATE_LIMIT_ENABLED=false
EMAIL_RATE_LIMIT_PER_HOUR=20
```

---

## 4. How to switch providers later (the maintainability promise)

Change **one line** plus that provider's credentials — nothing else in the code:

| Provider | `.env` change |
|---|---|
| Resend (SMTP) | `MAIL_MAILER=smtp` + `MAIL_HOST=smtp.resend.com`, `MAIL_USERNAME=resend`, `MAIL_PASSWORD=re_…` |
| Resend (API) | `MAIL_MAILER=resend` + `RESEND_API_KEY=re_…` (needs the `^0.23` package) |
| Amazon SES | `MAIL_MAILER=ses` + `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION` |
| Mailgun | `MAIL_MAILER=mailgun` + `MAILGUN_DOMAIN`, `MAILGUN_SECRET` |
| Postmark | `MAIL_MAILER=postmark` + `POSTMARK_TOKEN` |
| Local capture | `MAIL_MAILER=log` (writes to `storage/logs`) or MailHog |

The `SendEmailJob` / `EmailService` / `email_logs` layer is provider-agnostic — it never changes when you swap providers.

---

## 5. Future implementation blueprint — NOT YET IMPLEMENTED (next phase)

When approved, create these files (each mirrors its SMS counterpart). Skeletons below are starting points.

### 5.1 Migration — `database/migrations/xxxx_create_email_logs_table.php`
Mirrors `sms_logs` (see `database/migrations/2025_11_27_194546_create_sms_logs_table.php`):
```php
Schema::create('email_logs', function (Blueprint $table) {
    $table->id();
    $table->string('recipient_email');
    $table->string('subject');
    $table->text('body')->nullable();
    $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
    $table->string('message_id')->nullable();
    $table->integer('attempts')->default(0);
    $table->text('error_message')->nullable();
    $table->json('api_response')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('failed_at')->nullable();
    $table->string('context')->nullable();          // e.g. 'disbursement_voucher_returned'
    $table->unsignedBigInteger('user_id')->nullable();   // recipient
    $table->unsignedBigInteger('sender_id')->nullable(); // who triggered
    $table->timestamps();
    $table->index('recipient_email');
    $table->index('status');
    $table->index('created_at');
    $table->index('user_id');
});
```

### 5.2 Model — `app/Models/EmailLog.php`
Mirror `app/Models/SmsLog.php`: same fillable, `api_response`→array cast, `sent_at`/`failed_at`→datetime, `user()`/`sender()` relations, scopes (`sent`/`failed`/`pending`/`byContext`), and stats helpers (`getStatistics`, `getSuccessRate`, `getCountByContext`).

### 5.3 Service — `app/Services/EmailService.php`
Thin wrapper; no provider classes needed (native mailer handles providers):
```php
public function sendEmail(string $to, string $subject, string $body): array
{
    try {
        Mail::mailer(config('mail.default'))->html($body, function ($m) use ($to, $subject) {
            $m->to($to)->subject($subject);
        });
        return ['success' => true, 'error' => null, 'raw_response' => null];
    } catch (\Throwable $e) {
        return ['success' => false, 'error' => $e->getMessage(), 'raw_response' => null];
    }
}
```
(A dedicated Mailable + Blade template can replace the inline `html()` for branded layouts.)

### 5.4 Job — `app/Jobs/SendEmailJob.php`
Mirror `app/Jobs/SendSmsJob.php` exactly:
- Constructor `($email, $subject, $body, $context = null, $userId = null, $senderId = null)`
- `$tries = 3`, `$backoff = [60, 300, 900]`, `$timeout = 30`
- `handle(EmailService $svc)`: create `pending` `EmailLog` row → increment attempts → call `$svc->sendEmail()` → on success update `status='sent'`, `message_id`, `sent_at` → on error throw (triggers retry)
- `failed()`: update the row to `status='failed'`, store `error_message`, `failed_at`

### 5.5 Config — add to `config/services.php`
```php
'email' => [
    'log_enabled'        => env('EMAIL_LOG_ENABLED', true),
    'rate_limit_enabled' => env('EMAIL_RATE_LIMIT_ENABLED', false),
    'rate_limit_per_hour'=> env('EMAIL_RATE_LIMIT_PER_HOUR', 20),
],
```

### 5.6 `.env.example` — append the documented keys
Add the mail block (3c/3d) plus the toggles in 3f so new environments are self-documenting.

### 5.7 Wiring into features (per-feature, later)
At a high-importance call site (where SMS + realtime already fire), add — in its **own try/catch**, guarded on `$user->email`:
```php
try {
    if ($user->email) {
        SendEmailJob::dispatch($user->email, $subject, $body, 'context_key', $user->id, auth()->id());
    }
} catch (\Throwable $e) {
    \Log::error('Email dispatch failed: ' . $e->getMessage());
}
```
Only add this to the **important** transactions the client designates — not every SMS site.

---

## 6. Transparency & logging

- Every email attempt writes an `email_logs` row: `status` (pending → sent/failed), `error_message`, provider `api_response`, `subject`, `context`, recipient `user_id`, `sender_id`, timestamps. So "which email did/didn't send, and why" is always auditable — same as `sms_logs`.
- **Option B (Resend API)** additionally exposes dashboard/webhook events: `delivered`, `bounced`, `opened`, `clicked`, `complained`, `delivery_delayed`, `failed`.

---

## 7. Safety model

- **Independent channel** — email is a separate `SendEmailJob`; realtime and SMS are untouched.
- **Guarded** — dispatch only when `$user->email` is present.
- **Wrapped in try/catch** — a mail failure logs and continues; it can never crash a workflow action.
- **Queued** — runs via the queue worker (respect `QUEUE_CONNECTION`; the worker must use the same driver as `.env`, and must be **restarted on deploy**).

---

## 8. Testing plan (next phase)

1. **Local:** `MAIL_MAILER=smtp` → MailHog; trigger a test send; confirm it appears in MailHog UI and an `email_logs` row is `sent`.
2. **Staging:** point at a free personal SMTP (3b); send to a real test inbox; confirm receipt + `email_logs` row.
3. **Negative:** send to an empty/invalid address → `email_logs` row `failed` with `error_message`, **no crash**, SMS/realtime unaffected.
4. **Provider swap:** flip `MAIL_MAILER` to `log` and confirm output lands in `storage/logs` with no code change.

---

## 9. Deploy notes

- Set the correct `.env` mail block per environment; **verify the Resend domain** before live sends from a custom address.
- **No `npm run build`** (no front-end changes).
- **Restart queue workers** after deploy so `SendEmailJob` runs the new code.
- Roll out to the designated **important** transactions first; expand later.

---

## 10. Open item for the client

`User.email` currently holds institutional `@sksu.edu.ph` addresses (good for delivery). The client mentioned *"later in the model it will add recipient"* — if some notifications must go to a **different** address than the account's login email, add a dedicated recipient-email field at that point; the `SendEmailJob` signature already takes an explicit address, so no rework is needed.

---

### Reference (existing patterns this mirrors)
- `app/Jobs/SendSmsJob.php` · `app/Models/SmsLog.php` · `app/Services/SmsService.php`
- `config/services.php` (`sms` section) · `config/mail.php` · `app/Models/User.php`

---

## 11. Usage (the built scaffolding)

### What was created
| File | Purpose |
|---|---|
| `database/migrations/2026_06_08_000000_create_email_logs_table.php` | `email_logs` audit table |
| `app/Models/EmailLog.php` | Log model (scopes `sent`/`failed`/`pending`/`byContext`, stats helpers) |
| `app/Mail/GeneralNotificationMail.php` | Reusable Mailable (subject/title/body/action URL + attachments) |
| `resources/views/emails/general-notification.blade.php` | Branded email template |
| `app/Services/EmailService.php` | Thin sender over the native mailer (`sendEmail(...)`) |
| `app/Jobs/SendEmailJob.php` | Queued send, `$tries=3`, writes the log row |
| `app/Console/Commands/EmailTest.php` | `php artisan email:test` |
| `config/services.php` → `email` block | Toggles (`log_enabled`, rate limit, `max_attachment_mb`) |
| `app/Mail/PreAuditNoticeMail.php` + `resources/views/emails/pre-audit-notice.blade.php` | **Ready-made ICU Pre-Audit Notice template** (see §12) |

## 12. Pre-Audit Notice template (ready, not wired)

A specific transactional template that mirrors the on-screen **ICU Verification Report** (`resources/views/livewire/icu/icu-manage-verified-documents.blade.php`). It renders a "Summary of Findings" table, transaction details, pre-audit result (For Compliance / Verified), general comments, and the ICU office footer.

**Data source:** all fields come from the `DisbursementVoucher` model at render time — `getRelatedDocumentItems()` (document/status/remarks), `getRelatedDocumentsGeneralRemarks()`, `tracking_number`, `dv_number`, `payee`, `total_sum`, `log_number`, `documents_verified_at`, particulars purposes. Status mapping: `required`→Completed, `not_required`→For Compliance, `not_applicable`→Not Applicable.

**Where it could be wired later (not done yet):** the ICU pre-audit action that sets `related_documents` — `app/Http/Livewire/Offices/Traits/OfficeDashboardActions.php` (the ICU "Verify Related Documents" / return-from-ICU step). When ICU finishes verification, send to `$dv->user->email`.

**Usage when ready:**
```php
use App\Mail\PreAuditNoticeMail;
use Illuminate\Support\Facades\Mail;

Mail::to($dv->user->email)->send(
    new PreAuditNoticeMail($dv, $dv->user->name, $reviewerName, $reviewerPosition)
);
```
Verified by rendering against a real DV (all sections + findings populate correctly). Edit the blade to adjust wording/branding before going live.

### Dispatching an email (future feature wiring)
Mirror the SMS/realtime pattern — guarded, in its own try/catch so it never affects the action:
```php
use App\Jobs\SendEmailJob;

try {
    if ($user->email) {
        SendEmailJob::dispatch(
            $user->email,
            'DV Returned',                                  // subject
            'Your Disbursement Voucher was returned',       // title (heading)
            "DV {$dv->tracking_number} was returned with remarks: {$remarks}", // body
            'disbursement_voucher_returned',                // context (for email_logs)
            $user->id,                                      // recipient user_id
            auth()->id(),                                   // sender_id
            $attachmentSpecs                                // optional, see below
        );
    }
} catch (\Throwable $e) {
    \Log::error('Email dispatch failed: ' . $e->getMessage());
}
```

### Sending files — link vs attachment
- **Link (default):** put a URL in the body — a signed temp URL for R2 disks
  (`Storage::disk('remarks')->temporaryUrl($path, now()->addDays(7))`) or the existing
  authenticated route `route('attachments.download', $attachment)`. Best for large files / internal users.
- **Attachment (actual file):** pass `$attachmentSpecs` — an array of serializable disk+path descriptors
  (queue-safe; streamed from storage at send; files over `EMAIL_MAX_ATTACHMENT_MB` are skipped + logged):
  ```php
  $attachmentSpecs = [
      ['disk' => 'public', 'path' => 'fd/demand.pdf', 'as' => 'Formal-Demand.pdf'],
  ];
  ```
  Use for **external recipients** (COA, Resident Auditor, payee) and **official notices** (FD/SCO/endorsement).

### Testing
```bash
php artisan email:test you@example.com                                   # queued (writes email_logs)
php artisan email:test you@example.com --direct                          # synchronous
php artisan email:test you@example.com --attach=public:fd/sample.pdf     # with attachment (repeatable)
```
Set `MAIL_MAILER=log` to render into `storage/logs`, or use MailHog locally — no real send needed.

### Verified (branch `feature/email-channel-setup`)
- ✅ migration creates `email_logs`; `php -l` clean on all files
- ✅ queued + direct send render the branded email; `email_logs` row `status=sent`
- ✅ attachment from the `public` disk is attached and recorded in `email_logs.attachments`
- ✅ invalid address rejected; broken mailer → caught gracefully, `email_logs` row `status=failed`, no crash
- ✅ provider swap by `MAIL_MAILER` only — no code change
