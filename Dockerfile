FROM php:8-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apk update && apk add --no-cache \
    zlib-dev \
    libzip-dev \
    icu-dev \
    g++ \
    make \
    autoconf \
    git \
    unzip \
    postgresql-libs postgresql-dev \
    && docker-php-ext-install -j$(nproc) zip intl exif \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install pdo pdo_pgsql \
    && apk del postgresql-dev \
    && pecl install -o -f redis \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .
RUN composer install --prefer-dist --no-scripts --no-dev --no-progress --no-interaction \
    && composer clear-cache

CMD wait-for-it db:5432 -- bin/console doctrine:migrations:migrate

CMD ["php-fpm"]
