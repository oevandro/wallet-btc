#!/bin/bash

echo Uploading Application container 
docker-compose up --build -d

echo Install dependencies
docker run --rm --interactive --tty -v $PWD/lumen:/app composer install

echo Make migrations
docker exec -it php php /var/www/html/artisan migrate

echo Make tests
docker exec -it php php /var/www/html/vendor/bin/phpunit /var/www/html/tests

echo Containers information 
docker ps