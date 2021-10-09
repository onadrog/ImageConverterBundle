FROM php:8-fpm-alpine

RUN apk add --no-cache libjpeg-turbo-dev \
    autoconf g++ make \
    zlib-dev \
    libpng-dev \
    libwebp-dev

RUN apk add --no-cache \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure gd --with-webp --with-jpeg \
    && docker-php-ext-install gd \
    && apk del --purge autoconf g++ make

RUN curl -s https://getcomposer.org/installer | php

RUN mv composer.phar /usr/local/bin/composer


# Not mandatory, but recommended
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage --no-sandbox --display=:0 --disable-gpu --disable-extensions --remote-debugging-port=9222'
RUN apk add --no-cache chromium chromium-chromedriver

# Firefox and GeckoDriver (optional)
ARG GECKODRIVER_VERSION=0.28.0
RUN apk add --no-cache firefox libzip-dev; \
    docker-php-ext-install zip
RUN wget -q https://github.com/mozilla/geckodriver/releases/download/v$GECKODRIVER_VERSION/geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz; \
    tar -zxf geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz -C /usr/bin; \
    rm geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz


WORKDIR /var/www/html

RUN apk --no-cache add shadow && \
    usermod -u 1000 www-data