
# Use official PHP with Apache
FROM php:8.3-apache

# Install system dependencies for GD, Zip, and database
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Enable Apache rewrite module (Laravel needs this)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer and install dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction

# Expose port
EXPOSE 80

# Set permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run Apache
CMD ["apache2-foreground"]
