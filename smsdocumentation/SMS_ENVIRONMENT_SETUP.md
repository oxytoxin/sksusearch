# SMS Environment Setup Checklist — SEARCH System

**Purpose:** Verifies that each environment (production, staging, dev) is properly wired up to send SMS. The code is identical across environments, but each server must have the right configuration, queue, and credentials to actually deliver messages.

---

## Table of Contents

1. [Why Code Alone Is Not Enough](#1-why-code-alone-is-not-enough)
2. [Per-Environment Requirements](#2-per-environment-requirements)
3. [Common Cross-Environment Pitfalls](#3-common-cross-environment-pitfalls)
4. [Quick Audit Command](#4-quick-audit-command)
5. [Known SEARCH Environments](#5-known-search-environments)
6. [Setup Procedure for a New Environment](#6-setup-procedure-for-a-new-environment)
7. [Verification Test](#7-verification-test)
8. [Troubleshooting Matrix](#8-troubleshooting-matrix)

---

## 1. Why Code Alone Is Not Enough

The application code that dispatches SMS (e.g. `SendSmsJob::dispatch(...)`) is the same on every server. But for an SMS to actually leave the system, **the following must be true on the specific server**:

- The Semaphore API key is set
- Queue workers are running to process the job
- Redis (or DB queue) is accepting jobs
- The recipient user has a valid phone number in the database
- Semaphore has credit balance and the sender ID is approved

If any one of these is missing on a given environment, SMS will **silently fail** even though the code itself is correct.

---

## 2. Per-Environment Requirements

For SMS to work on any environment (staging, production, dev), **all** of these must be true on **that specific server**:

| # | Requirement | How to Check |
|---|---|---|
| 1 | `.env` has `SMS_PROVIDER=semaphore` | `grep ^SMS_PROVIDER .env` |
| 2 | `.env` has `SEMAPHORE_API_KEY=...` (valid key) | `grep ^SEMAPHORE_API_KEY .env` |
| 3 | `.env` has `SEMAPHORE_SENDER_NAME=SKSUSEARCH` | `grep ^SEMAPHORE_SENDER_NAME .env` |
| 4 | `.env` has `QUEUE_CONNECTION=redis` (or `database`) | `grep ^QUEUE_CONNECTION .env` |
| 5 | Redis server running (if using redis queue) | `redis-cli ping` should return `PONG` |
| 6 | Supervisor running queue workers | `sudo supervisorctl status` |
| 7 | Config cache cleared after `.env` change | `php artisan config:clear && php artisan config:cache` |
| 8 | Queue restarted after deploy | `php artisan queue:restart` |
| 9 | Semaphore account has credit balance | `curl https://api.semaphore.co/api/v4/account?apikey=...` |
| 10 | Sender ID `SKSUSEARCH` approved by Semaphore | Check Semaphore dashboard |
| 11 | Recipient `employee_information.contact_number` populated | DB query |

---

## 3. Common Cross-Environment Pitfalls

| Pitfall | Symptom |
|---|---|
| **Supervisor `queue:work` driver ≠ `.env QUEUE_CONNECTION`** | **Direct API works, queued SMS silently never sent. Jobs vanish into Redis (or DB) with no error and no log row** |
| Staging uses same Semaphore key as production | Production credits drain unexpectedly during testing |
| Staging has no Semaphore key at all | Jobs silently fail or throw "API key not configured" |
| `.env` updated but `config:cache` not cleared | Old config still in use, SMS not sent |
| Workers not restarted after deploy | Old code keeps running, new SMS logic ignored |
| Different DB → users on staging don't have phone numbers | Skip rule triggers, no SMS sent |
| Staging shares Redis with prod | Jobs cross-pollinate between environments (dangerous) |
| Supervisor stopped after server reboot | Jobs queue up indefinitely, nothing processes them |
| Semaphore sender ID not yet approved on new account | All sends stuck at "Pending" indefinitely |

### Critical: Supervisor / `.env` queue driver must match

This was a real production issue (May 2026) that silently broke all queued SMS for an unknown period.

**Symptom:** Direct `/api/sms/test-direct` works ✅. Queued `/api/sms/send` or DV/TO workflow SMS never arrive ❌. No errors in any log. `sms_logs` rows are never created.

**Cause:** `.env` had `QUEUE_CONNECTION=redis` but Supervisor was running `queue:work database`. Dispatchers pushed jobs to Redis; workers watched the (empty) database `jobs` table. Jobs accumulated in Redis with no consumer.

**Fix:** Edit `/etc/supervisor/conf.d/laravel-worker.conf` AND `/etc/supervisor/conf.d/laravel-proxy-worker.conf`:

```ini
# WRONG
command=php /var/www/sksusearch/artisan queue:work database --sleep=3 --tries=3 --max-time=3600

# CORRECT (must match .env QUEUE_CONNECTION)
command=php /var/www/sksusearch/artisan queue:work redis --queue=default --sleep=3 --tries=3 --max-time=3600
```

Then:
```bash
sudo supervisorctl reread && sudo supervisorctl update
sudo supervisorctl restart laravel-worker:* laravel-worker-proxy:*
```

**Verification command (run before/after any deploy or `.env` change):**
```bash
ENV_DRIVER=$(grep ^QUEUE_CONNECTION /var/www/sksusearch/.env | cut -d= -f2)
SUP_DRIVER=$(grep -h "queue:work" /etc/supervisor/conf.d/*.conf | grep -v "^#" | head -1 | grep -oE "queue:work [a-z]+" | awk '{print $2}')
echo ".env says:       $ENV_DRIVER"
echo "Supervisor says: $SUP_DRIVER"
[ "$ENV_DRIVER" = "$SUP_DRIVER" ] && echo "✅ MATCH" || echo "❌ MISMATCH — fix immediately"
```

---

## 4. Quick Audit Command

SSH into the server, then run this single block:

```bash
cd /var/www/sksusearch
echo "=== SMS Config ==="
grep -E "^(SMS_PROVIDER|SEMAPHORE_API_KEY|SEMAPHORE_SENDER_NAME|QUEUE_CONNECTION|APP_ENV|APP_URL)" .env

echo ""
echo "=== Redis ==="
redis-cli ping
echo "Queue pending: $(redis-cli LLEN queues:default)"
echo "Queue reserved: $(redis-cli ZCARD queues:default:reserved)"

echo ""
echo "=== Workers ==="
sudo supervisorctl status | grep -E "worker|reverb"

echo ""
echo "=== Recent SMS ==="
php artisan tinker --execute="echo 'Last SMS: ' . optional(App\Models\SmsLog::latest()->first())->created_at;"

echo ""
echo "=== Semaphore Credit ==="
KEY=$(grep ^SEMAPHORE_API_KEY .env | cut -d= -f2)
curl -s "https://api.semaphore.co/api/v4/account?apikey=$KEY"
```

**Expected output for a healthy environment:**

- All `SMS_*` / `SEMAPHORE_*` / `QUEUE_*` env vars present
- Redis `ping` returns `PONG`
- Queue pending = 0 or very low
- All `laravel-worker_*` show `RUNNING`
- Recent SMS timestamp not stale
- Semaphore returns a JSON object with `credit_balance > 0`

---

## 5. Known SEARCH Environments

Based on current `.env` configuration:

| Environment | URL | Server | Notes |
|---|---|---|---|
| Production Proxy | `https://proxy.sksusearch.com` | `209.97.172.227` | Confirmed working ✅ |
| Production WFP | `https://proxy-wfp.sksusearch.com` | — | WFP module |
| Production V2 | `https://proxy2.sksusearch.com` | — | Application V2 |
| Staging | (varies — confirm with team) | — | Must be audited separately |
| Local Dev | `http://127.0.0.1` | Developer machines | Usually `SMS_PROVIDER` unset or `QUEUE_CONNECTION=sync` |

> Every environment listed above must be audited individually. Working on one does NOT mean SMS works on another.

---

## 6. Setup Procedure for a New Environment

When provisioning a new server (staging or otherwise):

### Step 1: Set environment variables in `.env`
```env
SMS_PROVIDER=semaphore
SEMAPHORE_API_KEY=<get_from_semaphore_dashboard>
SEMAPHORE_SENDER_NAME=SKSUSEARCH
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

> Recommendation: use a **separate Semaphore account** for staging to avoid burning production credits during tests.

### Step 2: Apply configuration changes
```bash
php artisan config:clear
php artisan config:cache
```

### Step 3: Install and start Redis (if not already)
```bash
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
redis-cli ping   # should return PONG
```

### Step 4: Configure Supervisor for queue workers
Create `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sksusearch/artisan queue:work --queue=default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/log/supervisor/laravel-worker.log
stopwaitsecs=3600
```

Reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Step 5: Approve sender ID on Semaphore
- Log in to Semaphore dashboard
- Under "Sender IDs", request approval for `SKSUSEARCH`
- Wait for approval (can take a day or two for new accounts)

### Step 6: Test
See [Verification Test](#7-verification-test) below.

---

## 7. Verification Test

Run this after setup to confirm the full pipeline works on the new environment.

### Test A — Direct send (skips queue, checks provider only)
```bash
curl -s -X POST https://<environment-url>/api/sms/test-direct \
  -H "Content-Type: application/json" \
  -d '{"phone":"09XXXXXXXXX","message":"Env setup test - direct"}'
```

Expected: JSON response with `status: true` and a `message_id`. Phone should receive SMS within 1–5 minutes.

### Test B — Full pipeline (queue + job + DB log + provider)
```bash
curl -s -X POST https://<environment-url>/api/sms/send \
  -H "Content-Type: application/json" \
  -d '{"phone":"09XXXXXXXXX","message":"Env setup test - full pipeline","context":"TEST"}'
```

Expected: JSON response with `status: queued`. Within a few seconds, a new row appears in `sms_logs`, and phone receives SMS within 1–5 minutes.

### Test C — Confirm log was written
```bash
php artisan tinker --execute="echo App\Models\SmsLog::latest()->first()->toJson(JSON_PRETTY_PRINT);"
```

Expected: most recent row matches the test send, with `status = sent`.

---

## 8. Troubleshooting Matrix

| Symptom | Likely Cause | Fix |
|---|---|---|
| Test endpoint returns "API key not configured" | `SEMAPHORE_API_KEY` missing or blank in `.env` | Add key + `php artisan config:cache` |
| Test endpoint returns 500 error | Config cache stale | `php artisan config:clear && php artisan config:cache` |
| Job dispatched but no log entry created | Worker not processing | `sudo supervisorctl status` → restart workers |
| Log entry created with `status = failed` | Provider rejected | Inspect `error_message` + `api_response` columns |
| Log shows `status = sent` but phone never receives | Semaphore out of credit OR sender ID not approved | Check Semaphore dashboard |
| All sends stuck at Semaphore "Pending" | Sender ID `SKSUSEARCH` not approved for telco | Re-request sender ID approval |
| Works on production but not staging | Staging missing env vars OR workers not running | Run audit command, fix gaps |
| Worked yesterday, fails today | Credits exhausted OR worker crashed | Check credit + restart workers |
| `redis-cli ping` returns nothing | Redis not running | `sudo systemctl start redis-server` |
| Queue pending count rising | Workers stuck or stopped | `sudo supervisorctl restart laravel-worker:*` |
| **Direct API test sends OK but queued endpoint never produces `sms_logs` row** | **Supervisor `queue:work` driver ≠ `.env QUEUE_CONNECTION`** | **Edit both `laravel-worker.conf` and `laravel-proxy-worker.conf` to match `.env`; restart workers (see section 3)** |

---

## Summary

The SMS code is environment-agnostic. **Each server must be independently configured and verified.** Use the audit command in section 4 as the first check on any environment where SMS is suspected to be misbehaving. Working SMS on one environment is not evidence that it works on another.
