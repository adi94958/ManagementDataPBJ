#!/bin/bash

echo "Starting Laravel Cron Service..."
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
max_attempts=30
attempt=1

while [ $attempt -le $max_attempts ]; do
    if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connected'; } catch(Exception \$e) { echo 'Failed: ' . \$e->getMessage(); throw \$e; }" 2>/dev/null; then
        echo "Database connected successfully!"
        break
    else
        echo "Database connection attempt $attempt/$max_attempts failed. Waiting 10 seconds..."
        sleep 10
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

# Test command first
echo "Testing command..."
if php artisan cek:tanggal-kontrak --dry-run 2>/dev/null || php artisan cek:tanggal-kontrak; then
    echo "Command executed successfully!"
else
    echo "ERROR: Command failed to execute"
    exit 1
fi

echo "Cron service setup complete!"

# Keep container running (pilih salah satu):

# Option 1: Run once and exit
# exit 0

# Option 2: Run in loop
# while true; do
#     echo "Running scheduled command at $(date)"
#     php artisan cek:tanggal-kontrak
#     echo "Sleeping for 1 hour..."
#     sleep 3600
# done

# Option 3: Use Laravel scheduler
while true; do
    php artisan schedule:run
    sleep 60
done