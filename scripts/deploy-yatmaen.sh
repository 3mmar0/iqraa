#!/usr/bin/env bash
set -euo pipefail

APP=/var/www/clients/yatmaen.ammarelgndy.cloud
cd "$APP"

echo "==> Composer"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Frontend build (Vite is a devDependency)"
npm ci || npm install
npm run build
npm prune --omit=dev

echo "==> Laravel migrate / storage"
php artisan migrate --force
php artisan storage:link || true

echo "==> Clear caches so this release's Blade/CSS always win"
php artisan optimize:clear
php artisan view:clear
find storage/framework/views -type f -name '*.php' -delete 2>/dev/null || true

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache || true

echo "==> Permissions"
chown -R deploy:www-data "$APP"
find "$APP/storage" "$APP/bootstrap/cache" -type d -exec chmod 775 {} \;
find "$APP/storage" "$APP/bootstrap/cache" -type f -exec chmod 664 {} \;

echo "==> Queue workers"
# queue:restart writes a Redis cache key; timeout so a stuck Redis cannot block deploy.
timeout 15 php artisan queue:restart || echo "WARN: queue:restart timed out or failed (continuing)"
# stopwaitsecs on yatmaen-queue is 30s — give supervisorctl room, do not kill it mid-stop
# (killing restart mid-flight caused "ERROR (abnormal termination)").
sudo supervisorctl stop 'yatmaen-queue:*' || true
sudo supervisorctl start 'yatmaen-queue:*' || echo "WARN: supervisorctl start failed (continuing)"

echo "==> Reload PHP-FPM (drop OPcache)"
sudo systemctl reload php8.4-fpm 2>/dev/null \
  || sudo systemctl reload php8.3-fpm 2>/dev/null \
  || sudo systemctl reload php-fpm 2>/dev/null \
  || true

echo "==> On-disk homepage marker check"
if ! grep -Fq 'المقررات المتاحة' resources/views/public/home.blade.php; then
  echo "ERROR: resources/views/public/home.blade.php on server is missing expected content."
  echo "---- file head ----"
  head -n 40 resources/views/public/home.blade.php || true
  exit 1
fi

echo "==> Deploy finished at $(date -u +%Y-%m-%dT%H:%M:%SZ)"
exit 0
