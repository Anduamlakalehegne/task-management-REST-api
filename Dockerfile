FROM php:8.1-apache

# Install SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create .htaccess for URL rewriting
RUN echo 'RewriteEngine On' > /var/www/html/.htaccess
RUN echo 'RewriteCond %{REQUEST_FILENAME} !-f' >> /var/www/html/.htaccess
RUN echo 'RewriteCond %{REQUEST_FILENAME} !-d' >> /var/www/html/.htaccess
RUN echo 'RewriteRule ^(.*)$ index.php [QSA,L]' >> /var/www/html/.htaccess

# Initialize database
RUN php init_db.php

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"] 