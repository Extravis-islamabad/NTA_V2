#!/bin/bash
set -e

echo "=============================================="
echo "  NetFlow Analyzer - Starting..."
echo "=============================================="

# Wait for database
echo "Waiting for PostgreSQL..."
max_tries=30
counter=0
until php -r "new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    counter=$((counter + 1))
    if [ $counter -ge $max_tries ]; then
        echo "ERROR: Could not connect to PostgreSQL after $max_tries attempts"
        exit 1
    fi
    echo "PostgreSQL unavailable - attempt $counter/$max_tries"
    sleep 2
done
echo "PostgreSQL is ready!"

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Generate key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link 2>/dev/null || true

echo "=============================================="
echo "  NetFlow Analyzer - Ready!"
echo "  Web Interface: http://192.168.10.7:8003"
echo "  NetFlow Port: 2055/UDP"
echo "=============================================="

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
