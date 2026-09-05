#!/bin/bash
# Ensure no leftover apache PID
rm -f /var/run/apache2/apache2.pid
        
# Wait for database to be ready
RETRIES=15
until php artisan db:monitor > /dev/null 2>&1 || [ $RETRIES -eq 0 ]; do
  echo "Waiting for database server, $((RETRIES--)) remaining attempts..."
  sleep 2
done

# Run Laravel commands
php artisan migrate --force || true
php artisan optimize:clear || true

# Fix permissions again just to be safe
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
        
# Start Apache
exec apache2-foreground
