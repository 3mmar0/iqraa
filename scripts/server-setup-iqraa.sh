#!/usr/bin/env bash
# First-time VPS setup for iqraa.ammarelgndy.cloud
# Run on the server with sudo: sudo bash scripts/server-setup-iqraa.sh
set -euo pipefail

DOMAIN="iqraa.ammarelgndy.cloud"
APP="/var/www/clients/${DOMAIN}"
DB_NAME="iqraa"
DB_USER="iqraa"
NGINX_SITE="/etc/nginx/sites-available/${DOMAIN}"
SUPERVISOR_CONF="/etc/supervisor/conf.d/iqraa-queue.conf"
CREDS_FILE="/root/.iqraa-db-credentials"

if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
  echo "Run as root: sudo bash $0"
  exit 1
fi

echo "==> Create app directory"
mkdir -p "$APP"
chown -R deploy:www-data "$APP"

if [[ ! -f "$APP/artisan" ]]; then
  echo "ERROR: $APP/artisan not found. Rsync or clone the app into $APP first, then re-run."
  exit 1
fi

echo "==> MySQL database and user"
if [[ -f "$CREDS_FILE" ]]; then
  # shellcheck disable=SC1090
  source "$CREDS_FILE"
  echo "Using existing credentials from $CREDS_FILE"
else
  DB_PASS="$(openssl rand -base64 24 | tr -d '/+=' | head -c 24)"
  mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
  mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
  mysql -e "FLUSH PRIVILEGES;"
  umask 077
  cat > "$CREDS_FILE" <<EOF
DB_NAME=${DB_NAME}
DB_USER=${DB_USER}
DB_PASS=${DB_PASS}
EOF
  chmod 600 "$CREDS_FILE"
  echo "Saved DB credentials to $CREDS_FILE"
fi

echo "==> .env"
if [[ ! -f "$APP/.env" ]]; then
  cp "$APP/.env.example" "$APP/.env"
fi

sed -i "s|^APP_NAME=.*|APP_NAME=\"اقرأ\"|" "$APP/.env"
sed -i "s|^APP_ENV=.*|APP_ENV=production|" "$APP/.env"
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" "$APP/.env"
sed -i "s|^APP_URL=.*|APP_URL=https://${DOMAIN}|" "$APP/.env"
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" "$APP/.env"
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" "$APP/.env"
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|" "$APP/.env"
sed -i "s|^MAIL_FROM_ADDRESS=.*|MAIL_FROM_ADDRESS=\"noreply@${DOMAIN}\"|" "$APP/.env"

cd "$APP"
sudo -u deploy php artisan key:generate --force

echo "==> nginx vhost"
cat > "$NGINX_SITE" <<'NGINX'
server {
    server_name iqraa.ammarelgndy.cloud;
    root /var/www/clients/iqraa.ammarelgndy.cloud/public;
    index index.php;
    charset utf-8;
    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        include snippets/security-headers.conf;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    location ~ /\.(?!well-known).* { deny all; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 120s;
    }

    listen 80;
    listen [::]:80;
}
NGINX

ln -sf "$NGINX_SITE" "/etc/nginx/sites-enabled/${DOMAIN}"
nginx -t
systemctl reload nginx

echo "==> SSL (Certbot)"
if [[ ! -d "/etc/letsencrypt/live/${DOMAIN}" ]]; then
  certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos -m admin@ammarelgndy.cloud || true
fi

echo "==> Supervisor queue worker"
cat > "$SUPERVISOR_CONF" <<SUP
[program:iqraa-queue]
process_name=%(program_name)s_%(process_num)02d
command=php ${APP}/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
startsecs=3
stopasgroup=true
killasgroup=true
user=deploy
numprocs=1
redirect_stderr=true
stdout_logfile=${APP}/storage/logs/queue.log
stopsignal=TERM
stopwaitsecs=30
SUP

supervisorctl reread
supervisorctl update
supervisorctl start 'iqraa-queue:*' || true

echo "==> Initial deploy"
cp "$APP/scripts/deploy-iqraa.sh" /usr/local/bin/deploy-iqraa.sh
chmod 755 /usr/local/bin/deploy-iqraa.sh
/usr/local/bin/deploy-iqraa.sh

echo "==> Setup complete: https://${DOMAIN}"
echo "DB credentials: $CREDS_FILE"
