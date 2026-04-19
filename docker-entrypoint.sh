#!/bin/sh
set -e

# Publish Filament assets
php artisan filament:assets --no-interaction

# Cache config, routes, and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically on each deploy
php artisan migrate --force

# Start the built-in PHP server on Render's dynamic PORT
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
