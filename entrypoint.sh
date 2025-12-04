#!/bin/sh

# Esperar a que la base de datos responda
echo "Esperando a la base de datos..."
sleep 5

php artisan migrate --force || true
php artisan db:seed --force || true

# Iniciar servidor Laravel
php artisan serve --host=0.0.0.0 --port=10000
