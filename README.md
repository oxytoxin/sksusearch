# S.E.A.R.C.H.

## Runtime requirements

- PHP 8.3 or newer
- Composer 2
- Node.js 20.19 or newer, or Node.js 22.12 or newer
- Laravel 13

Install the locked frontend dependencies with `npm ci`. The application uses
Vite 7 and `laravel-vite-plugin` 2; the old Vite 2 downgrade workaround no
longer applies.

```bash
composer install
npm ci
npm run build
```

## Realtime broadcasting

Realtime notifications use Laravel Reverb over private, authorized channels.
Production environments must configure the `REVERB_*` and `VITE_REVERB_*`
variables documented in `.env.example`, run `reverb:start` under a process
supervisor, and configure the reverse proxy for WebSocket upgrades.

## Historical allocation branch

Do not delete `version-before-2026-allocation`. It represents the application
state from 2025-08-22.
