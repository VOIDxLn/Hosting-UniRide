#!/bin/bash
set -e

cd /var/www/html

echo "Asignando permisos..."
chmod -R 775 storage bootstrap/cache

echo "Esperando a la base de datos..."
sleep 5

echo "Ejecutando migraciones..."
php artisan migrate --force || true

echo "Ejecutando seeders..."
php artisan db:seed --force || true

echo "Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=10000
