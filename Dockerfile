### Stage 1: Composer dependencies
FROM composer:lts AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --ignore-platform-reqs

### Stage 2: Final Laravel + FrankenPHP
FROM dunglas/frankenphp:php8.2.28

ENV TZ="Asia/Jakarta"
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

# PHP custom config
COPY .docker/10-custom.ini $PHP_INI_DIR/app.conf.d/

# FrankenPHP Caddy config
COPY .docker/Caddyfile /etc/frankenphp/Caddyfile

# Install PHP extensions
RUN apt update && apt install -y --no-install-recommends \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev zlib1g-dev unzip curl \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
RUN install-php-extensions intl mbstring exif pcntl bcmath gd zip opcache redis pdo pdo_mysql mysqli

# Copy source + vendor from stage 1
WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor /app/vendor

# Laravel setup
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear
RUN php artisan storage:link

# (Opsional) Permissions
RUN chown -R www-data:www-data /app

# Expose port for Railway
EXPOSE 8080
