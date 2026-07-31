#!/usr/bin/env bash
set -euo pipefail
APP=/var/www/clients/yatmaen.ammarelgndy.cloud
cd "$APP"
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --omit=dev || npm install --omit=dev
npm run build
php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart || true
sudo supervisorctl restart 'yatmaen-queue:*' || true
chown -R deploy:www-data "$APP"
find "$APP/storage" "$APP/bootstrap/cache" -type d -exec chmod 775 {} \;
find "$APP/storage" "$APP/bootstrap/cache" -type f -exec chmod 664 {} \;

exit 0
