#!/bin/bash

cp .env.example .env
composer install
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
php artisan key:generate
php artisan migrate:fresh --seed
supervisord -c /etc/supervisor/conf.d/supervisord.conf &
sleep 5
exec php-fpm
