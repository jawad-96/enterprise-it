FROM serversideup/php:8.4-fpm-nginx

# Switch to root user to install dependencies
USER root

# Install PostgreSQL PHP extensions
RUN install-php-extensions pdo_pgsql pgsql

# Copy all backend files and set ownership to the default unprivileged 'webuser' (UID: 9999)
COPY --chown=webuser:webuser backend/ .

# Allow Composer to run as root and run composer install during image build
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Switch back to the default unprivileged webuser
USER webuser

# Expose the HTTP port
EXPOSE 8080
