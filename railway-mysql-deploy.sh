#!/bin/bash

# Railway MySQL Deployment Script
echo "🚀 Starting Railway MySQL Deployment..."

# Copy MySQL environment configuration
echo "📋 Setting up environment configuration..."
cp .env.mysql .env

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Build assets
echo "🏗️ Building frontend assets..."
npm run build

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Run database seeders
echo "🌱 Seeding database..."
php artisan db:seed --force

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Railway MySQL deployment completed!"
echo "🌐 Application should be available at your Railway URL"
echo "📊 Access MySQL setup checker at: /mysql-setup.php"