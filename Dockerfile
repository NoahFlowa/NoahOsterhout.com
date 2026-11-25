FROM php:8.5-apache

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && a2enmod rewrite \
    && sed -i 's/Listen 80/Listen 3000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:3000>/' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/Options Indexes FollowSymLinks/Options +FollowSymLinks -Indexes -MultiViews/' /etc/apache2/apache2.conf \
    && printf '\nServerName localhost\n' >> /etc/apache2/apache2.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 3000

CMD ["apache2-foreground"]
