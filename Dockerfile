FROM php:apache

# Copy the website files to the container
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
