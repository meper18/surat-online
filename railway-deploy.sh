#!/bin/bash

# Railway deployment script with database connection retry
echo "Starting Railway deployment..."

# Copy database file to production location
echo "Setting up database..."
php copy_database_to_railway.php

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

# Run migrations (in case new migrations exist)
echo "Running migrations..."
php artisan migrate --force

# Verify jenis surat data exists
echo "Verifying jenis surat data..."
php artisan tinker --execute="
\$count = App\Models\JenisSurat::count();
echo 'JenisSurat count: ' . \$count . PHP_EOL;
if (\$count == 0) {
    echo 'Running JenisSuratSeeder...' . PHP_EOL;
    Artisan::call('db:seed', ['--class' => 'JenisSuratSeeder', '--force' => true]);
    echo 'Seeder completed' . PHP_EOL;
}
"

echo "Deployment completed successfully!"