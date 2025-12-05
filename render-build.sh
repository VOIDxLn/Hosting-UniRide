#!/usr/bin/env bash
set -e

# 1. Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# 2. Generar la llave de la aplicación (solo si no está ya presente y es necesaria)
# Si tu APP_KEY ya está configurada en Render, puedes omitir esta línea,
# pero es seguro dejarla si la necesitas.
php artisan key:generate

# 3. LIMPIEZA DE CACHÉ (CRÍTICA)
# Esto obliga a Laravel a leer los archivos LoginController.php y User.php corregidos
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Compilar assets de frontend
npm install
npm run build 

# 5. Ejecutar migraciones
# Asegúrate de que esta línea exista para que las tablas (incluyendo role_user) 
# estén actualizadas en la BD de Render. 
# La bandera --force es necesaria en producción.
php artisan migrate --force
