#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/vedategunduz"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

echo "==> Deploy started: $(date -u)"
cd "$APP_DIR"

echo "==> Checking .env"
if [ ! -f ".env" ]; then
  echo "ERROR: .env not found in $APP_DIR"
  exit 1
fi

# IMPORTANT:
# Some environments end up with Laravel env() returning null.
# Exporting .env into the process environment makes env()/config work reliably for artisan commands.
echo "==> Export .env into shell environment"
set -a
# shellcheck disable=SC1091
. "$APP_DIR/.env"
set +a

echo "==> Git pull"
git pull --rebase

echo "==> Composer install (prod)"
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

echo "==> Permissions"
sudo chown -R vedat:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Optional: tighten .env perms (ok to keep as-is if you prefer)
sudo chown vedat:www-data .env || true
sudo chmod 640 .env || true

echo "==> Laravel optimize clear + cache"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

# ---------- FRONTEND BUILD (OPTIONAL) ----------
# If you want to build Vite assets on the server, enable this block.
# Requires Node.js + npm installed and sufficiently new for Vite.
#
# echo "==> Frontend build (Vite)"
# if command -v npm >/dev/null 2>&1; then
#   npm ci
#   npm run build
# else
#   echo "WARN: npm not found, skipping frontend build"
# fi
# ---------------------------------------------

echo "==> Migrate"
$PHP_BIN artisan migrate --force

echo "==> Reload services"
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

echo "==> Deploy finished: $(date -u)"
