#!/bin/bash
set -e

cd /var/www/html

chmod -R 775 storage bootstrap/cache

sleep 5

php artisan migrate --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=AdminUserSeeder --force

php artisan serve --host=0.0.0.0 --port=10000
