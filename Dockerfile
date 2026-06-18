FROM richarvey/nginx-php-fpm:3.1.6

# Install PostgreSQL development dependencies and PHP extensions
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql

# Set working directory
WORKDIR /var/www/html

# Copy all backend files from the backend directory to the container
COPY backend/ .

# Set configuration environment variables for richarvey/nginx-php-fpm
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1

# Configure permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the HTTP port
EXPOSE 80
