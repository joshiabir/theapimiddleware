# API Middleware

A self-hosted API gateway with a built-in admin dashboard. Point your clients at the gateway — it enforces policies, scans for sensitive data, and proxies traffic to your backend services. The admin dashboard gives you full visibility and control without touching config files or redeploying.

## What's included

| App | Role |
|---|---|
| **fiona** | Lightweight API gateway / reverse proxy |
| **plucker-app** | Admin dashboard for config, monitoring, and security |

Both apps share a single MySQL database and are shipped as Docker containers.

## Features

- **Domain routing** — map incoming domains to backend IPs via the dashboard; changes take effect immediately
- **DLP scanning** — inspect request and response bodies against regex/keyword rules; actions: alert, redact, or block
- **Rate limiting** — global and per-user requests-per-minute limits, enforced per domain
- **Authentication enforcement** — middleware checks tokens before proxying
- **Honeypot detection** — flags suspicious request patterns
- **Request logging** — every request is logged with timing metrics; log writes are queued so they never add latency to the client
- **Security incidents** — DLP matches and policy violations are surfaced as incidents in the dashboard
- **OAuth login** — admin dashboard supports Google, GitHub, and Microsoft login
- **Team-based multi-tenancy** — each team manages its own domains, clusters, APIs, and policies

## Quick start

The only requirement is Docker (with the Compose plugin).

```bash
git clone <repo-url>
cd theapimiddleware
./setup.sh
```

`setup.sh` will:
1. Install Docker if it isn't already present (macOS via Homebrew, Ubuntu/Debian/Fedora via package manager)
2. Generate secure app keys and database passwords
3. Write a `.env` file
4. Build and start all containers

Once running:

| Service | URL |
|---|---|
| API gateway (fiona) | http://localhost:8000 |
| Admin dashboard (plucker-app) | http://localhost:8001 |

## How it works

Every request to the gateway goes through a single pipeline:

1. **Policy middleware** — validates the domain, checks rate limits, authenticates the request, runs honeypot detection, and creates a log entry
2. **DLP scan (request)** — scans the request body against your configured rules
3. **Proxy** — looks up the target backend for the domain and forwards the request
4. **DLP scan (response)** — scans the response body before returning it to the client
5. **Log flush** — timing and result data are written to the database via a background queue job

All routing rules, rate limits, and DLP patterns are stored in the database and editable live from the admin dashboard — no redeployment needed.

## Configuration

Runtime behavior is entirely database-driven. Use the admin dashboard to manage:

- **APIs & Clusters** — group APIs into clusters and map them to domains
- **Policies** — attach rate limits and auth requirements to clusters
- **DLP rules** — define regex or keyword patterns with alert/redact/block actions
- **Domain routing** — map each domain to a backend IP

For Docker environment variables and production setup, see [DEPLOYMENT.md](DEPLOYMENT.md).

## Tech stack

- **PHP 8.2** / **Laravel 11**
- **MySQL 8**
- **Apache** (inside containers)
- **Livewire v3** (admin dashboard UI)
- **Tailwind CSS** (fiona: v3, plucker-app: v4)
- **Docker Compose**
