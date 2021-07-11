#!/usr/bin/env bash

USER_ID="$(id -u)"

sudo docker-compose down --remove-orphans
sudo docker-compose build --build-arg USER_ID="$USER_ID" --build-arg IS_WINDOWS=0
sudo docker-compose up -d
sleep 10

echo 'Run composer'
sudo docker-compose exec -u www-data -T php sh -c "cd /var/www/html/ && php /usr/local/bin/composer install"

echo "Run db migrations"
sudo docker-compose exec -u www-data -T php sh -c "php vendor/bin/phinx migrate -c db/migrations.php"