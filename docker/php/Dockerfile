FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev zip \
    && pecl install redis \
    && pecl install xdebug \
    && docker-php-ext-enable redis xdebug \
    && docker-php-ext-install pdo pdo_pgsql

# Xdebug config (coverage включается через env или php.ini)
RUN echo "xdebug.mode=coverage,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
WORKDIR /var/www
