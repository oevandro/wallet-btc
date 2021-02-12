FROM php:7.3-fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install mbstring tokenizer pgsql pdo pdo_pgsql 

