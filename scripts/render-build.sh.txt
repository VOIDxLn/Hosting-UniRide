#!/usr/bin/env bash
set -e

echo "📦 Instalando dependencias..."
composer install --optimize-autoloader --no-dev

echo "🧹 Eliminando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🗄️ Ejecutando migraciones..."
php artisan migrate:fresh --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

echo "✅ Build completado"
