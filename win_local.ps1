docker-compose down --remove-orphans
docker-compose build --build-arg USER_ID="123" --build-arg IS_WINDOWS=1
docker-compose up -d

Start-Sleep -s 10

Write-Host 'Run composer'
docker-compose exec -u www-data -T php sh -c "cd /var/www/html/ && php /usr/local/bin/composer install"
Write-Host "Run db migrations"
docker-compose exec -u www-data -T php sh -c "php vendor/bin/phinx migrate -c db/migrations.php"