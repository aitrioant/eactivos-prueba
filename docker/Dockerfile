FROM php:7.4-cli
WORKDIR "/application"

RUN apt-get update && apt-get -y install git unzip libzip-dev
RUN docker-php-ext-install zip

COPY . .
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN composer install
