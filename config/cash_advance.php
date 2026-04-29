<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Mode Toggle
    |--------------------------------------------------------------------------
    |
    | When true, the cash-advance reminder cron uses MINUTE-based waiting
    | periods (see "wait_minutes_per_step_demo" below) instead of DAY-based
    | ones. Use this for live demos and local QA when waiting 15+ days
    | between escalation steps is impractical.
    |
    | Toggle by editing .env: CA_REMINDER_DEMO_MODE=true|false
    | After changing .env, run `php artisan config:clear` (and config:cache
    | if you cache config in production) for the change to take effect.
    |
    | DEFAULT: false (production behaviour — real days between steps).
    |
    */
    'demo_mode' => env('CA_REMINDER_DEMO_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Production Waiting Periods (DAYS)
    |--------------------------------------------------------------------------
    |
    | How many days the cron waits between escalation steps. Each value is
    | measured from the date the previous notice was actually sent
    | (fmr_date / fmd_date / sco_date) — NOT from the original liquidation
    | deadline. Step 1 (initial trigger) is always driven by
    | liquidation_period_end_date and is not configured here.
    |
    | TODO: confirm these against the official COA circular before relying
    | on them in production. Current values are placeholders.
    |
    */
    'wait_days_per_step' => [
        2 => (int) env('CA_REMINDER_WAIT_DAYS_FMR_TO_FMD', 15),  // FMR sent -> wait N days -> bump to FMD
        3 => (int) env('CA_REMINDER_WAIT_DAYS_FMD_TO_SCO', 30),  // FMD sent -> wait N days -> bump to SCO
        4 => (int) env('CA_REMINDER_WAIT_DAYS_SCO_TO_END', 30),  // SCO sent -> wait N days -> bump to Endorsement
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Waiting Periods (MINUTES)
    |--------------------------------------------------------------------------
    |
    | Used only when CA_REMINDER_DEMO_MODE=true. Lets the entire escalation
    | chain be observed in a few minutes during a live demo.
    |
    | Note: the cron is scheduled every 5 minutes (Kernel.php), so the
    | observed cadence per step is approximately max(wait_minutes, 5).
    | For a tighter demo, manually trigger the cron between officer clicks:
    |
    |     php artisan cash-advance:check-reminders
    |
    */
    'wait_minutes_per_step_demo' => [
        2 => (int) env('CA_REMINDER_DEMO_MINUTES_FMR_TO_FMD', 2),
        3 => (int) env('CA_REMINDER_DEMO_MINUTES_FMD_TO_SCO', 2),
        4 => (int) env('CA_REMINDER_DEMO_MINUTES_SCO_TO_END', 2),
    ],

];
