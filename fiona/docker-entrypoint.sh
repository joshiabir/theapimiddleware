#!/usr/bin/env bash
set -e

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT:-3306}" --silent 2>/dev/null; do
    sleep 2
done
echo "MySQL ready."

echo "Running migrations..."
php /var/www/app/artisan migrate --force

exec "$@"
