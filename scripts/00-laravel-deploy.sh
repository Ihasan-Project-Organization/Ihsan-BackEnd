#!/usr/bin/env bash

set -e

echo "Preparing permissions and directories..."
mkdir -p /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
touch /var/www/html/database/database.sqlite || true
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

echo "Preparing Laravel..."
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Applying database migrations..."
php artisan migrate --force

echo "Seeding initial and test accounts..."
php artisan db:seed --force

echo "Ensuring web server write permissions on sqlite and storage..."
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chown -R nginx:nginx /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache || true
