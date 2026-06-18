FROM richarvey/nginx-php-fpm:3.1.6

# Install PHP extensions using the official helper script
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_pgsql pgsql

# Set working directory
WORKDIR /var/www/html

# Copy all backend files from the backend directory to the container
COPY backend/ .

# Allow Composer to run as root and run composer install during image build (ignore platform requirements to prevent php version errors)
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Set configuration environment variables for richarvey/nginx-php-fpm
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1

# Configure permissions for Laravel storage, cache, and vendor directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the HTTP port
EXPOSE 80
