# API Wallet BTC Test Eduuz

Neste projeto foi utilizado:
  - Lumen Framework
  - Postgres Database
  - Docker
  - Teste com PHPUnit
  - Envio de Email
  - Factories e Seeders
  - Filas (Queue)

 
### Iniciando o Projeto

Na raiz do Projeto tem um arquivo em shell script de setup, para executar com o seguinte comando ``` source setup.sh```   para iniciar os projeto, subindo os container Docker e fazendo os testes. 

```sh
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
```

Caso prefira deixei seeder para demais teste, tem um arquivo ``` ./.env.example ``` com dados para teste.

```sh
php artisan db:seed
```
 
```sh
$ node app
```
 
Qualquer dúvida estou a disposição.

 
 
