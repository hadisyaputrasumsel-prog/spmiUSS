#!/bin/bash
# Ensure no leftover apache PID
rm -f /var/run/apache2/apache2.pid
        
# Wait for database to be ready (robust check)
echo "Waiting for DB to be fully online..."
max_tries=30
count=0
while [ $count -lt $max_tries ]; do
    if php -r "try { new PDO('mysql:host=db;dbname=amira_uss', 'root', 'password'); exit(0); } catch(Exception \$e) { exit(1); }"; then
        echo "Database is ready!"
        break
    fi
    echo "DB not ready, retrying..."
    sleep 3
    count=$((count+1))
done

# Run Laravel commands
php artisan migrate --force || true
php artisan optimize:clear || true

# Fix permissions again just to be safe
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
        
# Start Apache
exec apache2-foreground
