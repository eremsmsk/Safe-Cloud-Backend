FROM composer:1.10.19 AS composerimage

################################################################################
FROM php:7.3-fpm AS base

# Debian dep and Apache2 config
RUN apt-get update -y && apt-get install -y --yes --force-yes \
    libzip-dev libpng-dev libicu-dev libc-client-dev libkrb5-dev libxml2-dev libgd-dev libfreetype6-dev libmcrypt-dev libbz2-dev libtidy-dev libz-dev libmemcached-dev libxslt-dev \
    bzip2 zlib1g-dev cron gettext openssl g++ zip unzip sendmail nano && \
    pecl install redis && \
    docker-php-ext-configure gd --with-freetype-dir=/usr --with-jpeg-dir=/usr && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-configure intl && \
    docker-php-ext-install zip opcache mbstring zip intl mysqli pdo pdo_mysql gd soap && \
    docker-php-ext-enable pdo_mysql redis && \
    rm -rf /var/lib/apt/lists/

COPY docker/local/api/env/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/local/api/env/php.ini /usr/local/etc/php/php.ini

COPY --from=composerimage /usr/bin/composer /usr/bin/composer
COPY --from=symfonycorp/cli /symfony /usr/local/bin/symfony

################################################################################

# Install Composer
FROM base AS deps

RUN rm -rf /var/www/* && mkdir -p /var/www/app && chown -hR www-data:www-data /var/www/app
USER www-data
WORKDIR /var/www/app
COPY --chown=www-data:www-data composer* /var/www/app/
RUN composer install --no-cache --no-scripts --prefer-dist

################################################################################

FROM base AS prod

COPY --from=deps /var/www /var/www

# Copy app files
COPY --chown=www-data:www-data . /var/www/app/
COPY --chown=www-data:www-data docker/local/api/env/.env /var/www/app/.env
COPY --chown=www-data:www-data docker/local/api/env/.env.test /var/www/app/.env.test
COPY --chown=www-data:www-data docker/local/api/env/phpstan.neon /var/www/app/phpstan.neon

WORKDIR /var/www/app

VOLUME /var/www/app

CMD ["php-fpm"]

################################################################################

FROM prod AS test
USER www-data
RUN php bin/console c:c && \
    symfony check:security && \
    ./vendor/bin/phpstan analyse -c phpstan.neon

################################################################################

FROM prod