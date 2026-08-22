#!/bin/sh
set -e

# Fix storage and cache permissions
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# If .env does not exist, copy from .env.example
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.docker.example ]; then
        cp /var/www/html/.env.docker.example /var/www/html/.env
    elif [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Ensure composer dependencies exist
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "📦 Đang cài đặt thư viện Composer (vendor)..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Ensure storage link exists
if [ ! -L /var/www/html/public/storage ] && [ -f /var/www/html/vendor/autoload.php ]; then
    php /var/www/html/artisan storage:link --no-interaction || true
fi

# Execute the main container command (php-fpm)
exec "$@"
