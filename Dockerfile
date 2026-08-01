#########################################################
# Base PHP
#########################################################
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    curl-dev \
    libxml2-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    sqlite-dev \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql bcmath gd zip pcntl intl exif

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Prepare directories & permissions
RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

#########################################################
# Development PHP
#########################################################
FROM base AS development

# We use wildcard * so it won't fail if composer files don't exist yet
COPY composer.json composer.lock* ./

RUN if [ -f composer.json ]; then \
        COMPOSER_MEMORY_LIMIT=-1 composer install --prefer-dist --no-interaction --no-progress --no-scripts; \
    fi

COPY . .

RUN if [ -f composer.json ]; then \
        composer dump-autoload --no-scripts; \
    fi && chown -R www-data:www-data storage bootstrap/cache

#########################################################
# Production PHP
#########################################################
FROM base AS production

COPY composer.json composer.lock* ./

RUN if [ -f composer.json ]; then \
        COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts; \
    fi

COPY . .

RUN if [ -f composer.json ]; then \
        composer dump-autoload --no-dev --optimize --no-scripts; \
    fi && chown -R www-data:www-data storage bootstrap/cache

#########################################################
# Production Nginx (Web Server)
#########################################################
FROM nginx:alpine AS web

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Extract public folder from production stage
COPY --from=production /var/www/html/public /var/www/html/public
