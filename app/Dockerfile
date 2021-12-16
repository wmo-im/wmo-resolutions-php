FROM php:7.2-apache
RUN apt-get update && apt-get install -y mariadb-client && rm -rf /var/lib/apt
RUN docker-php-ext-install mysqli

COPY . /var/www/html/
