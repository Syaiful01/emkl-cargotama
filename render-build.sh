#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Memulai Build PT. CARGOTAMA ---"

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install JS dependencies & Build assets
npm install
npm run build

# Buat database sqlite jika belum ada
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link

echo "--- Build Selesai! ---"
