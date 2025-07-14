FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Set working directory
WORKDIR /var/www

# Copy existing application
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Give permissions (if needed)
RUN chown -R www-data:www-data /var/www

# Expose port and start PHP-FPM
EXPOSE 9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
