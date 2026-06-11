# Email Channel — Setup & How It Works

This document explains the email-sending feature of SEARCH/VMS: how it is built,
how to configure it (using **Resend** as the provider), how to test it, and what is
required to go live in production.

---

## TL;DR — Do we need a domain?

| Goal | Domain needed? | From address | Can send to |
|---|---|---|---|
| **Testing** (verify it works) | ❌ No | `onboarding@resend.dev` | Only the Resend account's own email (e.g. `orbinobrian0506@gmail.com`) |
| **Production** (real notices) | ✅ **Yes** | `sksusearch@sksu.edu.ph` | Anyone |

So: email works **today** for testing with no domain. To send official notices from
`@sksu.edu.ph` to actual recipients, the `sksu.edu.ph` domain must be verified in
Resend by adding DNS records (SPF/DKIM) — this needs access to SKSU's DNS / Google
Workspace admin.

---

## How it works (architecture)

Email is the **third notification channel**, alongside **SMS** and **realtime
(broadcast) notifications**. The three are **independent**: a failure in one never
crashes the others (each is dispatched separately and email/SMS run as queued jobs).

```
Some app event (e.g. ICU finishes pre-audit)
   ├── Realtime notification  → NotificationController::sendGeneralNotification(...)
   ├── SMS                     → SendSmsJob::dispatch(...)        (queued, logs to sms_logs)
   └── Email                   → SendEmailJob::dispatch(...)      (queued, logs to email_logs)
                                       │
                                       ▼
                                 EmailService::sendEmail()
                                       │
                                       ▼
                                 Laravel Mail (smtp mailer) ──► Resend ──► recipient inbox
```

### Key components

| Component | File | Purpose |
|---|---|---|
| `EmailService` | `app/Services/EmailService.php` | Thin wrapper over Laravel `Mail`. Sends and returns a standardized `['success','error','raw_response']` result. Provider is whatever `MAIL_MAILER` points to. |
| `SendEmailJob` | `app/Jobs/SendEmailJob.php` | Queued send (`tries=3`, backoff 1/5/15 min). Writes/updates an `email_logs` row (pending → sent/failed). Mirrors `SendSmsJob`. |
| `EmailLog` | `app/Models/EmailLog.php` | One row per send attempt: recipient, subject, status, attempts, error, attachments, context, user/sender. |
| `GeneralNotificationMail` | `app/Mail/GeneralNotificationMail.php` | Reusable mailable (subject/title/body/action URL + **attachments by disk path**). |
| `PreAuditNoticeMail` | `app/Mail/PreAuditNoticeMail.php` | Pre-Audit Notice mailable (reads all data from the `DisbursementVoucher`). |
| Preview route | `routes/web.php` → `preview.pre-audit-notice` | `GET /preview/pre-audit-notice/{disbursementVoucher}` — renders the email in-browser, **never sends**. |
| Health-check route | `routes/web.php` → `email.test` | `GET /email/test` — sends a real test email and returns config + result as JSON. |

### Attachments (future "email the actual file" feature)

`GeneralNotificationMail` already supports attachments passed as **serializable disk
specs**, so files can be queued safely and streamed from any storage disk
(local, public, Cloudflare R2/S3):

```php
SendEmailJob::dispatch($email, $subject, $title, $body, $context, $userId, $senderId, [
    ['disk' => 'public', 'path' => 'fd/demand.pdf', 'as' => 'Formal-Demand.pdf'],
]);
```
Missing or oversized files are skipped with a logged warning (the email still sends).
Size cap: `EMAIL_MAX_ATTACHMENT_MB` (default 10 MB).

---

## Provider: Resend via SMTP (no extra package)

We send through Resend using Laravel's **built-in `smtp` mailer** — no Composer
package is installed (the `resend/resend-laravel` package was avoided because it
forces a full dependency re-resolve that conflicts with this project's locked,
security-flagged packages). Resend's SMTP relay works identically for sending.

### `.env` settings

```ini
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend                 # literally the word "resend"
MAIL_PASSWORD=re_xxxxxxxxxxxxxxxxx    # <-- your Resend API key (the ONLY secret)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="onboarding@resend.dev"   # test sender; change to sksusearch@sksu.edu.ph after domain verify
MAIL_FROM_NAME="${APP_NAME}"
```

Everything except `MAIL_PASSWORD` is fixed Resend boilerplate. **The API key is the
only unique input.** Keep it in `.env` only — never commit it (`.env` is git-ignored).

Resend SMTP reference: host `smtp.resend.com`, ports `587`/`2587` (STARTTLS / `tls`)
or `465`/`2465` (implicit SSL), username `resend`, password = API key.

---

## Setup steps

### 1. Get a Resend API key (one-time)
1. Sign up at https://resend.com
2. **API Keys → Create API Key** → name it (e.g. `sksusearch`), permission *Sending access* (or Full).
3. Copy the `re_...` value **immediately** (shown once). Treat it as a password.

### 2. Configure `.env`
Set the keys in the table above (put the real `re_...` in `MAIL_PASSWORD`), then:
```bash
php artisan config:clear
```

### 3. (Production only) Verify the domain
To send from `sksusearch@sksu.edu.ph`:
1. Resend → **Domains → Add Domain** → enter `sksu.edu.ph`.
2. Resend shows DNS records (SPF + DKIM, and a return-path/MX). Add them to the
   `sksu.edu.ph` DNS zone (requires SKSU DNS / Google Workspace admin access).
3. Wait for Resend to show the domain **Verified**.
4. Change `MAIL_FROM_ADDRESS="sksusearch@sksu.edu.ph"` and `php artisan config:clear`.

### 4. Deploy checklist
- [ ] `.env` has the Resend keys (and the **production** `MAIL_FROM_ADDRESS`).
- [ ] `php artisan config:clear` (or `config:cache`) after editing `.env`.
- [ ] **Queue worker running** if `QUEUE_CONNECTION` is not `sync` — the queued
      `SendEmailJob`/`SendSmsJob` only fire when a worker matching the configured
      driver is running (Supervisor). With `QUEUE_CONNECTION=sync` jobs run inline.
- [ ] Hit `GET /email/test` (see below) to confirm.

---

## Testing

### Health-check endpoint — `GET /email/test`
Hit this after deploy to confirm email is alive. Auth-gated; sends a real test email
and returns JSON. Uses a **direct send** (no queue dependency).

- Default recipient = the logged-in user's email.
- Override: `GET /email/test?to=someone@example.com`

Example response:
```json
{ "ok": true, "mailer": "smtp", "host": "smtp.resend.com",
  "from": "onboarding@resend.dev", "to": "you@example.com",
  "error": null, "sent_at": "2026-06-11 20:30:00" }
```

### Preview the Pre-Audit Notice (no send)
`GET /preview/pre-audit-notice/{disbursementVoucher}` — renders the email in the
browser. Example: `/preview/pre-audit-notice/241` (a "For Compliance" voucher).

### Test-mode limit (before domain verification)
Resend returns this **550** error if you try to email anyone other than the account
owner before a domain is verified — this is expected, not a bug:
> `You can only send testing emails to your own email address (...). To send emails to
> other recipients, please verify a domain at resend.com/domains ...`

---

## Cost — Resend pricing

| Plan | Price | Emails | Daily cap | Domains |
|---|---|---|---|---|
| **Free** (permanent) | $0 | 3,000 / month | 100 / day | 1 |
| Pro | $20/mo | 50,000 / month | none | 10 |

SEARCH/VMS email volume is low, so the **Free tier is sufficient**. Pro is only
needed if a single day ever exceeds 100 emails (or the month exceeds 3,000).

---

## Switching providers later

Because email goes through Laravel's native mail layer, changing providers is just a
`MAIL_MAILER` / `.env` change — no code changes:
- **Resend (current):** `smtp` mailer pointed at `smtp.resend.com` (this doc).
- **Gmail / Google Workspace SMTP:** `smtp` mailer with `smtp.gmail.com`, an app
  password as `MAIL_PASSWORD`.
- **Mailgun / SES / Postmark:** set `MAIL_MAILER` accordingly (those mailers already
  exist in `config/mail.php`).

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `550 You can only send testing emails to your own email address` | Domain not verified yet. Verify `sksu.edu.ph` or send only to the account email. |
| Email "sent" but never arrives | Check **Spam**; check Resend → **Logs**; confirm `MAIL_FROM_ADDRESS` is allowed for the current mode. |
| Queued email never sends on server | No queue worker, or worker driver ≠ `QUEUE_CONNECTION`. Start/fix the Supervisor worker. (Same gotcha as SMS.) |
| Config changes not taking effect | Run `php artisan config:clear` (or `config:cache`) after editing `.env`. |
| `email_logs` row stuck `pending` | The send threw after retries; check `error_message` on the row and the logs. |
