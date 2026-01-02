#!/bin/bash

# Quick Deployment Script for Production
# Run this script after pushing code to live server

echo "🚀 Starting Production Deployment..."

# Step 1: Install Dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Step 2: Run Migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Step 3: Clear Caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Step 4: Rebuild Caches
echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 5: Restart Queue Workers (if using Supervisor)
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting queue workers..."
    sudo supervisorctl restart muslimlynk-queue-worker:*
    echo "✅ Queue workers restarted"
else
    echo "⚠️  Supervisor not found. Please start queue worker manually:"
    echo "   php artisan queue:work"
fi

# Step 6: Verify Queue Worker
echo "🔍 Verifying queue worker..."
if pgrep -f "queue:work" > /dev/null; then
    echo "✅ Queue worker is running"
else
    echo "❌ Queue worker is NOT running!"
    echo "   Please start it manually or configure Supervisor"
fi

echo ""
echo "✅ Deployment complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Test email sending"
echo "   2. Monitor logs: tail -f storage/logs/laravel.log"
echo "   3. Check queue status: php artisan queue:monitor"

