FROM php:8.1-apache
#FROM php:7.3-apache

# Install system packages for PHP extensions recommended for Composer
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        zip \
  && docker-php-ext-install pdo pdo_mysql zip

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('composer-setup.php');"

# Enable Apache mod_rewrite for .htaccess and clean URLs
RUN a2enmod rewrite

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy application source
COPY ../www .

# Update Apache configuration to set the correct DocumentRoot to 'public' directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Ensure .htaccess and other Rewrite rules are honored by Apache
RUN echo '<Directory "/var/www/html/public">\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/conf-available/custom.conf \
  && a2enconf custom


# Set proper permissions on the public directory
RUN chown -R www-data:www-data /var/www/html/public && chmod -R 755 /var/www/html/public

COPY php/php.ini $PHP_INI_DIR/conf.d/
