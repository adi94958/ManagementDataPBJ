FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip libzip-dev zip git curl libonig-dev \
    libpng-dev libxml2-dev libgd-dev cron \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Create startup script
COPY cron-startup.sh /usr/local/bin/cron-startup.sh
RUN chmod +x /usr/local/bin/cron-startup.sh

CMD ["/usr/local/bin/cron-startup.sh"]