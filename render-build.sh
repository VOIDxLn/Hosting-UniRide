#!/usr/bin/env bash
set -e

# 1. Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# 2. LIMPIEZA DE CACHÉ (CRÍTICA)
# Esto obliga a Laravel a leer los archivos LoginController.php y User.php corregidos
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Compilar assets de frontend
npm install
npm run build 
