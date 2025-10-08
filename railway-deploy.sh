#!/bin/bash

# Railway deployment script with database connection retry
echo "Starting Railway deployment..."

# Wait for database to be ready
echo "Waiting for database connection..."
for i in {1..30}; do
    if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';" 2>/dev/null; then
        echo "Database connection established!"
        break
    else
        echo "Attempt $i: Database not ready, waiting 5 seconds..."
        sleep 5
    fi
    
    if [ $i -eq 30 ]; then
        echo "Database connection failed after 30 attempts"
        exit 1
    fi
done

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders
echo "Running seeders..."
php artisan db:seed --force

echo "Deployment completed successfully!"