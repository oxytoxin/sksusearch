# SMS Server Setup Checklist - Digital Ocean + Nginx

## Complete Setup Guide

**Date:** April 27, 2026
**Server:** Digital Ocean Droplet with Nginx

---

## Prerequisites

Before starting, ensure you have:
- [ ] SSH access to your Digital Ocean droplet
- [ ] Domain pointed to your droplet IP
- [ ] Semaphore or PhilSMS account with API credentials
- [ ] Laravel application deployed and working

---

## Step 1: Environment Configuration

### 1.1 SSH into your server

```bash
ssh root@your-server-ip
# or
ssh your-user@your-server-ip
```

### 1.2 Navigate to your project

```bash
cd /var/www/your-project-folder
# or wherever your Laravel project is located
```

### 1.3 Edit .env file

```bash
nano .env
# or
vim .env
```

### 1.4 Add SMS Configuration

Add these lines to your `.env` file:

```env
# ============================================
# SMS CONFIGURATION
# ============================================

# Choose your SMS provider: semaphore or philsms
SMS_PROVIDER=semaphore

# Rate Limiting (optional - set to true to enable)
SMS_RATE_LIMIT_ENABLED=false
SMS_RATE_LIMIT_PER_HOUR=5

# Auto-Blacklist (optional - set to true to enable)
SMS_BLACKLIST_ENABLED=false
SMS_BLACKLIST_THRESHOLD=10
SMS_BLACKLIST_PERIOD_DAYS=30

# ============================================
# SEMAPHORE PROVIDER (if using Semaphore)
# ============================================
SEMAPHORE_API_KEY=your_semaphore_api_key_here
SEMAPHORE_SENDER_NAME=SEARCH

# ============================================
# PHILSMS PROVIDER (if using PhilSMS)
# ============================================
PHILSMS_API_TOKEN=your_philsms_token_here
PHILSMS_SENDER_ID=SEARCH
```

### 1.5 Save and exit

For nano: `Ctrl+X`, then `Y`, then `Enter`
For vim: `Esc`, then `:wq`, then `Enter`

---

## Step 2: Database Migration

### 2.1 Run the SMS logs migration

```bash
php artisan migrate
```

This creates the `sms_logs` table for tracking all SMS.

### 2.2 Verify migration

```bash
php artisan migrate:status | grep sms
```

You should see:
```
| Yes   | 2025_11_27_194546_create_sms_logs_table |
```

---

## Step 3: Queue Configuration

SMS messages are sent via Laravel queues. You have two options:

### Option A: Database Queue (Recommended for most cases)

#### 3.1 Set queue driver in .env

```env
QUEUE_CONNECTION=database
```

#### 3.2 Create jobs table (if not exists)

```bash
php artisan queue:table
php artisan migrate
```

### Option B: Sync Queue (SMS sent immediately - for testing)

```env
QUEUE_CONNECTION=sync
```

---

## Step 4: Queue Worker Setup (Supervisor)

For production, use Supervisor to keep queue workers running.

### 4.1 Install Supervisor

```bash
sudo apt-get update
sudo apt-get install supervisor
```

### 4.2 Create configuration file

```bash
sudo nano /etc/supervisor/conf.d/search-worker.conf
```

### 4.3 Add this configuration

```ini
[program:search-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/your-project-folder/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/your-project-folder/storage/logs/worker.log
stopwaitsecs=3600
```

**IMPORTANT:** Replace `/var/www/your-project-folder` with your actual project path.

### 4.4 Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start search-worker:*
```

### 4.5 Verify workers are running

```bash
sudo supervisorctl status
```

You should see:
```
search-worker:search-worker_00   RUNNING   pid 12345, uptime 0:00:05
search-worker:search-worker_01   RUNNING   pid 12346, uptime 0:00:05
```

---

## Step 5: Clear Configuration Cache

After updating .env:

```bash
php artisan config:clear
php artisan config:cache
php artisan cache:clear
```

---

## Step 6: Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## Step 7: Test SMS Configuration

### 7.1 Test via Artisan Tinker

```bash
php artisan tinker
```

Then run:

```php
// Check provider configuration
$sms = app(\App\Services\SmsService::class);
echo "Provider: " . $sms->getProviderName();

// Test phone formatting
echo "Formatted: " . $sms->formatPhoneNumber('09171234567');
```

### 7.2 Test via API (if accessible)

```bash
# Check provider
curl https://your-domain.com/api/sms/provider

# Send test SMS
curl -X POST https://your-domain.com/api/sms/test-direct \
  -H "Content-Type: application/json" \
  -d '{"number": "09XXXXXXXXX", "message": "Test from server"}'
```

### 7.3 Check SMS logs

```bash
php artisan tinker
```

```php
\App\Models\SmsLog::latest()->first();
```

---

## Step 8: Nginx Configuration (if needed)

If your API routes are not accessible, ensure Nginx is configured:

### 8.1 Check Nginx config

```bash
sudo nano /etc/nginx/sites-available/your-site
```

### 8.2 Ensure this is in your server block

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 8.3 Test and reload Nginx

```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## Verification Checklist

Run through this checklist to verify everything is set up:

### Environment

- [ ] `.env` file has `SMS_PROVIDER` set
- [ ] `.env` file has provider API key/token set
- [ ] `.env` file has `QUEUE_CONNECTION` set (database or sync)
- [ ] Config cache cleared (`php artisan config:cache`)

### Database

- [ ] `sms_logs` table exists
- [ ] `jobs` table exists (if using database queue)
- [ ] Database connection working

### Queue Workers

- [ ] Supervisor installed
- [ ] Worker config file created in `/etc/supervisor/conf.d/`
- [ ] Workers running (`supervisorctl status`)
- [ ] Worker log file writable (`storage/logs/worker.log`)

### Permissions

- [ ] `storage/` directory writable by www-data
- [ ] `bootstrap/cache/` directory writable by www-data

### API Access

- [ ] `/api/sms/provider` returns provider info
- [ ] `/api/sms/test-direct` can send SMS
- [ ] `/api/sms/logs` shows SMS history

### SMS Provider

- [ ] API key is valid (not expired)
- [ ] Sender name/ID is registered
- [ ] Account has credits/balance

---

## Troubleshooting

### SMS not sending

1. Check queue workers:
   ```bash
   sudo supervisorctl status
   ```

2. Check worker logs:
   ```bash
   tail -f /var/www/your-project-folder/storage/logs/worker.log
   ```

3. Check Laravel logs:
   ```bash
   tail -f /var/www/your-project-folder/storage/logs/laravel.log
   ```

### API returning 404

1. Clear route cache:
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

2. Check routes exist:
   ```bash
   php artisan route:list | grep sms
   ```

### Permission denied errors

```bash
sudo chown -R www-data:www-data /var/www/your-project-folder
sudo chmod -R 775 /var/www/your-project-folder/storage
```

### Queue jobs stuck

1. Check failed jobs:
   ```bash
   php artisan queue:failed
   ```

2. Retry failed jobs:
   ```bash
   php artisan queue:retry all
   ```

3. Clear stuck jobs:
   ```bash
   php artisan queue:flush
   ```

### Provider API errors

1. Verify API key in .env
2. Check provider dashboard for:
   - Account balance/credits
   - API key status
   - Sender name registration
   - Rate limits

---

## Quick Reference Commands

```bash
# SSH into server
ssh user@your-server-ip

# Go to project folder
cd /var/www/your-project-folder

# Edit .env
nano .env

# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# Rebuild caches
php artisan config:cache && php artisan route:cache

# Check queue workers
sudo supervisorctl status

# Restart queue workers
sudo supervisorctl restart search-worker:*

# Check logs
tail -f storage/logs/laravel.log
tail -f storage/logs/worker.log

# Run migrations
php artisan migrate

# Test SMS in tinker
php artisan tinker
>>> app(\App\Services\SmsService::class)->getProviderName();
```

---

## SMS Provider Quick Setup

### Semaphore

1. Register at https://semaphore.co
2. Go to Dashboard > API Keys
3. Copy your API key
4. Add to .env: `SEMAPHORE_API_KEY=your_key`
5. Register sender name in Semaphore dashboard
6. Add to .env: `SEMAPHORE_SENDER_NAME=SEARCH`

### PhilSMS

1. Register at https://philsms.com
2. Go to API Settings
3. Copy your API token
4. Add to .env: `PHILSMS_API_TOKEN=your_token`
5. Register sender ID
6. Add to .env: `PHILSMS_SENDER_ID=SEARCH`

---

## Final Checklist Summary

| Step | Description | Done |
|------|-------------|------|
| 1 | Environment variables configured | [ ] |
| 2 | Database migrated | [ ] |
| 3 | Queue driver configured | [ ] |
| 4 | Supervisor installed & configured | [ ] |
| 5 | Config cache cleared | [ ] |
| 6 | Permissions set | [ ] |
| 7 | SMS test successful | [ ] |
| 8 | Nginx configured (if needed) | [ ] |

---

**Server Setup Complete - SMS System Ready**
