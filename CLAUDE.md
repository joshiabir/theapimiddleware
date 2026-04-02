# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a two-application Laravel 11 system:
- **fiona/** — Lightweight API gateway/reverse proxy with DLP (Data Loss Prevention) scanning
- **plucker-app/** — Full-featured admin dashboard for domain routing, policy management, and monitoring

Both apps share the same database schema. All commands below should be run from within the respective app directory.

## Commands

### Development (run from `fiona/` or `plucker-app/`)

```bash
composer dev        # Starts all dev services concurrently:
                    #   php artisan serve (port 8000)
                    #   php artisan queue:listen
                    #   php artisan pail (log viewer)
                    #   npm run dev (Vite)
```

Or run individually:
```bash
php artisan serve
npm run dev
php artisan queue:listen
```

### Build
```bash
npm run build       # Production Vite build
```

### Testing (PestPHP)
```bash
./vendor/bin/pest                       # Run all tests
./vendor/bin/pest tests/Unit            # Unit tests only
./vendor/bin/pest tests/Feature         # Feature tests only
./vendor/bin/pest --filter "test name"  # Single test
php artisan test                        # Alias
```

### Linting
```bash
./vendor/bin/pint                       # Format PHP (Laravel Pint)
./vendor/bin/pint --test                # Check without fixing
```

## Architecture

### Request Flow (fiona)

Every API request hits a single catch-all route (`routes/api.php`) handled by `app/Http/Controllers/main.php`:

1. **policyControl middleware** (`app/Http/Middleware/policyControl.php`) runs first: domain validation, rate limiting (global + per-user RPM), authentication checks, honeypot detection, log creation
2. **DLP scan** on request body via `app/Services/dlp_service.php` — matches regex/keyword patterns, can `alert`, `redact`, or `block`
3. **Domain routing** — looks up target backend IP in `domain_routing` DB table
4. **HTTP proxy** — forwards request to backend with original headers/method
5. **DLP scan** on response body
6. Response forwarded to client; timing metrics recorded

Log persistence is offloaded to a queue job (`app/Jobs/logProcessor.php`) to avoid client-facing latency.

### Admin Dashboard (plucker-app)

`app/Http/Controllers/main.php` (659 lines) renders views for:
- `/dashboard/{domain?}` — Analytics with time-range filtering
- `/APIs/{domain?}`, `/clusters/{domain?}` — API/cluster config
- `/logs/{domain?}`, `/security/incidents/{domain?}` — Log analysis and security events
- OAuth callbacks (Google, Microsoft, GitHub via Socialite)

Interactive UI components use **Laravel Livewire v3** under `app/Livewire/`, organized by feature (Apis, Config, Dashboard, Logs, Security, Settings).

Authentication uses **Jetstream + Sanctum** with team-based multi-tenancy.

### Database-Driven Configuration

All routing, policies, and DLP rules are stored in the database — no config files for runtime behavior. This enables live policy updates without redeployment. Key tables:

| Domain | Tables |
|---|---|
| Routing | `domain_routing`, `apis`, `cluster`, `cluster_api`, `protocol` |
| Policy | `policies`, `cluster_policy`, `cluster_policy_list`, `dlp_policy`, `dlp_bypass` |
| Monitoring | `logger`, `dlp_log`, `response_time`, `error_response`, `incidents` |
| Config | `app_user`, `client`, `license`, `pii_list`, `anonymizer` |

### Multi-Tenancy Model

Isolation is domain-based: each customer domain maps to a backend IP via `domain_routing`. Domains own clusters → clusters own APIs and policies → rate limits apply per domain and per user.

### Tech Stack Differences

| | fiona | plucker-app |
|---|---|---|
| Tailwind | v3.4 | v4.0 |
| Auth | None | Jetstream + Sanctum + Socialite |
| UI | Blade only | Blade + Livewire |
| Frontend deps | Minimal | ApexCharts, tw-elements, simple-datatables |
