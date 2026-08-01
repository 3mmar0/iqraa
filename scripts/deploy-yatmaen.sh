#!/usr/bin/env bash
set -euo pipefail
APP=/var/www/clients/yatmaen.ammarelgndy.cloud
cd "$APP"
composer install --no-dev --optimize-autoloader --no-interaction
# Vite and other build tools live in devDependencies; install them for the build, then prune.
npm ci || npm install
npm run build
npm prune --omit=dev
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
