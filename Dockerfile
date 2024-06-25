# Use the official PHP 8.3 image from Docker Hub
FROM php:8.3-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Copy your application code to the container
COPY . /var/www/html

# Set permissions for the Apache document root
RUN chown -R www-data:www-data /var/www/html

RUN echo '<VirtualHost *:80> \n\
  DocumentRoot /var/www/html \n\
  <Directory "/var/www/html"> \n\
  AllowOverride All \n\
  </Directory> \n\
  </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

