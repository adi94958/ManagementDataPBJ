FROM dunglas/frankenphp:php8.2.28

ENV TZ="Asia/Jakarta"
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

# Konfigurasi php.ini
COPY .docker/10-custom.ini $PHP_INI_DIR/app.conf.d/

# Konfigurasi Caddy/FrankenPHP
COPY .docker/Caddyfile /etc/frankenphp/Caddyfile

# Instal ekstensi PHP
RUN apt update && apt install -y --no-install-recommends \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
RUN install-php-extensions intl mbstring exif pcntl bcmath gd zip opcache redis pdo pdo_mysql mysqli

# Copy aplikasi Laravel
WORKDIR /app
COPY . .

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Laravel setup
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear
RUN php artisan storage:link

# Set permission (opsional tergantung kebutuhan)
RUN chown -R www-data:www-data /app

# Expose port 8080 (untuk Railway)
EXPOSE 8080
