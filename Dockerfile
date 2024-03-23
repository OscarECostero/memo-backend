FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
        default-mysql-client \
    && docker-php-ext-install zip pdo_mysql

COPY . /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

CMD ["php", "artisan", "serve", "--host=127.0.0.1", "--port=8000"]