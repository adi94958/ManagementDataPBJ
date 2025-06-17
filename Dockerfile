FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y unzip libzip-dev zip git curl libonig-dev libpng-dev libxml2-dev cron \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions & caches
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Run scheduled tasks
CMD ["php", "artisan", "schedule:run"]
