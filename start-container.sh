#!/bin/bash
# Ensure no leftover apache PID
rm -f /var/run/apache2/apache2.pid
        
# Wait 15 seconds to ensure DB is fully online before attempting migration
echo "Sleeping 15s to wait for DB..."
sleep 15

# Run Laravel commands
php artisan migrate --force || true
php artisan optimize:clear || true

# Fix permissions again just to be safe
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
        
# Start Apache
exec apache2-foreground
