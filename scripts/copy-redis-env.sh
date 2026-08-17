#!/usr/bin/env bash
set -euo pipefail
SRC=/var/www/clients/yatmaen.ammarelgndy.cloud/.env
DST=/var/www/clients/iqraa.ammarelgndy.cloud/.env
for key in REDIS_HOST REDIS_PASSWORD REDIS_PORT REDIS_CLIENT CACHE_STORE QUEUE_CONNECTION SESSION_DRIVER; do
  val=$(grep -m1 "^${key}=" "$SRC" | cut -d= -f2- || true)
  if [[ -n "$val" ]]; then
    sed -i "s|^${key}=.*|${key}=${val}|" "$DST"
  fi
done
echo REDIS_CFG_OK
