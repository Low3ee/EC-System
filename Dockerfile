# syntax=docker/dockerfile:1

# -- Build Stage
# ----------------------------------------------------------------------------
# 1. Install composer dependencies
FROM composer:2.5 as composer

WORKDIR /app
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# ----------------------------------------------------------------------------
# 2. Build node assets
FROM node:18 as node

WORKDIR /app
COPY package.json package.json
COPY package-lock.json package-lock.json
RUN npm ci

COPY . .
RUN npm run build

# -- App Stage
# ----------------------------------------------------------------------------
FROM php:8.2-fpm

# Install packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    # Install dependencies for other extensions
    libzip-dev \
    # Install git
    git \
    # Install supervisord
    supervisor \
    # Install nginx
    nginx

# Install extensions
RUN docker-php-ext-install pdo_mysql zip

# Create a non-root user
RUN useradd -ms /bin/bash -u 1000 laravel
USER laravel

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY --chown=laravel:laravel --from=composer /app/vendor/ /var/www/html/vendor/
COPY --chown=laravel:laravel --from=node /app/public/ /var/www/html/public/
COPY --chown=laravel:laravel . /var/www/html

USER root
# Copy configs
COPY docker/php.ini /usr/local/etc/php/conf.d/40-laravel.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/nginx.conf /etc/nginx/sites-available/default

RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default && \

    # Change user for nginx and php-fpm

    sed -i 's|user www-data;|user laravel;|g' /etc/nginx/nginx.conf && \

    sed -i 's|user = www-data|user = laravel|g' /usr/local/etc/php-fpm.d/www.conf && \

    sed -i 's|group = www-data|group = laravel|g' /usr/local/etc/php-fpm.d/www.conf && \

    # Give laravel user permission to storage and bootstrap/cache

    chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache && \

    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8080

# Entrypoint
CMD ["/usr/bin/supervisord"]
