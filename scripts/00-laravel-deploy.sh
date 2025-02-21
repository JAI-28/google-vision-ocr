#!/usr/bin/env bash
echo "Running composer"
apt-get update && apt-get install -y php-bcmath

composer install --no-dev --working-dir=/var/www/html
echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force 

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"
