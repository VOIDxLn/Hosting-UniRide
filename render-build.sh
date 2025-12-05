#!/usr/bin/env bash
set -e

# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Instalar dependencias JS
npm install
npm run build
