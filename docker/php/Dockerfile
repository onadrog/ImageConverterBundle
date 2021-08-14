FROM php:8-fpm-alpine

RUN apk add --no-cache libjpeg-turbo-dev \
    autoconf g++ make \
    zlib-dev \
    libpng-dev \
    libwebp-dev

RUN apk add --no-cache \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure gd --with-webp --with-jpeg \
    && docker-php-ext-install gd \
    && apk del --purge autoconf g++ make

RUN apk --no-cache add shadow && \
    usermod -u 1000 www-data

WORKDIR /var/www/html