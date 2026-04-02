# Deployment Guide

| Service | Role | URL |
|---|---|---|
| **fiona** | API gateway | http://localhost:8000 |
| **plucker-app** | Admin dashboard | http://localhost:8001 |

## Prerequisites

- Docker with the Compose plugin ([Docker Desktop](https://www.docker.com/products/docker-desktop/) covers both on Mac/Windows; on Linux `setup.sh` installs them automatically)

## Setup

```bash
./setup.sh
```

The script checks that Docker is installed and running, generates app keys and database passwords, creates `.env`, then builds and starts all containers. The shared database is created and migrated automatically on first boot.

## OAuth login (optional)

The admin dashboard supports Google, GitHub, and Microsoft login via OAuth. To enable any of them, add the relevant credentials to `.env` before running `./setup.sh` (or before rebuilding):

```env
# Google
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

# GitHub
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

# Microsoft
MICROSOFT_APP_ID=
MICROSOFT_CLIENT_SECRET=
```

Leave any unused providers blank — the login page will only show buttons for configured providers.

## Common commands

```bash
docker compose logs -f                                          # tail all logs
docker compose logs -f fiona                                    # single service
docker compose down                                             # stop everything
docker compose down -v                                          # stop + wipe database
docker compose up --build -d                                    # rebuild after code changes
docker compose exec plucker php artisan migrate                 # run migrations manually
docker compose exec plucker php artisan tinker                  # REPL
```

## Production

Before going live, update `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

Also:
- Set the correct `APP_URL` values for each service in `docker-compose.yml`
- Put a TLS-terminating reverse proxy (nginx, Caddy, etc.) in front of ports 8000/8001
- Replace the bundled `mysql` container with a managed database service (RDS, Cloud SQL, PlanetScale, etc.) and remove the `mysql` service and `mysql-data` volume from `docker-compose.yml`
