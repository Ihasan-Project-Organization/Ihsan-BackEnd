#!/usr/bin/env bash

set -e

echo "Preparing writable Laravel directories..."
mkdir -p /var/www/html/storage/framework/{cache,sessions,views} /var/www/html/storage/logs /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Preparing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link || true

echo "Applying database migrations..."
php artisan migrate --force

if [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
    echo "Ensuring the production administrator exists..."
    php artisan db:seed --class=ProductionAdminSeeder --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Finalizing web server permissions..."
chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache || true
