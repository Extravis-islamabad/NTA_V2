#!/bin/bash
set -e

echo "=============================================="
echo "  NetFlow Analyzer - Starting..."
echo "=============================================="

# Create .env file if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file..."
    cat > /var/www/html/.env << EOF
APP_NAME="NetFlow Analyzer"
APP_ENV=${APP_ENV:-production}
APP_KEY=
APP_DEBUG=${APP_DEBUG:-false}
APP_TIMEZONE=UTC
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST:-netflow-db}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-netflow_traffic_analyzer}
DB_USERNAME=${DB_USERNAME:-netflow_user}
DB_PASSWORD=${DB_PASSWORD:-NetFlow@Secure#2024!}

SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=file

NETFLOW_PORT=${NETFLOW_PORT:-2055}
EOF
    chown www-data:www-data /var/www/html/.env
fi

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
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
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
echo "  Web Interface: ${APP_URL}"
echo "  NetFlow Port: ${NETFLOW_PORT:-2055}/UDP"
echo "=============================================="

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
