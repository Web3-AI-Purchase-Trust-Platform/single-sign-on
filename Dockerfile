FROM php:8.1-cli
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get update && apt-get install -y unzip git
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/single-sign-on
COPY . .
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install
EXPOSE 8001
CMD ["php", "-S", "0.0.0.0:8000", "-t", "src"]