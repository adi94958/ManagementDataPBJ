#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
until php artisan tinker --execute="DB::connection()->getPdo();" 2>/dev/null; do
  echo "Database not ready, waiting 5 seconds..."
  sleep 5
done

echo "Database connected! Running migrations..."

# Run migrations
php artisan migrate --force

# Create sessions table if using database sessions
php artisan session:table --force 2>/dev/null || true
php artisan migrate --force

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application setup complete. Starting FrankenPHP..."

# Start FrankenPHP
exec frankenphp run --config /etc/frankenphp/Caddyfile