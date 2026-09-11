#!/usr/bin/env bash

set -e

echo "Preparing Laravel..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite || true
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Applying database migrations..."
php artisan migrate --force

echo "Seeding initial and test accounts..."
php artisan db:seed --force
