#!/usr/bin/env bash

set -e

echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --no-progress --working-dir=/var/www/html

echo "Preparing Laravel..."
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Applying database migrations..."
php artisan migrate --force
