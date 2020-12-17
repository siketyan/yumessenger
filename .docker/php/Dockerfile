FROM php:8.0-fpm-alpine

RUN apk --update --no-cache add git oniguruma-dev tzdata
RUN git clone -b 5.3.2 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis
RUN docker-php-ext-install mbstring pdo_mysql redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN mkdir -p /var/log/fpm \
    && ln -fns /dev/stdout /var/log/fpm/access.log \
    && ln -fns /dev/stderr /var/log/fpm/access.log

RUN mkdir -p /var/www/html
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --ignore-platform-reqs

ENTRYPOINT ["php-fpm", "-F", "-O"]
