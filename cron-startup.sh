#!/bin/bash

echo "Starting Laravel Cron Job Execution..."
echo "Timestamp: $(date)"

# Clear any existing cache
php artisan config:clear
php artisan route:clear 
php artisan view:clear

echo "Environment check:"
echo "DB_HOST: ${DB_HOST}"
echo "DB_PORT: ${DB_PORT}"
echo "DB_DATABASE: ${DB_DATABASE}"
echo "APP_ENV: ${APP_ENV}"

# Wait for database connection
echo "Testing database connection..."
max_attempts=10
attempt=1

while [ $attempt -le $max_attempts ]; do
    if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connected'; } catch(Exception \$e) { echo 'Failed: ' . \$e->getMessage(); throw \$e; }" 2>/dev/null; then
        echo "Database connected successfully!"
        break
    else
        echo "Database connection attempt $attempt/$max_attempts failed. Waiting 5 seconds..."
        sleep 5
        attempt=$((attempt + 1))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "ERROR: Could not connect to database after $max_attempts attempts"
    exit 1
fi

# Cache config setelah database tersambung
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Execute the specific command
echo "Executing scheduled command..."
echo "Running cek:tanggal-kontrak at $(date)"

if php artisan cek:tanggal-kontrak; then
    echo "✅ Command executed successfully at $(date)"
    exit 0
else
    echo "❌ Command failed to execute at $(date)"
    exit 1
fi