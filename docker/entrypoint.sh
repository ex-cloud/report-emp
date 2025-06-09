#!/bin/bash

if [ ! f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-dev --optimize-autoloader --no-progress
else
    echo "Composer dependencies already installed."
fi
if [ ! -f "storage/.env" ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
else
    echo ".env file already exists."
fi
if [ ! -d "storage/logs" ]; then
    echo "Creating storage/logs directory..."
    mkdir -p storage/logs
else
    echo "storage/logs directory already exists."
fi

php-fpm -D
nginx -g "daemon off;" &