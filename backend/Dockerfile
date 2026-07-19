FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    zip \
    unzip \
    supervisor \
    cron \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create www-data user with proper UID/GID
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install dependencies (no dev in production)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Create storage directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage \
    && chown -R www-data:www-data bootstrap/cache \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

# Optimize for production
RUN php artisan optimize \
    && php artisan view:cache \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan event:cache

# Copy supervisor configuration
COPY supervisor/*.conf /etc/supervisor/conf.d/

# Switch to non-root user
USER www-data

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
