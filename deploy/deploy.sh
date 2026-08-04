#!/usr/bin/env bash
#
# deploy.sh — RealEstatePro production deployment script
# Run on the EC2 instance (or invoked by GitHub Actions over SSH, see
# .github/workflows/deploy.yml). Assumes the repo already lives at
# /var/www/realestatepro and .env is already configured there.

set -euo pipefail

APP_DIR="/var/www/realestatepro"
cd "$APP_DIR"

echo "==> Putting application into maintenance mode..."
php artisan down --retry=15 || true

echo "==> Pulling latest code..."
git pull origin main

echo "==> Installing PHP dependencies (production, no dev)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Clearing and rebuilding caches..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Ensuring storage symlink exists..."
php artisan storage:link || true

echo "==> Fixing storage/bootstrap cache permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "==> Restarting PHP-FPM..."
sudo systemctl restart php8.3-fpm

echo "==> Restarting queue workers (Phase 18 email + any future queued jobs)..."
sudo supervisorctl restart realestatepro-worker:*

echo "==> Bringing application back up..."
php artisan up

echo "==> Deployment complete."
