FROM php:8.5-apache

# Install basic stuff we need
RUN apt-get update \
    && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    vim \
    && docker-php-ext-install pdo pdo_pgsql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Change Apache's default internal port from 80 to 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/' /etc/apache2/sites-available/*.conf

# Update Apache configuration to point to the public folder, enable rewrite engine, and allow overrides
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy config files
COPY composer.json composer.lock ./

# Build composer stuff
RUN composer install --no-interaction --no-scripts --no-autoloader

# Copy the source code into the image for production environments
COPY . /var/www/html

# Composer optimizations
RUN composer dump-autoload -o

# Make sure the Apache runtime directories are writable by everyone (since we change users)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/run/apache2 /var/lock/apache2 /var/log/apache2

CMD ["apache2-foreground"]
